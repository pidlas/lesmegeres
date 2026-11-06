# Diagnostic & Résolution — lesmegeresdelhumus.fr (YunoHost)

---

## 1) [Contexte & Objectif](#contexte-&-objectif)
## 2) [Architecture Technique](#architecture-technique-1)
## 3) [Corrections Locales Effectuées](#corrections-locales-effectuées-1)
## 4) [Diagnostic Serveur](#diagnostic-serveur-1)
## 5) [Fix Nginx Validé](#fix-nginx-validé-1)
## 6) [Explication du Fix Nginx](#explication-du-fix-nginx-1)
## 7) [Solution SSOWAT](#solution-ssowat-1)
## 8) [Checklist de Déploiement](#checklist-de-déploiement-1)
## 9) [Checklist de Validation Finale](#checklist-de-validation-finale-1)
## 10) [Déploiement Recommandé](#déploiement-recommandé-1)
## 11) [Résumé de la Cause Racine](#résumé-de-la-cause-racine-1)
## 12) [Clé SSH](#clé-ssh-1)
## 13) [Perte de passphrase](#perte-de-passphrase-1)

---

## 1) Contexte & Objectif

Le site **lesmegeresdelhumus.fr** est hébergé sur un serveur YunoHost accessible via SSH sur `192.168.0.51:1000` (utilisateur `root`).
L'application était déployée sous le chemin `/site1` grâce à l'app YunoHost `my_webapp__3`.
Les applications installées sur `lesmegeresdelhumus.fr` ont été désinstallées pour pouvoir réinstaller l'application sous le chemin `/` grâce à l'app Yunohost `my_webapp`. Un sous-domaine a été créé pour installer l'application Nextcloud sur nextcloud.lesmegeresdelhumus.fr.

**Problème initial : Toutes les sous-pages retournent HTTP 404**

| URL                                             | Résultat attendu | Résultat obtenu  |
|-------------------------------------------------|------------------|------------------|
| `https://lesmegeresdelhumus.fr/`                | 200 OK           | ✅ 200 OK        |
| `https://lesmegeresdelhumus.fr/compagnie`       | 200 OK           | ❌ 404 Not Found |
| `https://lesmegeresdelhumus.fr/contact`         | 200 OK           | ❌ 404 Not Found |
| `https://lesmegeresdelhumus.fr/ateliers`        | 200 OK           | ❌ 404 Not Found |

## 2) Architecture Technique

**Serveur**

| Élément               | Valeur                          | Site en production |
|-----------------------|---------------------------------|--------------------|
| Système               | YunoHost (Debian)               | idem               |
| SSH                   | `root@192.168.0.51 -p 1000`     | idem               |
| Domaine cible         | `lesmegeresdelhumus.fr`         | idem               |
| Chemin de l'app       | `/site1`                        | /                  |
| Nom de l'app YunoHost | `my_webapp__3`                  | my_webapp          |

**Fichiers importants**

| Fichier                                                             | Rôle                                                  |
|---------------------------------------------------------------------|-------------------------------------------------------|
| `/var/www/my_webapp/www/`                                           | Racine web publique (assets, index.php)               |
| `/var/www/my_webapp/www/index.php`                                  | Front controller PHP                                  |
| `/var/www/my_webapp/lesmegeres-app/`                                | Code applicatif (pages/, includes/)                   |
| `/etc/nginx/conf.d/lesmegeresdelhumus.fr.conf`                      | Vhost nginx principal (géré par YunoHost)             |
| `/etc/nginx/conf.d/lesmegeresdelhumus.fr.d/my_webapp.conf`          | Bloc location nginx de l'app                          |
| `/etc/nginx/conf.d/lesmegeresdelhumus.fr.d/my_webapp.d/php.conf`    | Configuration PHP-FPM                                 |
| `/etc/ssowat/conf.json`                                             | Table de permissions SSOWAT (générée automatiquement) |
| `/etc/yunohost/apps/my_webapp__3/settings.yml`                      | Paramètres de l'app YunoHost                          |

**Stack technique**

