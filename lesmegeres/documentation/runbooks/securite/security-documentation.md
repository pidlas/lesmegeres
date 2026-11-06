# Documentation sécurité du site

Public cible: administrateur technique
Quand utiliser ce document: publier et maintenir security.txt + cle PGP
Prerequis: acces aux fichiers du site et a la configuration Nginx/YunoHost
Temps de lecture: 8 a 10 min

Ce dossier contient les fichiers nécessaires pour déclarer une procédure de signalement de vulnérabilité sur le site.

## Fichiers créés

- [.well-known/security.txt](.well-known/security.txt) : fichier de déclaration de sécurité au format standard.
- [.well-known/pgp-key.txt](.well-known/pgp-key.txt) : clé publique PGP.
- [security.txt](security.txt) : copie du fichier de sécurité à la racine du site.
- [lesmegeres-app/pages/politique-de-securite.php](../lesmegeres-app/pages/politique-de-securite.php) : page de politique de sécurité servie via la route `/politique-de-securite`.

## Configuration recommandée

1. Créer un alias de messagerie comme `security@lesmegeresdelhumus.fr` vers une boîte de réception réelle. FAIT
2. Vérifier que les URLs pointent vers votre domaine réel.
3. Tester l’accès à :
   - https://lesmegeresdelhumus.fr/.well-known/security.txt
   - https://lesmegeresdelhumus.fr/security.txt
   - https://lesmegeresdelhumus.fr/politique-de-securite

## Trace de progression (2026-07-18)

État validé en local :

- Les fichiers existent et sont remplis :
  - [.well-known/security.txt](.well-known/security.txt)
  - [.well-known/pgp-key.txt](.well-known/pgp-key.txt)
  - [security.txt](security.txt)
  - [lesmegeres-app/pages/politique-de-securite.php](../lesmegeres-app/pages/politique-de-securite.php)
- La clé publique PGP réelle a été copiée dans [.well-known/pgp-key.txt](.well-known/pgp-key.txt).
- Le vhost local sert correctement les fichiers :
   - http://lesmegeres/security.txt -> HTTP 200
   - http://lesmegeres/.well-known/security.txt -> HTTP 200
   - http://lesmegeres/.well-known/pgp-key.txt -> HTTP 200
