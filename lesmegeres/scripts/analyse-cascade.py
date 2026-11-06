# Enregistrez ce script sous le nom "analyse_cascade.py" dans le même dossier que votre fichier CSS
import re
import os

def analyser_cascade_css():
    print("=== ANALYSEUR DE CASCADE CSS POUR REQUÊTES MEDIA ===")
    
    # 1. Demande du fichier
    nom_fichier = input("Entrez le nom du fichier CSS (ex: style.css) : ").strip()
    if not os.path.exists(nom_fichier):
        print(f"Erreur : Le fichier '{nom_fichier}' n'existe pas dans ce dossier.")
        return
        
    # 2. Demande du sélecteur
    sélecteur_cible = input("Entrez le sélecteur à analyser (ex: .button-container .card) : ").strip()
    if not sélecteur_cible:
        print("Erreur : Le sélecteur ne peut pas être vide.")
        return

    with open(nom_fichier, 'r', encoding='utf-8') as f:
        contenu = f.read()

    print(f"\n[Recherche en cours pour '{sélecteur_cible}' dans {nom_fichier}...]\n")

    # Regex pour capturer les blocs @media complets (gère les accolades imbriquées)
    blocs_media = re.findall(r'(@media[^{]+\{(?:[^{}]|\{[^{}]*\})*\})', contenu)

    # On nettoie le CSS global pour isoler uniquement les règles de base (hors media)
    css_base = contenu
    for bloc in blocs_media:
        css_base = css_base.replace(bloc, "")

    trouve = False

    # A. Recherche dans les règles de base
    regles_base = re.findall(r'([^{}]+)\{([^}]+)\}', css_base)
    for sel, props in regles_base:
        if sélecteur_cible in sel:
            print("=" * 60)
            print("📍 EMPLACEMENT : RÈGLE DE BASE (Mobile-First)")
            print(f"Sélecteur complet : {sel.strip()}")
            print("-" * 60)
            print(props.strip())
            print("=" * 60 + "\n")
            trouve = True

    # B. Recherche à l'intérieur des blocs @media
    for bloc in blocs_media:
        entete_media = re.match(r'(@media[^{]+)', bloc).group(1).strip()
        # Extraction des sous-règles dans ce media
        regles_internes = re.findall(r'([^{}]+)\{([^}]+)\}', bloc)
        for sel, props in regles_internes:
            if sélecteur_cible in sel and "@media" not in sel:
                print("=" * 60)
                print(f"🌍 EMPLACEMENT : {entete_media}")
                print(f"Sélecteur complet : {sel.strip()}")
                print("-" * 60)
                print(props.strip())
                print("=" * 60 + "\n")
                trouve = True

    if not trouve:
        print(f"❌ Aucune correspondance exacte trouvée pour '{sélecteur_cible}'.")

if __name__ == "__main__":
    analyser_cascade_css()