- **PHP 8.4-FPM** — socket : `/var/run/php/php8.4-fpm-my_webapp.sock`
- **Nginx** — géré par YunoHost, inclut le middleware Lua SSOWAT
- **SSOWAT** — SSO/portail YunoHost, middleware `access.lua` injecté dans chaque vhost

**Variables d'environnement PHP (fastcgi params)**

```
SITE_BASE_PATH = /
SITE_BASE_URL  = https://lesmegeresdelhumus.fr
```

## 3) Corrections Locales Effectuées

Ces corrections ont été faites sur la machine locale dans `/var/www/lesmegeres-app/` (ou `/var/www/public/`) et ont été **déployées sur le serveur** sur le chemin de l'application /site1. Une fois la réinstallation faite sur le nouveau chemin `/`, le ** problème initial** est réapparu. 

### 3.1 `pages/contact.php`

**Problème :** 4 attributs `src` d'images utilisaient des chemins absolus racine (`/images/...`) incompatibles avec le sous-chemin `/site1`.

**Correction :** Remplacement par `site_path('/images/...')` (helper PHP qui préfixe `SITE_BASE_PATH`).

```php
// Avant
<img src="/images/favicon/adresse.svg" ...>

// Après
<img src="<?= htmlspecialchars(site_path('/images/favicon/adresse.svg'), ENT_QUOTES, 'UTF-8') ?>" ...>
```

Images concernées : `adresse.svg`, `tel.svg`, `mail.svg`, `connect.svg`

### 3.2 `pages/rapport-2025.php`

**Problème : Les liens CSS et JS utilisaient des chemins racine absolus**

**Correction : Remplacement par `site_path()`**

```php
// Avant
<link rel="stylesheet" href="/css/flip-rapport.css">
<script src="/script/flip-rapport.js"></script>

// Après
<link rel="stylesheet" href="<?= site_path('/css/flip-rapport.css') ?>">
<script src="<?= site_path('/script/flip-rapport.js') ?>"></script>
```

## 4) Diagnostic Serveur

**Étape 1 — Hypothèse SSOWAT (réfutée)**

Théorie : SSOWAT bloque les sous-pages car son URI de permission ne couvre que `/site1` (sans les sous-chemins)

Test : Vérification de `/etc/ssowat/conf.json`

```json
"my_webapp.main": {
    "public": true,
    "uris": ["lesmegeresdelhumus.fr"]
}
```
→ Effectivement, seule la racine est listée.

Patch test direct sur `conf.json` :

```python
d["permissions"]["my_webapp__3.main"]["uris"] = [
    "lesmegeresdelhumus.fr/site1",
    "lesmegeresdelhumus.fr/site1/",
    "lesmegeresdelhumus.fr/site1/.+"
]
```
→ `/site1/compagnie`, `/site1/contact`, `/site1/ateliers` répondent **200 OK** ✅

Mais : `yunohost app ssowatconf` régénère `conf.json` et écrase le patch → retour aux 404

Tentative `settings.yml`

Ajout de `additional_urls: [re:/.+]` dans les permissions de `settings.yml`. Le résultat généré par ssowatconf n'inclut pas le bon pattern → inefficace.

**Étape 2 — Instrumentation SSOWAT (preuve décisive)**

Pour confirmer si SSOWAT bloque ou non, une ligne de debug a été temporairement ajoutée dans `/usr/share/ssowat/access.lua` (ligne 219) :

```lua
ngx.header["X-SSO-PERM"] = matched_permission
```

Résultat de `/site1/compagnie` :

```
HTTP/1.1 404 Not Found
x-sso-perm: my_webapp__3.main   ← SSOWAT accorde l'accès !
x-sso-wat: ...
```

Conclusion : SSOWAT n'est PAS la cause des 404. Il accorde correctement l'accès. Le problème vient de Nginx, en aval de SSOWAT.

**Étape 3 — Diagnostic Nginx (cause réelle)**

Log d'erreur Nginx :
```
open() "/usr/share/nginx/htmlindex.php" failed (2: No such file or directory)
```

Analyse : Ce chemin absurde (`/usr/share/nginx/html` + `index.php` sans `/`) révèle le bug

