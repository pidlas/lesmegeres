# Reprise projet - État actuel

Objectif de ce document:
- repartir rapidement du bon état du projet,
- savoir ce qui a été corrigé,
- savoir quoi tester avant/après déploiement,
- savoir quels fichiers transférer selon le besoin.

---

## 1) [État fonctionnel actuel](#1-état-fonctionnel-actuel)
## 2) [Fichiers clefs modifiés](#2-fichiers-clefs-modifiés)
## 3) [Ce que fait deploy.sh maintenant](#3-ce-que-fait-deploysh-maintenant)
## 4) [Procédure de reprise (prochaine session)](#4-procédure-de-reprise-prochaine-session)
## 5) [Checklist test rapide mobile](#5-checklist-test-rapide-mobile)
## 6) [Si on doit reprendre une correction précise](#6-si-on-doit-reprendre-une-correction-précise)

---
## 1) État fonctionnel actuel

### Menu hamburger mobile
- Le menu hamburger est stabilisé sur les paliers mobiles.
- Les sous-menus mobiles sont gérés de manière plus robuste au tactile:
  - ouverture par action JavaScript (`.menu.is-open`),
  - prévention des clics accidentels immédiats sur le premier lien du sous-menu,
  - gestion `touchstart` sur les boutons de sous-menu pour fiabiliser le premier appui.

### Responsive global
- Le comportement responsive a été ajusté sur plusieurs paliers (notamment 620/768/960px).
- Correction des débordements horizontaux via contraintes de largeur (`min-width:0` et ajustements associés).
- Stabilisation de l'affichage des titres superposés sur les pages concernées.

### Débordement
- Réductions de tailles d'images par paliers pour limiter les débordements visuels.

### Page Équipe - grille de photos
- Correction du bug d'affichage de l'image item10 (Philippe Guerrini) qui s'affichait trop petite (139-211px au lieu de 250px).
- Cause racine: `place-items:center` sur `.g_equipe` activait `justify-items:center`, empêchant les items de s'étirer dans leur colonne; combiné a `max-width:100%` sur les images, cela créait une dependance circulaire de largeur.
- Corrections dans [css/style.css](css/style.css):
  - palier 769px: `repeat(2, minmax(0, 1fr))` + `justify-items:stretch`
  - palier 960px: `repeat(3, minmax(0, 1fr))` + `justify-items:stretch`
  - palier 1200px: `repeat(4, minmax(0, 1fr))` + `justify-items:stretch` ; `justify-self:center` sur items 1/2/3/6 (2 colonnes) pour les recentrer visuellement.

### Déploiement
- Le script [deploy.sh](deploy.sh) a été amelioré:
  - normalisation automatique owner:group des fichiers transférés,
  - récapitulatif des fichiers concernés en dry-run,
  - récapitulatif des fichiers transférés en apply,
  - compteur des ownership appliqués,
  - exclusions des fichiers de sauvegarde et fichiers système parasites.

## 2) Fichiers clefs modifiés

### Front / comportement
- [css/style.css](css/style.css)
- [script/app.js](script/app.js)
- [script/audio.js](script/audio.js)

### Templates / pages
- [../lesmegeres-app/includes/header.php](../lesmegeres-app/includes/header.php)
- [../lesmegeres-app/pages/projet-pedagogique.php](../lesmegeres-app/pages/projet-pedagogique.php)
- [../lesmegeres-app/pages/theatre-d-ombres.php](../lesmegeres-app/pages/theatre-d-ombres.php)
- [../lesmegeres-app/pages/sieste-contee.php](../lesmegeres-app/pages/sieste-contee.php)

### Déploiement
- [deploy.sh](deploy.sh)

### Documentation existante
La documentation est présente dans `/home/pilipe/Documents/Winformatique/srv-web-pic/lesmegeresdelhumus.fr/doc`au format Markdown :

