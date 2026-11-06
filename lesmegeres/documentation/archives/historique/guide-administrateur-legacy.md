# Guide de l'administrateur - lesmegeresdelhumus.fr (Yunohost / Nginx / APACHE2)

---

## 1) [Contexte et objectif](#1-contexte-et-objectif)
## 2) [Architecture technique](#2-architecture-technique)
### 2.1. [La documentation](#21-la-documentation)
### 2.2. [Les serveurs web](#22-les-serveurs-web)
### 2.3. [Architecture des fichiers](#23-architecture-des-fichiers)
### 2.4. [Les chemins pour accéder au site](#24-les-chemins-pour-accéder-au-site)
### 2.5. [Sécurité : permissions fichiers et droits d'accès](#25-sécurité--permissions-fichiers-et-droits-daccès)
### 2.6. [Sécurité HTTP : headers de protection](#25-sécurité--permissions-fichiers-et-droits-daccès)
### 2.7. [Minification automatique du CSS lors du déploiement](#27-minification-automatique-du-css-lors-du-déploiement)
### 2.8. [Architecture / Layout du site](#28-architecture--layout-du-site)


---

## 1) Contexte et objectif

Le site Les Mégères de l'Humus est le site institutionnel de l'association du même nom qui promeut des spectacles de théâtre éco-queer. L'association propose également des cours, des stages, des ateliers et du théâtre forum.
Le site a été développé en HTML5, CSS3, JS et PHP par Philippe Guerrini. Le JS et le  PHP ont été réalisés avec l'aide de Chat GPT pour couvrir le référencement SEO et la sécurité du site.

Pour le développement du code, j'utilise l'éditeur de Microsoft VSCODE. L'ouverture des répertoires nécessaires au développement et à la maintenance de ce site sont stockés dans `{} public.code-workspace`.

## 2) Architecture technique

### 2.1. La documentation

La documentation est présente dans `/home/pilipe/Documents/Winformatique/srv-web-pic/lesmegeresdelhumus.fr/doc`au format Markdown :