Ancienne configuration Nginx `my_webapp__3.conf` :

```nginx
location ^~ /site1/ {
    alias /var/www/my_webapp__3/www/;
    index index.php index.html;
    try_files $uri $uri/ /site1/index.php?$args;
    ...
}
```

Pourquoi ça échoue ?

Avec la directive `alias`, le fallback de `try_files` utilisant un chemin absolu comme `/site1/index.php` est résolu par Nginx **par rapport à la valeur de `root`** dans le contexte parent (qui est `/usr/share/nginx/html`, la racine par défaut de Nginx), et non par rapport à l'`alias`. Nginx concatène alors `/usr/share/nginx/html` + `index.php` (en perdant le `/`) → fichier introuvable → 404.

## 5) Fix Nginx Validé

**Correctif effectivement mis en place en production**

Le correctif a été appliqué directement sur le serveur de production dans `/etc/nginx/conf.d/lesmegeresdelhumus.fr.d/my_webapp__3.conf`.

Changement réel effectué :

```nginx
rewrite ^/site1$ /site1/ permanent;
location /site1/ {

    # Path to source
    alias /var/www/my_webapp__3/www/;

    # Default indexes and catch-all
    index index.php index.html;
    try_files $uri $uri/ @site1_php;

    # Prevent useless logs
    location = /site1/favicon.ico {
        log_not_found off;
        access_log off;
    }

    location = /site1/robots.txt {
        allow all;
        log_not_found off;
        access_log off;
    }

    # Deny access to hidden files and directories
    location ~ ^/site1/(.+/|)\.(?!well-known\/) {
        deny all;
    }

    include conf.d/lesmegeresdelhumus.fr.d/my_webapp__3.d/*.conf;

    # Include SSOWAT user panel.
    include conf.d/yunohost_panel.conf.inc;
}

location @site1_php {
    fastcgi_pass unix:/var/run/php/php8.4-fpm-my_webapp__3.sock;
    include fastcgi_params;
    fastcgi_param SCRIPT_FILENAME /var/www/my_webapp__3/www/index.php;
    fastcgi_param PATH_INFO       $request_uri;
    fastcgi_param SITE_BASE_PATH  /site1;
    fastcgi_param SITE_BASE_URL   https://lesmegeresdelhumus.fr;
}
```

**Pourquoi ce correctif marche**

Le fallback problématique :

```nginx
try_files $uri $uri/ /site1/index.php?$args;
```

forçait Nginx à résoudre un chemin absolu dans un bloc avec `alias`, ce qui déclenchait la mauvaise résolution vers la racine Nginx par défaut.

Le remplacement par :

```nginx
try_files $uri $uri/ @site1_php;
```

fait basculer la requête vers un **named location** qui envoie explicitement la requête à PHP-FPM avec :

- `SCRIPT_FILENAME=/var/www/my_webapp__3/www/index.php`
- `SITE_BASE_PATH=/site1`
- `SITE_BASE_URL=https://lesmegeresdelhumus.fr`

Cela supprime complètement l'ambiguïté liée à `alias` + `try_files`.

**Validation réalisée après déploiement**

Après modification :

1. `nginx -t` → syntaxe OK
2. `systemctl reload nginx` → rechargement OK
3. Tests HTTP réalisés avec `curl`

**Résultats observés**

| URL                                             | Résultat final |
|-------------------------------------------------|----------------|
| `https://lesmegeresdelhumus.fr/site1/`          | ✅ 200 OK      |
| `https://lesmegeresdelhumus.fr/site1/compagnie` | ✅ 200 OK      |
| `https://lesmegeresdelhumus.fr/site1/contact`   | ✅ 200 OK      |
| `https://lesmegeresdelhumus.fr/site1/ateliers`  | ✅ 200 OK      |

**Impact fonctionnel**

- Les URLs restent propres : `/site1/compagnie`, `/site1/contact`, etc.
- Il n'a pas été nécessaire de dégrader les URLs vers un format moins esthétique.
- Le correctif agit uniquement sur le routage serveur Nginx/PHP-FPM.

**Schéma de fonctionnement — Avant / Après**

Avant le correctif