* ajouter-une-actu-rapide.md : alimenter manuellement la page /actualites à partir du compte facebook et instagram des Mégères de l'Humus.
* avant-mise-en-production.md : comment activer le serveur local et comment passer en production sur le serveur sante9naturel.fr.
* checklist-production-5-lignes.md : mise en production résumée en 5 lignes.
* checklist-production-10-lignes.md : mise en production résumée en 10 lignes.
* doc_DIAGNOSTIC-YUNOHOST-SITE1.md : rendre le serveur NGINX conforme à l'architecture du serveur de production sous YunoHost. **Installée à la racine de /site1/**
* doc_guide-administrateur.md : ce guide à remettre aux propriétaires. **Installée à la racine de /site1/**
* guide-mobile-s21-maintenance.md : modifications de style pour un affichage correct sur SAMSUNG S21.
* GUIDE-SPLIT-COLOR-TITLES.md : explique comment reproduire le titre split-color sur les pages theatre-d-ombres, sieste-contee et projet-pedagogique. **Styles retirés**.
* JOUR-J-MISE-EN-PEODUCTION.md : procédure pour le passage de `/site1/` vers `/`
* mode-maintenance.md : configuration du mode en local et sur le serveur
* plan-actualites-api-facebook-instagram.md : remplacer la saisie manuelle du tableau PHP par un fetch automatique des posts dans la page /actualites.
* doc_REPRISE-PROJET-ETAT-ACTUEL-2026-04-02.md : repartir rapidement du bon état du projet, savoir ce qui a été corrigé... **Installée à la racine de /site1/**
* seo-checklist-deploiement.md : liste à suivre pour le déploiement sur le serveur de production.
* seo-checklist-proprietaires-imprimable.md : document à remettre aux propriétaires pour impression.
* seo-checklist-proprietaires.md : document à remettre aux propriétaires
* seo-documentation.md : documentation des modifications apportées au code pour un bon référencement.
* technique-css-notes.md : décisions et choix d'implémentation.
* TECHNIQUE-FULL-BLEED.md : ou comment afficher un élément en pleine largeur viewer ? **technique non appliquée**

## 3) Ce que fait deploy.sh maintenant

### Dry-run
Commande:
```bash
./deploy.sh
```
Affiche:
- les changements rsync détectés,
- un bloc "Summary (DRY-RUN)",
- la liste des chemins distants qui seraient transférés.

### Apply
Commande:
```bash
./deploy.sh --apply
```
Commande recommandée (droits auto, valeur par défaut my_webapp__3:my_webapp__3):
```bash
./deploy.sh --apply
```
Pour activer aussi la vérification owner distante dans le post-check automatique:
```bash
./deploy.sh --apply --verify-post-owners
```
Affiche:
- "Summary (APPLY)",
- la liste des chemins distants transférés,
- le nombre de chemins avec ownership appliqué,
- les éléments ignorés en cas de chemin manquant ou erreur chown.
- exécution automatique de [check-post-deploy.sh](check-post-deploy.sh) en fin d'apply.

Options utiles:
- désactiver le post-check auto: `--skip-post-check`
- garder uniquement les checks deploy internes: `--skip-post-check`

### Règle ownership (important)
- Tous les chemins transférés en mode apply sont normalisés vers un owner:group cible unique.
- Valeur par défaut integrée dans le script: `my_webapp:my_webapp`.
- Pour forcer une valeur différente: `REMOTE_OWNER_GROUP=autre:groupe ./deploy.sh --apply`
- En production, la commande simple suffit:
```bash
./deploy.sh --apply
```

### Exclusions automatiques
Le déploiement ignore notamment:
- `*.old`, `*.old.*`, `*.bak`, `*.bak.*`, `*~`
- `.DS_Store`, `"**/.DS_Store"`, `Thumbs.db`, `"**/Thumbs.db"` 
- `desktop.ini`, `"**/desktop.ini"`, `._*`, `"**/._*"`
- "_inutilise/", "**/_inutilise/"

## 4) Procédure de reprise

Commande production sûre (recommandée):
```bash
./deploy.sh --apply --verify-post-owners
```

1. Ouvrir ce document en premier
2. Lancer un dry-run:
```bash
./deploy.sh
```
3. Vérifier la liste de fichiers:
- si la liste est cohérente, déployer.
- sinon, corriger localement avant apply.
4. Lancer l'apply:
```bash
./deploy.sh --apply
```
Version complète avec vérification owners distante:
```bash
./deploy.sh --apply --verify-post-owners
```
5. Vérifier:
- menu hamburger mobile,
- ouverture des sous-menus au tactile,
- pages theatre-d-ombres / sieste-contee / projet-pedagogique.

## 5) Checklist test rapide mobile

- Hamburger visible et cliquable.
- Un appui sur un menu principal ouvre le sous-menu (sans partir directement sur le 1er lien).
- Le clic suivant sur un lien du sous-menu navigue correctement.
- Aucune disparition du menu/hamburger entre les paliers critiques.

## 6) Si on doit reprendre une correction précise

### Sous-menu encore instable au tactile
- Point principal: [script/app.js](script/app.js)
- Revoir la logique `touchstart`, `click` et délai de protection des liens sous-menu.

### Problème d'alignement responsive/overlays
- Point principal: [css/style.css](css/style.css)
- Revoir les règles des paliers 620/768/960 et la taille max des blocs images.

### Page Equipe - image item10 trop petite (résolu 2026-04-05)
- Point principal: [css/style.css](css/style.css) (blocs media 769px, 960px, 1200px)
- Symptôme: `item10` s'affichait à 139-211px causé par des colonnes implicites CSS Grid et `justify-items:center` créant une dépendance circulaire de largeur.
- Solution: `repeat(N, minmax(0, 1fr))` explicite + `justify-items:stretch` à chaque palier. Items 1/2/3/6 (sur 2 colonnes) ont `justify-self:center` pour rester centrés dans leur zone.

### Problème de droits après déploiement
- Cause 1: SSH_USER=root créait les fichiers en root:root, et l'auto-détection retournait root:root.
- Fix 1: `my_webapp:my_webapp` est désormais la valeur par défaut dans deploy.sh.
- Cause 2: rsync teste par checksum — si aucun fichier n'est transféré, `apply_target_ownership` ne tourne pas, les anciens fichiers restent en root:root, le site retourne HTTP 500.
- Fix 2: option `--fix-owners` ajoutée dans deploy.sh. Elle refait un `chown` récursif sur tout `REMOTE_BASE` sans transfert.
- Commande de correction d'urgence (si 500 après deploy sans fichiers changés):
```bash
./deploy.sh --fix-owners
```
- La commande normale suffit pour un deploy avec changements: `./deploy.sh --apply`
