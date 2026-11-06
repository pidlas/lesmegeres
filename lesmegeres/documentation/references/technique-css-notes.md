# Notes techniques CSS — décisions et choix d'implémentation

---

## 1) [Menu hamburger : `display:none` vs `opacity`](#1-menu-hamburger--displaynone-vs-opacity)
## 2) [Menu hamburger : attribut `inert` sur le contenu principal](#2-menu-hamburger--attribut-inert-sur-le-contenu-principal)
## 3) [Remplacement de `:is()` : compatibilité Wallace](#3-remplacement-de-is--compatibilité-wallace)
## 4) [Sélecteur frère `~` pour cibler un élément adjacent hors descendance](#4-sélecteur-frère--pour-cibler-un-élément-adjacent-hors-descendance)
## 5) [Gestion des marges : convention et variables sémantiques](#5-gestion-des-marges--convention-et-variables-sémantiques)
## 6) [Font Awesome : passage à l'auto-hébergement local](#6-font-awesome--passage-à-lauto-hébergement-local)
## 7) [Diaporama index — scroll hint (hint de défilement)](#7-diaporama-index--scroll-hint-hint-de-défilement)
## 8) [Animation d'indication de cliquabilité sur images](#8-animation-dindication-de-cliquabilité-sur-images)
## 9) [Liste des sélecteurs/propriétés utilisant !important dans style.css](#9-liste-des-sélecteurspropriétés-utilisant-important-dans-stylecss)
## 10) [Polices web custom : chargement, debug et workflow](#10-polices-web-custom--chargement-debug-et-workflow)
## 11) [Propriétés CSS nécessitant un préfixe](#11-propriétés-css-nécessitant-un-préfixe)
## 12) [Audio Player Grid Layout](#12-audio-player-grid-layout)
## 13) [Amélioration du traitement et du chargement des images](#13-amélioration-du-traitement-et-du-chargement-des-images)
## 14) [Accessibilité](#14-accessibilité)
## 15) [Structure et exploitation](#15-structure-et-exploitation)
## 16) [Responsive layout](#16-responsive-layout)
## 17) [Encadrés décoratifs de la page gresilhette — technique et réglages](#17-encadrés-décoratifs-de-la-page-gresilhette--technique-et-réglages)

---

## 1) Menu hamburger : `display:none` vs `opacity`

**Fichier concerné :** `css/style.css`  
**Sélecteurs :** `.menu-petit-ecran`, `button.hamburger.box`, `#hamburger`

### Pourquoi `display:none` est utilisé pour masquer le bouton hamburger sur grand écran

Sur les résolutions larges (breakpoint `min-width` du menu horizontal), le bouton hamburger est masqué avec :

```css
.menu-petit-ecran,
button.hamburger.box {
    display: none;
}
```

`opacity: 0` seul **ne convient pas** ici pour trois raisons :

| Propriété                         | `display:none` |         `opacity:0` seul                  |
|-----------------------------------|----------------|-------------------------------------------|
| Retire du flux (layout)           |      ✅ oui    | ❌ non — occupe toujours l'espace         |
| Retire du tab order (clavier)     |      ✅ oui    | ❌ non — toujours focusable               |
| Retire de l'arbre d'accessibilité |      ✅ oui    | ❌ non — annoncé par les lecteurs d'écran |
| Clics possibles                   |      ❌ non    | ✅ oui — toujours cliquable               |

Pour obtenir un effet équivalent avec `opacity`, il faudrait combiner trois déclarations :

```css
/* À éviter : plus complexe pour le même résultat */
opacity: 0;
visibility: hidden;   /* retire du tab order et de l'arbre d'accessibilité */
pointer-events: none; /* désactive les clics */
```

`display:none` est donc l'unique déclaration nécessaire et la plus sémantique.

### Sur les breakpoints mobiles : redondance de `visibility` et `opacity`

Dans les media queries petits écrans, le bouton est réaffiché avec :

```css
#hamburger,
#hamburger.menu-petit-ecran {
    display: flex !important;
    visibility: visible !important; /* redondant */
    opacity: 1 !important;          /* redondant */
    ...
}
```

`visibility:visible` et `opacity:1` sont **redondants** quand `display:flex` est déjà déclaré — un élément avec `display:flex` est visible par défaut. Ces deux déclarations sont conservées uniquement par précaution, pour écraser un éventuel héritage venant d'une règle parente ou d'un plugin qui aurait ajouté `opacity:0` ou `visibility:hidden`.