```text
Requête navigateur
    |
    v
https://lesmegeresdelhumus.fr/site1/compagnie
    |
    v
SSOWAT
    |
    |-- permission matchée : my_webapp__3.main
    |-- accès autorisé
    v
Nginx location /site1/
    |
    |-- alias /var/www/my_webapp__3/www/
    |-- try_files $uri $uri/ /site1/index.php?$args
    |
    |-- /site1/compagnie n'est pas un vrai fichier
    |-- fallback vers /site1/index.php
    v
Mauvaise résolution du chemin
    |
    |-- Nginx combine avec son root par défaut
    |-- tente d'ouvrir :
    |   /usr/share/nginx/htmlindex.php
    v
Fichier introuvable
    |
    v
404 Not Found
```

Après le correctif

```text
Requête navigateur
    |
    v
https://lesmegeresdelhumus.fr/site1/compagnie
    |
    v
SSOWAT
    |
    |-- permission matchée : my_webapp__3.main
    |-- accès autorisé
    v
Nginx location /site1/
    |
    |-- alias /var/www/my_webapp__3/www/
    |-- try_files $uri $uri/ @site1_php
    |
    |-- /site1/compagnie n'est pas un vrai fichier
    |-- bascule vers named location @site1_php
    v
location @site1_php
    |
    |-- fastcgi_pass php8.4-fpm
    |-- SCRIPT_FILENAME = /var/www/my_webapp__3/www/index.php
    |-- SITE_BASE_PATH = /site1
    v
index.php
    |
    |-- lit le slug "compagnie"
    |-- charge la page PHP correspondante
    v
200 OK
```

**Résumé ultra-court**

```text
AVANT
URL -> SSOWAT OK -> Nginx try_files -> mauvais index.php -> 404

APRÈS
URL -> SSOWAT OK -> Nginx try_files -> @site1_php -> bon index.php -> 200
```

**Idée clé**

Avant, Nginx devait déduire seul comment résoudre le fallback vers `index.php` à l'intérieur d'un bloc `alias`.

Après, le comportement devient explicite : si l'URL ne correspond pas à un fichier réel, Nginx envoie la requête vers `@site1_php`, qui exécute directement le bon front controller.

## 6) Explication du Fix Nginx

**Principe**

Utiliser un **named location** (`@site1_php`) comme cible du fallback `try_files`. Les named locations contournent le problème de résolution de chemin avec `alias`.

**Configuration cible pour `/etc/nginx/conf.d/lesmegeresdelhumus.fr.d/my_webapp__3.conf`**

```nginx
rewrite ^/site1$ /site1/ permanent;

location ^~ /site1/ {
    alias /var/www/my_webapp__3/www/;
    index index.php index.html;

    # Le fallback pointe vers un named location, pas un chemin absolu
    try_files $uri $uri/ @site1_php;

    location = /site1/favicon.ico { log_not_found off; access_log off; }
    location = /site1/robots.txt  { allow all; log_not_found off; access_log off; }
    location ~ ^/site1/(.+/|)\.(?!well-known\/) { deny all; }

    include conf.d/lesmegeresdelhumus.fr.d/my_webapp__3.d/*.conf;
    include conf.d/yunohost_panel.conf.inc;
}

# Named location : passe toujours au front controller PHP
location @site1_php {
    fastcgi_pass unix:/var/run/php/php8.4-fpm-my_webapp__3.sock;
    include fastcgi_params;
    fastcgi_param SCRIPT_FILENAME /var/www/my_webapp__3/www/index.php;
    fastcgi_param PATH_INFO       $request_uri;
    fastcgi_param SITE_BASE_PATH  /site1;
    fastcgi_param SITE_BASE_URL   https://lesmegeresdelhumus.fr;
}
```

**Commandes à exécuter sur le serveur**

