#!/bin/bash

# Définissez ici le répertoire obligatoire
REPERTOIRE_ATTENDU="/var/www/lesmegeres-app/pages/"

# Récupère le répertoire où se trouve physiquement le script
REPERTOIRE_ACTUEL="$(cd "$(dirname "$0")" && pwd)"

# Vérification
if [ "$REPERTOIRE_ACTUEL" != "$REPERTOIRE_ATTENDU" ]; then
    echo "Erreur : Ce script doit impérativement se trouver dans le dossier : $REPERTOIRE_ATTENDU"
    exit 1
fi

echo "Début de la migration"

for file in *.html; do
    [ -e "$file" ] || continue
    
    folder="${file%.html}"
    folder_slug=$(echo "$folder" | tr '[:upper:]' '[:lower:]' | tr ' _' '-')
    mkdir -p "$folder_slug"
    
    echo "Traitement natif de : $file -> $folder_slug/default.fr.md"
    
    # 1. Extraction du titre (cherche la première balise <h1> ou <h2>)
    title=$(sed -n 's/.*<h1>\(.*\)<\/h1>.*/\1/p' "$file" | head -n 1)
    if [ -z "$title" ]; then
        title=$(sed -n 's/.*<h2[^>]*>\(.*\)<\/h2>.*/\1/p' "$file" | head -n 1)
    fi
    if [ -z "$title" ]; then
        title="${folder//_/ }"
    fi

    # 2. Copie du contenu d'origine dans le fichier temporaire
    cp "$file" temp.md

    # 3. NETTOYAGE DU PHP DANS LES MÉDIAS (Images, Audios, Liens)
    # Extrait les fichiers audios (ex: /multimedia/fichier.mp3 -> fichier.mp3)
    sed -i "s/<?php echo htmlspecialchars(site_path('\/multimedia\/\([^']*\)'),[^>]*?>/\1/g" temp.md
    
    # Extrait les images (ex: /images/photo.jpg -> photo.jpg)
    sed -i "s/<?php echo htmlspecialchars(site_path('\/images\/\([^']*\)'),[^>]*?>/\1/g" temp.md
    
    # Extrait les liens internes (ex: /contact -> contact)
    sed -i "s/<?php echo htmlspecialchars(site_path('\/\([^']*\)'),[^>]*?>/\1/g" temp.md

    # 4. NETTOYAGE DES ATTRIBUTS PARASITES
    # Nettoie les attributs data-type et les spans vides pour les liens
    sed -i 's/ data-type="external"//g' temp.md
    sed -i 's/ data-type="internal"//g' temp.md
    sed -i 's/<span class="lien-[ei]-seul"><\/span>//g' temp.md

    # Traduit les entités HTML en vrais caractères lisibles
    sed -i 's/&ldquo;/“/g' temp.md
    sed -i 's/&rdquo;/”/g' temp.md
    sed -i 's/&nbsp;/ /g' temp.md
    sed -i 's/&middot;/·/g' temp.md

    # 5. ASSEMBLAGE DU FICHIER FINAL POUR GRAV
    {
        echo "---"
        echo "title: \"${title}\""
        echo "---"
        echo ""
        cat temp.md
    } > "$folder_slug/default.fr.md"

    # 6. Suppression du fichier temporaire
    rm temp.md
done

echo "Migration terminée ! Vos structures HTML sont 100% préservées."