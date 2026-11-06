# Analyse : Problème d'affichage de l'image c3 dans le diaporama

**Date**: 2 juillet 2026  
**Problème**: Image c3 (Ta grand-mère sous le figuier) n'affiche pas en pleine largeur - blanc visible à gauche et à droite, cassant l'effet border-radius.  
**Autres images**: Fonctionnent correctement  

## Mise à jour du 18 juillet 2026

Le problème persistant n'etait pas principalement un bug CSS. Le fichier image c3 contenait des bords tres clairs integres, ce qui donnait visuellement des bandes blanches sur les cotes lors de l'ouverture de la carte.

Correctif applique:

- sauvegarde des originaux:
    - `p_ta-grand-mere-sous-le-figuier.jpg.pre-c3fix-20260718`
    - `p_ta-grand-mere-sous-le-figuier.webp.pre-c3fix-20260718`
- recadrage centre de l'image source puis regeneration des fichiers cibles:
    - JPG: `p_ta-grand-mere-sous-le-figuier.jpg`
    - WebP: `p_ta-grand-mere-sous-le-figuier.webp`

Validation technique:

- avant fix, luminance moyenne des bords c3 ~`0.99` et `0.98` (quasi blanc)
- apres fix, luminance moyenne des bords c3 ~`0.49` et `0.38`

Conclusion: le rendu lateral blanc provenait principalement du contenu de l'image et non de `background-position`.

---

## 1. Diagnostic Technique

### 1.1 Architecture du Diaporama

**Structure HTML** (`index.php` lignes 122-154):
```html
<input type="radio" name="slide" id="c3">
<label for="c3" class="card">
    <div class="row">
        <div class="description">
            <a href="...">
                <h3 class="radio-title">Ta grand-mère sous le figuier</h3>
            </a>
        </div>
    </div>
</label>
```

**Flux CSS** (style.css):
- `.card` : `display: flex`, `flex-basis: 5%`, `overflow: hidden`, `background-size: cover`
- Quand cochée : `input:checked + label.card` → `width: 100%`, `flex-grow: 1`, `display: flex`
- `.row` : `display: flex`, `width: 100%` (normal) → `width: fit-content` (quand card cochée)
- Fond d'image : `.card[for="c3"]` → `background-image: url(p_ta-grand-mere-sous-le-figuier.jpg)`

### 1.2 Dimensions des Images Comparées

Aspect ratios mesurés :

| Image                 | Dimensions   | Aspect Ratio            | Notes              |
|-----------------------|--------------|-------------------------|--------------------|
| c1 (À propos)         | 1138×640     | 1.78:1                  | ✓                  |
| c2 (Grésilhette)      | 2048×1152    | 1.78:1                  | ✓                  |
| **c3 (Grand-mère)**   | **1280×720** | **1.78:1 (16:9 exact)** | ⚠️ **SUSPECT**     |
| c4 (Théâtre forum)    | 4000×3000    | 1.33:1                  | Carré-ish, visible |
| c5 (Cours)            | 1135×639     | 1.77:1                  | ✓                  |
| c6 (Stage)            | 1525×857     | 1.78:1                  | ✓                  |
| c7 (Ateliers)         | 2045×1154    | 1.77:1                  | ✓                  |

**Observation clé** : c3 est la SEULE image avec dimensions exactes en 16:9 **entier** (1280×720).  
Toutes les autres ont des dimensions non-standardisées qui ont probablement forcé un redimensionnement lors de leur création.

### 1.3 CSS Potentiellement Problématique

**Ligne 940-942** (style.css):
```css
.card[for="c3"],
.card[for="c4"] {
    background-position: 49.5%;
    border-radius: 1rem;
}
```

**Ligne 1022-1024** (style.css):
```css
input:checked + label .row {
    backdrop-filter: blur(0.3125rem);
    width: fit-content;
}
```

### 1.4 JavaScript du Diaporama

Présent dans `public/script/app.js` (lignes 1-40). Le JavaScript :
- Gère le clic sur les cartes pour ouvrir/fermer  
- N'ajoute PAS de styles CSS spécifiques à c3  
- N'interfère PAS avec le rendu des images de fond

---

## 2. Causes Probables (Ordre de Probabilité)

### 2.1 **[TRÈS PROBABLE]** Problème de `background-size: cover` + `background-position`

**Hypothèse** : L'image c3 (1280×720) a un aspect ratio exactement 16:9. Quand appliqué avec `background-size: cover` et `background-position: 49.5%`, le navigateur peut calculer un clipping légèrement différent.

**Pourquoi affecte surtout c3** :
- Les autres images ont des dimensions irrégulières (2048×1152, 1138×640, etc.)
- c4 a un aspect ratio très différent (1.33:1) qui n'est pas affecté par le même positionnement
- c3 est "parfaitement" 16:9, causant potentiellement un calcul de positionnement qui laisse des marges

### 2.2 **[PROBABLE]** Flexbox + `width: fit-content` sur `.row`

**Hypothèse** : Lors du passage de l'état fermé au state ouvert :
- Le `.card` change de `flex-basis: 5%` à `flex-grow: 1` (100%)
- Le `.row` change de `width: 100%` à `width: fit-content`
- Cette combinaison peut causer un recalcul du contexte de formatage du fond

**Symptôme** : Le fond d'image n'est pas re-stretched correctement pour remplir la nouvelle dimension du `.card`.