```bash
# 1. Sauvegarder la config actuelle
cp /etc/nginx/conf.d/lesmegeresdelhumus.fr.d/my_webapp__3.conf \
   /etc/nginx/conf.d/lesmegeresdelhumus.fr.d/my_webapp__3.conf.bak.$(date +%s)

# 2. Éditer my_webapp__3.conf avec la config ci-dessus (nano ou cat > ...)

# 3. Vérifier la syntaxe
nginx -t

# 4. Recharger Nginx si OK
systemctl reload nginx

# 5. Tester
curl -I https://lesmegeresdelhumus.fr/site1/compagnie
curl -I https://lesmegeresdelhumus.fr/site1/contact
curl -I https://lesmegeresdelhumus.fr/site1/ateliers
```

## 7) Solution SSOWAT

**Problème**

Le fichier `/etc/ssowat/conf.json` est **regénéré** par `yunohost app ssowatconf` à chaque mise à jour ou reconfiguration YunoHost. Le patch manuel est donc perdu.

**Solution recommandée — Hook post-ssowatconf**

YunoHost supporte des hooks qui s'exécutent après certaines actions. Créer le fichier suivant :

`/etc/yunohost/hooks.d/post_app_ssowatconf/99-patch-my_webapp__3`

```bash
#!/bin/bash
# Patch SSOWAT pour inclure les sous-chemins de my_webapp__3
python3 - <<'EOF'
import json

conf_path = "/etc/ssowat/conf.json"
with open(conf_path) as f:
    d = json.load(f)

perm = d.get("permissions", {}).get("my_webapp__3.main", {})
uris = perm.get("uris", [])

needed = [
    "lesmegeresdelhumus.fr/site1",
    "lesmegeresdelhumus.fr/site1/",
    "lesmegeresdelhumus.fr/site1/.+"
]
changed = False
for u in needed:
    if u not in uris:
        uris.append(u)
        changed = True

if changed:
    perm["uris"] = uris
    with open(conf_path, "w") as f:
        json.dump(d, f, indent=4)
    print("SSOWAT patched for my_webapp__3.main")
else:
    print("SSOWAT already correct, no change needed")
EOF
```

```bash
# Rendre le hook exécutable
chmod +x /etc/yunohost/hooks.d/post_app_ssowatconf/99-patch-my_webapp__3
mkdir -p /etc/yunohost/hooks.d/post_app_ssowatconf
```

> **Note :** Ce hook n'est peut-être pas un emplacement officiel YunoHost. Une alternative plus robuste est d'utiliser `yunohost app addaccess` ou les URL additionnelles via la CLI YunoHost si elles sont supportées pour les regex.

## 8) Checklist de Déploiement

Cette checklist sert à éviter qu'un développement local casse la production lors d'une migration vers le serveur YunoHost.

### Avant le déploiement

- [ ] Vérifier quels fichiers PHP ont réellement changé en local
- [ ] Vérifier si des assets ont changé (`css/`, `script/`, `images/`, `fonts/`, `audio/`, `pdf/`)
- [ ] Vérifier que les nouveaux chemins utilisent bien `site_path()` dès qu'un lien dépend de `/site1`
- [ ] Vérifier qu'aucun chemin absolu racine de type `/images/...`, `/css/...`, `/script/...` n'a été réintroduit sans helper
- [ ] Conserver la configuration Nginx de production telle quelle si aucun changement serveur n'est voulu
- [ ] Sauvegarder les fichiers de production avant écrasement si le déploiement est manuel

### Fichiers à synchroniser au minimum

- [ ] `/var/www/public/index.php`
- [ ] `/var/www/lesmegeres-app/includes/header.php`
- [ ] `/var/www/lesmegeres-app/includes/footer.php`
- [ ] `/var/www/lesmegeres-app/pages/*.php`
- [ ] Les assets réellement utilisés par les pages modifiées

### Points de vigilance spécifiques à cette application

- [ ] Ne pas écraser `/etc/nginx/conf.d/lesmegeresdelhumus.fr.d/my_webapp__3.conf` par erreur
- [ ] Conserver le fallback Nginx avec `@site1_php` en production
- [ ] Ne pas supposer que YunoHost/SSOWAT corrigera un problème de routage applicatif
- [ ] Vérifier qu'aucune image requise n'est absente sur le serveur

### Déploiement

