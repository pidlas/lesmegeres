# Stratégie de migration vers Grav (document vivant)

Public cible: administrateur technique et pilotage du projet
Quand utiliser ce document: préparer, piloter et valider la migration du site vers Grav
Prérequis: connaître la structure actuelle du site, les contraintes SEO, et le contexte YunoHost
Temps de lecture: 10 à 15 min

## 1) Objectif
Permettre aux proprietaires de:
- modifier les pages existantes (texte, photos, blocs de contenu)
- creer de nouvelles pages
- publier sans toucher au code

Tout en préservant:
- la stabilite d'exploitation
- le référencement existant
- la possibilité de rollback

## 2) Décisions prises
1. CMS cible: Grav.
2. Positionnement: Grav devient le CMS principal du site (pas un backend cache derrière le site actuel).
3. Thème de depart recommande: Quark, avec thème enfant pour les adaptations.
4. Priorité méthode: contenu + URLs + SEO avant personnalisation visuelle avancée.

## 3) Hypothèses et limites
1. Les pages PHP majoritairement statiques sont migrables vers des pages Grav.
2. Le PHP applicatif ne sera pas repris tel quel et devra être remplacé ou isolé.
3. Les headers de sécurité locaux peuvent rester optionnels en environnement local hors YunoHost.
4. Le contexte local (PC) et la production auto-hébergée ont des vhosts differents.

## 4) Stratégie en 3 phases

### 4.0 Périmètre de migration (explicite)

Pages à migrer dans Grav (coeur éditorial):
1. presentation.php
2. compagnie.php
3. equipe.php
4. le-spectacle.php
5. theatre-d-ombres.php
6. sieste-contee.php
7. tagrand-mere.php
8. le-laboratoire.php
9. ateliers.php
10. stages.php
11. cours.php
12. projet-pedagogique.php
13. actualites.php
14. partenaires.php
15. contact.php
16. mentions-legales.php
17. politique-de-securite.php
18. credits.php
19. pages profils (anne.php, delphine.php, estelle.php, gresilhette.php, juliette.php, marie.php, philippe.php, sabine.php, suzie.php)

