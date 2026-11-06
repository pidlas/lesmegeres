#!/usr/bin/env bash

set -euo pipefail

HOST="${1:-sante9naturel.fr}"
PERSONAL_PUB="${PERSONAL_PUB:-$HOME/.ssh/id_ed25519.pub}"
DEPLOY_PUB="${DEPLOY_PUB:-$HOME/.ssh/id_ed25519_deploy.pub}"

ok() {
  printf '[OK] %s\n' "$1"
}

warn() {
  printf '[WARN] %s\n' "$1"
}

fail() {
  printf '[FAIL] %s\n' "$1"
}

require_file() {
  local path="$1"
  local label="$2"
  if [[ -f "$path" ]]; then
    ok "$label present: $path"
  else
    fail "$label missing: $path"
    return 1
  fi
}

status=0

echo "== SSH Key Health Check =="
echo "Host: $HOST"

require_file "$PERSONAL_PUB" "Personal public key" || status=1
require_file "$DEPLOY_PUB" "Deploy public key" || status=1

if [[ $status -ne 0 ]]; then
  echo "Aborting: missing local key files."
  exit 1
fi

PERSONAL_FP="$(ssh-keygen -lf "$PERSONAL_PUB" | awk '{print $2}')"
DEPLOY_FP="$(ssh-keygen -lf "$DEPLOY_PUB" | awk '{print $2}')"

echo "Personal key fingerprint: $PERSONAL_FP"
echo "Deploy key fingerprint:   $DEPLOY_FP"

EFFECTIVE_IDENTITY="$(ssh -G "$HOST" 2>/dev/null | awk '/^identityfile / {print $2; exit}')"
if [[ -n "$EFFECTIVE_IDENTITY" ]]; then
  ok "Effective identity for $HOST: $EFFECTIVE_IDENTITY"
else
  warn "Could not resolve effective identity from ssh -G"
fi

REMOTE_KEYS="$(ssh "$HOST" "cat ~/.ssh/authorized_keys 2>/dev/null || true")"
if [[ -z "$REMOTE_KEYS" ]]; then
  fail "Could not read remote authorized_keys for $HOST"
  exit 1
fi

PERSONAL_KEY="$(cat "$PERSONAL_PUB")"
DEPLOY_KEY="$(cat "$DEPLOY_PUB")"

if grep -qxF "$PERSONAL_KEY" <<<"$REMOTE_KEYS"; then
  ok "Personal key is present on server"
else
  fail "Personal key is NOT present on server"
  status=1
fi

if grep -qxF "$DEPLOY_KEY" <<<"$REMOTE_KEYS"; then
  ok "Deploy key is present on server"
else
  fail "Deploy key is NOT present on server"
  status=1
fi

if ssh "$HOST" "echo connected" >/dev/null 2>&1; then
  ok "SSH connection test passed"
else
  fail "SSH connection test failed"
  status=1
fi

if [[ $status -eq 0 ]]; then
  echo "Result: HEALTHY"
else
  echo "Result: ISSUES_FOUND"
fi

exit $status