### Quand utiliser `opacity` pour le menu

`opacity` est adapté uniquement pour des **animations de fondu** (apparition/disparition progressive). La combinaison correcte dans ce cas est :

```css
/* État masqué avec transition */
opacity: 0;
visibility: hidden;
transition: opacity 0.3s, visibility 0.3s;

/* État visible */
opacity: 1;
visibility: visible;
```

`visibility` est couplé à `opacity` pour garantir que l'élément quitte le tab order une fois le fondu terminé.

## 2) Menu hamburger : attribut `inert` sur le contenu principal

**Fichier concerné :** `script/app.js`  
**Fonction :** `setMenuState(isOpen)`

### Problème résolu

Quand le panneau de navigation s'ouvre en overlay sur mobile, le `<main>` reste visuellement masqué mais **reste accessible** au clavier (Tab) et aux lecteurs d'écran. Un utilisateur peut donc quitter le menu et atteindre des éléments situés derrière l'overlay.

### Solution implémentée

```js
if (mainContent) {
    if (isOpen) {
        mainContent.setAttribute('inert', '');
    } else {
        mainContent.removeAttribute('inert');
    }
}
```

L'attribut `inert` désactive en une seule déclaration :
- la navigation au clavier (Tab) vers les éléments de `<main>`
- les clics sur le fond derrière l'overlay
- la visibilité dans l'arbre d'accessibilité (lecteurs d'écran)

C'est la même approche que pour les modales (`role="dialog"` + `aria-modal`).

### Pourquoi seulement `mainContent` et pas `navBar`

Quand le menu est **fermé**, `nav.navbar` reçoit déjà `display:none` via CSS — `inert` n'apporterait rien de plus (voir section précédente sur `display:none` vs `opacity`).

`inert` n'est donc utile que dans le sens **ouverture → bloquer le contenu derrière l'overlay**.

### Support navigateur

`inert` est **baseline** depuis 2023 : Chrome 102+, Firefox 112+, Safari 15.5+. Aucun polyfill nécessaire.

## 3) Remplacement de `:is()` : compatibilité Wallace

**Fichier concerné :** `css/style.css`  

### Contexte

L'outil d'analyse CSS **Wallace** signale `:is()` comme un sélecteur « complexe », ce qui peut pénaliser le score ou déclencher des avertissements dans certains audits.

### Solution

Chaque occurrence de `:is(a, .menu-trigger)` a été dépliée en deux sélecteurs explicites séparés par une virgule.

**Avant :**
```css
.menu > :is(a, .menu-trigger) { … }
```

**Après :**
```css
.menu > a,
.menu > .menu-trigger { … }
```

L'effet CSS est **strictement identique** : la spécificité ne change pas (`:is()` adopte la spécificité de son argument le plus spécifique, ce qui est équivalent à lister les sélecteurs séparément).

### Occurrences remplacées (7 au total)

| Sélecteur d'origine                                             |          Contexte              |
|-----------------------------------------------------------------|--------------------------------|
| `.navbar.responsive .menu > :is(a, .menu-trigger)`              | styles du menu responsive (2×) |
| `.menu > :is(a, .menu-trigger)`                                 | styles du menu horizontal (2×) |
| `.menu > :is(a, .menu-trigger):hover`, `:focus-visible`         |      états interactifs         |
| `.menu.is-open > :is(a, .menu-trigger)`                         |         état ouvert            |
| `.menu > :is(a, .menu-trigger)` dans `@media (min-width:960px)` |     responsive desktop         |

---

## 4) Sélecteur frère `~` pour cibler un élément adjacent hors descendance

**Fichier concerné :** `css/style.css`, `pages/theatre-d-ombres.php`  
**Sélecteur :** `.d_theatre-d-ombres ~ ul.jolie li::marker`

### Problème

La règle initiale utilisait un sélecteur **descendant** (espace) :

```css
.d_theatre-d-ombres ul.jolie li::marker { color: #e4896a; }
```

Elle ne fonctionnait pas car dans [pages/theatre-d-ombres.php](../../../lesmegeres-app/pages/theatre-d-ombres.php), la `<ul class="jolie">` est un **frère** du `<div class="d_theatre-d-ombres">` — elle suit ce div dans la même `<section>`, elle n'en est pas un enfant.

```html
<section>
    <div class="d_theatre-d-ombres">…</div>  <!-- le div -->
    <p>…</p>
    <ul class="jolie">…</ul>                  <!-- frère, pas enfant -->
</section>
```

### Solution

Utiliser le **combinateur de frère général** `~` :

```css
.d_theatre-d-ombres ~ ul.jolie li::marker { color: #e4896a; }
```

`A ~ B` cible tout élément `B` qui suit `A` dans le même parent, quelle que soit la distance. C'est le combinateur adapté quand l'élément cible n'est pas un descendant mais un successeur au même niveau du DOM.

## 5) Gestion des marges : convention et variables sémantiques

**Fichier concerné :** `css/style.css`

### Problème de maintenance

Le fichier utilisait un mélange de `margin-top`, `margin-bottom` et `margin` à deux valeurs (`margin: X 0`), sans direction privilégiée. Cela rendait difficile de prévoir l'espacement résultant (fusion des marges CSS, surcharges en cascade).

### Convention adoptée

**`margin-top` uniquement** pour les espacements verticaux entre éléments de flux.  
Chaque élément déclare l'espace qui le sépare de ce qui le précède.

La règle `margin-bottom` n'est conservée que dans deux cas légitimes :
- liste (`.marge`, `ul li.puce:last-child`) où l'espace bas est fonctionnel

### Variables sémantiques ajoutées

```css
:root {
    --space-xs:.625rem;
    --space-s:1rem;/*espacement texte courant (= 16px)*/
    --space-m:1.5625rem;/*espacement composants (= 25px)*/
    --space-l:3.125rem;/*espacement sections (= 50px)*/
}
```

### Conversions effectuées (à verifier car j'ai fait d'autres changements)

| Règle                        | Avant                | Après                      |
|------------------------------|----------------------|----------------------------|
| p                            | margin-top:1rem      | margin-top:var(--space-s)  |
| ul.jolie li.puce:first-child | margin-top:1rem      | margin-top:var(--space-s)  |
| section                      | margin:3.125rem auto | margin:var(--space-l) auto |
| .accueil-intro               | margin:3.125rem auto | margin:var(--space-l) auto |
| p.traduction                 | margin:30px 0        | margin-top:var(--space-m)  | 
| .adresse                     | margin:2rem 0        | margin-top:2rem            |
| .telephone                   | margin:2rem 0        | margin-top:2rem            |
| .association                 | margin:2rem 0        | margin-top:2rem            |
| .courriel                    | margin:3.125rem auto | margin:var(--space-l) auto |
| .fb                          | margin:3.125rem auto | margin:var(--space-l) auto |
| .insta                       | *(pas de marge)*     | margin-top:2rem            |
| .conteneur-audio             | margin:2rem 0        | margin-top:2rem            |
| .marge                       | margin-bottom:2rem   | margin-bottom:2rem         |
| h2                           |margin-top:1em`+`margin-bottom:var(--15px)|margin-top:var(--space-l)|

### Ce qui reste intentionnellement inchangé

- `ul li.puce:last-child { margin-bottom: 1rem }` — la liste peut être suivie d'éléments sans `margin-top`
- Les `!important` sur `#hamburger` — overrides JavaScript légitimes nécessaires pour positionner le bouton sur mobile

## 6) Font Awesome : passage à l'auto-hébergement local
  
**Fichiers modifiés :** `lesmegeres-app/includes/header.php`, `lesmegeres-app/includes/footer.php`  
**Fichiers créés :** `public/fonts/fontawesome/css/all.min.css`, `public/fonts/fontawesome/webfonts/fa-brands-400.woff2`, `public/fonts/fontawesome/webfonts/fa-regular-400.woff2`, `public/fonts/fontawesome/webfonts/fa-solid-900.woff2`

### Problème rencontré

Les icônes Facebook et Instagram du menu hamburger s'affichaient correctement en simulation responsive desktop (Firefox), mais **restaient invisibles sur le Samsung Galaxy S21** réel (Samsung Internet / Chrome Android). Les icônes étaient pourtant cliquables — preuve que le HTML était correct et que c'était uniquement le fichier de police qui ne se chargeait pas.

### Causes identifiées

Deux approches externes ont été testées et toutes deux ont échoué sur le S21 :

1. **FA Kit JS** (`kit.fontawesome.com/2768f7454f.js`) — bloqué par les protections de traçage du navigateur mobile
2. **CDN cdnjs.cloudflare.com** — également bloqué (domaine externe non reconnu)

### Solution : auto-hébergement (même origine)

Un fichier servi depuis le même domaine ne peut jamais être bloqué par une protection anti-traceurs. La solution retenue est d'héberger Font Awesome 6.7.2 directement sur le serveur :

```
public/fonts/fontawesome/
├── css/
│   └── all.min.css          (73 Ko — contient toutes les classes d'icônes)
└── webfonts/
    ├── fa-brands-400.woff2  (116 Ko — icônes de marques : Facebook, Instagram…)
    ├── fa-regular-400.woff2  (25 Ko — icônes régulières : copyright…)
    └── fa-solid-900.woff2   (155 Ko — icônes solides : flèche lien externe…)
```

Le fichier `all.min.css` utilise des chemins relatifs (`../webfonts/…`) qui correspondent exactement à cette structure.

### Changements HTML

**`header.php` (ligne ~303) :**
```html
<!-- Avant -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" crossorigin="anonymous">

<!-- Après -->
<link rel="stylesheet" href="[site_path]/fonts/fontawesome/css/all.min.css">
```

**`footer.php` (ligne 2) :** correction adjacente — syntaxe FA4 migrée en FA6 :
```html
<!-- Avant (FA4 — ne fonctionne plus en FA6 sans fichier de compatibilité) -->
<i class="fa fa-copyright"></i>

<!-- Après (FA6) -->
<i class="fa-regular fa-copyright" aria-hidden="true"></i>
```

### Icônes Font Awesome utilisées sur le site

| Classe                                   | Famille | Page(s)      |
|------------------------------------------|---------|--------------|
| `fa-brands fa-facebook-f`                | brands  | header (nav) |
| `fa-brands fa-instagram`                 | brands  | header (nav) |
| `fa-brands fa-facebook`                  | brands  | contact      |
| `fa-brands fa-square-instagram`          | brands  | contact      |
| `fa-solid fa-arrow-up-right-from-square` | solid   | footer, credits, mentions-legales | + .g_bio
| `fa-regular fa-copyright`                | regular | footer       |

### Note sur les navigateurs anciens

Les fichiers `.ttf` ne sont pas inclus (inutiles pour les navigateurs récents qui supportent tous `.woff2`). Si un navigateur très ancien devait un jour poser problème, télécharger les `.ttf` correspondants depuis le même CDN et les placer dans le même dossier `webfonts/`.

## 7) Diaporama index — scroll hint (hint de défilement)
  
**Fichier concerné :** `css/style.css` — `.button-container` dans les media queries ≤ 767px

### Problème

Avec 7 cards, le conteneur `.button-container` dépasse la largeur de l'écran. L'internaute ne voit que 5–6 cards et ne sait pas qu'il peut faire défiler vers la droite pour en voir d'autres.

### Solution retenue : dégradé `mask-image`

Un masque CSS en dégradé efface progressivement le bord droit du conteneur, rendant les dernières cards visibles partiellement estompées. C'est le signal visuel standard "il y a du contenu au-delà".

```css
.button-container {
  -webkit-mask-image: linear-gradient(to right, black 74%, transparent 100%);
    mask-image: linear-gradient(to right, black 74%, transparent 100%);
}
```

- `black` = opaque (affiché normalement)
- `transparent` = invisible
- La transition commence à 74% de la largeur du conteneur et se termine au bord

**Aucun JS, aucun marqueur HTML**
Le navigateur se charge de tout.

### Limites de cette approche

Le masque est appliqué sur le **conteneur** lui-même. Quand l'utilisateur scrolle jusqu'à la fin, les dernières cards restent partiellement masquées (la dernière card ne s'affiche jamais entièrement). Ce n'est pas un problème de lisibilité car le titre reste visible, mais c'est à avoir en tête si le design évolue.

### Alternatives possibles

| Technique                       | Avantage                | Inconvénient |
|---------------------------------|-------------------------|--------------|
| `mask-image` dégradé (actuel)   | Zéro JS, natif CSS      | Masque permanent, même en fin de scroll |
| Indicateur flèche `>` absolu    | Disparaît au scroll (via JS) | Nécessite du JS |
| Première card "peek" dépassante | Très naturel            | Nécessite un calcul de largeur précis |
| Scrollbar stylisée visible      | Universellement compris | Look moins moderne  |

### Réglage visuel

La valeur `74%` (resp. `78%` pour la plage 621–767px) contrôle à partir de quel point le fondu commence. Plus la valeur est basse, plus l'effet est prononcé. Valeurs recommandées selon la densité de cards visible :

- `65–70%` → fondu fort, très visible
- `74–80%` → fondu discret, élégant
- `85–90%` → presque invisible, peu efficace comme hint

## 8) Animation d'indication de cliquabilité sur images (avril 2026)

**Fichiers concernés :** `css/style.css`, pages `pages/theatre-d-ombres.php`, `pages/sieste-contee.php`, `pages/projet-pedagogique.php`

**Contexte**
Pour indiquer que les images des pages theatre-d-ombres, sieste-contee et projet-pedagogique sont cliquables, les images des 2 autres pages sont blanchies par un voile et une main est affichée. Une animation CSS a été ajoutée au moment du :hover.

**Effets appliqués**
- Les deux images affichent un effet de zoom et une ombre portée.
- Les images s'agrandissent et pivote vers la gauche.

```css
.image-wrapper img{
    transition: transform 0.25s cubic-bezier(.4,2,.6,1), box-shadow 0.25s cubic-bezier(.4,2,.6,1), filter 0.25s;
    cursor: pointer;
    border-radius: 12px;
    pointer-events: auto;
}
/*animation sur tout le lien, pas seulement l'image*/
.image-wrapper:hover img{
    transform: scale(1.07) rotate(-2deg);
    box-shadow: 0 8px 24px 0 rgba(56,43,76,0.18), 0 1.5px 6px 0 rgba(0,0,0,0.10);
    filter: brightness(1.08) saturate(1.1);
}
```

Cet effet n'a pas plu et a été remplacé par la même animation que pour le logo.

**Sélecteurs CSS principaux :**
```css
.image-wrapper img:hover
.image-wrapper::after
```

**But**
- Améliorer l'accessibilité et l'intuitivité pour l'utilisateur.
- Différencier visuellement les deux images pour une expérience plus dynamique.

## 9) Liste des sélecteurs/propriétés utilisant !important dans style.css

### À nettoyer progressivement (souvent non indispensables) :

- input:checked + label .description { opacity:1 !important; transform:translateY(0) !important; }
- input:checked + label { transform:none!important; }
- #hamburger.menu-petit-ecran, #hamburger (plusieurs media queries) : display, visibility, opacity, position, top, right, z-index (tous !important)
- .menu-petit-ecran, #hamburger.menu-petit-ecran (positionnement responsive, top/right)
- .navbar.responsive .menu, .navbar.responsive .rs { display:flex !important; }
- .navbar.responsive.submenu-active .menu:not(.is-open), .navbar.responsive.submenu-active .rs { display:none !important; }
- .button-container .card { ... } (si jamais !important ajouté)

### Cas où !important est probablement nécessaire :

- #hamburger, #hamburger.menu-petit-ecran (dans les media queries) :
  - display, position, top, right, z-index : pour forcer l’affichage du bouton hamburger sur mobile/tablette, car beaucoup de règles CSS et JS peuvent interférer.
- .navbar.responsive .menu, .navbar.responsive .rs :
  - display:flex !important; pour garantir l’affichage du menu responsive.
- .navbar.responsive.submenu-active .menu:not(.is-open), .navbar.responsive.submenu-active .rs :
  - display:none !important; pour masquer les sous-menus ouverts/fermés.
- input:checked + label .description :
  - opacity:1 !important; transform:translateY(0) !important; pour garantir l’animation d’apparition même si d’autres transitions sont en conflit.

### Cas où !important est rarement utile (à supprimer si possible) :

- transform sur des éléments statiques.

**Conseil**
- Supprime !important sur les propriétés de couleur, border-radius, transform, etc. sauf si tu constates un bug visuel. FAIT
- Garde-le sur le hamburger/menu responsive et les animations d’apparition si tu observes des conflits ou des bugs d’affichage.

## 10) Polices web custom : chargement, debug et workflow

**Fichiers concernés** `css/style.css`, `css/style.min.css`, polices dans `fonts/`, script `watch-css.sh`

### Problème rencontré
- La police personnalisée "Coco Gothic" ne s'affichait pas sur le site malgré une déclaration CSS correcte.
- Les fichiers de police étaient accessibles, mais le navigateur ne tentait pas de les charger.
- La cause : le site local utilisait uniquement le fichier minifié `style.min.css`, qui n'était pas régénéré après chaque modification de `style.css`.

### Solution apportée
- Vérification de la déclaration `@font-face` (nom sans espace, chemins sans échappement, formats multiples).
- Ajout d'une règle CSS temporaire pour forcer la police sur les paragraphes et faciliter le debug.
- Mise en place d'un script automatique de minification locale :

```bash
# /var/www/lesmegeres/watch-css.sh
while inotifywait -e close_write css/style.css; do
  npx postcss css/style.css --use cssnano -o css/style.min.css --no-map
done
```
- Ce script surveille toute modification de `style.css` et régénère instantanément `style.min.css`.
- Nécessite le paquet `inotify-tools` (sudo apt-get install inotify-tools) et les modules Node.js `postcss` et `cssnano`.

### Bonnes pratiques
- Toujours régénérer le CSS minifié après modification du CSS source.
- En cas de problème d'affichage de police, vérifier :
  - L'inclusion du bon fichier CSS dans le HTML
  - Le nom exact de la police dans `@font-face` et dans `font-family`
  - L'accessibilité des fichiers de police (URL, droits, Content-Type)
  - Le cache navigateur
- Utiliser un script de surveillance pour éviter les oublis lors du développement local.

## 11) Propriétés CSS nécessitant un préfixe

**Fichier concerné** `css/style.css`

Après analyse, voici les propriétés qui nécessitent un préfixe pour une compatibilité optimale :

### 1. image-set
- Utilisé avec `background-image`.
- Préfixe nécessaire : `-webkit-image-set` (pour Chrome, Safari, Edge).
- Exemple :
  ```css
  background-image: -webkit-image-set(...);
  background-image: image-set(...);
  ```

### 2. mask-image
- Utilisé pour appliquer des masques (dégradés, images, etc.).
- Préfixe nécessaire : `-webkit-mask-image` (pour Chrome, Safari, Edge).
- Exemple :
  ```css
  -webkit-mask-image: ...;
  mask-image: ...;
  ```

**Bonnes pratiques**
- Toujours placer la version préfixée avant la version standard.
- Vérifier la compatibilité sur caniuse.com pour d’autres propriétés avancées.
- D’autres propriétés comme `backdrop-filter`, `appearance`, `user-select`, etc., peuvent aussi nécessiter un préfixe selon les usages, mais ne sont pas présentes dans ce fichier.

**À faire** : Ajouter systématiquement la version préfixée pour les propriétés ci-dessus si ce n’est pas déjà fait.

## 12) Audio Player Grid Layout

### Objectif
Créer un lecteur audio personnalisé, moderne et responsive, en utilisant CSS Grid pour positionner précisément les boutons, la barre de progression et le timer.

### Structure HTML recommandée
```html

```

### Structure CSS (à placer avant /*page CONTACT*/)
```css

```

### Conseils
- Supprime ou commente les anciennes règles `.audio-wrapper input[type=range]` pour éviter les conflits.
- Utilise l’inspecteur pour tester et ajuster le rendu.
- Adapte les couleurs et tailles selon ta charte graphique.

## 13) Amélioration du traitement et du chargement des images

### Traitement
```css
img{
    max-width:100%;
    height:auto;
    aspect-ratio:1 / 1;/* ou 16 / 9 ou 9 / 16*/
    object-fit:cover;
    }

### Optimisation
- Converti en WebP (cwebp): p_theatre-forum (-89%), p_gresilhette-et-salimonde (-95%),
  les-stages-recto (-90%), les-stages-verso (-82%), les-cours (-90%), livre-blanc-fleur-remplie (-38%)
- CSS background-image: migre vers image-set() WebP+fallback (p_theatre-forum, p_gresilhette, livre-blanc)
- HTML img: migre vers <picture><source type="image/webp">...</picture> (affiches stages, cours)

  Commandes de conversion (depuis le répertoire de l'image) :
  ```bash

  # Convertir une image seule (qualité 80)
  cwebp -q 80 image-originale.jpg -o image-originale.webp

  # Convertir toutes les images d'un répertoire en lot
  for f in *.jpg *.png; do
    cwebp -q 80 "$f" -o "${f%.*}.webp"
  done
  ```
### Paramétrage du navigateur pour <picture> et <srcset>
- Il semblerait, et c'est à vérifier, que les balise HTML <picture> et <srvset> ait besoin d'un réglage spécifique de Firefox pour être interprêtée correctement. Il s'agirait de `dom.image.srcset.enabled` et `dom.image.picture.enabled` qui doivent être TRUE.
 
### Lazy loading
- loading="lazy" ajouté sur: SVG décoratifs (champignons, feuilles) dans toutes les pages,
  photos equipe items 3-10, images compagnie, logos partenaires, affiches stages/cours
- LCP exclus (pas de lazy): webJolie_si.jpg, webJolie_sr.jpg (1ère rangée page equipe),
  portraits biographies (fade-zoom), sieste-contee.png
  
## 14) Accessibilité
- Focus clavier: règle global `a:focus-visible`, `button:focus-visible` ajoutée (outline orange #e4896a)
- Focus nav: déjà couvert par règles spécifiques `navbar` (outline violet #382b4c)
- Tailles tactiles: `menu-item` 2.75rem (44px OK), `sous-menu` 2.5rem (40px acceptable)
- Contraste: couleurs existantes conservées — aucune régression détectée
- Navigation tactile: déjà stabilisée (touchstart + protection clic accidentel dans app.js)

Critère de validation:
- Navigation menu stable en tactile (aucun clic fantôme)
- Score Lighthouse mobile en hausse (perf et a11y) — à vérifier après deploiement

## 15) Structure et exploitation

### Monitoring léger
- Script check-uptime.sh : vérifie HTTP 200 sur les 7 pages critiques
- Sortie horodatée PASSE/ÉCHEC, exit 1 si anomalie (compatible cron + alerte mail)
- Exemple cron : */15 * * * * /var/www/lesmegeres/check-uptime.sh >> /var/log/megeres-uptime.log 2>&1

### Rollback opérable rapidement
- deploy.sh --apply crée automatiquement un backup (REMOTE_BASE.backup) avant chaque déploiement
- rollback.sh restaure le backup en une commande + vérifie les pages automatiquement
- Option --no-backup sur deploy.sh si besoin de sauter le backup

### Sécurité HTTP de base
- nginx-public.conf : X-Frame-Options SAMEORIGIN, X-Content-Type-Options nosniff,
  Referrer-Policy strict-origin-when-cross-origin, CSP progressive (object-src none, unsafe-inline autorisé)
- Appliquer la config sur le serveur nginx de production

### SEO technique continu
- check-post-deploy.sh étendu : vérifie sitemap.xml (HTTP 200 + contenu <urlset>),
  robots.txt (HTTP 200 + présence Sitemap:), et meta title sur la page d'accueil

Critère de validation:
- Incident deploy récupérable en moins de 10 minutes ✓ (rollback.sh)
- Vérification post-deploy standardisée et documentée ✓ (check-post-deploy.sh étendu)

## 16) Responsive layout
- La logique c'est de faire du mobile first et d'ajouter les media-query min-width et ensuite d'ajouter les media-query selon les breakpoints du design.
- Utiliser ```css font-size : clamp(min, préféré, max);```. La taille préféré s'applique sinon c'est min ou max. Si la taille préférée est donnée en vw alors il n'y aura ni zoom ni dézoom. Pour que le zoom fonctionne il faut calculer la taille préférée avec quelque chose comme `calc(7vw*1rem)`
- Il existe une nouvelle unité de hauteur pour les écrans spartphone : `dvh`. Un même élément peut avoir les deux hauteurs et c'est le navigateur qui utilisera la bonne hauteur. ```css height: 100vh; height: 100dvh;```

## 17) Encadrés décoratifs de la page gresilhette — technique et réglages

### Contexte

Les trois sections de la page `gresilhette` sont encadrées par un motif décoratif tiré de l'image `livre-blanc-fleur-remplie.png` (1868×4138px, RGBA). Les classes CSS concernées sont `.encadre` (orange), `.encadre-lenga` (violet), `.encadre-extrait` (saumon).

### Pourquoi border-image a été abandonné

La technique `border-image` a été tentée puis abandonnée après plusieurs bugs croisés Chrome/Firefox :

| CSS appliqué                      |     Chrome 121+     |              Firefox                |
|-----------------------------------|---------------------|-------------------------------------|
|`border-image-slice: 10% repeat` + |                     |                                     |
|`@supports ::-webkit-scrollbar` →  |                     |                                     |
|`slice: 30 round`                  |❌ border invisible  |                                     |
|                                   |(bords transparents  |                                     |
|                                   |de l'image)          |                 ✅                  |
|`border-image: 30 round` +         |                     |                                     |
|`background-clip: padding-box`     |          ✅         | ❌ border disparaît après chargement|
|`border-image: 10% round` +        |                     |                                     | 
|`background-clip: padding-box`     |          ✅         | ❌ border disparaît après chargement|
| `border-image: 10% round` sans    |                     |                                     |
|`background-clip` |          ✅         | ✅ (border couleur OK mais image quasi-invisible)    |

**Cause fondamentale** les bords extérieurs de `livre-blanc-fleur-remplie.png` sont transparents. Avec `border-image`, Chrome remplace totalement le rendu du border par l'image (spec stricte) → zone transparente = border invisible. Firefox montrait la `border-color` par transparence (comportement indulgent). Aucune valeur de `slice` ne permettait de cibler la zone décorative réelle de l'image dans les deux navigateurs.

### Technique actuelle : pseudo-élément ::before

L'image est appliquée via un `::before` positionné en `absolute` sur tout l'encadré :

```css
.encadre,
.encadre-lenga,
.encadre-extrait {
    position: relative;
    margin-top: var(--20px);
}
.encadre::before,
.encadre-lenga::before,
.encadre-extrait::before {
    content: '';
    position: absolute;
    inset: 0;
    background: url(../images/livre-blanc-fleur-remplie.png) no-repeat center / 100% 100%;
    pointer-events: none;
    z-index: 1;
}
.encadre {
    padding: var(--30px);      /* remplace border — zone décorative = padding */
    background-color: #f7ae56; /* couleur fallback si image indisponible */
}
.gresilhette-et-la-salimonde {
    position: relative;
    z-index: 2;                /* au-dessus du ::before */
    background-color: #fffefc;
    padding: var(--20px);
}
```

**Principe** `::before` avec `inset: 0` couvre exactement la zone content+padding du parent — pas la zone `border`. En remplaçant `border` par `padding` de même valeur, la zone décorative (30px) devient du padding, donc dans la portée du `::before`. L'image est étirée sur tout l'élément via `background-size: 100% 100%`. Le div blanc central (texte) passe au-dessus grâce à `z-index: 2`.

### Paramètres de réglage de l'apparence décorative

**`background-size` — le plus impactant**

Contrôle quelle portion de l'image est visible dans la zone de padding.

```css
background: url(...) no-repeat center / 100% 100%;   /* actuel : toute l'image étirée */
background: url(...) no-repeat center / 120% 120%;   /* zoom : plus de décoration dans le padding */
background: url(...) no-repeat center / 80% 80%;     /* dézoom */
```

**`background-position` — cadrage**

Décale la position de l'image par rapport à l'encadré.

```css
background-position: center;        /* centré (défaut) */
background-position: center top;    /* décale vers le haut */
background-position: 50% 20%;       /* fine-tuning précis */
```

**`inset` sur `::before` — débordement**

Valeurs négatives font déborder l'image au-delà du padding, ce qui peut révéler plus de décoration. Ajouter `overflow: hidden` sur `.encadre` si on ne veut pas de débordement visuel.

```css
inset: -10px;    /* ::before dépasse de 10px de chaque côté */
```

**`padding` — épaisseur de la zone décorative**

Plus le padding est grand, plus la bande décorative est large.

```css
padding: var(--50px);   /* bande plus large, plus de motif visible */
padding: var(--30px);   /* actuel */
```

### Pour modifier et tester

Édite `/var/www/lesmegeres/css/style.css`, cherche `.encadre::before`, ajuste `background-size` ou `background-position`, puis déploie :

```bash
cd /var/www/lesmegeres
./deploy.sh --apply --skip-post-check
```

Recharge dans le navigateur avec `Ctrl+Shift+R` (vide le cache).