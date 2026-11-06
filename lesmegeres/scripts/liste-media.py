# liste-media.py
import re

# Chemin vers votre fichier (à adapter si nécessaire)
nom_fichier = "_components.css"

print(f"--- Analyse des requêtes @media screen dans {nom_fichier} ---")

try:
    with open(nom_fichier, 'r', encoding='utf-8') as f:
        for num_ligne, ligne in enumerate(f, start=1):
            # On retire les espaces ou tabulations au début et à la fin de la ligne
            ligne_nettoyee = ligne.strip()
            
            # Vérifie si la ligne commence exactement par @media screen
            if ligne_nettoyee.startswith("@media screen"):
                print(f"Ligne {num_ligne} : {ligne_nettoyee}")
                
except FileNotFoundError:
    print(f"Erreur : Le fichier '{nom_fichier}' est introuvable. Placez le script dans le même dossier.")
