#!/usr/bin/env bash
# Rollback en une commande — Les Mégères de l'Humus
#
# Restaure le backup créé automatiquement par ./deploy.sh --apply
# Le backup se trouve à REMOTE_BASE.backup sur le serveur distant.
#
# Usage :
#   ./rollback.sh                      # restaure le backup et vérifie les pages
#   ./rollback.sh --skip-post-check    # restaure sans vérification HTTP

set -euo pipefail

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"

SSH_HOST="${SSH_HOST:-192.168.0.51}"
SSH_PORT="${SSH_PORT:-1000}"
SSH_USER="${SSH_USER:-root}"
REMOTE_BASE="${REMOTE_BASE:-/var/www/my_webapp__3}"
BACKUP_PATH="${BACKUP_PATH:-${REMOTE_BASE}.backup}"
BASE_URL="${BASE_URL:-https://lesmegeresdelhumus.fr/site1}"
POST_DEPLOY_SCRIPT="$SCRIPT_DIR/check-post-deploy.sh"
RUN_POST_CHECK=1

usage() {
  cat <<EOF
Usage: ./rollback.sh [options]

Restaure le backup créé par le dernier ./deploy.sh --apply.
Le backup est situé à: REMOTE_BASE.backup  (default: $BACKUP_PATH)

Options:
  --skip-post-check   Ne pas lancer les vérifications HTTP après rollback.
  --help              Afficher cette aide.

Environment overrides:
  SSH_HOST, SSH_PORT, SSH_USER, REMOTE_BASE, BACKUP_PATH, BASE_URL

Exemple:
  ./rollback.sh
  SSH_HOST=192.168.0.51 ./rollback.sh
EOF
}

while [[ $# -gt 0 ]]; do
  case "$1" in
    --skip-post-check)
      RUN_POST_CHECK=0
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

require_cmd ssh

TARGET="$SSH_USER@$SSH_HOST"
CONTROL_SOCKET="/tmp/lesmegeres-rollback-%r@%h:%p.sock"
SSH_OPTS=(-p "$SSH_PORT" -o ControlMaster=auto -o ControlPersist=60 -o ControlPath="$CONTROL_SOCKET")

cleanup() {
  ssh "${SSH_OPTS[@]}" -O exit "$TARGET" >/dev/null 2>&1 || true
}
trap cleanup EXIT

echo "==> Rollback: $BACKUP_PATH → $REMOTE_BASE"
echo "==> Ouverture connexion SSH"
ssh "${SSH_OPTS[@]}" "$TARGET" true

ssh "${SSH_OPTS[@]}" "$TARGET" "
  if [[ ! -d '$BACKUP_PATH' ]]; then
    echo 'ERREUR: aucun backup trouvé à $BACKUP_PATH' >&2
    echo 'Lancez ./deploy.sh --apply au moins une fois pour créer un backup automatique.' >&2
    exit 1
  fi
  echo '==> Backup trouvé, restauration en cours...'
  rm -rf '${REMOTE_BASE}_old' 2>/dev/null || true
  mv '$REMOTE_BASE' '${REMOTE_BASE}_old'
  cp -a '$BACKUP_PATH' '$REMOTE_BASE'
  echo '==> Restauration terminée.'
  echo '==> Ancienne version conservée dans ${REMOTE_BASE}_old (supprimable manuellement).'
"

if [[ "$RUN_POST_CHECK" -eq 1 ]]; then
  if [[ -x "$POST_DEPLOY_SCRIPT" ]]; then
    echo "==> Vérification post-rollback"
    BASE_URL="$BASE_URL" "$POST_DEPLOY_SCRIPT"
  else
    echo "ATTENTION: script de vérification introuvable: $POST_DEPLOY_SCRIPT" >&2
  fi
fi

echo "==> Rollback terminé avec succès."
