# Avant mise en production (historique operatoire)

Public cible: administrateur technique
Quand utiliser ce document: consulter l'historique des actions et incidents avant bascule
Prerequis: acces shell, connaissance YunoHost et Nginx
Temps de lecture: 15 a 20 min
Statut: historique detaille (se referer a checklist-production.md pour l'operationnel courant)

## Resume executif

Ce document conserve l'historique complet des tentatives de bascule et de diagnostic autour de /site1, SSOWAT et des permissions YunoHost.
Pour un deploiement courant, utiliser d'abord checklist-production.md, puis revenir ici seulement en cas d'incident atypique.

## Parcours de lecture recommande

1. Lire d'abord les sections 3 et 5 pour comprendre les causes frequentes (routes, permissions, SSO).
2. Utiliser la section 6 pour rollback propre si necessaire.
3. En dernier recours, suivre les sections 7 a 9 pour le diagnostic profond.

## Index operatoire (acces rapide)

- 1) Activer le serveur local Nginx
- 2) Passer en production
- 3) Verifications YunoHost avant bascule
- 4) Incident historique 404 sur slug
- 5) Resolution en production
- 6) Retour arriere propre
- 7) Diagnostic 500/302 persistant
- 8) Modification permissions par CLI
- 9) Reprise complete

---
## 1) Activer le serveur local Nginx
## 2) Passer en production
## 3) A faire sur le serveur YunoHost avant de basculer le nouveau site en production
## 4) L'acces au slug donnait des erreurs en 404
## 5) Mode de resolution du probleme dans l'environnement de production
## 6) Retour arriere propre dans l'environnement de production
## 7) Probleme persistant: qui renvoie le 500 ou le 302 dans l'environnement de production ?
## 8) Modifier les permissions par CLI dans l'environnement de production
## 9) On recommence

---
## 1) Activer le serveur local Nginx

sudo cp /etc/nginx/conf.d/lesmegeres.conf /etc/nginx/conf.d/lesmegeres.conf.bak
sudo sed -i 's#access_log   /var/log/nginx/lesmegeres.access.log main;#access_log /var/log/nginx/lesmegeres.access.log;#' /etc/nginx/conf.d/lesmegeres.conf
sudo nginx -t
sudo systemctl restart nginx
sudo systemctl status nginx --no-pager -l