- [ ] Copier le code PHP modifié vers `/var/www/my_webapp__3/lesmegeres-app/`
- [ ] Copier le front controller public vers `/var/www/my_webapp__3/www/index.php`
- [ ] Copier les assets modifiés vers `/var/www/my_webapp__3/www/`
- [ ] Vérifier les permissions et propriétaires si le mode de copie les modifie

### Contrôle après déploiement

- [ ] Ouvrir `https://lesmegeresdelhumus.fr/site1/`
- [ ] Ouvrir `https://lesmegeresdelhumus.fr/site1/compagnie`
- [ ] Ouvrir `https://lesmegeresdelhumus.fr/site1/contact`
- [ ] Ouvrir `https://lesmegeresdelhumus.fr/site1/ateliers`
- [ ] Ouvrir toute page nouvellement modifiée en local
- [ ] Vérifier le chargement des images, CSS et JS sur ces pages
- [ ] Vérifier qu'aucune URL ne renvoie 404 dans l'inspecteur réseau du navigateur

### Contrôle serveur si anomalie

- [ ] `nginx -t`
- [ ] `systemctl reload nginx` si une modification Nginx a été faite
- [ ] Vérifier les logs Nginx en cas de 404 ou 500
- [ ] Vérifier que `my_webapp__3.conf` contient toujours `try_files $uri $uri/ @site1_php;`

### État actuellement confirmé

- [x] Le routage de production fonctionne pour `/site1/`, `/site1/compagnie`, `/site1/contact`, `/site1/ateliers`
- [x] Le correctif Nginx avec `@site1_php` est en place
- [x] Le local et la production sont synchronisés (déploiement du 20 mars 2026)

## 9) Checklist de Validation Finale

- [x] Appliquer le fix nginx (named location `@site1_php`)
- [x] `nginx -t` → syntaxe OK
- [x] `systemctl reload nginx` → rechargement OK
- [x] `curl -I .../site1/compagnie` → 200 OK
- [x] `curl -I .../site1/contact` → 200 OK
- [x] `curl -I .../site1/ateliers` → 200 OK
- [ ] (Optionnel) Mettre en place le hook SSOWAT persistant

## 10) Déploiement recommandé

**Script automatique**

Un script de déploiement est disponible dans `/var/www/public/deploy.sh`.

**Ce que fait le script**

- synchronise `index.php`
- synchronise `includes/` et `pages/`
- synchronise les répertoires publics utiles (`css`, `script`, `images`, `fonts`, `audio`, `pdf`, etc.)
- ne touche pas à la configuration Nginx du serveur
- effectue des tests HTTP après déploiement
- fonctionne en dry-run par défaut
- fait un backup des fichiers modifiés selon le checksum par défaut
- rétablit les owner:group par défaut
- s'assure que les permissions pour les répertoires et les fichiers restent inchangées
- minifie le fichier style.css en style.min.css
- test l'accès à index.php et à quelques slugs

**Utilisation**

```bash
cd /var/www/public

# Voir ce qui serait déployé sans rien changer
./deploy.sh

# Déployer réellement
./deploy.sh --apply

# Déployer sans les tests HTTP finaux
./deploy.sh --apply --skip-check
```

**Variables surchargeables si besoin**

```bash
SSH_HOST=192.168.0.51
SSH_PORT=1000
SSH_USER=root
REMOTE_BASE=/var/www/my_webapp
BASE_URL=https://lesmegeresdelhumus.fr/
```

Exemple :

```bash
SSH_HOST=192.168.0.51 SSH_PORT=1000 ./deploy.sh --apply
```

**Procédure manuelle courte**

Si vous préférez ne pas utiliser le script, voici la procédure manuelle minimale.

**Copier le code PHP applicatif**

```bash
rsync -avz -e 'ssh -p 1000' /var/www/lesmegeres-app/includes/ root@192.168.0.51:/var/www/my_webapp/lesmegeres-app/includes/
rsync -avz -e 'ssh -p 1000' /var/www/lesmegeres-app/pages/ root@192.168.0.51:/var/www/my_webapp/lesmegeres-app/pages/
```

**Copier le front controller public**

```bash
rsync -avz -e 'ssh -p 1000' /var/www/public/index.php root@192.168.0.51:/var/www/my_webapp/www/index.php
```

