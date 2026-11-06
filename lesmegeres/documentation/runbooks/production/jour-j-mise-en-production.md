# Jour J — Mise en production sur `https://lesmegeresdelhumus.fr/`

> Document de procédure pour le passage de `/site1/` vers `/`

Public cible: administrateur technique
Quand utiliser ce document: operation exceptionnelle de bascule structurelle (site1 vers racine)
Prerequis: acces root YunoHost, sauvegardes valides, fenetre de maintenance
Temps de lecture: 15 min + execution
Statut: procedure specialisee (ne pas utiliser comme checklist de deploiement courant)

## Resume executif

Ce document decrit une bascule exceptionnelle de /site1 vers la racine /. Il n'est pas destine aux deploiements habituels.
Pour le quotidien, utiliser checklist-production.md. Utiliser ce document uniquement lors d'une migration de chemin d'application.

## Demarrage rapide (si vous avez 5 minutes)

1. Verifier prerequis (sauvegardes, acces root, fenetre de maintenance).
2. Lire entierement les etapes marquees INOPERANT avant execution.
3. Preparer le rollback (configs nginx + ssowat) avant toute modification.
4. Appliquer la procedure, puis valider les URLs critiques en fin d'operation.

---

## Index de navigation

- 1) Contexte
- 2) Procedure
- 3) Mises a jour locales apres le Jour J
- 4) En cas de probleme - Reprendre avec GitHub Copilot

---

## 1) Contexte

| Avant le Jour J                                            | Après le Jour J                                              |
|------------------------------------------------------------|--------------------------------------------------------------|
| Site accessible sur `https://lesmegeresdelhumus.fr/site1/` | Site accessible sur `https://lesmegeresdelhumus.fr/`         |
| App YunoHost : `my_webapp__3`, chemin `/site1`             | App YunoHost : `my_webapp`, chemin `/`                       |
| Page de maintenance sur `/` (app `my_webapp`)              | Page de maintenance accessible sur `/maintenance` uniquement |
| `deploy.sh` cible `/var/www/my_webapp__3`                  | `deploy.sh` cible `/var/www/my_webapp`, `BASE_URL` mise à jour          |

## Prérequis

- [x] Dernier déploiement du site effectué : `./deploy.sh --apply`
- [x] Site vérifié sur `https://lesmegeresdelhumus.fr/site1/`
- [x] Accès SSH disponible : `ssh -p 1000 root@192.168.0.51`

## 2) Procédure

### Étape 1 — Sauvegarder les configs Nginx personnalisées

```bash
ssh -p 1000 root@192.168.0.51

**Sauvegarde de la config PHP personnalisée (SITE_BASE_PATH, etc.)**
[OK] cp /etc/nginx/conf.d/lesmegeresdelhumus.fr.d/my_webapp__3.conf \
   /etc/nginx/conf.d/lesmegeresdelhumus.fr.d/my_webapp__3.conf.avant-jour-j

[OK] cp /etc/nginx/conf.d/lesmegeresdelhumus.fr.d/my_webapp__3.d/php.conf \
   /etc/nginx/conf.d/lesmegeresdelhumus.fr.d/my_webapp__3.d/php.conf.avant-jour-j
```

### Étape 2 — Re-synchroniser l'app de maintenance  --  INOPÉRANT

`my_webapp` a une déclaration de permissions SSOWAT à `/` qui peut entrer en conflit.  
Cette étape force sa re-synchronisation sur son chemin réel `/maintenance` :

```bash
[OK] yunohost app change-url my_webapp --domain lesmegeresdelhumus.fr --path /maintenance
```
Le retour de la commande est : Error: The old and new domain/url_path are identical ('lesmegeresdelhumus.fr/maintenance'), nothing to do.

### Étape 3 — Changer l'URL de `my_webapp__3` --  INOPÉRANT

```bash
[OK] yunohost app change-url my_webapp__3 --domain lesmegeresdelhumus.fr --path /
```
Le retour de la commande est : Error: This URL is either unavailable, or conflicts with the already installed app(s):
 * lesmegeresdelhumus.fr/nextcloud → Nextcloud (nextcloud)
 * lesmegeresdelhumus.fr/maintenance → Les Mégères de l'Humus (my_webapp)

> YunoHost régénère automatiquement `/etc/nginx/conf.d/lesmegeresdelhumus.fr.d/my_webapp__3.conf`.  
> Les fichiers dans `my_webapp__3.d/` (dont `php.conf`) sont conservés.

### Remplace les Étapes 2 et 3 inopérantes

- Sauvegarde dans la console Yunohost des applications de lesmegeresdelhumus.fr ainsi que des données système
- Désinstallation des 3 applications associées au domaine lesmegeresdelhumus.fr sur les chemins lesmegeresdelhumus.fr/maintenance -> app yunohost my_webapp, lesmegeresdelhumus.fr/site1 -> app yunohost my_webapp__3, lesmegeresdelhumus.fr/nextcloud -> app yunohost nextcloud.
- Création d'un sous-domaine nextcloud.lesmegeresdelhumus.fr et ajout d'une entrée dans le DNS de Gandi `nextcloud CNAME lesmegeresdelhumus.fr.`
- Réinstallation de l'app webapp sur la racine de lesmegeresdelhumus.fr
- Réinstallation de l'app nextcloud sur nextcloud.lesmegeresdelhumus.fr
- Ajout du **named location** dans `/etc/nginx/conf.d/lesmegeresdelhumus.d/my_webapp.conf` pour éviter les pages PHP en 404

### Étape 4 — `SITE_BASE_PATH` dans la config PHP

