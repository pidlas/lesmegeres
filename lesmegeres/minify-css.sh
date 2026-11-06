#!/usr/bin/env bash
# Legacy wrapper. Canonical script: scripts/minify-css.sh
SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
exec "$SCRIPT_DIR/scripts/minify-css.sh" "$@"
