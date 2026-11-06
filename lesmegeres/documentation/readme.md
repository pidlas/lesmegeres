# Documentation du site lesmegeresdelhumus.fr

Ce dossier est organisé pour séparer:
- les guides stables (par public)
- les runbooks opérationnels (procédures)
- les références techniques (notes utiles)
- les archives (historique de chantiers)

## Comment s'orienter rapidement

### Si vous êtes administrateur technique
Lisez d'abord:
- guides/guide-administrateur.md
- guides/guide-serveur-web-nginx-yunohost.md
- guides/guide-serveur-web-apache-hors-yunohost.md
- guides/strategie-migration-grav.md
- references/scripts-index.md

Puis utilisez les runbooks:
- runbooks/production/
- runbooks/maintenance/
- runbooks/securite/

### Si vous êtes proprietaire du site (non technique)
Lisez:
- guides/guide-proprietaires.md

Puis utilisez:
- runbooks/contenu/ajouter-une-actu-rapide.md
- runbooks/seo/seo-checklist-proprietaires.md

## Arborescence
- guides/ : documentation de référence, orientée par rôle
- runbooks/ : procédures pas-a-pas opérationnelles
- references/ : notes techniques transverses
- archives/ : historique, analyses ponctuelles, refactoring, correctifs

## Règles de maintenance documentaire
1. Un sujet vivant = un document de référence unique.
2. Les procédures exécutionnelles vont dans runbooks/.
3. Les comptes-rendus de correction vont dans archives/.
4. Chaque nouveau document doit commencer par:
   - Public cible
   - Quand utiliser ce document
   - Prérequis
5. Revue documentaire trimestrielle: fusionner, simplifier, archiver.

## Convention de nommage
- guide-*.md : guide stable de reference
- checklist-*.md / dossier runbooks/<theme>/ : procédure opérationnelle
- archives/<theme>/... : historique et traces de chantier
- Règle generale: noms de fichiers en minuscules avec tirets (kebab-case).