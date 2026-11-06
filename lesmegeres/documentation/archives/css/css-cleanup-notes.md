# Notes de nettoyage CSS

Date : 2026-07-02

## Contexte
Un fichier CSS source avait été perdu ou remplacé par une version minifiée. Une sauvegarde a été conservée avant toute transformation :
- [public/css/style.css.backup-20260702-clean](css/style.css.backup-20260702-clean)

## Nettoyage effectué
- Reconstitution d’une version source lisible à partir du fichier minifié.
- Normalisation du formatage du CSS (indentation, sauts de ligne, espaces).
- Conservation du comportement visuel existant, sans modification fonctionnelle volontaire.
- Préservation des règles liées au menu, aux sous-menus, au responsive et aux composants principaux.
- Déduplication conservatrice des blocs CSS identiques détectés dans la feuille.
- Suppression des doublons exacts de blocs CSS ayant la même définition, sans modifier les sélecteurs ni les valeurs.

## Principes suivis
- Garder la feuille de style exploitable et compréhensible.
- Éviter les suppressions de règles sans vérification préalable.
- Ne pas toucher au workflow de minification déjà en place.

## Point de vigilance
Le fichier source principal est désormais [public/css/style.css](css/style.css).
La minification continue d’être gérée par le watcher via [public/watch-css.sh](watch-css.sh).

## Détails du nettoyage fin
- Les blocs CSS strictement identiques ont été retirés au profit de la première occurrence.
- Les sélecteurs de type media query, composants de galerie et blocs de mise en forme répétitifs ont été vérifiés avant suppression.
- Les règles de base liées au menu, au responsive, aux cartes, aux images et aux sections ont été conservées intactes.
- Aucune règle de style fonctionnelle n’a été supprimée sans vérification préalable.

## Vérification effectuée
- Le fichier [public/css/style.css](css/style.css) a été contrôlé après nettoyage pour s’assurer qu’aucun bloc identique n’y subsistait.
- La sauvegarde d’origine reste disponible dans [public/css/style.css.backup-20260702-clean](css/style.css.backup-20260702-clean).
