#!/usr/bin/env bash

set -euo pipefail

BASE_URL="${BASE_URL:-https://lesmegeresdelhumus.fr}"
CURL_TIMEOUT="${CURL_TIMEOUT:-20}"
EXPECTED_STATUS="${EXPECTED_STATUS:-200}"

SSH_VERIFY_OWNERS="${SSH_VERIFY_OWNERS:-0}"
SSH_HOST="${SSH_HOST:-192.168.0.51}"
SSH_PORT="${SSH_PORT:-1000}"
SSH_USER="${SSH_USER:-root}"
REMOTE_BASE="${REMOTE_BASE:-/var/www/my_webapp}"
REMOTE_OWNER_GROUP="${REMOTE_OWNER_GROUP:-my_webapp:my_webapp}"

usage() {
  cat <<EOF
Usage: ./check-post-deploy.sh [options]

Options:
  --base-url URL      Base URL to check (default: $BASE_URL)
  --status CODE       Expected HTTP status (default: $EXPECTED_STATUS)
  --verify-owners     Also verify remote owner:group consistency over SSH
  --help              Show this help

Environment overrides:
  BASE_URL, EXPECTED_STATUS, CURL_TIMEOUT
  SSH_VERIFY_OWNERS, SSH_HOST, SSH_PORT, SSH_USER, REMOTE_BASE, REMOTE_OWNER_GROUP

Examples:
  ./check-post-deploy.sh
  ./check-post-deploy.sh --base-url https://lesmegeresdelhumus.fr
  SSH_VERIFY_OWNERS=1 REMOTE_OWNER_GROUP=my_webapp:my_webapp ./check-post-deploy.sh
EOF
}

while [[ $# -gt 0 ]]; do
  case "$1" in
    --base-url)
      BASE_URL="$2"
      shift 2
      ;;
    --status)
      EXPECTED_STATUS="$2"
      shift 2
      ;;
    --verify-owners)
      SSH_VERIFY_OWNERS=1
      shift
      ;;
    --help|-h)
      usage
      exit 0
      ;;
    *)
      echo "Option inconnue: $1" >&2
      usage >&2
      exit 1
      ;;
  esac
done

require_cmd() {
  if ! command -v "$1" >/dev/null 2>&1; then
    echo "Commande manquante: $1" >&2
    exit 1
  fi
}

require_cmd curl

URLS=(
  "$BASE_URL/"
  "$BASE_URL/compagnie"
  "$BASE_URL/contact"
  "$BASE_URL/ateliers"
  "$BASE_URL/theatre-d-ombres"
  "$BASE_URL/sieste-contee"
  "$BASE_URL/projet-pedagogique"
)

echo "==> Vérification HTTP Post-deploy"
echo "==> Base URL: $BASE_URL"

ok_count=0
fail_count=0

for url in "${URLS[@]}"; do
  result="$(curl -L -ksS --max-time "$CURL_TIMEOUT" -o /dev/null -w '%{http_code} %{url_effective}' "$url")"
  status="${result%% *}"
  effective_url="${result#* }"

  if [[ "$status" == "$EXPECTED_STATUS" ]]; then
    echo "PASSE $status $url -> $effective_url"
    ok_count=$((ok_count + 1))
  else
    echo "ÉCHEC $status $url -> $effective_url"
    fail_count=$((fail_count + 1))
  fi
done

echo "==> Résumé HTTP: PASSE=$ok_count ÉCHEC=$fail_count"

# --- Vérifications SEO (Phase 3) ----------------------------------------

echo "==> Vérifications SEO"

# sitemap.xml : accessible et contient <urlset
sitemap_body="$(curl -L -ksS --max-time "$CURL_TIMEOUT" "$BASE_URL/sitemap.xml")"
sitemap_status="$(curl -L -ksS --max-time "$CURL_TIMEOUT" -o /dev/null -w '%{http_code}' "$BASE_URL/sitemap.xml")"
if [[ "$sitemap_status" == "200" ]] && echo "$sitemap_body" | grep -qi "<urlset"; then
  echo "PASSE sitemap.xml ($sitemap_status, contient <urlset>)"
  ok_count=$((ok_count + 1))
else
  echo "ÉCHEC sitemap.xml ($sitemap_status)"
  fail_count=$((fail_count + 1))
fi

# robots.txt : accessible et contient Sitemap:
robots_body="$(curl -L -ksS --max-time "$CURL_TIMEOUT" "$BASE_URL/robots.txt")"
robots_status="$(curl -L -ksS --max-time "$CURL_TIMEOUT" -o /dev/null -w '%{http_code}' "$BASE_URL/robots.txt")"
if [[ "$robots_status" == "200" ]] && echo "$robots_body" | grep -qi "Sitemap:"; then
  echo "PASSE robots.txt ($robots_status, contient Sitemap:)"
  ok_count=$((ok_count + 1))
else
  echo "ÉCHEC robots.txt ($robots_status)"
  fail_count=$((fail_count + 1))
fi

# meta title page d'accueil : doit contenir du contenu
home_body="$(curl -L -ksS --max-time "$CURL_TIMEOUT" "$BASE_URL/")"
if echo "$home_body" | grep -qi "<title"; then
  echo "PASSE meta title présent sur /"
  ok_count=$((ok_count + 1))
else
  echo "ÉCHEC meta title absent sur /"
  fail_count=$((fail_count + 1))
fi

echo "==> Résumé SEO: PASSE=$ok_count ÉCHEC=$fail_count"

if [[ "$SSH_VERIFY_OWNERS" -eq 1 ]]; then
  require_cmd ssh

  owner_user="${REMOTE_OWNER_GROUP%%:*}"
  owner_group="${REMOTE_OWNER_GROUP##*:}"

  echo "==> Vérification distante ownership"
  echo "==> owner:group cible: $owner_user:$owner_group"

  bad_count="$(ssh -p "$SSH_PORT" "$SSH_USER@$SSH_HOST" "find '$REMOTE_BASE' -type f \( -path '$REMOTE_BASE/www/*' -o -path '$REMOTE_BASE/lesmegeres-app/*' \) ! -user '$owner_user' -o ! -group '$owner_group' | wc -l" | tr -d ' ')"

  # Re-run list with exact filter (the previous expression can be precedence-sensitive depending on find version).
  bad_list="$(ssh -p "$SSH_PORT" "$SSH_USER@$SSH_HOST" "find '$REMOTE_BASE/www' '$REMOTE_BASE/lesmegeres-app' -type f \( ! -user '$owner_user' -o ! -group '$owner_group' \) | head -n 20")"

  if [[ "$bad_count" == "0" ]]; then
    echo "PASS ownership: all checked files are $owner_user:$owner_group"
  else
    echo "FAIL ownership: $bad_count files are not $owner_user:$owner_group"
    if [[ -n "$bad_list" ]]; then
      echo "Sample (max 20):"
      echo "$bad_list"
    fi
    fail_count=$((fail_count + 1))
  fi
fi

if [[ "$fail_count" -gt 0 ]]; then
  echo "==> Résultat final: ÉCHEC"
  exit 1
fi

echo "==> Résultat final: PASSE"