* ajouter-une-actu-rapide.md : alimenter manuellement la page /actualites à partir du compte facebook et instagram des Mégères de l'Humus.
* avant-mise-en-production.md : comment activer le serveur local et comment passer en production sur le serveur sante9naturel.fr.
* checklist-production-5-lignes.md : mise en production résumée en 5 lignes.
* checklist-production-10-lignes.md : mise en production résumée en 10 lignes.
* doc_diagnostic-yunohost.md : rendre le serveur NGINX conforme à l'architecture du serveur de production sous YunoHost. **Installée à la racine de /site1/**
* doc_guide-administrateur.md : ce guide à remettre aux propriétaires. **Installée à la racine de /site1/**
* guide-mobile-s21-maintenance.md : modifications de style pour un affichage correct sur SAMSUNG S21.
* GUIDE-SPLIT-COLOR-TITLES.md : explique comment reproduire le titre split-color sur les pages theatre-d-ombres, sieste-contee et projet-pedagogique. **Styles retirés**.
* JOUR-J-MISE-EN-PEODUCTION.md : procédure pour le passage de `/site1/` vers `/`
* mode-maintenance.md : configuration du mode en local et sur le serveur
* plan-actualites-api-facebook-instagram.md : remplacer la saisie manuelle du tableau PHP par un fetch automatique des posts dans la page /actualites.
* doc_REPRISE-PROJET-ETAT-ACTUEL-2026-04-02.md : repartir rapidement du bon état du projet, savoir ce qui a été corrigé... **Installée à la racine de /site1/**
* seo-checklist-deploiement.md : liste à suivre pour le déploiement sur le serveur de production.
* seo-checklist-proprietaires-imprimable.md : document à remettre aux propriétaires pour impression.
* seo-checklist-proprietaires.md : document à remettre aux propriétaires
* seo-documentation.md : documentation des modifications apportées au code pour un bon référencement.
* technique-css-notes.md : décisions et choix d'implémentation.
* TECHNIQUE-FULL-BLEED.md : ou comment afficher un élément en pleine largeur viewer ? **technique non appliquée**

### 2.2. Les serveurs web

Le chemin du site en local est `/var/www/public`.

Le site est servi par un serveur APACHE2 et NGINX `public`. Comme aucun serveur ne se lance au démarrage du PC, c'est à vous de démarrer le serveur web avec lequel vous voulez travailler. Le fichier `/etc/hosts` a été modifié pour lié le serveur APACHE2 et le serveur NGINX à l'adresse locale `127.0.0.1`. Le fichier `.htaccess` couvre la conf nécessaire à APACHE2 en local et en production. Le fichier `nginx-megeres.conf` couvre la conf nécessaire à NGINX en local.

Le chemin du fichier de `conf` du serveur APACHE2 est `/etc/apache2/sites-available/public.conf`.

Le chemin du fichier de `conf` du serveur NGINX est `/etc/nginx/conf.d/megeres.conf`.

### 2.3. Architecture des fichiers

Le répertoire /var/www/public contient les répertoires suivants :

* /fonts --> les polices de caractères
* /images --> les images, les icônes
* /pdf --> les rapports au format PDF
* /script --> les scripts javascript

et les fichiers **index.php**, **.htaccess**, **robots.txt**, **sitemap.xml**

Le répertoire /var/www contient les répertoires suivants pour sécuriser le site:

* /lesmegeres-app --> le répertoire /includes et le répertoire /pages
* /lesmegeres-app/includes --> les fichiers header.php et footer.php
* /lesmegeres-app/pages --> tous les fichiers PHP du site

Le répertoire /lesmegeres-app regroupent les fichiers qui ont été exclus de la racine du site pour raison de sécurité. La racine du site ne comporte ainsi que les fichiers dits public, les assets comme les feuilles de styles, les fonts, etc, et les fichiers pour le référencement `robots.txt` et `sitemap.xml`, le fichier index `index.php`.

### 2.4. Les chemins pour accéder au site 

Toutes les connexions aboutissent sur la page **INDEX.PHP**. Les autres points d'entrée sur le site passent par l'utilisation des **slug**, comme par exemple *https://lesmegeresdelhumus.fr/contact*. Il n'est pas possible de se connecter à une page du site en précisant le nom du fichier PHP. Par exemple, se connecter à *https://lesmegeresdelhumus.fr/compagnie.php* générera une page d'erreur **404**. La page index.php calcule le **SITE_PATH** et détermine si le site est en local ou en production. **Cela devra être changé car le site en production n'est plus sur le chemin /site1. Il est comme en local à la racine.** La page INDEX.PHP fait appel à la page **HEADER.PHP** et à la page **FOOTER.PHP**. La page index.php est la page d'accueil du site sur laquelle est installée le diaporama.

## 2.5. Sécurité : permissions fichiers et droits d'accès

### Principe appliqué

L'ensemble des fichiers et répertoires de `/var/www/my_webapp/` est géré par l'utilisateur et le groupe `my_webapp:my_webapp`.

Les permissions ont été **standardisées** :

| Type        | Mode  | Signification                                   |
|-------------|-------|-------------------------------------------------|
| Répertoires | `750` | `my_webapp` = rwx, groupe = r-x, other = --- |
| Fichiers    | `640` | `my_webapp` = rw-, groupe = r--, other = --- |

### Pourquoi www-data doit être dans le groupe my_webapp__3

Nginx tourne avec l'utilisateur `www-data`. Pour servir les fichiers statiques (CSS, JS, images, fonts, PDF, audio…), nginx doit pouvoir les lire. Avec le mode `750`/`640`, seuls le propriétaire et le groupe ont accès — `other` est bloqué.

**`www-data` a été ajouté au groupe `my_webapp`**

```bash
usermod -aG my_webapp www-data
systemctl reload nginx
```

Résultat : `id www-data` retourne `groups=33(www-data),991(my_webapp__3)`.

Le pool PHP-FPM (`/etc/php/8.4/fpm/pool.d/my_webapp__3.conf`) s'exécute lui aussi sous `my_webapp:my_webapp` → accès complet aux fichiers PHP sans changement.

### Commande de vérification / ré-application

En cas de doute ou après une intervention manuelle, utiliser `./deploy.sh --fix-owners`.

### Garantie lors du déploiement

Le script `./deploy.sh` applique **`chown` + `chmod`** sur l'ensemble des fichiers distants après chaque transfert, via la fonction `apply_target_ownership()`. Sans cela, rsync utilisait `--no-perms` et les nouveaux fichiers héritaient du umask distant (644/755 au lieu de 640/750).

Le `chown`/`chmod` porte sur **tous** les fichiers de `www/` et `lesmegeres-app/`, pas seulement les fichiers transférés lors du deploy. Cela évite qu'un fichier oublié reste en `root:root`.

### Règle des owner:group selon les niveaux (important)

| Chemin sur le serveur                                       | Owner:group                 | Pourquoi ?                                      |
|-------------------------------------------------------------|-----------------------------|-------------------------------------------------|
| `/var/www/my_webapp/` (répertoire parent)                   | `root:root`                 | Nécessaire pour l'accès FileZilla via SFTP root |
| `/var/www/my_webapp/www/` et tout son contenu               | `my_webapp:my_webapp`       | Servi par PHP-FPM et Nginx                      |
| `/var/www/my_webapp/lesmegeres-app/` et tout son contenu    | `my_webapp:my_webapp`       | Servi par PHP-FPM et Nginx                      |

`deploy.sh --apply` et `deploy.sh --fix-owners` opèrent **uniquement** sur `www/` et `lesmegeres-app/` — ils ne touchent jamais le répertoire `my_webapp/` lui-même, qui reste `root:root`.

### Commande de vérification / ré-application

Utiliser `--fix-owners` plutôt que la commande manuelle SSH :
```bash
./deploy.sh --fix-owners
```

Ou manuellement en SSH si le script n'est pas disponible :
```bash
ssh -p 1000 root@192.168.0.51 "
  find /var/www/my_webapp/www /var/www/my_webapp/lesmegeres-app -not -path '*/.git/*' -print0 | xargs -0 chown my_webapp__3:my_webapp__3
  find /var/www/my_webapp/www /var/www/my_webapp/lesmegeres-app -type d -exec chmod 750 {} +
  find /var/www/my_webapp/www /var/www/my_webapp/lesmegeres-app -type f -exec chmod 640 {} +
"
```

Les modes sont paramétrables via les variables d'environnement :

| Variable           | Défaut | Rôle                           |
|--------------------|--------|--------------------------------|
| `REMOTE_DIR_MODE`  | `750`  | chmod appliqué aux répertoires |
| `REMOTE_FILE_MODE` | `640`  | chmod appliqué aux fichiers    |

Exemple pour forcer des modes différents :
```bash
REMOTE_FILE_MODE=644 ./deploy.sh --apply
```

## 2.6. Sécurité HTTP : headers de protection

Les headers suivants ont été ajoutés dans `nginx-public.conf` (config locale de référence) :

```nginx
add_header X-Frame-Options          "SAMEORIGIN"                       always;
add_header X-Content-Type-Options   "nosniff"                          always;
add_header Referrer-Policy          "strict-origin-when-cross-origin"  always;
add_header Content-Security-Policy   "default-src 'self' https://*.helloasso.com 'unsafe-inline' data:; style-src-elem 'self' https://*.helloasso.com; script-src 'self' 'unsafe-inline' https://*.helloasso.com; frame-src 'self' https://*.helloasso.com; media-src 'self' https://*.helloasso.com; object-src 'none'" always;
```

### Application sur le serveur de production (YunoHost)

Sur YunoHost, le vhost nginx est géré dans deux fichiers :

| Fichier                                                       | Rôle                                |
|---------------------------------------------------------------|-------------------------------------|
| `/etc/nginx/conf.d/lesmegeresdelhumus.fr.conf`                | Vhost principal (géré par YunoHost) |
| `/etc/nginx/conf.d/lesmegeresdelhumus.fr.d/my_webapp__3.conf` | Bloc `location` de l'app            |

Les `add_header` doivent être ajoutés à l'intérieur du bloc `location` dans `my_webapp.conf`.

#### Méthode recommandée : script Python (évite les problèmes de copier-coller vim)

Le script `add_security_headers.py` à la racine du projet automatise l'insertion sans risque d'erreur de guillemets.

```bash
# 1. Copier le script sur le serveur
scp -P 1000 /var/www/public/add_security_headers.py root@192.168.0.51:/tmp/

# 2. L'exécuter sur le serveur
ssh -p 1000 root@192.168.0.51 python3 /tmp/add_security_headers.py
```

**Comportement du script**
- Idempotent : ne fait rien si les headers sont déjà présents
- Crée un backup automatique du fichier (`my_webapp__3.conf.bak`) avant modification
- Insère les 4 headers juste après l'ouverture `{` du premier bloc `location`
- Lance `nginx -t` ; en cas d'échec, restaure automatiquement le backup
- Recharge nginx si tout est OK

#### Méthode manuelle (si Python indisponible)

```bash
ssh -p 1000 root@192.168.0.51
nano /etc/nginx/conf.d/lesmegeresdelhumus.fr.d/my_webapp__3.conf
# → ajouter les 4 lignes add_header dans le bloc location principal
nginx -t && systemctl reload nginx
```

**Attention YunoHost**
YunoHost peut écraser ce fichier lors d'une mise à jour de l'app. Réappliquer les headers après chaque mise à jour YunoHost si nécessaire (relancer le script Python suffit — il est idempotent).

## 2.7. Minification automatique du CSS lors du déploiement

Le script `./deploy.sh` minifie automatiquement la feuille de style principale avant chaque déploiement effectif avec l'option `--apply`.

**Outils utilisés** 
[postcss-cli](https://github.com/postcss/postcss-cli) + [cssnano](https://cssnano.co/) via `npx` (nécessite Node.js et npm installés sur la machine de développement)
**Fichier source** `public/css/style.css`
**Fichier minifié généré** `public/css/style.min.css`
**Commande exécutée**

  ```bash
  npx postcss public/css/style.css --use cssnano -o public/css/style.min.css
  ```

**Remarques :**
- Si `npx`, `postcss` ou `cssnano` ne sont pas disponibles, la minification est ignorée (un message s’affiche dans le terminal).
- Il n’est pas nécessaire d’installer Node.js/npm/postcss/cssnano sur le serveur de production : seule la machine de développement doit en être équipée.
- Le fichier minifié est ensuite transféré comme les autres fichiers lors du déploiement.

**Pour activer la minification**
1. Installer Node.js et npm sur la machine de développement (voir https://nodejs.org/)
2. Installer postcss-cli et cssnano globalement :
    ```bash
    npm install -g postcss postcss-cli cssnano
    ```
3. Lancer le déploiement normalement :
    ```bash
    ./deploy.sh --apply
    ```

**Pour vérifier la minification**
Après déploiement, le fichier `public/css/style.min.css` doit être présent et optimisé (une seule ligne, sans espaces inutiles).

## 2.8. Architecture / Layout du site

Toutes les pages sont insérées dans un <WRAPPER> qui est une grille CSS d'une colonne et de 4 lignes définissant 4 zones qui sont <header>, <nav>, <main> et <footer>. Le <WRAPPER> est ramené à 90% de largeur pour que le contenu soit centré dans le viewer.

### 2.9. Messagerie : ports exposes et bonnes pratiques

Pour le serveur de production, les ports verifies comme utiles sont :

- `993` : IMAPS (reception chifree)
- `587` : SMTP submission (envoi authentifie)

Le port `143` (IMAP non chiffre / STARTTLS) etait encore expose publiquement. Il a ete recommande de fermer sa redirection sur la box internet si tous les clients mail utilisent deja `993`.

#### Risque a fermer 143

- faible si tous les clients mail sont deja configures sur `993`
- possible uniquement pour d'anciens clients mail encore configures sur `143`

#### Verification utile cote serveur

```bash
sudo ss -ltnp | grep -E ':(143|587|993)\b'
```

#### Verification utile depuis l'exterieur

```bash
timeout 5 bash -lc '</dev/tcp/lesmegeresdelhumus.fr/143' && echo OPEN || echo CLOSED
timeout 5 bash -lc '</dev/tcp/lesmegeresdelhumus.fr/993' && echo OPEN || echo CLOSED
timeout 5 bash -lc '</dev/tcp/lesmegeresdelhumus.fr/587' && echo OPEN || echo CLOSED
```

Note : fermer la redirection `143` sur la box n'impose pas necessairement de changement YunoHost si `993` et `587` restent utilises.

### 2.10. DNSSEC chez Gandi

`lesmegeresdelhumus.fr` est enregistre chez Gandi. DNSSEC ne se configure pas dans YunoHost mais au niveau du registrar / hebergeur DNS.

#### Impact reel

- absence de DNSSEC : pas bloquant pour le fonctionnement du site
- presence de DNSSEC : durcissement utile contre certaines falsifications DNS

#### Checklist courte d'activation

Avant :

1. Verifier que la zone DNS du domaine est bien geree chez Gandi.
2. Reperer les enregistrements critiques a surveiller apres activation : `A`, `AAAA`, `MX`, `TXT`, `DKIM`, `DMARC`, `CNAME`.
3. Prevoir un court creneau de verification apres activation.

Pendant :

1. Ouvrir l'interface Gandi du domaine.
2. Aller dans la section DNSSEC.
3. Activer DNSSEC.

Apres :

```bash
dig lesmegeresdelhumus.fr
dig MX lesmegeresdelhumus.fr
dig TXT lesmegeresdelhumus.fr
dig +dnssec lesmegeresdelhumus.fr
```

Verification attendue :

- le site repond toujours
- les mails continuent de fonctionner
- `dig +dnssec` renvoie des signatures (`RRSIG`)

### 2.10.b. DMARC recommande chez Gandi

Etat observe le 2026-07-18 :

- SPF publie : `v=spf1 mx a -all`
- DKIM public detecte sur `mail._domainkey.lesmegeresdelhumus.fr`
- DMARC publie : `v=DMARC1; p=none`

Interpretation :

- SPF : correct
- DKIM : correct cote DNS public
- DMARC : actif mais en mode observation uniquement (`p=none`)

#### Recommandation progressive

Pour durcir sans prendre de risque brutal, publier d'abord une politique DMARC prudente :

```txt
v=DMARC1; p=quarantine; pct=25; adkim=s; aspf=s
```

Etat de mise en oeuvre :

- etape 1 : a installer maintenant
- etape 2 : a faire plus tard apres verification du bon passage des mails legitimes
- etape 3 : facultative, uniquement si tout est stable

Puis monter progressivement :

1. `v=DMARC1; p=quarantine; pct=25; adkim=s; aspf=s`
2. `v=DMARC1; p=quarantine; pct=100; adkim=s; aspf=s`
3. Eventuellement plus tard : `v=DMARC1; p=reject; pct=100; adkim=s; aspf=s`

#### Enregistrement exact a publier chez Gandi

Nom / hote :

```txt
_dmarc
```

Type :

```txt
TXT
```

Valeur prudente recommandee :

```txt
v=DMARC1; p=quarantine; pct=25; adkim=s; aspf=s
```

#### Verification apres changement

```bash
dig +short TXT _dmarc.lesmegeresdelhumus.fr
```

#### Point de vigilance

Ne passer a `pct=100` puis a `p=reject` qu'apres verification que tous les mails legitimes du domaine passent bien SPF et/ou DKIM avec alignement correct.

#### Restant a faire

1. Verifier que l'etape 1 est bien publiee chez Gandi.
2. Controler pendant quelque temps que les mails legitimes arrivent normalement.
3. Passer ensuite a l'etape 2 : `v=DMARC1; p=quarantine; pct=100; adkim=s; aspf=s`.
4. Envisager seulement apres cela l'etape 3 : `p=reject`.

### 2.11. Web Check : warnings non bloquants

Certains avertissements Web Check ne sont pas prioritaires pour ce site.

#### Headers cross-origin optionnels

Les warnings sur les headers suivants ne sont pas consideres comme bloquants pour le site actuel :

- `Cross-Origin-Opener-Policy`
- `Cross-Origin-Resource-Policy`
- `Cross-Origin-Embedder-Policy`

Raison : ils servent surtout a des besoins d'isolation cross-origin avances. Les activer sans besoin fonctionnel precis peut casser des integrations ou des ressources externes.

Position retenue :

- conserver les headers deja en place (`CSP`, `nosniff`, `Referrer-Policy`, etc.)
- ne pas ajouter COOP / CORP / COEP par defaut
- reevaluer seulement si un besoin technique precis apparait

#### Cas OCSP : "OCSP response: no response sent"

Contexte observe sur le domaine en production :

```bash
openssl x509 -in /etc/yunohost/certs/lesmegeresdelhumus.fr/crt.pem -noout -issuer -subject -ocsp_uri
```

Resultat observe :

- `issuer` et `subject` sont bien presents
- aucune URL `ocsp_uri` n'est retournee

Interpretation :

- dans cette configuration de chaine Let\'s Encrypt (intermediaire YR1), l\'endpoint OCSP exploitable n\'est pas expose sur le certificat feuille teste ;
- nginx ne peut donc pas agrafer (staple) de reponse OCSP ;
- le message `OCSP response: no response sent` est attendu.

Impact :

- ce point n\'est pas bloquant pour l\'accessibilite du site ;
- la verification TLS globale reste valide (`Verify return code: 0 (ok)`).

Action retenue :

- conserver ce warning comme non critique dans Web Check ;
- ne pas sur-investir sur ce point tant que TLS, mises a jour, permissions et securite mail (SPF/DKIM/DMARC) sont correctement geres.

## 3) Particularités

### 3.1. hintrc
C'est le fichier de configuration de webhint (hint), un outil d'analyse qualité pour sites web. Il vérifie les bonnes pratiques CSS/HTML, la compatibilité navigateur, l'accessibilité, les performances, etc.

La configuration :

"extends": ["development"] — utilise le profil development (moins strict que production)
"compat-api/css" — active les vérifications de compatibilité navigateur pour le CSS
"ignore": ["backdrop-filter"] — ignore l'alerte sur backdrop-filter (propriété peu supportée que vous utilisez intentionnellement, notamment dans votre menu hamburger)
