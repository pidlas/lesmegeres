#!/usr/bin/env bash

set -euo pipefail

FILES=(
    "/var/www/lesmegeres/index.php"
    /var/www/lesmegeres-app/includes/*.php
    /var/www/lesmegeres-app/pages/*.php
)

echo "🔍 Simulation (dry-run) des remplacements (.jpg, .jpeg, .png -> .webp) :"

for file in "${FILES[@]}"; do
    [ -f "$file" ] || continue
    # Affiche les lignes qui seront modifiées (en excluant favicon, apple-touch-icon et og:image)
    grep -niE "\.(jpe?g|png)" "$file" | grep -viE "(favicon|apple-touch-icon|og:image)" | while read -r line; do
        echo "  📄 $file : $line"
    done
done