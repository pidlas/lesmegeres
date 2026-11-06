#!/usr/bin/env bash

set -euo pipefail

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
REPO_ROOT="$(cd "$SCRIPT_DIR/.." && pwd)"
APP_DIR="$REPO_ROOT/../lesmegeres-app"
OUTPUT_FILE="$REPO_ROOT/sitemap.xml"
BASE_URL="${BASE_URL:-https://lesmegeresdelhumus.fr}"

require_cmd() {
  if ! command -v "$1" >/dev/null 2>&1; then
    echo "Commande manquante: $1" >&2
    exit 1
  fi
}

require_cmd stat
require_cmd mktemp

if [[ ! -d "$APP_DIR/pages" ]]; then
  echo "Répertoire pages introuvable: $APP_DIR/pages" >&2
  exit 1
fi

tmp_file="$(mktemp)"
mapfile -t page_files < <(printf '%s\n' "$APP_DIR/pages"/*.php | sort)

lastmod_from_file() {
  local source_file="$1"
  stat -c '%y' "$source_file" | cut -d' ' -f1
}

{
  echo '<?xml version="1.0" encoding="UTF-8"?>'
  echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'

  printf '  <url>\n'
  printf '    <loc>%s</loc>\n' "$BASE_URL/"
  printf '    <lastmod>%s</lastmod>\n' "$(lastmod_from_file "$REPO_ROOT/index.php")"
  printf '  </url>\n'

  for item in "${page_files[@]}"; do
    slug="$(basename "$item" .php)"
    printf '  <url>\n'
    printf '    <loc>%s</loc>\n' "$BASE_URL/$slug"
    printf '    <lastmod>%s</lastmod>\n' "$(lastmod_from_file "$item")"
    printf '  </url>\n'
  done

  echo '</urlset>'
} > "$tmp_file"

mv "$tmp_file" "$OUTPUT_FILE"