# Technique "full-bleed" — élément en pleine largeur viewport

## Statut : RÉSOLU (6 avril 2026)

Le problème de largeur du diaporama en mode paysage sur S21 a été résolu **sans recourir au full-bleed CSS**.

La cause réelle était un mauvais breakpoint : la frontière mobile/tablet était à `max-width:768px` / `min-width:769px`. Le S21 en paysage mesurant 869px de large, il entrait bien dans le layout tablet — mais à exactement 768px le scroll-snap mobile s'appliquait encore, contraignant la largeur des cartes.

En décalant la frontière à `max-width:767px` / `min-width:768px`, le layout tablet s'applique dès 768px et le diaporama occupe toute la largeur de son conteneur en paysage.

---

## Contexte historique — tentatives full-bleed (non appliquées)

Objectif initial : faire occuper à `.button-container` 100% de la largeur du viewport alors qu'il est imbriqué dans `main` (96%) → `.wrapper` (90%).

Trois approches testées, toutes sans effet, probablement neutralisées par le contexte `display:grid` de `.wrapper` et `display:flex` de `main` :

1. `position:relative + left:50% + transform:translateX(-50%)`
2. Ajout de `max-width:100vw`
3. `margin-left:calc(50% - 50vw)`

Si le besoin réapparaît, la solution robuste est la **piste 4** : déplacer `.button-container` hors de `<main>` dans `index.php`, comme enfant direct de `.wrapper`, avec `grid-column:1/-1`.

Faire occuper au diaporama `.button-container` toute la largeur du viewport en mode paysage smartphone, alors qu'il est imbriqué dans `main` (96%) → `.wrapper` (90%) → viewport, soit environ 86% de largeur disponible.

---

## Contexte HTML/CSS concerné

- `.button-container` est enfant direct de `<main>` (pas dans une `<section>`)
- `main` : `width:96%`, `display:flex`, `flex-direction:column`
- `.wrapper` : `width:90%`, `display:grid`
- Le bloc ciblé : `@media screen and (orientation:landscape) and (max-height:500px)`

---

## État au 6 avril 2026 — NON RÉSOLU

Trois approches testées, aucune n'a fonctionné sur le S21 en mode paysage :

### Tentative 1 — `position:relative + left:50% + transform`
```css
body { overflow-x:clip; }
.button-container {
    width:100vw;
    position:relative;
    left:50%;
    transform:translateX(-50%);
}
```
**Résultat :** largeur inchangée. Probablement neutralisée par le contexte `display:grid` de `.wrapper` ou `display:flex` de `main`.

### Tentative 2 — `max-width:100vw` ajouté
```css
.button-container {
    width:100vw;
    max-width:100vw; /* pour lever max-width:100% hérité */
    position:relative;
    left:50%;
    transform:translateX(-50%);
}
```
**Résultat :** toujours sans effet.

### Tentative 3 — `margin-left:calc(50% - 50vw)` (approche flux normal)
```css
body { overflow-x:clip; }
.button-container {
    height:21rem;
    width:100vw;
    max-width:100vw;
    margin-left:calc(50% - 50vw);
}
```
**Résultat :** toujours sans effet.

---

## État actuel du CSS (style.css, fin de fichier)

```css
@media screen and (orientation:landscape) and (max-height:500px){
    body { overflow-x:clip; }
    .button-container {
        height:21rem;
        width:100vw;
        max-width:100vw;
        margin-left:calc(50% - 50vw);
    }
    :root {
        --menu-panel-padding:1.75rem 0 0.25rem;
        --menu-item-min-height:2.2rem;
        --menu-subitem-min-height:1.9rem;
        --menu-subitem-padding:0.2rem 0.75rem;
    }
}
```

---

## Pistes à explorer

1. **Inspecter avec Firefox remote debugging** (USB + `about:debugging` sur desktop) pour voir quelles règles s'appliquent réellement à `.button-container` en paysage sur le S21. Vérifier si `width`, `max-width` ou `margin-left` sont bien lus par le navigateur et s'il y a une règle plus spécifique qui les écrase.

2. **Vérifier le contexte flex de `main`** : un enfant en `display:flex` avec `flex-direction:column` peut contraindre la largeur de ses enfants. Essayer d'ajouter `align-self:stretch` ou de sortir `.button-container` de `main` dans le HTML.

3. **Approche négative de `margin`** : à tester sans `width:100vw` —
   ```css
   .button-container {
       margin-left:calc(-1 * (100vw - 100%) / 2);
       margin-right:calc(-1 * (100vw - 100%) / 2);
   }
   ```

4. **Restructurer le HTML** : déplacer `.button-container` hors de `main` dans `index.php`, entre `</section>` et `<main>`, pour qu'il soit enfant direct de `.wrapper`. Avec `grid-column:1/-1`, il prendrait toute la largeur du grid. C'est la solution la plus robuste mais elle touche au HTML.

---

## Pourquoi `overflow-x:clip` plutôt que `hidden`

`overflow:hidden` crée un nouveau contexte de formatage qui perturbe `position:fixed` (menu hamburger). `overflow-x:clip` rogne uniquement le débordement horizontal sans créer ce contexte.
