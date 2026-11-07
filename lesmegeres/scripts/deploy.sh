#!/usr/bin/env bash

set -euo pipefail

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
REPO_ROOT="$(cd "$SCRIPT_DIR/.." && pwd)"
LOCAL_PUBLIC_DIR="$REPO_ROOT"
LOCAL_APP_DIR="$(cd "$REPO_ROOT/../lesmegeres-app" && pwd)"

SSH_HOST="${SSH_HOST:-192.168.0.51}"
SSH_PORT="${SSH_PORT:-1000}"
SSH_USER="${SSH_USER:-root}"
REMOTE_BASE="${REMOTE_BASE:-/var/www/my_webapp}"
REMOTE_PUBLIC_DIR="$REMOTE_BASE/www"
REMOTE_APP_DIR="$REMOTE_BASE/lesmegeres-app"
BASE_URL="${BASE_URL:-https://lesmegeresdelhumus.fr}"
REMOTE_OWNER_GROUP="${REMOTE_OWNER_GROUP:-my_webapp:my_webapp}"
REMOTE_DIR_MODE="${REMOTE_DIR_MODE:-750}"
REMOTE_FILE_MODE="${REMOTE_FILE_MODE:-640}"
POST_DEPLOY_SCRIPT="$SCRIPT_DIR/check-post-deploy.sh"
GENERATE_SITEMAP_SCRIPT="$SCRIPT_DIR/generate-sitemap.sh"

APPLY_CHANGES=0
RUN_CHECKS=1
RUN_POST_DEPLOY_CHECK="${RUN_POST_DEPLOY_CHECK:-1}"
POST_DEPLOY_OWNER_VERIFY="${POST_DEPLOY_OWNER_VERIFY:-0}"
FIX_OWNERS=0
BACKUP_ENABLED="${BACKUP_ENABLED:-1}"
BACKUP_PATH="${BACKUP_PATH:-${REMOTE_BASE}.backup}"

PUBLIC_ITEMS=(
  "index.php"
  "security.txt"
  ".well-known"
  "multimedia"
  "maintenance"
  "css"
  "fonts"
  "images"
  "pdf"
  "robots.txt"
  "script"
  "sitemap.xml"
)

RSYNC_EXCLUDES=(
  "/style.css"  # Empêche d'envoyer le fichier CSS non minifié sur le serveur
  "*.old"
  "*.old.*"
  "*.bak"
  "*.bak.*"
  "*.sauve"
  "*.sauve.*"
  "*~"
  ".DS_Store"
  "**/.DS_Store"
  "Thumbs.db"
  "**/Thumbs.db"
  "desktop.ini"
  "**/desktop.ini"
  "._*"
  "**/._*"
  "_inutilise/"
  "**/_inutilise/"
  "*.html"
  "*.md"
)

usage() {
  cat <<EOF
Usage: ./deploy.sh [options]

Options:
  --apply               Execute the deployment. Default is dry-run.
  --dry-run             Show what would be deployed without changing the server.
  --fix-owners          Re-apply owner:group to all remote files without transferring.
  --skip-check          Skip HTTP smoke tests after deployment.
  --skip-post-check     Skip automatic check-post-deploy.sh execution.
  --verify-post-owners  Enable remote owner:group verification in post-deploy checks.
  --no-backup           Skip remote backup creation before applying.
  --help                Show this help.
EOF
}

while [[ $# -gt 0 ]]; do
  case "$1" in
    --apply) APPLY_CHANGES=1; shift ;;
    --dry-run) APPLY_CHANGES=0; shift ;;
    --no-backup) BACKUP_ENABLED=0; shift ;;
    --fix-owners) FIX_OWNERS=1; shift ;;
    --skip-check) RUN_CHECKS=0; shift ;;
    --skip-post-check) RUN_POST_DEPLOY_CHECK=0; shift ;;
    --verify-post-owners) POST_DEPLOY_OWNER_VERIFY=1; shift ;;
    --help|-h) usage; exit 0 ;;
    *) echo "Unknown option: $1" >&2; usage >&2; exit 1 ;;
  esac
