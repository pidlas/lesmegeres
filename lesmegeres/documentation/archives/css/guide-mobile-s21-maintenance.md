# Guide Maintenance Mobile Samsung S21

Périmètre: correction du header mobile (menu hamburger, logo rond, taille du titre)

---

## 1) [Objectif](#1-objectif)
## 2) [Fichiers modifiés](#2-fichiers-modifiés)
## 3) [Changements appliqués](#3-changements-appliqués)
## 4) [Procédure de retour arrière (sans Git)](#4-procédure-de-retour-arrière-sans-git)
## 5) [Checklist de vérification après modif](#5-checklist-de-vérification-après-modif)
## 6) [Règle de maintenance conseillée](#6-règle-de-maintenance-conseillée)
## 7) [Journal rapide](#7-journal-rapide)
## 8) [Diaporama — refonte des breakpoints](#8-diaporama--refonte-des-breakpoints)
## 9) [Mode carrousel mobile (test propriétaires)](#9-mode-carrousel-mobile-test-propriétaires)
## 10) [Analyse complète et correctif final](#10-analyse-complète-et-correctif-final)
## 11) [Variante desktop retenue: séparation visuelle 1px](#11-variante-desktop-retenue-séparation-visuelle-1px)
## 12) [Compatibilité Chrome - bordures Gresilhette](#12-compatibilité-chrome---bordures-gresilhette)
## 13) [Résume ultra court - retours arrière](#13-résume-ultra-court---retours-arrière)
## 14) [Balayage compatibilité navigateurs](#14-balayage-compatibilité-navigateurs)
## 15) [Sensibilite tactile du menu hamburger (sous-menus)](#15-sensibilite-tactile-du-menu-hamburger-sous-menus) 

---

## 1) Objectif
Ce document vous permet de:
- comprendre exactement ce qui a été modifié,
- revenir en arrière rapidement,
- réappliquer les corrections si besoin,
- vérifier que tout fonctionne après une modification.

## 2) Fichiers modifiés
- [public/css/style.css](css/style.css)
- [lesmegeres-app/includes/header.php](../lesmegeres-app/includes/header.php)

## 3) Changements appliqués

### header.php
Fichier: [lesmegeres-app/includes/header.php](../lesmegeres-app/includes/header.php)

**Logo rendu carré en HTML pour éviter la déformation initiale**
- Avant: width="230px" height="222px"
- Après: width="230" height="230"

**Bouton hamburger relié à la classe de positionnement mobile**
- Avant: class="hamburger box"
- Après: class="menu-petit-ecran hamburger box"

**Effets attendus**
- le bouton hamburger se place correctement en haut à droite,
- le logo ne se déforme plus.

### style.css
Fichier: [public/css/style.css](css/style.css)

**Logo forcé en ratio 1:1 + rendu rond**
- Bloc .morph img ajusté avec:
  - aspect-ratio: 1 / 1
  - object-fit: cover
  - border-radius: 50%

**Animation hover du logo conservée sans ratio cassé**
- Bloc .morph:hover img ajusté pour garder ratio 1:1.

**Taille du titre réduite et plus souple**
- Bloc p.titre ajuste avec clamp plus modéré.
- Ajustements aussi dans les media queries mobiles.

**Hamburger fiabilisé**
- Bloc button.box: position: relative + centrage explicite.
- Bloc .container-lignes: rendu des 3 traits stabilisé.
- Correction syntaxe animation:
  - Avant: top .4s ease-in-out (sans virgule)
  - Après: top .4s ease-in-out, transform .4s .4s ease-in-out

**Effets attendus**
- les 3 traits sont visibles,
- le bouton n'est plus écrasé,
- le titre est plus lisible sur S21.

## 4) Procédure de retour arrière (sans Git)

Option simple (manuelle):
1. Ouvrir [lesmegeres-app/includes/header.php](../lesmegeres-app/includes/header.php).
2. Remettre:
   - img width="230px" height="222px"
   - bouton class="hamburger box"