**Copier les assets utiles**

```bash
rsync -avz -e 'ssh -p 1000' /var/www/public/css/ root@192.168.0.51:/var/www/my_webapp/www/css/
rsync -avz -e 'ssh -p 1000' /var/www/public/script/ root@192.168.0.51:/var/www/my_webapp/www/script/
rsync -avz -e 'ssh -p 1000' /var/www/public/images/ root@192.168.0.51:/var/www/my_webapp/www/images/
```

Ajoutez de la même façon les autres dossiers si vous les avez modifiés : `fonts/`, `audio/`, `pdf/`, `iframes/`, `pureHtml/`, `archive/`.

**Vérifier le site après copie**

```bash
curl -I https://lesmegeresdelhumus.fr/
curl -I https://lesmegeresdelhumus.fr/compagnie
curl -I https://lesmegeresdelhumus.fr/contact
curl -I https://lesmegeresdelhumus.fr/ateliers
```

**Si quelque chose casse**

```bash
ssh -p 1000 root@192.168.0.51
nginx -t
tail -n 50 /var/log/nginx/error.log
```

**Règle importante**

Ne recopiez pas les fichiers de configuration système YunoHost/Nginx avec votre déploiement applicatif. Le code PHP et les assets peuvent être synchronisés régulièrement, mais la configuration serveur doit rester pilotée séparément.

## 11) Résumé de la Cause Racine

```
Nginx  alias + try_files fallback absolu
         ↓
try_files $uri $uri/ /site1/index.php?$args
                      ↑
         Avec alias, ce chemin est résolu par rapport
         à root (=/usr/share/nginx/html), pas à l'alias
                      ↓
         open("/usr/share/nginx/htmlindex.php") → ENOENT → 404
```

**Fix :** Remplacer le fallback absolu par un named location qui appelle directement PHP-FPM avec le chemin explicite du script (`SCRIPT_FILENAME`).

---

## 12) Clé SSH

**Premier déploiement complet via `deploy.sh`**

Le déploiement a été effectué le 20 mars 2026 avec le script `deploy.sh --apply`.

**Résultat des smoke tests post-déploiement :**

| URL                                             | Résultat  |
|-------------------------------------------------|-----------|
| `https://lesmegeresdelhumus.fr/site1/`          | ✅ 200 OK |
| `https://lesmegeresdelhumus.fr/site1/compagnie` | ✅ 200 OK |
| `https://lesmegeresdelhumus.fr/site1/contact`   | ✅ 200 OK |
| `https://lesmegeresdelhumus.fr/site1/ateliers`  | ✅ 200 OK |

**Clé SSH sans passphrase pour le déploiement**

Une clé SSH dédiée a été créée pour permettre à `deploy.sh` de se connecter sans saisir de mot de passe.

**Fichiers créés**

| Fichier                        | Rôle                                  |
|--------------------------------|---------------------------------------|
| `~/.ssh/id_ed25519_deploy`     | Clé privée (ne jamais partager)       |
| `~/.ssh/id_ed25519_deploy.pub` | Clé publique installée sur le serveur |

**Commandes utilisées**

```bash
# Génération de la clé (sans passphrase)
ssh-keygen -t ed25519 -f ~/.ssh/id_ed25519_deploy -N "" -C "deploy@lesmegeres"

# Installation sur le serveur (demande le mot de passe une dernière fois)
ssh-copy-id -i ~/.ssh/id_ed25519_deploy.pub -p 1000 root@sante9naturel.fr
```

**Bloc ajouté dans `~/.ssh/config`**

```
Host sante9naturel.fr
    HostName sante9naturel.fr
    User root
    Port 1000
    IdentityFile ~/.ssh/id_ed25519_deploy
    IdentitiesOnly yes
```

Grâce à ce bloc, `./deploy.sh` n'a plus besoin de mot de passe et peut être lancé directement.

## 13) Perte de passphrase

Cette section décrit la procédure exacte pour ce poste, en conservant la clé de déploiement déjà opérationnelle.

**État actuel des clés**

