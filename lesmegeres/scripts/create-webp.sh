#!/usr/bin/env bash

set -euo pipefail

# Les répertoires exacts de votre site PHP
DIRS=(
    "/var/www/lesmegeres"
    "/var/www/lesmegeres-app/includes"
    "/var/www/lesmegeres-app/pages"
)

echo "🎨 Recherche et conversion des images (.jpg, .jpeg, .png) en .webp (qualité 80)..."

for dir in "${DIRS[@]}"; do
    if [ ! -d "$dir" ]; then
        echo "⚠️  Dossier ignoré (introuvable) : $dir"
        continue
    fi

    echo "📂 Analyse de : $dir"

    find "$dir" -type f \
        \( -iname "*.png" -o -iname "*.jpg" -o -iname "*.jpeg" \) \
        -not -iname "*favicon*" \
        -not -iname "*apple-touch-icon*" | while read -r img; do
        
        ext="${img##*.}"
        webp_file="${img%.$ext}.webp"

        if [ ! -f "$webp_file" ]; then
            echo "  ⚡ Conversion [${ext^^} -> WEBP] de : $img"
            magick "$img" -strip -quality 80 "$webp_file"
        fi
    done
done

echo "✅ Analyse et conversions WebP terminées !"