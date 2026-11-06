#!/bin/bash
# Script de minification CSS
# Usage: bash minify-css.sh

CSS_SRC="/var/www/lesmegeres/css/style.css"
CSS_MIN="/var/www/lesmegeres/css/style.min.css"

echo "🔧 Minification CSS..."
echo "Source: $CSS_SRC"
echo "Output: $CSS_MIN"

# Vérifier que PostCSS/cssnano est installé
if command -v npx &> /dev/null; then
    echo "✓ npx trouvé"
    
    # Essayer avec PostCSS si les dépendances sont présentes
    if npx cssnano --version &> /dev/null; then
        echo "✓ cssnano trouvé"
        npx cssnano "$CSS_SRC" > "$CSS_MIN"
        echo "✅ CSS minifié avec cssnano"
    else
        echo "⚠️  cssnano non installé. Tentative avec un simple minificateur..."
        # Simple minification : supprimer espaces/commentaires
        cat "$CSS_SRC" | \
            sed 's/\/\*.*\*\///g' | \
            sed 's/[ \t]*:[ \t]*/:/g' | \
            sed 's/[ \t]*{[ \t]*/\{/g' | \
            sed 's/[ \t]*}[ \t]*/\}/g' | \
            sed 's/[ \t]*;[ \t]*/;/g' | \
            sed 's/[ \t]*,[ \t]*/,/g' | \
            tr -s ' ' | \
            sed 's/^ *//g' | \
            sed '/^[[:space:]]*$/d' | \
            tr '\n' ' ' | \
            sed 's/ \}/\}/g' | \
            sed 's/ {/{/g' > "$CSS_MIN"
        echo "✅ CSS minifié (simple)"
    fi
else
    echo "❌ npx non trouvé. Minification simple..."
    cat "$CSS_SRC" | \
        sed 's/\/\*.*\*\///g' | \
        tr '\n' ' ' | \
        sed 's/ \}/\}/g' | \
        sed 's/ {/{/g' > "$CSS_MIN"
    echo "✅ CSS minifié (simple)"
fi

echo ""
echo "📊 Taille:"
echo "  Source: $(wc -c < "$CSS_SRC") bytes"
echo "  Minifié: $(wc -c < "$CSS_MIN") bytes"
echo "  Ratio: $(echo "scale=1; 100 * $(wc -c < "$CSS_MIN") / $(wc -c < "$CSS_SRC")" | bc)%"
echo ""
echo "✅ Minification complète!"
