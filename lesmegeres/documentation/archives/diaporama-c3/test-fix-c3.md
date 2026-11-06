# Guide de Test - Fix c3 Diaporama

## Modifications Appliquées

**Fichier** : `public/css/style.css`

### Changement 1 : Spécification directe pour c3 (lignes 930-940)
```css
.card[for="c3"] {
    /* ... images ... */
    background-position: center !important;      /* AJOUTÉ */
    background-size: cover !important;           /* AJOUTÉ */
}
```

### Changement 2 : Bloc partagé c3/c4 (ligne 942-946)
```css
.card[for="c3"],
.card[for="c4"] {
    background-position: center;                 /* CHANGÉ de 49.5% */
    border-radius: 1rem;
}
```

---

## Checklist de Test

Testez dans Chrome, Firefox, Safari et Edge. Pour chaque test :

### Test 1 : Affichage Initial
- [ ] La page d'accueil charge sans erreur
- [ ] Tous les diaporamas (c1-c7) sont visibles en miniature en bas
- [ ] Aucun blanc visible aux côtés de c3 à l'état fermé

### Test 2 : Ouverture de c3
1. Cliquez sur la carte **c3** ("Ta grand-mère sous le figuier")
2. Attendez 0.6 secondes (durée de la transition CSS)
3. Vérifiez :
   - [ ] ✅ L'image s'affiche en **pleine largeur** du viewport
   - [ ] ✅ **Pas de blanc** à gauche
   - [ ] ✅ **Pas de blanc** à droite
   - [ ] ✅ Les **4 coins ont le border-radius** visible (1rem)
   - [ ] ✅ L'image n'est **pas distordue**

### Test 3 : Comparaison avec Autres Cartes
- Cliquez sur **c1** : Vérifiez que l'affichage est identique à c3 (pleine largeur)
- Cliquez sur **c2** : Vérifiez que l'affichage est identique
- Cliquez sur **c5** : Vérifiez que l'affichage est identique
- Cliquez sur **c4** : Vérifiez que l'affichage fonctionne (c4 devrait aussi hériter du fix)

### Test 4 : Navigation
1. Ouvrez c3 complètement
2. Cliquez sur le titre "Ta grand-mère sous le figuier"
3. [ ] ✅ Vous êtes redirigé vers `/tagrand-mere`

### Test 5 : Responsive (Mobile)
- [ ] Testez sur mobile portrait (320px) - c3 doit être pleine largeur
- [ ] Testez sur tablet (768px) - c3 doit être pleine largeur
- [ ] Vérifiez qu'aucun blanc n'apparaît aux redimensionnements

### Test 6 : Caching/Rafraîchissement
1. Forcez le rafraîchissement du cache : `Ctrl+Shift+Delete` (ou `Cmd+Shift+Delete` sur Mac)
2. Rechargez la page avec `Ctrl+F5` (ou `Cmd+Shift+R` sur Mac)
3. Testez à nouveau l'ouverture de c3
4. [ ] ✅ Le fix persiste après le vidage du cache

---

## Rollback si Nécessaire

Si le fix crée d'autres problèmes, revenir à la version originale :

```bash
git checkout public/css/style.css
# ou manuellement revenir à background-position: 49.5% pour c3
```

---

## Résultat Attendu

**Avant le fix** : 
```
┌────────┐
│  [|||] │ <- Blanc visible
│ IMAGE  │ <- Pas pleine largeur
│ [|||]  │ <- Blanc visible  
└────────┘
```

**Après le fix** :
```
┌──────────────┐
│    IMAGE     │ <- Pleine largeur
│   (centered) │
│  Pas de blanc│
└──────────────┘
```

---

## Support Navigateurs

- ✅ Chrome/Chromium 90+
- ✅ Firefox 88+
- ✅ Safari 14+
- ✅ Edge 90+

L'utilisation de `background-position: center` est supportée par tous les navigateurs modernes depuis des décennies, donc il n'y a pas de risque de régression.