Éléments à garder temporairement hors Grav pendant la transition:
1. Front controller PHP actuel (index.php) pour assurer la cohabitation tant que la bascule n'est pas complète.
2. Includes PHP existants (header.php, footer.php) tant que toutes les pages ne sont pas portées en Twig.
3. Mode maintenance statique (/maintenance/index.html).
4. Fichiers techniques à la racine (robots.txt, security.txt, .well-known/*, config Nginx/Apache, scripts de déploiement).

Éléments explicitement hors périmètre de migration contenu:
1. documentation/
2. scripts/
3. configurations serveur (Nginx, Apache, .htaccess)

Règle de bascule page par page:
1. Une page n'est consideree migrée que si contenu, SEO (title/H1/meta), media et URL cible sont valides.
2. Tant qu'une page n'est pas validée, l'ancienne route PHP reste active.
3. Une fois validée, activer la redirection 301 vers la route Grav correspondante.

### 4.1 Tableau de suivi opérationnel (page par page)

Convention de suivi:
- Statut: à planifier | en cours | validé
- SEO valide: oui | non
- Redirection 301: oui | non | n/a
- Proprio édition: personne référence pour valider le contenu

|Page source      |Rte actuelle |Rte Grav cible|Lot|Statut     |Proprio|SEO valide|Redirection 301|
|-----------------|-------------|--------------|---|-----------|-------|----------|---------------|
|presentation.php |/presentation|/presentation | A |à planifier|Sabine | non      | n/a           |
|compagnie.php    |/compagnie   |/compagnie    | A |à planifier|Sabine | non      | n/a           |
|equipe.php       |/equipe      |/equipe       | A |à planifier|Sabine | non      | n/a           |
|le-spectacle.php |/le-spectacle|/le-spectacle | A |à planifier|Suzie  | non      | n/a           |
|contact.php      |/contact     |/contact      | A |à planifier|Sabine+Suzie|non  | n/a           |
|theatre-d-ombres.php|/theatre-d-ombres|/theatre-d-ombres|A|à planifier|Suzie|non | n/a           |
|mentions-legales.php|/mentions-legales|/mentions-legales|A|à planifier|Sabine+Suzie|non| n/a     |
|actualites.php   |/actualites  |/actualites   | B |à planifier|à definir| non    | n/a           |
|ateliers.php     |/ateliers    |/ateliers     | B |à planifier|à definir| non    | n/a           |
|stages.php       |/stages      |/stages       | B |à planifier|à definir| non    | n/a           |
|cours.php        |/cours       |/cours        | B |à planifier|à definir| non    | n/a           |
|credits.php      |/credits     |/credits      | B |à planifier|à definir| non    | n/a           |
|partenaires.php  |/partenaires |/partenaires  | B |a planifier|à definir| non    | n/a           |
|projet-pedagogique.php|/projet-pedagogique|/projet-pedagogique|B|à planifier|à definir|non| n/a  |
|le-laboratoire.php |/le-laboratoire |/le-laboratoire|B|à planifier|à definir|non | n/a           |
|politique-de-securite.php|/politique-de-securite|/politique-de-securite|B|à planifier|à definir| non| n/a |
|tagrand-mere.php |/tagrand-mere|/tagrand-mere | C |à planifier|à definir| non    | n/a           |
|anne.php         |/anne        |/anne         | C |à planifier|à definir| non    | n/a           |
|delphine.php     |/delphine    |/delphine     | C |à planifier|à definir| non    | n/a           |
|estelle.php      |/estelle     |/estelle      | C |à planifier|à definir| non    | n/a           |
|gresilhette.php  |/gresilhette |/gresilhette  | C |à planifier|à definir| non    | n/a           |
|juliette.php     |/juliette    |/juliette     | C |à planifier|à definir| non    | n/a           |
|marie.php        |/marie       |/marie        | C |à planifier|à definir| non    | n/a           |
|philippe.php     |/philippe    |/philippe     | C |à planifier|à definir| non    | n/a           |
|sabine.php       |/sabine      |/sabine       | C |à planifier|à definir| non    | n/a           |
|suzie.php        |/suzie       |/suzie        | C |à planifier|à definir| non    | n/a           |
|sieste-contee.php|/sieste-contee|/sieste-contee| C |à planifier|à definir| non   | n/a           |

Règle de priorisation des lots:
1. Lot A: pages vitales pour parcours visiteur (à migrer en premier).
2. Lot B: pages de contenu récurrent et institutionnel.
3. Lot C: pages profils et contenus annexes.

### 4.2 Ordre recommandé pour le Lot A

Ordre de migration conseille:
1. presentation.php
2. compagnie.php
3. le-spectacle.php
4. theatre-d-ombres.php
5. equipe.php
6. contact.php
7. mentions-legales.php

Pourquoi cet ordre:
1. Stabiliser d'abord les pages d'entrée et de contexte du site.
2. Ensuite migrer les pages coeur de l'offre artistique.
3. Puis consolider la preuve sociale (equipe) et la conversion (contact).
4. Finir par la page légale pour compléter le socle de production.

Critère de validation par page du Lot A:
1. Contenu: texte principal repris et relu.
2. SEO: title, H1, meta description, slug, canonique vérifiés.
3. Media: images présentes, alt remplis, poids acceptable.
4. Navigation: lien depuis menu principal et liens internes valides.
5. Technique: rendu mobile/desktop correct, aucun bloc cassant.
6. Publication: validation conjointe admin + propriétaire référence.

Definition de done (Lot A):
1. Les 7 pages sont marquees "validée" dans le tableau.
2. SEO valide = "oui" pour les 7 pages.
3. Redirection 301 activee si URL finale différente.
4. Vérification post-deploiement sans regression critique.

### 4.3 Référents éditoriaux proposés (Lot A)

Règle:
1. Un référent contenu valide le fond éditorial.
2. Un référent admin valide SEO, media et publication.

Proposition initiale (à confirmer):
1. presentation.php -> Référent contenu: Sabine | Référent admin: Admin site
2. compagnie.php -> Référent contenu: Sabine | Référent admin: Admin site
3. le-spectacle.php -> Référent contenu: Suzie | Référent admin: Admin site
4. theatre-d-ombres.php -> Référentrent contenu: Suzie | Référent admin: Admin site
5. equipe.php -> Référent contenu: Sabine | Référent admin: Admin site
6. contact.php -> Référent contenu: Sabine + Suzie | Référent admin: Admin site
7. mentions-legales.php -> Référent contenu: Sabine + Suzie | Référent admin: Admin site

Note:
- Si besoin, passer à un seul référent contenu par page pour accélérer les validations.

### 4.4 Calendrier simple proposé (Lot A)

Semaine 1:
1. Jour 1: presentation.php et compagnie.php
2. Jour 2: le-spectacle.php
3. Jour 3: theatre-d-ombres.php
4. Jour 4: equipe.php
5. Jour 5: réservé corrections (SEO, media, liens)

Semaine 2:
1. Jour 1: contact.php
2. Jour 2: mentions-legales.php
3. Jour 3: recette complete Lot A (desktop/mobile, SEO, navigation)
4. Jour 4: pré-bascule et plan de rollback
5. Jour 5: bascule contrôlée + vérification post-deploiement

Cadence de suivi recommandée:
1. Point quotidien de 15 min (statut, blocages, décisions).
2. Point de validation hebdomadaire avec propriétaires.

### Phase 1 - Cadrage et sécurisation SEO
Objectif: figer les regles du jeu avant tout chantier visuel.

Actions:
1. Définir le périmètre des pages à migrer.
2. Établir la table Ancienne URL -> Nouvelle URL.
3. Lister les metadonnées SEO à conserver (title, meta description, H1, alt, maillage interne).
4. Définir la politique de redirection 301.
5. Préparer un plan de rollback opérationnel.

Sortie attendue:
- table de redirections validée
- checklist SEO de migration validée

### Phase 2 - Pilote Grav sur environnement de test
Objectif: valider l'usage editorial par les propriétaires et la compatibilité SEO.

Actions:
1. Installer Grav + Admin sur un environnement de test.
2. Installer Quark + thème enfant minimal.
3. Migrer un echantillon représentatif de pages (accueil, contenu long, contact, mentions).
4. Reprendre medias et navigation.
5. Faire tester la création/modification de pages par les propriétaires.
6. Corriger seulement les écarts bloquants (édition, UX, SEO).

Sortie attendue:
- pilote utilisable par les propriétaires
- validation fonctionnelle et éditoriale

### Phase 3 - Bascule progressive et exploitation
Objectif: mise en production maitrisée, avec contrôles et retour arrière.

Actions:
1. Migrer les pages restantes par lots.
2. Activer les redirections 301.
3. Exécuter les checks post-deploiement.
4. Surveiller erreurs, 404, et performances percevables.
5. Garder un rollback exécutable rapidement.

Sortie attendue:
- site Grav en production
- édition autonome par les propriétaires
- SEO conservé

## 5) Critère de succès
1. Les propriétaires créent et modifient des pages sans intervention technique.
2. Les pages principales conservent leur trafic SEO et leurs positions cibles.
3. Les URL critiques ne cassent pas (ou sont correctement redirigées).
4. Le rollback est documenté et testé.

## 6) Registre des risques
1. Risque: perte SEO par changement d'URL.
   Mesure: table de redirections + contrôle post-bascule.
2. Risque: complexité de theming trop tôt.
   Mesure: theme Quark + thème enfant, personnalisation progressive.
3. Risque: confusion local/production.
   Mesure: rappeler les vhosts actifs par environnement dans les guides serveur.

## 7) Questions ouvertes (à alimenter)
1. Quelles pages PHP contiennent encore de la logique non migrable telle quelle?
2. Quel niveau d'autonomie éditoriale vise-t-on dès la phase pilote?
3. Quelles URL sont non-négociables pour le SEO historique?
4. Quelles intégrations externes doivent être préservées (formulaire, analytics, suivi)?

## 8) Journal des décisions
- 2026-07-19: choix de Grav confirmé.
- 2026-07-19: approche prudentielle retenue (SEO et structure avant theming avance).
- 2026-07-19: thème recommande: Quark + thème enfant.
- 2026-07-19: périmètre initial de migration explicite (pages éditoriales migrées, pile technique hors périmètre).
- 2026-07-19: mise en place système retenue: Grav en production via YunoHost sur sous-domaine + Grav local hors YunoHost sur PC.

## 9) Ressources d'apprentissage
- Twig (officiel): https://twig.symfony.com/doc/
- Grav Learn: https://learn.getgrav.org/
- Sass guide: https://sass-lang.com/guide
- Sass documentation: https://sass-lang.com/documentation/

## 10) Mode d'utilisation de ce document
1. Toute nouvelle question stratégique est ajoutée dans la section Questions ouvertes.
2. Toute décision validée est ajoutée dans le Journal des décisions.
3. Si une hypothèse devient fausse, la corriger immédiatement dans Hypotheses et limites.

## 11) Mise en place système (production + local)

Runbook detaillé:
- ../runbooks/production/installation-grav-local-et-yunohost.md

Décision d'architecture:
1. Production: installer Grav via YunoHost sur un sous-domaine dedie (exemple: `grav.lesmegeresdelhumus.fr`).
2. Local PC: installer Grav hors YunoHost pour prototypage, tests de thème et formation Twig/Sass.

Ordre recommandé:
1. Installer et valider d'abord Grav en local (hors YunoHost).
2. Installer ensuite Grav sur le sous-domaine YunoHost de production.
3. Reproduire les réglages utiles entre local et production (plugins, thème enfant, structure de pages).

Pourquoi cet ordre:
1. Réduire le risque sur la production.
2. Former les usages éditoriaux en local avant exposition publique.
3. Corriger les écarts de thème/SEO avant la bascule.

Checks système minimaux après installation:
1. Accès admin Grav OK.
2. Création/modification d'une page test OK.
3. Upload image et rendu front OK.
4. URL propre de page test OK.
5. Sauvegarde/restauration de base testée au moins une fois.

Note d'exploitation:
1. Le sous-domaine Grav en production sert d'abord de pilote éditorial.
2. La bascule du site principal se fera uniquement apres validation complète du Lot A.