done

BACKUP_PATH="${BACKUP_PATH:-${REMOTE_BASE}.backup}"

require_cmd() {
  if ! command -v "$1" >/dev/null 2>&1; then
    echo "Missing required command: $1" >&2
    exit 1
  fi
}

require_path() {
  if [[ ! -e "$1" ]]; then
    echo "Required path not found: $1" >&2
    exit 1
  fi
}

require_cmd ssh
require_cmd rsync
require_cmd curl
require_cmd git

require_path "$LOCAL_PUBLIC_DIR/index.php"
require_path "$LOCAL_APP_DIR/includes"
require_path "$LOCAL_APP_DIR/pages"

TARGET="$SSH_USER@$SSH_HOST"
CONTROL_SOCKET="/tmp/lesmegeres-deploy-%r@%h:%p.sock"
SSH_BASE_OPTS=(
  -p "$SSH_PORT"
  -o ControlMaster=auto
  -o ControlPersist=600
  -o ControlPath="$CONTROL_SOCKET"
)

declare -A CHANGED_REMOTE_PATHS=()
declare -A SYNC_REMOTE_PATHS=()
RESTORED_OWNERSHIP_COUNT=0
SKIPPED_OWNERSHIP_COUNT=0
TARGET_OWNER_GROUP=""
create_remote_backup() {
  if [[ "$BACKUP_ENABLED" -eq 0 ]]; then
    echo "==> Backup skipped (--no-backup)"
    return
  fi

  echo "==> Creating remote backup: $REMOTE_BASE → $BACKUP_PATH"
  ssh "${SSH_BASE_OPTS[@]}" "$TARGET" "
    if [[ -d '$REMOTE_BASE/www' || -d '$REMOTE_BASE/lesmegeres-app' ]]; then
      rm -rf '$BACKUP_PATH'
      cp -a '$REMOTE_BASE' '$BACKUP_PATH'
      echo 'Backup created at $BACKUP_PATH'
    else
      echo 'No existing deployment found, skipping backup'
    fi
  "
}

