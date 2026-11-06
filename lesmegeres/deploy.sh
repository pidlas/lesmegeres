#!/usr/bin/env bash
# Legacy wrapper. Canonical script: scripts/deploy.sh
SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
exec "$SCRIPT_DIR/scripts/deploy.sh" "$@"
