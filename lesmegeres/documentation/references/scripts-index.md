# Index des scripts operationnels

Public cible: administrateur technique
Quand utiliser ce document: trouver rapidement le bon script et son usage
Prerequis: acces shell au projet
Temps de lecture: 4 a 6 min
Statut: reference operationnelle

## Principe d'organisation

Les scripts Bash/Python sont regroupes dans:
- /var/www/lesmegeres/scripts/

Compatibilite legacy:
- Des wrappers restent a la racine (/var/www/lesmegeres/*.sh, add_security_headers.py)
- Les commandes historiques continuent de fonctionner.

## Scripts canoniques

- scripts/deploy.sh: deploiement (dry-run/apply/fix-owners, backup, post-check)
- scripts/rollback.sh: restauration rapide depuis backup distant
- scripts/check-post-deploy.sh: verifications HTTP/SEO post-deploiement
- scripts/check-uptime.sh: monitoring periodique des pages critiques
- scripts/check-ssh-keys.sh: verification des cles SSH locales/distantes
- scripts/minify-css.sh: minification manuelle de css/style.css
- scripts/watch-css.sh: minification automatique sur modification
- scripts/add_security_headers.py: injection des en-tetes de securite dans conf Nginx cible

## Commandes recommandees

Depuis /var/www/lesmegeres:
- ./scripts/deploy.sh
- ./scripts/rollback.sh
- ./scripts/check-post-deploy.sh
- ./scripts/check-uptime.sh
- ./scripts/check-ssh-keys.sh
- ./scripts/minify-css.sh
- ./scripts/watch-css.sh
- python3 ./scripts/add_security_headers.py

## Compatibilite legacy (wrappers)

Toujours valides:
- ./deploy.sh
- ./rollback.sh
- ./check-post-deploy.sh
- ./check-uptime.sh
- ./check-ssh-keys.sh
- ./minify-css.sh
- ./watch-css.sh
- python3 ./add_security_headers.py

## Regle de maintenance

- Tout nouveau script Bash/Python doit etre cree dans scripts/.
- Si un ancien nom racine est retire, documenter la migration ici et dans le guide administrateur.

## Politique de deploiement

Principe actuel:
- Le deploiement publie le site (pages, assets, fichiers publics) via scripts/deploy.sh.
- Le dossier scripts/ n'est pas deploye sur le serveur cible.
- Le dossier documentation/ n'est pas deploye non plus.

Implications:
- Les scripts sont executes localement depuis le poste d'administration.
- Les wrappers legacy a la racine existent pour compatibilite des anciennes commandes.

Si un jour des scripts doivent etre disponibles sur le serveur:
1. Ajouter explicitement scripts/ dans la logique de deploiement.
2. Documenter le changement dans ce fichier et dans guide-administrateur.md.
3. Ajouter une verification post-deploiement adaptee.