detect_target_owner_group() {
  if [[ -n "$REMOTE_OWNER_GROUP" ]]; then
    TARGET_OWNER_GROUP="$REMOTE_OWNER_GROUP"
    echo "==> Ownership target (forced): $TARGET_OWNER_GROUP"
    return
  fi

  if TARGET_OWNER_GROUP="$(ssh "${SSH_BASE_OPTS[@]}" "$TARGET" "for ref in '$REMOTE_PUBLIC_DIR/index.php' '$REMOTE_APP_DIR/includes/header.php' '$REMOTE_APP_DIR/pages/compagnie.php' '$REMOTE_PUBLIC_DIR' '$REMOTE_APP_DIR/pages'; do if [[ -e \\\"\$ref\\\" ]]; then stat -c '%U:%G' \\\"\$ref\\\"; exit 0; fi; done; exit 1")"; then
    echo "==> Ownership target (auto): $TARGET_OWNER_GROUP"
    return
  fi

  echo "Unable to detect remote owner:group automatically." >&2
  echo "Set REMOTE_OWNER_GROUP explicitly, for example: REMOTE_OWNER_GROUP=my_webapp__3:www-data ./deploy.sh --apply" >&2
  exit 1
}

track_rsync_changes() {
  local itemized_file="$1"
  local remote_path="$2"
  local is_dir="$3"
  local line=""
  local rel=""
  local changed_remote_path=""

  while IFS= read -r line; do
    if [[ ! "$line" =~ ^[\<\>ch][^[:space:]]+[[:space:]]+(.+)$ ]]; then
      continue
    fi

    rel="${BASH_REMATCH[1]}"
    rel="${rel#./}"
    rel="${rel%/}"

    if [[ "$is_dir" -eq 1 ]]; then
      if [[ -z "$rel" || "$rel" == "." ]]; then
        continue
      fi
      changed_remote_path="$remote_path/$rel"
    else
      changed_remote_path="$remote_path"
    fi

    SYNC_REMOTE_PATHS["$changed_remote_path"]=1

    if [[ $APPLY_CHANGES -eq 1 ]]; then
      CHANGED_REMOTE_PATHS["$changed_remote_path"]=1
    fi

  done < "$itemized_file"
}

sync_with_tracking() {
  local local_path="$1"
  local remote_path="$2"
  local is_dir="$3"
  local itemized_file=""

  itemized_file="$(mktemp)"

  if [[ "$is_dir" -eq 1 ]]; then
    rsync "${RSYNC_OPTS[@]}" "$local_path/" "$TARGET:$remote_path/" | tee "$itemized_file"
  else
    rsync "${RSYNC_OPTS[@]}" "$local_path" "$TARGET:$remote_path" | tee "$itemized_file"
  fi

  track_rsync_changes "$itemized_file" "$remote_path" "$is_dir"

  rm -f "$itemized_file"
}

apply_target_ownership() {
  echo "==> Normalizing ownership and permissions on all remote files to $TARGET_OWNER_GROUP (dirs=$REMOTE_DIR_MODE, files=$REMOTE_FILE_MODE)"

  ssh "${SSH_BASE_OPTS[@]}" "$TARGET" "
    find '$REMOTE_PUBLIC_DIR' '$REMOTE_APP_DIR' -not -path '*/.git/*' -print0 \
      | xargs -0 chown '$TARGET_OWNER_GROUP'
    find '$REMOTE_PUBLIC_DIR' '$REMOTE_APP_DIR' -type d -not -path '*/.git/*' -exec chmod '$REMOTE_DIR_MODE' {} +
    find '$REMOTE_PUBLIC_DIR' '$REMOTE_APP_DIR' -type f -not -path '*/.git/*' -exec chmod '$REMOTE_FILE_MODE' {} +
    echo 'Ownership + permissions applied.'
  "
  RESTORED_OWNERSHIP_COUNT=1
}

print_sync_summary() {
  local mode_label=""
  local title=""

  if [[ $APPLY_CHANGES -eq 1 ]]; then
    mode_label="APPLY"
    title="Files transferred"
  else
    mode_label="DRY-RUN"
    title="Files that would be transferred"
  fi

  echo "==> Summary ($mode_label)"
  echo "==> $title: ${#SYNC_REMOTE_PATHS[@]}"

  if [[ ${#SYNC_REMOTE_PATHS[@]} -gt 0 ]]; then
    while IFS= read -r path; do
      echo " - $path"
    done < <(printf '%s\n' "${!SYNC_REMOTE_PATHS[@]}" | sort)
  fi

  if [[ $APPLY_CHANGES -eq 1 ]]; then
    echo "==> Ownership + permissions normalized on all remote files"
    if [[ $SKIPPED_OWNERSHIP_COUNT -gt 0 ]]; then
      echo "==> Ownership skipped (missing path or chown error): $SKIPPED_OWNERSHIP_COUNT"
    fi
  fi
}

run_post_deploy_checks() {
  if [[ "$RUN_POST_DEPLOY_CHECK" -eq 0 ]]; then
    echo "==> Skipping post-deploy checks (--skip-post-check)"
    return
  fi

  if [[ ! -x "$POST_DEPLOY_SCRIPT" ]]; then
    echo "Post-deploy check script is missing or not executable: $POST_DEPLOY_SCRIPT" >&2
    echo "Run: chmod +x $POST_DEPLOY_SCRIPT" >&2
    exit 1
  fi

  echo "==> Running post-deploy checks ($POST_DEPLOY_SCRIPT)"
  if [[ "$POST_DEPLOY_OWNER_VERIFY" -eq 1 ]]; then
    BASE_URL="$BASE_URL" \
    SSH_HOST="$SSH_HOST" \
    SSH_PORT="$SSH_PORT" \
    SSH_USER="$SSH_USER" \
    REMOTE_BASE="$REMOTE_BASE" \
    REMOTE_OWNER_GROUP="$TARGET_OWNER_GROUP" \
    SSH_VERIFY_OWNERS=1 \
    "$POST_DEPLOY_SCRIPT"
  else
    BASE_URL="$BASE_URL" "$POST_DEPLOY_SCRIPT"
  fi
}

push_to_github() {
  if [[ $APPLY_CHANGES -eq 0 ]]; then
    echo "==> Skipping GitHub push (DRY-RUN mode)"
    return
  fi

  echo "==> Checking Git repository status..."
  
  # 🌟 ADAPTATION MASTER-DÉPÔT : On cible le dossier parent commun /var/www
  local git_root="/var/www"

  # Vérifie si le dossier local est un dépôt Git valide
  if ! git -C "$git_root" rev-parse --is-inside-work-tree >/dev/null 2>&1; then
    echo "Warning: $git_root is not a Git repository. Skipping GitHub push." >&2
    return
  fi

  # Vérifie s'il y a des changements non commités en local (Public + App)
  if ! git -C "$git_root" diff-index --quiet HEAD --; then
    echo "==> Local changes detected. Creating an automatic deployment commit..."
    git -C "$git_root" add .
    git -C "$git_root" commit -m "chore(deploy): automatic backup before deployment - $(date +'%Y-%m-%d %H:%M:%S')"
  fi

  # Récupère le nom de la branche courante (ex: main)
  local current_branch
  current_branch="$(git -C "$git_root" branch --show-current)"

  echo "==> Pushing local history to GitHub (branch: $current_branch)..."
  if git -C "$git_root" push origin "$current_branch"; then
    echo "==> GitHub repository successfully updated!"
  else
    echo "Error: Failed to push to GitHub. Please check your network or remote URL." >&2
    exit 1
  fi
}

cleanup() {
  ssh "${SSH_BASE_OPTS[@]}" -O exit "$TARGET" >/dev/null 2>&1 || true
}

trap cleanup EXIT

echo "==> Target: $TARGET"
echo "==> Remote base: $REMOTE_BASE"
if [[ $FIX_OWNERS -eq 1 ]]; then
  echo "==> Mode: FIX-OWNERS"
elif [[ $APPLY_CHANGES -eq 1 ]]; then
  echo "==> Mode: APPLY"
else
  echo "==> Mode: DRY-RUN"
fi

echo "==> Opening SSH control connection"
ssh "${SSH_BASE_OPTS[@]}" "$TARGET" true

if [[ $FIX_OWNERS -eq 1 ]]; then
  detect_target_owner_group
  echo "==> Re-applying owner:group $TARGET_OWNER_GROUP to all remote files"
  ssh "${SSH_BASE_OPTS[@]}" "$TARGET" "
    find '$REMOTE_PUBLIC_DIR' '$REMOTE_APP_DIR' -not -path '*/.git/*' -print0 | xargs -0 chown '$TARGET_OWNER_GROUP'
    find '$REMOTE_PUBLIC_DIR' '$REMOTE_APP_DIR' -type d -not -path '*/.git/*' -exec chmod '$REMOTE_DIR_MODE' {} +
    find '$REMOTE_PUBLIC_DIR' '$REMOTE_APP_DIR' -type f -not -path '*/.git/*' -exec chmod '$REMOTE_FILE_MODE' {} +
    echo 'Done.'
  "
  echo "==> fix-owners complete."
  exit 0
fi

RSYNC_SSH="ssh -p $SSH_PORT -o ControlMaster=auto -o ControlPersist=600 -o ControlPath=$CONTROL_SOCKET"
RSYNC_OPTS=(
  -rz
  --links
  --checksum
  --no-owner
  --no-group
  --no-perms
  --no-times
  --itemize-changes
  --human-readable
  --omit-dir-times
  -e "$RSYNC_SSH"
)

for pattern in "${RSYNC_EXCLUDES[@]}"; do
  RSYNC_OPTS+=(--exclude="$pattern")
done

if [[ $APPLY_CHANGES -eq 0 ]]; then
  RSYNC_OPTS+=(--dry-run)
fi

if [[ $APPLY_CHANGES -eq 1 ]]; then
  detect_target_owner_group
  create_remote_backup

  if [[ -x "$GENERATE_SITEMAP_SCRIPT" ]]; then
    echo "==> Generating sitemap.xml"
    "$GENERATE_SITEMAP_SCRIPT"
  else
    echo "==> Sitemap generator not found: skipping sitemap.xml regeneration"
  fi
fi

if command -v npx >/dev/null 2>&1; then
  if [[ -f "$LOCAL_PUBLIC_DIR/css/style.css" ]]; then
    echo "==> Minifying CSS (style.css → style.min.css) via postcss+cssnano"
    npx postcss "$LOCAL_PUBLIC_DIR/css/style.css" --use cssnano -o "$LOCAL_PUBLIC_DIR/css/style.min.css" --no-map
  else
    echo "==> CSS file not found for minification: $LOCAL_PUBLIC_DIR/css/style.css"
  fi
else
  echo "==> npx not found: skipping CSS minification. Install Node.js and npm to enable."
fi

echo "==> Ensuring remote directories exist"
if [[ $APPLY_CHANGES -eq 1 ]]; then
  ssh "${SSH_BASE_OPTS[@]}" "$TARGET" "mkdir -p '$REMOTE_PUBLIC_DIR' '$REMOTE_APP_DIR' '$REMOTE_APP_DIR/includes' '$REMOTE_APP_DIR/pages' && chown '$TARGET_OWNER_GROUP' '$REMOTE_PUBLIC_DIR' '$REMOTE_APP_DIR' '$REMOTE_APP_DIR/includes' '$REMOTE_APP_DIR/pages'"
else
  ssh "${SSH_BASE_OPTS[@]}" "$TARGET" "mkdir -p '$REMOTE_PUBLIC_DIR' '$REMOTE_APP_DIR/includes' '$REMOTE_APP_DIR/pages'"
fi

echo "==> Syncing application PHP"
sync_with_tracking "$LOCAL_APP_DIR/includes" "$REMOTE_APP_DIR/includes" 1
sync_with_tracking "$LOCAL_APP_DIR/pages" "$REMOTE_APP_DIR/pages" 1

echo "==> Syncing public web root items"
for item in "${PUBLIC_ITEMS[@]}"; do
  local_path="$LOCAL_PUBLIC_DIR/$item"
  if [[ ! -e "$local_path" ]]; then
    echo "Skipping missing local item: $local_path"
    continue
  fi

  if [[ -d "$local_path" ]]; then
    sync_with_tracking "$local_path" "$REMOTE_PUBLIC_DIR/$item" 1
  else
    sync_with_tracking "$local_path" "$REMOTE_PUBLIC_DIR/$item" 0
  fi
done

if [[ $APPLY_CHANGES -eq 0 ]]; then
  print_sync_summary
  echo "==> Dry-run complete. Re-run with --apply to deploy."
  exit 0
fi

if [[ $RUN_CHECKS -eq 1 ]]; then
  apply_target_ownership

  echo "==> Running HTTP smoke tests"
  URLS=(
    "$BASE_URL/"
    "$BASE_URL/compagnie"
    "$BASE_URL/contact"
    "$BASE_URL/ateliers"
    "$BASE_URL/security.txt"
    "$BASE_URL/.well-known/security.txt"
    "$BASE_URL/.well-known/pgp-key.txt"
    "$BASE_URL/politique-de-securite"
  )

  for url in "${URLS[@]}"; do
    status="$(curl -ksS -o /dev/null -w '%{http_code}' "$url")"
    echo "$status $url"
    if [[ "$status" != "200" ]]; then
      echo "Smoke test failed for $url" >&2
      exit 1
    fi
  done
fi

if [[ $RUN_CHECKS -eq 0 ]]; then
  apply_target_ownership
fi

print_sync_summary
run_post_deploy_checks

# 🌟 DÉCLENCHEMENT DE LA SAUVEGARDE AUTOMATIQUE SUR GITHUB
push_to_github

echo "==> Déploiement terminé avec succès"