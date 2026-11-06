# analyse-collisions.py
import re

nom_fichier = "style.css"  # À remplacer par "custom.css" si nécessaire

def extraire_bloc_320(chemin):
    with open(chemin, 'r', encoding='utf-8') as f:
        contenu = f.read()
    
    # Recherche du bloc @media spécifique et capture de son contenu entre accolades
    # Cette regex gère les accolades imbriquées simples du bloc media
    match = re.search(r'@media\s+screen\s+and\s+\(\s*max-width\s*:\s*320px\s*\)\s*\{(.*?)\}\s*(?=@media|\Z)', contenu, re.DOTALL)
    if not match:
        return None, contenido
    
    bloc_320 = match.group(1)
    # Partie haute : tout ce qui précède les media queries (on coupe au premier @media)
    partie_haute = contenu.split('@media')[0]
    return bloc_320, partie_haute

def parser_css_en_dictionnaire(texte_css):
    # Extrait les sélecteurs et leurs blocs de déclarations
    regles = {}
    blocs = re.findall(r'([^{]+)\{([^}]+)\}', texte_css)
    
    for selecteur, declarations in blocs:
        sel_nettoye = selecteur.strip()
        if sel_nettoye not in regles:
            regles[sel_nettoye] = {}
        
        # Extrait chaque couple propriété: valeur
        lignes_prop = declarations.split(';')
        for ligne in lignes_prop:
            if ':' in ligne:
                prop, val = ligne.split(':', 1)
                regles[sel_nettoye][prop.strip()] = val.strip()
    return regles

try:
    bloc_320_texte, haut_texte = extraire_bloc_320(nom_fichier)
    
    if bloc_320_texte is None:
        print("Le bloc @media screen and (max-width: 320px) n'a pas été trouvé.")
    else:
        regles_320 = parser_css_en_dictionnaire(bloc_320_texte)
        regles_haut = parser_css_en_dictionnaire(haut_texte)
        
        print(f"=== ANALYSE DES COLLISIONS POUR MAX-WIDTH: 320px ===")
        
        for selecteur, props_320 in regles_320.items():
            print(f"\nSélecteur ciblé : {selecteur}")
            
            # Vérifie si le sélecteur existe en haut
            if selecteur in regles_haut:
                props_haut = regles_haut[selecteur]
                
                for prop, val_320 in props_320.items():
                    # Vérifie si la propriété exacte existe aussi en haut
                    if prop in props_haut:
                        val_haut = props_haut[prop]
                        print(f"  ⚠️ COLLISION détectée sur '{prop}' :")
                        print(f"    - Valeur globale (en haut) : {val_haut}")
                        print(f"    - Valeur micro-mobile (320px): {val_320}")
                    else:
                        print(f"  ➕ Propriété '{prop}' unique au bloc 320px (Valeur: {val_320})")
            else:
                print(f"  ❌ Le sélecteur '{selecteur}' n'existe pas du tout dans la partie haute.")

except FileNotFoundError:
    print(f"Erreur : Le fichier '{nom_fichier}' n'a pas pu être trouvé.")