- Clé ancienne (passphrase oubliée) : `~/.ssh/id_ed25519`
- Clé de déploiement active : `~/.ssh/id_ed25519_deploy`
- Le fichier `~/.ssh/config` pour `sante9naturel.fr` pointe déjà vers `id_ed25519_deploy`

**Objectif**

Régénérer `id_ed25519` (usage personnel) sans casser l'accès serveur existant via `id_ed25519_deploy`.

**Procédure recommandée**

```bash
#Sauvegarder l'ancienne paire de clés
mkdir -p ~/.ssh/backup-keys
mv ~/.ssh/id_ed25519 ~/.ssh/backup-keys/id_ed25519.old
mv ~/.ssh/id_ed25519.pub ~/.ssh/backup-keys/id_ed25519.pub.old

#Générer une nouvelle clé id_ed25519 (choisir une nouvelle passphrase)
ssh-keygen -t ed25519 -f ~/.ssh/id_ed25519 -C "phlip@sante9naturel.fr"

#(Optionnel) Installer cette nouvelle clé sur le serveur
# Important: la clé deploy continue de fonctionner même si cette étape est ignorée.
ssh-copy-id -i ~/.ssh/id_ed25519.pub -p 1000 root@sante9naturel.fr
```

**Supprimer l'ancienne clé du serveur (recommandé)**

Se connecter puis nettoyer `~/.ssh/authorized_keys` en retirant l'ancienne ligne :

```bash
ssh sante9naturel.fr
nano ~/.ssh/authorized_keys
```

**Repère pratique (ancienne clé locale connue)**

```text
ssh-ed25519 AAAAC3NzaC1lZDI1NTE5AAAAIHFC8hh98THe3qmIvfxZJGTRjTdXXYecSNh1TiSxagCS phlip@sante9naturel.fr
```

**Vérifications**

```bash
#La connexion déploiement doit rester OK
ssh sante9naturel.fr "echo OK deploy"

#Test de la nouvelle clé id_ed25519
ssh -i ~/.ssh/id_ed25519 -p 2279 root@sante9naturel.fr "echo OK id_ed25519"
```

**Notes de sécurité**

- Ne jamais partager les fichiers privés (`id_ed25519`, `id_ed25519_deploy`).
- Si vous suspectez une compromission, retirez immédiatement la clé concernée de `authorized_keys` et regénérez une nouvelle paire.

**Exécution réelle du 20 mars 2026 (cas rencontré)**

Lors de `ssh-copy-id` après régénération de `id_ed25519`, le terminal a semblé ne rien afficher. La vérification a montré que la clé personnelle n'était pas encore présente dans `authorized_keys` (seule la clé `deploy` était active).

La correction a été appliquée en ajout idempotent direct :

```bash
NEW_KEY="$(cat ~/.ssh/id_ed25519.pub)"
ssh sante9naturel.fr "mkdir -p ~/.ssh && chmod 700 ~/.ssh && grep -qxF '$NEW_KEY' ~/.ssh/authorized_keys || echo '$NEW_KEY' >> ~/.ssh/authorized_keys; chmod 600 ~/.ssh/authorized_keys"
```

**État final constaté**

- `~/.ssh/authorized_keys` côté serveur contient 2 clés :
    - `deploy@lesmegeres`
    - `phlip@sante9naturel.fr` (nouvelle clé régénérée)
- L'accès de déploiement (`Host sante9naturel.fr` via `id_ed25519_deploy`) reste opérationnel.

**Script de vérification rapide SSH**

Un script local a été ajouté pour vérifier en une commande l'état des clés et l'accès SSH :

```bash
cd /var/www/public
./check-ssh-keys.sh
```

Ce script contrôle :

- présence des clés locales `id_ed25519.pub` et `id_ed25519_deploy.pub`
- empreintes des deux clés
- identité SSH effectivement utilisée pour `sante9naturel.fr` (`ssh -G`)
- présence des deux clés dans `~/.ssh/authorized_keys` côté serveur
- test de connexion SSH

Résultat attendu : `Result: HEALTHY`

Optionnel : vérifier un autre hôte

```bash
./check-ssh-keys.sh autre-host
```