### 2.3 **[POSSIBLE]** Timing du chargement de l'image

**Hypothèse** : L'image c3 peut être cachée/chargée différemment selon le timing du radio button.

Moins probable car même les autres images devraient avoir ce problème.

### 2.4 **[MOINS PROBABLE]** Problème spécifique du navigateur

Navigation rendering du `background-size: cover` + `flex-grow` peut avoir des bugs navigateur-spécifiques.

---

## 3. Solutions à Implémenter

### **Solution 1** : Forcer `background-size` et `background-attachment` (RECOMMANDÉE)

**Fichier** : `/var/www/public/css/style.css`  
**Ligne** : ~930 (`.card[for="c3"]`)

```css
.card[for="c3"] {
    background-image: -webkit-image-set(
        url(../images/p_ta-grand-mere-sous-le-figuier.webp) type("image/webp"),
        url(../images/p_ta-grand-mere-sous-le-figuier.jpg) type("image/jpeg")
    );
    background-image: image-set(
        url(../images/p_ta-grand-mere-sous-le-figuier.webp) type("image/webp"),
        url(../images/p_ta-grand-mere-sous-le-figuier.jpg) type("image/jpeg")
    );
    
    /* AJOUTER CES LIGNES */
    background-size: cover !important;
    background-position: center !important;
    background-attachment: fixed;
}
```

**Rationale** :
- `background-position: center` remplace `49.5%` qui peut causer du clipping asymétrique
- `background-size: cover !important` force le recalcul à chaque redimensionnement
- `background-attachment: fixed` isole l'image du contexte de flexbox

### **Solution 2** : Utiliser `object-fit` via une image réelle au lieu du fond

**Approach** : Remplacer le background-image par une balise `<img>` positionnée en absolue.

```html
<label for="c3" class="card">
    <img src="/images/p_ta-grand-mere-sous-le-figuier.jpg" 
         class="card-image" 
         alt="Ta grand-mère sous le figuier">
    <div class="row">
        ...
    </div>
</label>
```

```css
.card {
    position: relative;
}

.card-image {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: center;
    z-index: -1;
}
```

**Avantages** :
- `object-fit: cover` + `object-position` sont plus prévisibles que `background-size`
- Évite les problèmes de flexbox sur les backgrounds

### **Solution 3** : Ajuster le `background-position` uniquement pour c3

**Fichier** : `/var/www/public/css/style.css`  
**Ligne** : ~940

```css
.card[for="c3"],
.card[for="c4"] {
    background-position: center; /* au lieu de 49.5% */
    border-radius: 1rem;
}
```

**Rationale** : 
- Simple
- Le `49.5%` pourrait être responsable du clipping asymétrique
- `center` fonctionne pour tous les aspect ratios

### **Solution 4** : Normaliser les dimensions d'image

**Approche** : Créer une nouvelle version de c3 avec des dimensions non-standardisées (comme les autres).

Exemple : Redimensionner 1280×720 → 1200×675 (toujours 16:9 mais pas une puissance de 2).

```bash
convert p_ta-grand-mere-sous-le-figuier.jpg \
    -resize 1200x675 \
    p_ta-grand-mere-sous-le-figuier-new.jpg
```

**Avantages** : Aligne c3 avec le pattern des autres images  
**Inconvénients** : Perte de qualité potentielle

---

## 4. Prochaines Étapes Recommandées

1. **Immédiat** : Appliquer **Solution 1** (modification CSS simple, sans risque)
   - Tester ensuite sur tous les navigateurs (Chrome, Firefox, Safari, Edge)
   
2. **Si Solution 1 échoue** : Essayer **Solution 3** (changer `49.5%` → `center`)

3. **Si les solutions CSS échouent** : Implémenter **Solution 2** (refonte avec `<img>` réelle)
   - Nécessite plus de modification du CSS/HTML
   - Mais garantit le comportement attendu

4. **Dernier recours** : **Solution 4** (normaliser les dimensions)
   - À combiner avec une autre solution pour garantir le succès

---

## 5. Fichiers Concernés

| Fichier | Ligne(s) | Modification |
|---------|----------|--------------|
| `public/css/style.css` | 930-937 | `.card[for="c3"]` → Ajouter `background-size: cover !important;` |
| `public/css/style.css` | 940-942 | Changer `background-position: 49.5%` → `center` |
| `public/index.php` | 140-148 | *(Si Solution 2 implémentée)* Ajouter `<img>` dans le label |

---

## 6. Questions pour Reproduction

Si vous décidez d'implémenter une solution, testez ceci :

1. Ouvrir la page d'accueil
2. Cliquer sur la carte **c3** ("Ta grand-mère sous le figuier")
3. Vérifier que :
   - ✓ L'image s'affiche en pleine largeur (pas de blanc)
   - ✓ Le `border-radius: 1rem` est visible aux 4 coins
   - ✓ Aucune distorsion de l'image
4. Comparer avec **c1**, **c2** pour vérifier l'alignement

---

## Conclusion

Le problème est **très probablement** dû à une interaction entre :
1. L'aspect ratio exact 16:9 de l'image c3 (1280×720)
2. Le positionnement CSS `background-position: 49.5%` qui ne fonctionne pas bien pour ce ratio
3. Le changement de propriétés flexbox lors de l'ouverture (`width: fit-content` sur `.row`)

**Priorité d'implémentation** : **Solution 1** est la plus rapide et a le moins de risque.
