# Guide: Titres H1 couleur split (image + débordement)

Cette documentation explique comment reproduire le titre split-color sur les pages theatre-d-ombres, sieste-contee et
projet-pedagogique.

## État final attendu

- **Partie 1** (ex: "Projet"): blanc sur l'image, positionnée à `left:118%`
- **Partie 2** (ex: "pédagogique"): violet (#382b4c) qui déborde en dessous/à droite
- Texte reste visible et n'écrase pas le hamburger sur tous les écrans

## Modifications HTML

Pour chaque page (theatre-d-ombres.php et sieste-contee.php), modifier le H1 en deux spans:

### theatre-d-ombres.php
```html
<!-- AVANT -->
<h1 class="tan overlay-theatre-d-ombres">Théâtre d'ombres</h1>

<!-- APRÈS -->
<h1 class="tan overlay-theatre-d-ombres"><span class="blanc">Théâtre</span> <span class="violet">d'ombres</span></h1>
```

### sieste-contee.php
```html
<!-- AVANT -->
<h1 class="tan overlay-sieste-contee">Sieste contée</h1>

<!-- APRÈS -->
<h1 class="tan overlay-sieste-contee"><span class="blanc">Sieste</span> <span class="violet">contée</span></h1>
```

## Modifications CSS (déjà appliquées à style.css)

### 1. Positionner le texte à left:118% (horizontal centering seulement)
```css
.overlay-theatre-d-ombres,
.overlay-sieste-contee,
.overlay-projet-pedagogique{
    position:absolute;
    top:50%;
    left:118%;
    transform:translateX(-50%);  /* Pas de translateY pour permettre débordement */
    margin:0;
    text-align:center;
    line-height:1.1;
    white-space:nowrap;
}
```

### 2. Ajouter les règles de couleur (déjà présentes)
```css
.overlay-theatre-d-ombres .blanc,
.overlay-sieste-contee .blanc,
.overlay-projet-pedagogique .blanc{
    color:white;
}

.overlay-theatre-d-ombres .violet,
.overlay-sieste-contee .violet,
.overlay-projet-pedagogique .violet{
    color:#382b4c;
}
```

### 3. Ajouter overflow:visible au conteneur des images
```css
.d_theatre-d-ombres,
.d_sieste-contee,
.d_projet-pedagogique{
    position:relative;
    display:inline-block;
    width:100%;
    max-width:min(100%, 15.625rem);
    margin:0 auto 1.5rem;
    overflow:visible;  /* Important: permet au texte de déborder */
}
```

### 4. Réduire les images à différentes tailles d'écran (déjà présentes)

**≤620px** (media query existante):
```css
@media screen and (max-width:620px){
    .d_theatre-d-ombres,
    .d_sieste-contee,
    .d_projet-pedagogique{
        max-width:9rem;
    }
}
```

**621-768px** (media query existante):
```css
@media screen and (max-width:768px) and (min-width:621px){
    .d_theatre-d-ombres,
    .d_sieste-contee,
    .d_projet-pedagogique{
        max-width:12rem;
    }
}
```

**960px+** (media query existante):
```css
@media screen and (min-width:960px){
    .d_theatre-d-ombres,
    .d_sieste-contee,
    .d_projet-pedagogique{
        max-width:11rem;
    }
}
```

## Résumé des fichiers modifiés

- ✅ **style.css**: Déjà mis à jour (positioning, colors, media queries)
- ⏳ **theatre-d-ombres.php**: À modifier (H1 split)
- ⏳ **sieste-contee.php**: À modifier (H1 split)
- ✅ **projet-pedagogique.php**: Déjà terminé

## Points clés

1. **left:118%**: Place le point d'ancrage du texte légèrement en dehors de l'image (à droite)
2. **transform:translateX(-50%)**: Centre horizontalement le texte autour de ce point
3. **max-width réduite par breakpoint**: Évite que le texte débordant ne fasse disparaître le hamburger
4. **overflow:visible**: Permet au texte de sortir du conteneur
5. **white-space:nowrap**: Garde "Théâtre d'ombres" sur une seule ligne

## Test

Vérifier sur tous les écrans:
- Mobile ≤620px: Texte visible, pas de débordement horizontal
- Tablet 621-768px: Texte visible, légèrement plus d'espace
- Desktop 769px+: 3 images côte à côte, titres débordent correctement