- Le chemin dossier seul `http://lesmegeres/.well-known/` peut répondre HTTP 403, ce qui est normal (pas d'index de répertoire).

## Reprise ultérieure pour la production

Objectif : rendre les mêmes URLs publiques sur https://lesmegeresdelhumus.fr.

Étapes prévues :

1. Vérifier le vhost HTTPS réellement actif (YunoHost/SSO) pour `lesmegeresdelhumus.fr`.
2. Ajouter les locations explicites suivantes dans ce vhost HTTPS :
   - `location = /security.txt`
   - `location = /.well-known/security.txt`
   - `location = /.well-known/pgp-key.txt`
   avec `default_type text/plain;` et `alias` vers `/var/www/lesmegeres/...`.
3. Vérifier que la règle de blocage des dotfiles autorise `.well-known` (forme attendue : `location ~ /\.(?!well-known) { deny all; }`).
4. Recharger nginx puis tester :
   - https://lesmegeresdelhumus.fr/security.txt
   - https://lesmegeresdelhumus.fr/.well-known/security.txt
   - https://lesmegeresdelhumus.fr/.well-known/pgp-key.txt

## Procedure de bascule production (YunoHost + deploy.sh)

Contrainte importante : ne pas pousser de config depuis `nginx-lesmegeres.conf` en production, car YunoHost gère sa propre conf nginx.

1. Déployer les fichiers du site uniquement :
   - lancer `./scripts/deploy.sh --apply`
   - ce script transfère maintenant aussi `security.txt` et `.well-known/`
2. Identifier le fichier nginx de l'app YunoHost active :
   - `sudo nginx -T | grep -nE "lesmegeresdelhumus\.fr|my_webapp|location"`
   - repère attendu : `/etc/nginx/conf.d/lesmegeresdelhumus.fr.d/my_webapp.conf`
3. Ajouter dans le bloc `location` principal de l'app (pas dans `nginx-lesmegeres.conf`) :
   - `location = /security.txt { default_type text/plain; alias /var/www/my_webapp/www/security.txt; }`
   - `location = /.well-known/security.txt { default_type text/plain; alias /var/www/my_webapp/www/.well-known/security.txt; }`
   - `location = /.well-known/pgp-key.txt { default_type text/plain; alias /var/www/my_webapp/www/.well-known/pgp-key.txt; }`
   - et vérifier la règle dotfiles : `location ~ /\.(?!well-known) { deny all; }`
4. Valider puis recharger :
   - `sudo nginx -t`
   - `sudo systemctl reload nginx`
5. Tester publiquement :
   - `curl -I https://lesmegeresdelhumus.fr/security.txt`
   - `curl -I https://lesmegeresdelhumus.fr/.well-known/security.txt`
   - `curl -I https://lesmegeresdelhumus.fr/.well-known/pgp-key.txt`

Note YunoHost : une mise à jour d'app peut écraser la conf nginx de l'app. Garder ces lignes dans votre checklist de post-update.

## Procedure pour publier un security.txt signe PGP

Objectif : obtenir un fichier `security.txt` detecte comme `PGP signed: YES` par les outils comme Web Check.

Principe :

- le champ `Encryption:` pointe vers la cle publique publique ;
- mais cela ne suffit pas pour un `security.txt` "signe" ;
- le contenu du fichier `security.txt` lui-meme doit etre publie au format OpenPGP clear-signed.

### Flux recommande

1. Maintenir un fichier source non signe, par exemple `security.txt.src`, a la racine de `/var/www/lesmegeres/`.
2. Signer ce fichier source avec `gpg --clearsign` vers `/.well-known/security.txt`.
3. Copier exactement le meme contenu signe vers `/security.txt`.
4. Verifier la signature localement.
5. Deployer avec `./scripts/deploy.sh --apply`.

### Exemple de fichier source non signe

```text
Contact: mailto:security@lesmegeresdelhumus.fr
Encryption: https://lesmegeresdelhumus.fr/.well-known/pgp-key.txt
Acknowledgments: https://lesmegeresdelhumus.fr/politique-de-securite
Policy: https://lesmegeresdelhumus.fr/politique-de-securite
Preferred-Languages: fr, en
Canonical: https://lesmegeresdelhumus.fr/.well-known/security.txt
Expires: 2027-07-18T23:59:59.000Z
```

### Commandes de signature

Depuis `/var/www/lesmegeres/` :

```bash
gpg --clearsign --output .well-known/security.txt security.txt.src
cp .well-known/security.txt security.txt
```

### Verification locale

Verifier d'abord la signature :

```bash
gpg --verify .well-known/security.txt
```

Verifier ensuite le contenu servi localement :

```bash
curl -s http://lesmegeres/.well-known/security.txt | sed -n '1,12p'
curl -s http://lesmegeres/security.txt | sed -n '1,12p'
```

Le debut attendu doit contenir :

```text
-----BEGIN PGP SIGNED MESSAGE-----
Hash: SHA512
```

### Deploiement et verification production

```bash
./scripts/deploy.sh --apply

Compatibilite legacy: `./deploy.sh` reste disponible via wrapper.
curl -s https://lesmegeresdelhumus.fr/.well-known/security.txt | sed -n '1,12p'
curl -s https://lesmegeresdelhumus.fr/security.txt | sed -n '1,12p'
```

### Regle de maintenance importante

Chaque modification du contenu (par exemple `Expires`, `Policy`, `Contact`) impose de re-signer le fichier.

### Etat actuel

Au 2026-07-18, les fichiers suivants sont deja signes en cleartext PGP :

- [.well-known/security.txt](.well-known/security.txt)
- [security.txt](security.txt)

## Remarques

- Le fichier `security.txt` est un standard de signalement de vulnérabilité.
- L’ajout d’une clé PGP est recommandé si vous souhaitez permettre un échange chiffré.
- Si vous ne souhaitez pas gérer une boîte dédiée, un alias est suffisant.
- Les sujets d'exploitation connexes (ports mail 143/993/587, DNSSEC chez Gandi, warnings Web Check non bloquants) sont documentes dans [doc_guide-administrateur.md](doc_guide-administrateur.md).
- La recommandation DMARC progressive (`p=quarantine` puis durcissement) est egalement documentee dans [doc_guide-administrateur.md](doc_guide-administrateur.md).

## Suivi DMARC

Etat courant :

- etape 1 a installer maintenant chez Gandi : `v=DMARC1; p=quarantine; pct=25; adkim=s; aspf=s`
- etape 2 restera a faire plus tard : `v=DMARC1; p=quarantine; pct=100; adkim=s; aspf=s`
- etape 3 restera eventuellement a faire ensuite : `v=DMARC1; p=reject; pct=100; adkim=s; aspf=s`

Le detail operatoire et les points de vigilance sont documentes dans [doc_guide-administrateur.md](doc_guide-administrateur.md).

## Warnings Web Check connus (non bloquants)

Cette section recense les avertissements deja analyses et acceptes comme non critiques dans le contexte actuel du site.

1. `Server disclosing server: nginx`
- Interpretation : hardening cosmetique.
- Priorite : faible.
- Action : `server_tokens off;` est deja active ; masquer totalement la valeur `Server` n'est pas prioritaire.

2. `OCSP response: no response sent`
- Interpretation : attendu avec la chaine/certificat observee (pas d'`ocsp_uri` exploitable detecte sur le certificat teste).
- Priorite : faible.
- Impact : TLS reste valide (`Verify return code: 0 (ok)`).

3. `Cross-Origin-Opener-Policy` / `Cross-Origin-Resource-Policy` / `Cross-Origin-Embedder-Policy` absents
- Interpretation : protections optionnelles selon besoin applicatif avance.
- Priorite : faible a moyenne selon usage.
- Action : ne pas activer par defaut pour eviter les regressions sur les ressources externes.

Regle de priorisation : traiter d'abord les sujets a impact direct (TLS versions, mises a jour, permissions, SPF/DKIM/DMARC), puis revenir sur ces warnings si un besoin metier precise apparait.