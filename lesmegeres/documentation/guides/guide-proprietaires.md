# Guide Proprietaires du site

Public cible: proprietaires et responsables de contenu
Quand utiliser ce document: comprendre le fonctionnement general du site et piloter les actions courantes
Prerequis: aucun prerequis technique avance
Temps de lecture: 6 a 8 min

## 1) Ce que vous devez savoir
Le site est organise en:
- contenus/pages PHP (dans lesmegeres-app/pages)
- medias (images, pdf, multimedia)
- styles et scripts (dans public/css et public/script)

La documentation a ete simplifiee pour vous orienter vers les actions utiles.

## 2) Actions que vous pouvez faire en autonomie

### Publier une actualite
Utiliser: ../runbooks/contenu/ajouter-une-actu-rapide.md

### Verifier la qualite SEO avant publication
Utiliser:
- ../runbooks/seo/seo-checklist-proprietaires.md (source canonique)
- ../runbooks/seo/seo-checklist-proprietaires-imprimable.md (modele papier)

### Verifier que le site est pret pour une mise en ligne
Utiliser: ../runbooks/seo/seo-checklist-proprietaires.md

## 3) Ce qu'il faut demander a un administrateur
- changements de configuration serveur (nginx/apache)
- modifications de securite
- deploiement/rollback
- maintenance technique et diagnostics d'incident

## 4) Circuit de decision recommande
1. Definir le besoin (nouvelle actu, page a corriger, visuel a remplacer).
2. Verifier la checklist proprietaires.
3. Demander validation technique si impact serveur ou SEO critique.
4. Publier et controler le resultat.

## 5) En cas de passation proprietaire
Donner au nouveau responsable:
- ce guide
- la checklist SEO proprietaires (canonique) + sa version imprimable
- la procedure d'ajout d'actualite
- un contact administrateur technique