Le fichier `php.conf` contient `SITE_BASE_PATH /site1` dans l'application my_webapp__3 — il faut le remplacer par `SITE_BASE_PATH /` dans l'application my_webapp.

```bash
grep SITE_BASE_PATH /etc/nginx/conf.d/lesmegeresdelhumus.fr.d/my_webapp.d/php.conf
**Doit afficher : fastcgi_param SITE_BASE_PATH /;**
```

Sinon le modifier :

```bash
sed -i 's|fastcgi_param SITE_BASE_PATH /site1;|fastcgi_param SITE_BASE_PATH /;|' \
    /etc/nginx/conf.d/lesmegeresdelhumus.fr.d/my_webapp.d/php.conf
```

Le bash ne fonctionnera pas si /site1 n'est pas présent dans le fichier de conf.


### Étape 5 — Rétablir les en-têtes de sécurité personnalisés

YunoHost injecte des en-têtes de base via `security.conf.inc`, mais notre CSP spécifique doit être restaurée.  
Créer (ou restaurer) ce fichier :

```bash
cat > /etc/nginx/conf.d/lesmegeresdelhumus.fr.d/my_webapp.d/headers.conf << 'EOF'
# En-têtes de sécurité supplémentaires (complètent security.conf.inc)
add_header X-Frame-Options           "SAMEORIGIN"                       always;
add_header X-Content-Type-Options    "nosniff"                          always;
add_header Referrer-Policy           "strict-origin-when-cross-origin"  always;
add_header Content-Security-Policy   "default-src 'self' https://*.helloasso.com 'unsafe-inline' data:; style-src-elem 'self' https://*.helloasso.com; script-src 'self' 'unsafe-inline' https://*.helloasso.com; frame-src 'self' https://*.helloasso.com; media-src 'self' https://*.helloasso.com; object-src 'none'" always;

EOF
```
**'unsafe-eval' a été retiré de default-src**

### Étape 6 — Tester et recharger Nginx

```bash
nginx -t && systemctl reload nginx
```

### Étape 7 — Vérifier le site

```bash
**Doit retourner 200**
curl -s -o /dev/null -w "%{http_code}" https://lesmegeresdelhumus.fr/
curl -s -o /dev/null -w "%{http_code}" https://lesmegeresdelhumus.fr/compagnie
curl -s -o /dev/null -w "%{http_code}" https://lesmegeresdelhumus.fr/contact
**Doit retourner 404**
curl -s -o /dev/null -w "%{http_code}" https://lesmegeresdelhumus.fr/contact.php

**La page de maintenance doit être installée en s'inspirant de paysaflore.fr**
curl -s -o /dev/null -w "%{http_code}" https://lesmegeresdelhumus.fr/maintenance
```

## 3) Mises à jour locales après le Jour J

**deploy.sh - Mettre à jour REMOTE_DIR ligne 12**
```bash
REMOTE_BASE="${REMOTE_BASE:-/var/www/my_webapp}" -> l'application est maintenant installée sur my_webapp
```

**deploy.sh - Mettre à jour BASE_URL ligne 15**
```bash
BASE_URL="${BASE_URL:-https://lesmegeresdelhumus.fr}" -> le chemin de l'application n'est plus /site1 mais la racine
```

### `index.php` — Supprimer le fallback `/site1`

Dans `/var/www/lesmegeres/index.php`, supprimer le bloc de fallback (vers la ligne 62) :

```php
// Supprimer ces lignes :
if ($siteBasePath === '' && ($routePath === '/site1' || str_starts_with($routePath, '/site1/'))) {
    $siteBasePath = '/site1';
}
```

### `sitemap.xml` et `robots.txt` — Vérifier les URLs

S'assurer qu'ils ne contiennent plus de référence à `/site1/`.

```bash
grep -n "site1" /var/www/lesmegeres/sitemap.xml /var/www/lesmegeres/robots.txt
```

### Déployer les changements

```bash
cd /var/www/lesmegeres
./deploy.sh --apply
```

## Résultat attendu

```
https://lesmegeresdelhumus.fr/            → Site réel (my_webapp, chemin /)
https://lesmegeresdelhumus.fr/maintenance → Page de maintenance (my_webapp, répertoire /maintenance)
https://lesmegeresdelhumus.fr/site1/      → affiche la page d'erreur de `index.php`
https://lesmegeresdelhumus.fr/site1       → 500 Internal Server Error **problème à régler**
```

## 4) En cas de problème — Reprendre avec GitHub Copilot

### Ce qu'il faut communiquer en début de conversation

> *"Je suis en train d'exécuter la procédure décrite dans `doc/jour-j-mise-en-production.md`. J'en suis à l'étape N et voici le problème : [...]. Voici l'output de la commande : [...]"*

L'agent lira le fichier et aura immédiatement le contexte complet.

**Règle : toujours coller l'output complet de la commande qui a échoué, pas seulement le message d'erreur final**

### Commandes de diagnostic à lancer avant de demander de l'aide

**Syntaxe Nginx et dernières erreurs**
```bash
nginx -t
journalctl -u nginx --since "5 minutes ago"
```

**État de l'app après changement de chemin de l'URL lesmegeresdelhumus.fr**
yunohost app info my_webapp
cat /etc/nginx/conf.d/lesmegeresdelhumus.fr.d/my_webapp.conf
cat /etc/nginx/conf.d/lesmegeresdelhumus.fr.d/my_webapp.d/php.conf

**Test HTTP rapide**
```bash
curl -sv https://lesmegeresdelhumus.fr/ 2>&1 | grep -E "< HTTP|Location"
```

