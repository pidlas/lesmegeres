#!/usr/bin/env bash
# Legacy wrapper. Canonical script: scripts/check-post-deploy.sh
SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
exec "$SCRIPT_DIR/scripts/check-post-deploy.sh" "$@"
