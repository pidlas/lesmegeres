# Documentation SEO - Les Mégères de l'Humus

Public cible: administrateur technique
Quand utiliser ce document: comprendre les choix SEO techniques et editoriaux deja appliques
Prerequis: acces aux fichiers header.php, pages PHP, robots.txt et sitemap.xml
Temps de lecture: 12 a 15 min
Statut: reference technique

Périmètre: environnement local et production

## Resume executif

Ce document decrit les travaux SEO deja realises sur le site: metadata dynamiques, canonical, robots/sitemap, structure Hn, hygiene HTML et enrichissement editorial.
Il sert de reference de reprise et de controle qualite apres deploiement.

## Par ou commencer selon le besoin

1. Besoin "deploiement/verification rapide": lire sections 2.4, 9 et 10.
2. Besoin "snippet Google/CTR": lire sections 2.1, 5 et 10.
3. Besoin "structure des pages": lire sections 3 et 4.
4. Besoin "accessibilite et hygiene HTML": lire section 6.
5. Besoin "menu/navigation": lire section 7.

## Index prioritaire (acces rapide)

- 2) Changements techniques SEO
- 3) Structure semantique (Hn)
- 5) Optimisation titles/meta pour clic + local
- 6) Hygiene HTML/SEO additionnelle
- 9) Validation realisee
- 10) Recommandations pour la mise en production
- 11) Restauration rapide (si besoin)

## Notes d'usage

- Ce document explique le pourquoi et le comment des changements, pas une checklist operationnelle.
- Pour l'execution recurrente, utiliser d'abord seo-checklist-proprietaires.md.
- En cas d'ecart entre comportement constate et contenu ici, prioriser l'etat reel du code puis mettre ce document a jour.

## 1) Objectif de la passe SEO

Cette passe avait 2 objectifs principaux:

1. Corriger les fondamentaux techniques SEO (metadata, canonical, discoverability, structure de titres).
2. Renforcer les pages faibles en contenu pour améliorer la compréhension par les moteurs et le taux de clic.

---

## 2) Changements techniques SEO

### 2.1. Metadata dynamiques par page

Fichier principal:
- header.php

Actions réalisées:
- Mise en place d'une map metadata par slug de page (`$pageMetadata`)
- Génération dynamique de:
  - `<title>`
  - `<meta name="description">`
  - `<link rel="canonical">`
  - Open Graph (`og:title`, `og:description`, `og:url`, `og:image`, etc.)
  - Twitter Card (`twitter:title`, `twitter:description`, `twitter:image`)

Impact:
- Suppression du modèle "title/description identiques pour toutes les pages".
- Snippets mieux différenciés page par page.

### 2.2. Canonical + base URL configurable

Fichier principal:
- header.php

Actions réalisées:
- Ajout d'une base URL configurable via `SITE_BASE_URL`.
- Fallback intelligent:
  - host détecte si non local
  - sinon fallback `https://lesmegeresdelhumus.fr`

Impact:
- Canonicals cohérents en dev/staging/prod.
- Moins de risque d'URL canonique erronée lors du deploiement.

### 2.3. Données structurées

Fichier principal:
- header.php

Actions réalisées:
- Ajout d'un script JSON-LD avec:
  - `Organization`
  - `WebSite`
  
**Où est ce script ? Quel est ce script ?**

Impact:
- Meilleure compréhension de l'entité de marque et du site par les moteurs.

### 2.4. Robots et sitemap

Fichiers:
- robots.txt
- sitemap.xml

Actions réalisées:
- Création de `robots.txt` avec déclaration du sitemap.
- Création de `sitemap.xml` avec les URLs principales du site.

Impact:
- Crawl plus propre.
- Base prête pour Search Console de Google.

---

## 3) Structure sémantique (Hn)

### 3.1. Rééquilibrage H1/H2

Pages modifiées:
- pages/compagnie.php
- pages/gresilhette.php
- pages/tagrand-mere.php
- pages/mentions-legales.php
- pages/contact.php
- pages/rapport-2025.php
- index.php

Actions réalisées:
- Conservation d'un H1 principal par page (quand pertinent).
- Conversion de certains anciens H1 secondaires en H2.
- Ajout d'un H1 manquant sur certaines pages (ex: contact, rapport).
- Enrichissement de la home avec un H1 clair + intro.

Impact:
- Hiérarchie sémantique plus lisible pour SEO et accessibilité.

### 3.2. Conservation du rendu visuel ancien