3. Ouvrir [public/css/style.css](css/style.css).
4. Remettre les anciens blocs:
   - .morph img (width/height d'origine)
   - .morph:hover img (height d'origine)
   - p.titre (ancienne taille)
   - button.box et .container-lignes (ancienne version)
   - animation .box.active .ligne:nth-child(1) (ancienne ligne transition)

Option recommandée (backup local avant toute future modif):
- Créer des copies:
  - cp /var/www/public/css/style.css /var/www/public/css/style.css.bak
  - cp /var/www/lesmegeres-app/includes/header.php /var/www/lesmegeres-app/includes/header.php.bak
- Restaurer si besoin:
  - cp /var/www/public/css/style.css.bak /var/www/public/css/style.css
  - cp /var/www/lesmegeres-app/includes/header.php.bak /var/www/lesmegeres-app/includes/header.php

## 5) Checklist de vérification après modif
Sur Samsung S21:
- Ouvrir la page d'accueil
- Vérifier que le logo est bien rond
- Vérifier que le titre ne déborde pas
- Vérifier que le bouton hamburger est visible
- Ouvrir le menu et vérifier que les 3 traits restent lisibles
- Faire un rechargement force si cache navigateur (vider cache)

## 6) Règle de maintenance conseillée
À chaque correction visuelle:
- Faire une copie .bak des fichiers touchés
- Noter la date + ce qui change dans ce fichier guide
- Tester mobile avant publication

## 7) Journal rapide
- 2026-04-02: correction header mobile S21 (hamburger, logo, titre) sur [public/css/style.css](css/style.css) et [lesmegeres-app/includes/header.php](../lesmegeres-app/includes/header.php).
- 2026-04-06: refonte des breakpoints du diaporama + améliorations mode paysage (voir section 9).

## 8) Diaporama — refonte des breakpoints

### Problème initial
Le diaporama se rétrécissait et ne remplissait pas son conteneur à exactement 768px.

### Cause
La frontière mobile/tablet était à `max-width:768px` / `min-width:769px`. À 768px, le bloc mobile scroll-snap s'appliquait (cartes trop petites), tandis que le layout tablet ne démarrait qu'à 769px — laissant 768px dans un état dégradé.

### Correction appliquée
Tous les breakpoints 768/769 ont été décalés à 767/768 :

| Avant                                       | Après             |
|---------------------------------------------|-------------------|
| `min-width:362px and max-width:768px`       | `max-width:767px` |
| `max-width:768px and min-width:621px`       | `max-width:767px` |
| `min-width:769px` (layout tablet principal) | `min-width:768px` |
| `min-width:769px` (actu-item)               | `min-width:768px` |

Les règles `.button-container` scroll-snap ont également été retirées du bloc `362-767px` (elles restaient actives jusqu'à 767px alors qu'elles ne sont utiles que jusqu'à 620px, où des blocs dédiés existent déjà).

### Répartition finale des breakpoints diaporama
- **≤ 420px** : scroll-snap, cartes très petites (`flex:0 0 2.35rem`)
- **≤ 560px** : scroll-snap, petites cartes (`flex:0 0 2.9rem`)
- **≤ 620px** : scroll-snap standard (`flex:0 0 3.75rem`, carte active `min(60vw, 16rem)`)
- **621px–767px** : layout de base — pas de scroll-snap, cartes s'étalent
- **≥ 768px** : layout tablet — `height:clamp(18rem, 40vh, 30rem)`

### Mode paysage smartphone (S21 et similaires)
Bloc ajouté : `@media screen and (orientation:landscape) and (max-height:500px)`

- Menu hamburger : réduction des hauteurs d'items pour éviter le tassement
- Diaporama : `height:21rem` fixe
- **Pleine largeur en paysage résolue** — la correction des breakpoints (768→767) a suffi : à 869px de large (S21 paysage), le layout tablet s'applique désormais correctement et le diaporama occupe toute la largeur de son conteneur.

### Cache navigateur
Les assets `style.css` et `app.js` ont un suffixe `?v=20260406` ajouté dans `header.php` et `footer.php` pour forcer le rechargement après cette mise à jour.

## 9) Mode carrousel mobile (test propriétaires)

Contexte:
- Un comportement de disparition progressive des cards du diaporama apparait vers 620-640px.
- Un mode carrousel mobile a été activé pour les écrans <= 768px.

Ce mode fait quoi:
- garde le bouton hamburger visible en permanence sur mobile,
- transforme le diaporama en rail horizontal scrollable,
- impose une largeur minimale aux cards pour éviter leur disparition visuelle.

Fichier impacte:
- [public/css/style.css](css/style.css)

Blocs à connaître:
- `@media screen and (max-width:620px)`
- `@media screen and (min-width:362px) and (max-width:768px)`
- `@media screen and (max-width:959px)`
- Règles `#hamburger.menu-petit-ecran`
- Règles `.button-container`, `.button-container .card`, `input:checked + label.card`

Correction complémentaire:
- Le texte sous le diaporama est forcé au retour à la ligne (`overflow-wrap:anywhere; word-break:break-word;`) pour éviter les débordements sur petits ecrans.
- Le padding des sections est symétrique (`padding-inline`) pour éviter l'effet de décalage vers la gauche.
- Le conteneur principal bloque le débordement horizontal (`overflow-x:clip`) pour empêcher qu un élément décale visuellement tout le contenu.
- Le hamburger est forcé par identifiant (`#hamburger`) dans les media queries mobiles (et plus seulement via sa classe) pour éviter sa disparition à 620-622px.

### Retour arrière rapide du mode carrousel (si non valide)

Option A (recommandée, la plus rapide):
- Restaurer votre sauvegarde:
  - cp /var/www/public/css/style.css.bak /var/www/public/css/style.css

Option B (manuelle, précise):
1. Ouvrir [public/css/style.css](css/style.css).
2. Supprimer entièrement le bloc:
   - `@media screen and (max-width:620px){ ... }`
3. Conserver le fonctionnement précédent du diaporama (hors carrousel) en gardant les règles générales `.button-container` et `.card`.
4. Garder uniquement l'ancrage du hamburger nécessaire:
   - `.menu-petit-ecran`
   - `#hamburger.menu-petit-ecran` (sans les règles de carrousel)

### Validation avant décision propriétaires
- Tester en 390px (S21), 620px et 640px dans l'inspecteur Firefox.
- Vérifier:
  - hamburger visible en haut à droite,
  - diaporama lisible,
  - aucune disparition de card,
  - navigation touch/scroll confortable.

### Notes compatibilité
- Le positionnement par défaut du hamburger est forcé a `top:20px` et `right:20px` pour stabiliser Firefox en mode responsive desktop.
- Les valeurs `env(safe-area-inset-*)` sont desormais appliquées uniquement sur appareils tactiles réels (`hover:none` + `pointer:coarse`) pour conserver la compatibilité encoche sans perturber les tests desktop.

**Problème à régler**
Le lien dans `.button-container input [type="radio"] label <div> <div> <a>` se propage dans les parents et selon où les images fermées sont touchées, l'image ne s'ouvre pas et le lien s'active directement entraînant l'ouverture de la page associée au lien. Sur les PC ce comportement se révèle de la façon suivante :
- en approchant le curseur de la souris vers le bas d'une image d'une des .card qui n'est pas :checked, le lien apparaît indiquant que si je clique à cet endroit là, la page pointée va directement s'ouvrir empêchant l'image de la .card de s'ouvrir.

## 10) Analyse complète et correctif final

Problèmes observés dans le temps:
- Le hamburger disparaissait vers 640px, puis 622px, puis 680px selon les ajustements.
- Le texte semblait pousser vers la droite quand la largeur diminuait.
- Le diaporama semblait se figer et ne plus se réduire sous certains seuils (560px ensuite).

Diagnostic technique retenu:
1. Débordement horizontal latent dans une chaîne de conteneurs:
- `.wrapper` à 90% et `main` à 96% réduisent déjà l'espace utile.
- Sans garde-fou, certains items flex/grid gardent une largeur minimale implicite (`min-width:auto`) et peuvent forcer le dépassement.

2. Diaporama en flex avec largeur minimale du contenu:
- Les cards du slider étaient contraintes par des `min-width` trop élevés pour les petits écrans.
- Le texte interne des cards participait à une largeur incompressible.
- Résultat: le rail horizontal n'était plus suffisamment compact sous 560px.

3. Effet secondaire sur la perception du hamburger:
- Quand un débordement horizontal existe, l'interface paraît décalée à droite et certains éléments fixes deviennent visuellement instables selon navigateur/zoom.

Corrections appliquées (état final):
- Stabilisation des largeurs minimales:
  - ajout `min-width:0` sur `main`
  - ajout `min-width:0` sur `section, .accueil-intro`
  - ajout `min-width:0` sur `.card`
- Mode compact diaporama <=560px:
  - réduction de la hauteur du conteneur
  - réduction de la largeur des cards fermées
  - réduction de la largeur mini de la card ouverte
- Mode ultra-compact diaporama <=420px:
  - nouvelle réduction de hauteur du conteneur
  - cards fermées encore plus étroites
  - card ouverte plus compacte
  - légère réduction du `padding-left` de `.description` pour gagner de la place
- Mode très petits écrans <=320px:
  - panneau du menu mobile pleine largeur pour éviter toute perte d'espace utile
  - réduction complémentaire des tailles du header (logo, titre, sous-titre, bouton)
  - réduction explicite des tailles de police du menu mobile pour rester lisibles sans déborder
  - diaporama encore plus compact avec cards fermées et carte ouverte réduites

Blocs CSS de reference (fichier [public/css/style.css](css/style.css)):
- `@media screen and (max-width:620px)`
- `@media screen and (max-width:560px)`
- `@media screen and (max-width:420px)`
- `@media screen and (max-width:320px)`
- Règles de base `main`, `section, .accueil-intro`, `.card`

Décision de maintenance:
- Conserver l'approche par paliers (620, 560, 420, 320) pour la lisibilité.
- Éviter de remonter les `min-width` des cards mobiles sans test réel sur téléphone.

Journal (mise a jour):
- 2026-04-02: correction structurelle des débordements (`min-width:0` sur conteneurs critiques) et compactage du diaporama sous 560px.
- 2026-04-02: ajout d un palier ultra-compact sous 420px pour garantir la réduction du diaporama sur très petits ecrans.
- 2026-04-02: correction du menu mobile qui pouvait apparaître vide: affichage des items conditionné a `nav.navbar:not(.responsive)`, forçage d affichage des éléments en `nav.navbar.responsive`, et ajustement des z-index pour superposition fiable.
- 2026-04-02: correction lisibilité du texte du menu hamburger: taille de police explicite (menu principal et sous-menu) en mode `nav.navbar.responsive` pour éviter des caracteres trop petits.
- 2026-04-02: ajout d'un palier trés petits écrans <=320px pour compacter header, menu mobile et diaporama sans casser la lisibilité.
- 2026-04-02: ajustement de la barre de navigation desktop pour limiter l'espacement entre les blocs de menu à 1px (`gap:1px` sur `nav.navbar`, suppression des marges latérales compensatoires).
- 2026-04-02: variante visuelle desktop appliquée pour test: suppression de l'espace réel entre blocs (`gap:0`) et remplacement par une séparation graphique de 1px via bordure gauche sur chaque bloc suivant.

## 11) Variante desktop retenue: séparation visuelle 1px

Objectif:
- Garder une barre de navigation compacte.
- Éviter un vide visible entre les blocs.
- Conserver une séparation propre et discrète entre les éléments.

Implementation retenue dans [public/css/style.css](css/style.css):
- Dans `@media screen and (min-width:960px)`:
  - `nav.navbar { gap:0; }`
  - `.menu, .rs { margin-right:0; }`
  - `nav.navbar > * + *` a été remplacé par `nav.navbar .menu`
  - `nav.navbar .menu { border-left:1px solid rgba(56,43,76,0.18); }`

Effet visuel:
- Les blocs de navigation se touchent.
- La séparation est créée par un trait vertical fin de 1px.
- Le rendu est plus compact qu'avec un vrai espace de 1px.

Retour arrière rapide:
Si cette variante ne convient pas, remettre l'ancienne version avec un vrai espace de 1px.

Dans [public/css/style.css](css/style.css), section `@media screen and (min-width:960px)`:
1. Remplacer:
  - `nav.navbar { gap:0; }`
  par:
  - `nav.navbar { gap:1px; }`
2. Supprimer entièrement la règle:
  - `nav.navbar .menu { border-left:1px solid rgba(56,43,76,0.18); }`
3. Laisser:
  - `.menu, .rs { margin-right:0; }`

Résultat après retour en arrière:
- Les blocs seront séparés par un espace réel de 1px.
- Il n'y aura plus de trait vertical entre eux.

## 12) Compatibilité Chrome - bordures Gresilhette

Problème remonté:
- Sur Chrome, les bordures décoratives de la page Gresilhette pouvaient ne pas s'afficher correctement.

Cause probable:
- Les blocs `.encadre`, `.encadre-lenga` et `.encadre-extrait` utilisaient `border-image-source` avec un `border-image-slice` en pourcentage (`10%`).
- Ce type de découpage peut être interprêté de manière moins stable selon le moteur de rendu, surtout avec une image "raster decorative".

Correction appliquée dans [public/css/style.css](css/style.css):
- Restauration du rendu standard Firefox:
  - `border-image-source:url(../images/livre-blanc-fleur-remplie.png);`
  - `border-image-slice:10%;`
  - `border-image-repeat:repeat;`
- Ajout d'une surcouche ciblée Blink/WebKit uniquement via:
  - `@supports selector(::-webkit-scrollbar)`
- Dans cette surcouche uniquement:
  - `border-image-slice:30;`
  - `border-image-width:1;`
  - `border-image-repeat:round;`
  - `background-clip:padding-box;`
  - `-webkit-border-image:url(../images/livre-blanc-fleur-remplie.png) 30 round;`
  - `border-image:url(../images/livre-blanc-fleur-remplie.png) 30 round;`

**Effets attendus**
- Bordures décoratives stables dans Chrome.
- Retour au comportement historique dans Firefox.
- Maintien d'une bordure couleur de secours si l'image décorative ne se charge pas.

Retour arrière rapide:
Dans [public/css/style.css](css/style.css):
1. Supprimer entierement le bloc:
   - `@supports selector(::-webkit-scrollbar){ ... }`
2. Conserver les déclarations standard des trois blocs (`border-image-source`, `border-image-slice:10%`, `border-image-repeat:repeat`)

## 13) Résume ultra court - retours arrière

Menu desktop avec trait 1px:
- Revenir a un vrai espace:
  - remettre `nav.navbar { gap:1px; }`
  - supprimer `nav.navbar .menu { border-left:1px solid rgba(56,43,76,0.18); }`

Menu mobile / hamburger:
- Si le menu devient vide ou illisible:
  - vérifier `nav.navbar:not(.responsive) .menu, nav.navbar:not(.responsive) .rs { display:none; }`
  - conserver les tailles explicites dans `.navbar.responsive .menu > :is(a, .menu-trigger)` et `.navbar.responsive .niv1 > a`

Diaporama mobile:
- Si le mode compact ne convient pas:
  - retirer les paliers `@media screen and (max-width:560px)`, `420px` et `320px`
  - conserver seulement le comportement général mobile si souhaité

Page Gresilhette sur Chrome:
- Si besoin de retour à l ancienne version:
  - supprimer le bloc `@supports selector(::-webkit-scrollbar){ ... }`
  - conserver la version standard des trois blocs

## 14) Balayage compatibilité navigateurs

Zones vérifiées en priorité:
- header mobile et menu hamburger
- diaporama de la page d'accueil
- bordures décoratives Gresilhette

Points confirmés comme sensibles mais laissés en place:
- `env(safe-area-inset-*)`: déjà limité aux appareils tactiles réels
- `scroll-snap-type`: conserve pour le diaporama mobile
- `backdrop-filter`: effet secondaire cosmétique seulement si non supporté
- `shape-outside`: utilisé ailleurs sur le site, sans impact sur les pages corrigées ici

Corrections appliquées pendant ce balayage:
- Stabilisation specifique Blink/WebKit pour les bordures Gresilhette sans casser Firefox

## 15) Sensibilité tactile du menu hamburger (sous-menus)

Problème remonté:
- Sur mobile tactile, un appui sur un menu principal (Infos, Spectacles, Theatre Forum, Prestations) pouvait parfois ouvrir directement la première page du sous-menu au lieu de laisser le sous-menu visible.

Cause:
- Le clic tactile pouvait être interprêté comme une activation quasi immédiate d'un lien du sous-menu juste après son ouverture.

Correction appliquée:
- Ajout d une protection temporelle dans [public/script/app.js](script/app.js):
  - mémorisation de l'instant d'ouverture du sous-menu (`lastSubmenuOpenTime`)
  - blocage des clics sur les liens de sous-menu pendant 150 ms (`SUBMENU_CLICK_DELAY = 150`)
- Ajout d'une gestion tactile explicite sur les boutons de menu (`touchstart`):
  - le premier appui tactile ouvre le sous-menu sans navigation
  - le clic synthétique suivant est ignoré pour éviter le double déclenchement
- En mode menu responsive, suppression de l'ouverture des sous-menus via `:hover` et `:focus-within`:
  - ouverture uniquement via la classe `.is-open` pilotée par JavaScript

**Effets attendus**
- Un appui sur un menu principal ouvre d'abord le sous-menu de manière fiable.
- La navigation vers une page se fait ensuite sur une action volontaire.

Retour arrière rapide:
1. Ouvrir [public/script/app.js](script/app.js)
2. Supprimer le bloc:
   - déclaration `lastSubmenuOpenTime` et `SUBMENU_CLICK_DELAY`
   - listener `click` sur `.niv1 a` qui bloque les clics pendant 150 ms
  - listener `touchstart` sur `.menu-trigger` et la logique `dataset.touchHandled`
3. Ouvrir [public/css/style.css](css/style.css)
4. Réintroduire (si souhaité) l ouverture via:
  - `.navbar.responsive .menu:hover .niv1`
  - `.navbar.responsive .menu:focus-within .niv1`