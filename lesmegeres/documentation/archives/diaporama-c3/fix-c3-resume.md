# RÉSUMÉ - Fix du problème d'affichage c3

**Date** : 2 juillet 2026  
**Status** : ✅ **FIX APPLIQUÉ**

---

## 🎯 Problème Identifié

Quand l'image c3 ("Ta grand-mère sous le figuier") était affichée en grand, elle ne prenait pas toute la largeur du viewport. Du blanc visible à gauche et à droite cassait le `border-radius`.

**Cause racine** :
- Image c3 : dimensions exactes 1280×720 (16:9)
- CSS utilisant : `background-position: 49.5%`
- Cette combinaison crée un clipping asymétrique

---

## ✅ Solution Appliquée

**Fichier modifié** : `/var/www/public/css/style.css`

### Modification 1 (ligne ~930)
```css
.card[for="c3"] {
    /* ... images ... */
    background-position: center !important;      /* ← AJOUTÉ */
    background-size: cover !important;           /* ← AJOUTÉ */
}
```

### Modification 2 (ligne ~942)
```css
.card[for="c3"],
.card[for="c4"] {
    background-position: center;    /* ← CHANGÉ de 49.5% */
    border-radius: 1rem;
}
```

---

## 📋 Fichiers Créés pour Documentation

| Fichier | Contenu |
|---------|---------|
| [analyse-probleme-c3-diaporama.md](analyse-probleme-c3-diaporama.md) | Diagnostic technique complet, 4 solutions alternatives proposées |
| [test-fix-c3.md](test-fix-c3.md) | Checklist de test complète (Chrome, Firefox, Safari, Edge, responsive) |
| [minify-css.sh](minify-css.sh) | Script pour régénérer style.min.css |

---

## 🚀 Prochaines Étapes

### 1. Vérifier le fix dans le navigateur
Ouvrez l'accueil, cliquez sur c3, vérifiez qu'il n'y a plus de blanc latéral.

### 2. Mettre à jour le CSS minifié (Important!)

L'application utilise actuellement `style.min.css` (version compressée). Vous devez la régénérer :

**Option A - Script bash** (Recommandé)
```bash
cd /var/www/public
bash minify-css.sh
```

**Option B - À la main avec PostCSS**
```bash
cd /var/www/public
npm install cssnano postcss-cli  # Si pas déjà installé
npx postcss css/style.css -o css/style.min.css
```

**Option C - Minificateur online**
- Copier le contenu de `style.css`
- Coller sur https://cssnano.co/ (ou https://www.minifier.org/)
- Sauvegarder le résultat dans `style.min.css`

### 3. Vider le cache navigateur
```bash
# Dans DevTools (F12) :
# - Inspector → Settings → Onglet "Network" 
# - Cocher "Disable cache (while DevTools is open)"
# - Rechargez (Ctrl+F5 ou Cmd+Shift+R)
```

### 4. Tester (voir [test-fix-c3.md](test-fix-c3.md))

---

## 🔄 Si le Fix ne Fonctionne Pas

Consultez [analyse-probleme-c3-diaporama.md](analyse-probleme-c3-diaporama.md) section **"3. Solutions à Implémenter"** pour :
- Solution 2 : Utiliser `<img>` réelle au lieu de background-image
- Solution 3 : Autres ajustements CSS
- Solution 4 : Normaliser les dimensions d'image

---

## 📊 Informations Téchniques

**Image c3**
- Fichier : `/var/www/public/images/p_ta-grand-mere-sous-le-figuier.jpg`
- Dimensions : 1280 × 720 px (16:9)
- Size : 214 KB (JPG), 129 KB (WebP)

**Comparaison avec autres images du diaporama**
| Carte | Dimensions | Ratio | Format |
|-------|-----------|-------|--------|
| c1 | 1138 × 640 | 1.78 | .jpg, .webp |
| c2 | 2048 × 1152 | 1.78 | .jpg, .webp |
| **c3** | **1280 × 720** | **1.78 (16:9 exact)** | .jpg, .webp |
| c4 | 4000 × 3000 | 1.33 | .jpg, .webp |
| c5 | 1135 × 639 | 1.77 | .jpg, .webp |
| c6 | 1525 × 857 | 1.78 | .jpg, .webp |
| c7 | 2045 × 1154 | 1.77 | .jpg, .webp |

*c3 est la seule avec dimensions exactes 16:9 - c'est ce qui causait le problème.*

---

## 🔗 Références

- [HTML](index.php#L122) : Structure du diaporama (lignes 122-154)
- [CSS](css/style.css#L863) : Styles du diaporama (lignes 863-1020)
- [JavaScript](script/app.js#L1) : Logique du diaporama (lignes 1-40)

---

## ✨ Support

Si vous avez besoin de réactiver le problème ou apporter des ajustements, tous les détails techniques sont dans [analyse-probleme-c3-diaporama.md](analyse-probleme-c3-diaporama.md).

**Crédit** : Fix appliqué par l'assistant GitHub Copilot le 2/07/2026.