Fichier CSS:
- css/style.css

Actions réalisées:
- Création d'une classe `.display-title` pour garder un rendu "grand titre" sur certains H2 convertis.
- Application sur les sections qui avaient un rendu de type H1 (ex: La Lenga Occitana, Extrait, etc.).

Impact:
- SEO et sémantique préservés sans perdre la direction graphique demandée.

---

## 4) Contenu éditorial enrichi

### 4.1. Pages initialement très faibles (ou quasi vides) enrichies

Pages enrichies:
- pages/theatre-d-ombres.php
- pages/sieste-contee.php
- pages/projet-pedagogique.php
- pages/rapport-2025.php
- pages/stages.php
- pages/partenaires.php
- pages/actualites.php
- pages/ateliers.php
- pages/presentation.php
- pages/forum-jeunesse.php
- pages/forum-adulte.php
- index.php

Actions réalisées:
- Ajout de paragraphes de contexte
- Ajout de formulations orientées public/intention
- Ajout d'ancres de contact quand pertinent

Impact:
- Réduction des pages "thin content".
- Meilleure compréhension thématique des pages.

---

## 5) Optimisation titles/meta pour clic + local

Fichier:
- header.php

Actions réalisées:
- Réécriture fine de nombreuses entrées metadata avec:
  - intention utilisateur (spectacle, atelier, forum, contact)
  - ancrage local (Aude, Moussoulens, Carcassonne, Occitanie)
  - formulations plus incitatives au clic

Pages cibles principales:
- actualites
- compagnie
- equipe
- partenaires
- gresilhette
- theatre-d-ombres
- sieste-contee
- projet-pedagogique
- presentation
- forum-jeunesse
- forum-adulte
- ateliers
- cours
- stages
- contact
- rapport-2024
- rapport-2025

---

## 6) Hygiène HTML/SEO additionnelle

### 6.1. Liens externes

Pages touchées (sélection):
- pages/bastien.php
- pages/delphine.php
- pages/marie.php
- pages/credits.php
- pages/sabine.php
- pages/suzie.php
- pages/partenaires.php
- pages/rapport-2025.php
- pages/mentions-legales.php
- header.php
- footer.php

Actions réalisées:
- Ajout de `rel="noopener noreferrer"` sur les liens `target="_blank"`.
- Suppression d'attributs `alt` invalides sur balises `<a>`.

Impact:
- HTML plus propre, sécurité des liens externes améliorée.

### 6.2. Images décoratives

Pages touchées:
- nombreuses pages avec motifs `champignons`/`feuilles`
- page contact (icônes)

Actions réalisées:
- Passage en `alt=""` pour les visuels décoratifs répétitifs.

Impact:
- Meilleure accessibilité (lecteurs d'écran moins pollués).
- Qualité sémantique ameliorée.

## 7) Navigation/menu

Fichiers:
- header.php
- css/style.css
- script/app.js

Actions réalisées:
- Remplacement de faux liens `href="#"` par boutons de menu pour les catégories mère.
- Gestion `aria-expanded` et ouverture/fermeture plus propre des sous-menus.
- Harmonisation visuelle hover/focus du menu.

Impact SEO indirect:
- Structure de navigation plus propre sémantiquement.

## 8) Fichiers créés

- robots.txt
- sitemap.xml
- seo-documentation.md (ce document)

## 9) Validation réalisée

Plusieurs passes de contrôle ont été faites avec vérification d'erreurs sur les fichiers modifiés (PHP/CSS/JS/XML/TXT).
Aucune erreur de syntaxe n'a été retenue sur les lots validés.

## 10) Recommandations pour la mise en production

1. Définir `SITE_BASE_URL` dans l'environnement de prod.
2. Vérifier l'accessibilite publique de:
   - `/robots.txt`
   - `/sitemap.xml`
3. Déclarer le sitemap dans Google Search Console.
4. Faire une passe "snippets" finale (5 pages prioritaires) après publication pour ajuster le CTR réel.
5. Surveiller indexation, pages exclues et performances Core Web Vitals.

## 11) Restauration rapide (si besoin)

Si un lot ne convient pas visuellement:
- Les ajustements de style des H2 sont centralisés dans `css/style.css` (`.display-title` et styles juridiques).
- Les metadata sont centralisées dans `header.php` (`$pageMetadata`).
- Les enrichissements éditoriaux sont locaux à chaque page et peuvent être ajustés indépendamment.
