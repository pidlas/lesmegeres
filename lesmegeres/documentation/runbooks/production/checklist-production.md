# Checklist production (canonique)

Public cible: administrateur technique
Quand utiliser ce document: juste avant et juste apres un deploiement
Prerequis: acces shell au projet, scripts/deploy.sh et scripts/check-post-deploy.sh disponibles
Temps de lecture: 4 a 6 min

Commande de deploiement recommandee:
./scripts/deploy.sh --apply --verify-post-owners

Compatibilite legacy:
- ./deploy.sh (wrapper)

Politique de deploiement:
- Voir ../references/scripts-index.md (section "Politique de deploiement") pour le perimetre exact deploye (site public) et non deploye (scripts/, documentation/).

## Procedure standard

1. Lancer un dry-run: `./scripts/deploy.sh`.
2. Lire le resume DRY-RUN et verifier la liste des fichiers.
3. Verifier qu'aucun fichier parasite (backup, temporaire) ne part en production.
4. Lancer l'apply: `./scripts/deploy.sh --apply` (ou la commande recommandee ci-dessus).
5. Lire le resume APPLY et confirmer les fichiers transferes.
6. Verifier la ligne `Ownership normalized` en fin de deploiement.
7. Si `0 fichiers transferes` et comportement anormal (ex: 500), lancer: `./scripts/deploy.sh --fix-owners`.
8. Verifier le post-check automatique (PASS/FAIL).
9. Tester le site sur mobile: menu hamburger, sous-menus, navigation vers une page.
10. Tester les pages critiques: accueil, contact, theatre-d-ombres, sieste-contee, projet-pedagogique.
11. En cas d'anomalie, corriger localement puis relancer dry-run + apply.
12. Consigner l'incident et la correction dans `documentation/archives/` si le cas est non trivial.

## Validation minimale de fin

- [ ] Dry-run valide
- [ ] Apply valide
- [ ] Ownership valide
- [ ] Post-check PASS
- [ ] Smoke test mobile et pages critiques valide

## Legacy

Les anciens fichiers suivants sont conserves comme alias de transition:
- checklist-production-5-lignes.md
- checklist-production-10-lignes.md
