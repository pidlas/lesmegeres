#!/usr/bin/env bash
# Minifie automatiquement style.css en style.min.css à chaque modification
# Nécessaire en local car c'est le style.min.css qui s'exécute
# En production la minification se fait avant le déploiement : style.css et style.min.css sont donc transférés modifiés en même temps
# Usage : ./watch-css.sh

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
REPO_ROOT="$(cd "$SCRIPT_DIR/.." && pwd)"
CSS_SRC="$REPO_ROOT/css/style.css"
CSS_MIN="$REPO_ROOT/css/style.min.css"

while inotifywait -e close_write "$CSS_SRC"; do
  echo "==> Minification de style.css..."
  (cd "$REPO_ROOT" && npx postcss "$CSS_SRC" --use cssnano -o "$CSS_MIN" --no-map)
  if [[ $? -eq 0 ]]; then
    echo "Minification réussie."
  else
    echo "Erreur lors de la minification !"
  fi
done
