#!/usr/bin/env bash
# Monitoring périodique des pages critiques — Les Mégères de l'Humus
#
# Utilisation manuelle :
#   ./check-uptime.sh
#   BASE_URL=https://lesmegeresdelhumus.fr/site1 ./check-uptime.sh
#
# Exemple cron (toutes les 15 minutes, alerte par mail si cron est configuré) :
#   */15 * * * * /var/www/lesmegeres/check-uptime.sh >> /var/log/megeres-uptime.log 2>&1

set -euo pipefail

BASE_URL="${BASE_URL:-https://lesmegeresdelhumus.fr/site1}"
CURL_TIMEOUT="${CURL_TIMEOUT:-30}"
TIMESTAMP="$(date '+%Y-%m-%d %H:%M:%S')"

require_cmd() {
  if ! command -v "$1" >/dev/null 2>&1; then
    echo "[$TIMESTAMP] ERREUR: commande manquante: $1" >&2
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

fail_count=0

for url in "${URLS[@]}"; do
  status="$(curl -L -ksS --max-time "$CURL_TIMEOUT" -o /dev/null -w '%{http_code}' "$url")"
  if [[ "$status" == "200" ]]; then
    echo "[$TIMESTAMP] PASS $status $url"
  else
    echo "[$TIMESTAMP] FAIL $status $url" >&2
    fail_count=$((fail_count + 1))
  fi
done

if [[ "$fail_count" -gt 0 ]]; then
  echo "[$TIMESTAMP] ALERTE: $fail_count page(s) en anomalie sur $BASE_URL" >&2
  exit 1
fi

echo "[$TIMESTAMP] OK: toutes les pages répondent 200"