sudo cp -a /etc/nginx/conf.d /etc/nginx/conf.d.bak.$(date +%F-%H%M%S)
sudo sed -E -i 's#(access_log[[:space:]]+[^;[:space:]]+)[[:space:]]+main;#\1;#g' /etc/nginx/conf.d/*.conf
sudo nginx -t
sudo systemctl restart nginx
sudo systemctl status nginx --no-pager -l

### 1.1. Copier le vhost dans Nginx
sudo cp /var/www/lesmegeres/nginx-lesmegeres.conf /etc/nginx/conf.d/megeres.conf

### 1.2. Désactiver le site default (qui intercepte tout)
sudo rm -f /etc/nginx/sites-enabled/default

### 1.3. Tester et recharger
sudo nginx -t && sudo systemctl reload nginx

curl -I http://lesmegeres/
curl -I http://lesmegeres/compagnie
curl -I http://lesmegeres/index.php

## 2) Passer en production

### 2.1. Adapter le server_name dans /etc/nginx/conf.d/megeres.conf

[] Retirer "lesmegeres", garder lesmegeresdelhumus.fr

### 2.2. Générer le certificat SSL

[] sudo certbot --nginx -d lesmegeresdelhumus.fr -d www.lesmegeresdelhumus.fr

## 3) A faire sur le serveur YunoHost avant de basculer le nouveau site en production

### 3.1. Vérifier les routes actuelles

[OK] sudo yunohost app map

### 3.2. Basculer l'app de /site1 vers /

sudo yunohost app change-url my_webapp__3 -d lesmegeresdelhumus.fr -p /

### 3.3. Vérifier nginx

sudo nginx -t
sudo systemctl reload nginx

### 3.4. Vérifier les liens

curl -I https://lesmegeresdelhumus.fr/
curl -I https://lesmegeresdelhumus.fr/compagnie
curl -I https://lesmegeresdelhumus.fr/site1/
curl -I https://lesmegeresdelhumus.fr/pages/compagnie.php

Pour que les deux derniers liens fonctionnent il ne faut pas oublier de saisir la barre oblique finale.

## 4) L'acces au slug donnait des erreurs en 404

Le problème a été corrigé par chatGPT sur le serveur en production https://lesmegeresdelhumus.fr/site1/.

## 5) Mode de resolution du probleme dans l'environnement de production

sudo sed -i 's#try_files \$uri \$uri/ /site1/index.php?\$args =404;#try_files $uri $uri/ /site1/index.php?$args;#' /etc/nginx/conf.d/lesmegeresdelhumus.fr.d/my_webapp__3.conf

sudo nginx -t
sudo systemctl reload nginx

curl -I https://lesmegeresdelhumus.fr/site1/compagnie
curl -I https://lesmegeresdelhumus.fr/site1/pages/compagnie.php

### 5.1. Vérifier si des dossiers "pages" / "includes" sont présents dans le dossier public

sudo ls -ld /var/www/my_webapp__3/www/pages /var/www/my_webapp__3/www/includes 2>/dev/null || true
sudo find /var/www/my_webapp__3/www/pages -maxdepth 1 -type f | sed -n '1,20p' 2>/dev/null || true

### 5.2. En cliquant sur les liens du menu je tombe sur le SSO de YunoHost

#### Voir la permission actuelle de l'app
sudo yunohost user permission info my_webapp__3.main

#### Rendre l'app publique (accès visiteurs non connectés)
sudo yunohost user permission update my_webapp__3.main --add visitors

#### Régénérer la conf SSOwat
sudo yunohost app ssowatconf

#### Recharger nginx

sudo systemctl reload nginx

#### Tester

curl -I https://lesmegeresdelhumus.fr/site1/compagnie
curl -I https://www.lesmegeresdelhumus.fr/site1/compagnie
curl -s -o /dev/null -w "final=%{http_code} url=%{url_effective}\n" -L https://lesmegeresdelhumus.fr/site1/compagnie
curl -s -o /dev/null -w "final=%{http_code} url=%{url_effective}\n" -L https://www.lesmegeresdelhumus.fr/site1/compagnie
 

### 5.3. Ajouter explicitement les sous-chemins de /site1 en public

regex absolue domaine/path
sudo yunohost user permission url my_webapp__3.main --add "re:^lesmegeresdelhumus.fr/site1(/.*)?$"

### 5.4. S'assurer que visitors est bien autorisé

sudo yunohost user permission update my_webapp__3.main --add visitors

#### Voir les options disponibles pour cette version
sudo yunohost user permission update --help | sed -n '1,140p'

#### Recaler la permission (URL principale + sous-URLs)
sudo yunohost user permission update my_webapp__3.main --url "/" --add-url "/.*"

#### Garder l'app publique
sudo yunohost user permission update my_webapp__3.main --add visitors

### 5.5. Régénérer la conf SSOwat + reload nginx

sudo yunohost app ssowatconf
sudo systemctl reload nginx

## 6) Retour arriere propre dans l'environnement de production

### 6.1. Rollback de la modif SSOwat persistante

sudo cp /etc/ssowat/conf.json.persistent.bak.* /etc/ssowat/conf.json.persistent

### 6.2. Regénérer SSOwat + reload nginx

sudo yunohost app ssowatconf
sudo systemctl reload nginx

### 6.3. Re-test rapide

curl -I https://lesmegeresdelhumus.fr/site1/
curl -I https://lesmegeresdelhumus.fr/site1/compagnie

## 7) Probleme persistant: qui renvoie le 500 ou le 302 dans l'environnement de production ?

### 7.4. Voir la conf effective SSO pour l'app

sudo jq '.permissions["my_webapp__3.main"]' /etc/ssowat/conf.json

### 7.5 Lancer une requête puis lire les logs juste après

curl -I https://lesmegeresdelhumus.fr/site1/compagnie
sudo tail -n 80 /var/log/nginx/error.log
sudo tail -n 80 /var/log/php8.4-fpm.log

## 8) Modifier les permissions par CLI dans l'environnement de production

sudo yunohost user permission update my_webapp__3.main --add-url '/.*'

### 8.1. Regénérer SSOwat + reload nginx

sudo yunohost app ssowatconf
sudo systemctl reload nginx

### 8.2 Backup de la conf active

sudo cp /etc/ssowat/conf.json /etc/ssowat/conf.json.bak.$(date +%F-%H%M)

### 8.3 Ajouter une URI wildcard pour l'app

tmp=$(mktemp)
sudo jq '
  .permissions["my_webapp__3.main"].public = true
  | .permissions["my_webapp__3.main"].uris =
    ((.permissions["my_webapp__3.main"].uris + ["lesmegeresdelhumus.fr/site1/.*"]) | unique)
' /etc/ssowat/conf.json > "$tmp" \
&& sudo install -o root -g root -m 644 "$tmp" /etc/ssowat/conf.json \
&& rm -f "$tmp"

### 8.4. Vérifier la règle présente

sudo jq '.permissions["my_webapp__3.main"]' /etc/ssowat/conf.json

### 8.5. Recharger nginx sans regénérer ssowatconf pour ne pas écraser

sudo systemctl reload nginx

### 8.6. Test

curl -I https://lesmegeresdelhumus.fr/site1/compagnie
curl -s -o /dev/null -w "final=%{http_code} url=%{url_effective}\n" -L https://lesmegeresdelhumus.fr/site1/compagnie

## 9) On recommence

### 9.1. Backup conf active + persistante

sudo cp /etc/ssowat/conf.json /etc/ssowat/conf.json.bak.$(date +%F-%H%M)
sudo cp /etc/ssowat/conf.json.persistent /etc/ssowat/conf.json.persistent.bak.$(date +%F-%H%M)

### 9.2. Patch conf active

tmp=$(mktemp)
sudo jq '
  .permissions["my_webapp__3.main"].public = true
  | .permissions["my_webapp__3.main"].uris =
    ((.permissions["my_webapp__3.main"].uris + [
      "lesmegeresdelhumus.fr/site1/",
      "re:^lesmegeresdelhumus\\.fr/site1(/.*)?$"
    ]) | unique)
' /etc/ssowat/conf.json > "$tmp" \
&& sudo install -o root -g root -m 644 "$tmp" /etc/ssowat/conf.json \
&& rm -f "$tmp"

### 9.3. Patch conf persistante pour survivre à la regen

tmp=$(mktemp)
sudo jq '
  .permissions["my_webapp__3.main"].public = true
  | .permissions["my_webapp__3.main"].uris =
    ((.permissions["my_webapp__3.main"].uris + [
      "lesmegeresdelhumus.fr/site1/",
      "re:^lesmegeresdelhumus\\.fr/site1(/.*)?$"
    ]) | unique)
' /etc/ssowat/conf.json.persistent > "$tmp" \
&& sudo install -o root -g root -m 644 "$tmp" /etc/ssowat/conf.json.persistent \
&& rm -f "$tmp"

### 9.4. Redémarrage complet nginx, pas juste reload

sudo systemctl restart nginx

### 9.5. Vérification

sudo jq '.permissions["my_webapp__3.main"].uris' /etc/ssowat/conf.json
curl -I https://lesmegeresdelhumus.fr/site1/compagnie
curl -s -o /dev/null -w "final=%{http_code} url=%{url_effective}\n" -L https://lesmegeresdelhumus.fr/site1/compagnie
