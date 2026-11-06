#!/usr/bin/env bash
# Legacy wrapper. Canonical script: scripts/watch-css.sh
SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
exec "$SCRIPT_DIR/scripts/watch-css.sh" "$@"
