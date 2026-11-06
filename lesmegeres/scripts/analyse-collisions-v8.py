# analyse-collisions-v8.py
import re
import os

nom_fichier = "style.css"

def charger_fichier_css(chemin):
    if not os.path.exists(chemin):
        print(f"❌ Erreur : Le fichier '{chemin}' n'a pas pu être trouvé.")
        return None
    with open(chemin, 'r', encoding='utf-8') as f:
        return f.read()

def nettoyer_texte(texte):
    """
    Normalisation absolue du texte CSS :
    1. Réduit tous les types d'espaces blancs/sauts de ligne à un espace unique.
    2. Force un formatage strict et identique autour des combinateurs (+, >, ~).
    """
    if not texte:
        return ""
    texte_unifie = re.sub(r'\s+', ' ', texte).strip()
    texte_unifie = re.sub(r'\s*([+>~])\s*', r' \1 ', texte_unifie)
    return re.sub(r'\s+', ' ', texte_unifie).strip()

def extraire_structures_css(contenu_complet):
    """Analyse le CSS caractère par caractère pour isoler la zone globale et capturer les blocs @media."""
    contenu_propre = re.sub(r'/\*.*?\*/', '', contenu_complet, flags=re.DOTALL)
    css_global_pur = []
    blocs_media = {}
    i = 0
    longueur = len(contenu_propre)
    
    while i < longueur:
        if contenu_propre[i].isspace():
            i += 1
            continue
            
        if contenu_propre[i] == '@':
            debut_regle = i
            while i < longueur and contenu_propre[i] != '{':
                i += 1
            entete = contenu_propre[debut_regle:i].strip()
            i += 1
            
            compteur_accolades = 1
            debut_contenu = i
            while i < longueur and compteur_accolades > 0:
                if contenu_propre[i] == '{':
                    compteur_accolades += 1
                elif contenu_propre[i] == '}':
                    compteur_accolades -= 1
                i += 1
            fin_contenu = i - 1
            contenu_bloc = contenu_propre[debut_contenu:fin_contenu]
            
            if entete.startswith('@media'):
                nom_media = nettoyer_texte(entete)
                blocs_media[nom_media] = contenu_bloc
            continue
        else:
            debut_selecteur = i
            while i < longueur and contenu_propre[i] != '{':
                i += 1
            if i >= longueur:
                break
                
            selecteur = contenu_propre[debut_selecteur:i]
            i += 1
            
            compteur_accolades = 1
            debut_declarations = i
            while i < longueur and compteur_accolades > 0:
                if contenu_propre[i] == '{':
                    compteur_accolades += 1
                elif contenu_propre[i] == '}':
                    compteur_accolades -= 1
                i += 1
            fin_declarations = i - 1
            declarations = contenu_propre[debut_declarations:fin_declarations]
            
            css_global_pur.append(f"{selecteur}{{{declarations}}}")
            
    return "\n".join(css_global_pur), blocs_media

def parser_bloc_en_dictionnaire(texte_css):
    """Extrait les sélecteurs et les convertit en dictionnaire."""
    regles = {}
    if not texte_css.strip():
        return regles
    
    blocs = re.findall(r'([^{]+)\{([^}]+)\}', texte_css)
    for selecteurs_bruts, declarations in blocs:
        liste_selecteurs = selecteurs_bruts.split(',')
        for sel in liste_selecteurs:
            sel_nettoye = nettoyer_texte(sel)
            if not sel_nettoye:
                continue
            if sel_nettoye not in regles:
                regles[sel_nettoye] = {}
            
            lignes_prop = declarations.split(';')
            for ligne in lignes_prop:
                if ':' in ligne:
                    prop, val = ligne.split(':', 1)
                    regles[sel_nettoye][prop.strip()] = nettoyer_texte(val)
    return regles

def afficher_collisions(regles_globales, regles_media, nom_media):
    print(f"\n=======================================================")
    print(f"📊 ANALYSE DES SÉLECTEURS DUBLIQUÉS POUR : {nom_media}")
    print(f"=======================================================\n")
    
    for selecteur, props_media in regles_media.items():
        # CAS 1 : Le sélecteur existe dans les deux mondes (C'est ce que vous recherchez !)
        if selecteur in regles_globales:
            print(f"💥 SÉLECTEUR COMMUN détecté : {selecteur}")
            props_globales = regles_globales[selecteur]
            
            for prop, val_media in props_media.items():
                if prop in props_globales:
                    val_globale = props_globales[prop]
                    print(f"  ⚠️ ÉCRASEMENT direct sur '{prop}' :")
                    print(f"    - Valeur de base  : {val_globale}")
                    print(f"    - Valeur mobile   : {val_media}")
                else:
                    print(f"  🧩 Surcharge structurelle sur '{prop}' (absent de la base globale) :")
                    print(f"    - Valeur mobile   : {val_media}")
            print("-" * 50)
            
        # CAS 2 : Le sélecteur n'existe qu'en mobile
        else:
            print(f"🆕 Sélecteur unique à ce @media : {selecteur}")
            for prop, val_media in props_media.items():
                print(f"  ➕ {prop}: {val_media}")
            print("-" * 50)

def main():
    contenu = charger_fichier_css(nom_fichier)
    if not contenu:
        return
    css_global_texte, dictionnaire_blocs_media = extraire_structures_css(contenu)
    regles_globales = parser_bloc_en_dictionnaire(css_global_texte)
    liste_media = list(dictionnaire_blocs_media.keys())
    if not liste_media:
        print("Aucune règle @media n'a été détectée.")
        return
        
    print("--- MENU DE SÉLECTION DES MEDIA QUERIES ---")
    for i, mq in enumerate(liste_media, 1):
        print(f"[{i}] {mq}")
        
    try:
        choix = int(input(f"\nEntrez le numéro du bloc à analyser (1-{len(liste_media)}) : "))
        if 1 <= choix <= len(liste_media):
            media_choisi = liste_media[choix - 1]
            texte_bloc_choisi = dictionnaire_blocs_media[media_choisi]
            regles_media = parser_bloc_en_dictionnaire(texte_bloc_choisi)
            afficher_collisions(regles_globales, regles_media, media_choisi)
        else:
            print("❌ Numéro invalide.")
    except ValueError:
        print("❌ Veuillez entrer un nombre valide.")

if __name__ == "__main__":
    main()