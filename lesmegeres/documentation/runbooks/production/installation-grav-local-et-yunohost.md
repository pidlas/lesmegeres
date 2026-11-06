# Installation Grav - local hors YunoHost + production YunoHost

Public cible: administrateur technique
Quand utiliser ce document: installer Grav pour le pilote éditorial (local + production)
Prérequis: accès shell, droits sudo, accès admin YunoHost, domaine/sous-domaine fonctionnel
Temps de lecture: 20 à 30 min

## 1) Objectif
Mettre en place deux environnements cohérents:
1. Local PC hors YunoHost pour prototypage et formation Twig/Sass.
2. Production YunoHost sur sous-domaine pour pilote éditorial.

## 2) Convention d'architecture
1. Local: instance de travail rapide, non exposée publiquement.
2. Production: instance stable sur sous-domaine (grav.lesmegeresdelhumus.fr/grav).
3. Le contenu migre vers le site principal uniquement après validation des lots.

## 3) Volet A - Installation locale (hors YunoHost)

### 3.1 Prérequis système (Debian/Ubuntu) -> FAIT
```bash
sudo apt update
sudo apt install -y php php-cli php-curl php-xml php-mbstring php-zip unzip curl
```

### 3.2 Installer Grav + Admin -> FAIT
```bash
mkdir -p "$HOME/sites" && cd "$HOME/sites"
curl -L -o grav-admin.zip https://getgrav.org/download/core/grav-admin/latest
unzip grav-admin.zip
mv grav-admin grav-local
cd grav-local
```

### 3.3 Lancer un serveur local de test -> FAIT
PHP webserver requires a router to run Grav, please use:
```bash
php -S 127.0.0.1:8080 system/router.php
```
Puis ouvrir:
- http://127.0.0.1:8080
- http://127.0.0.1:8080/admin

### 3.4 Initialiser le socle de travail -> FAIT
1. Créer le compte admin via l'interface.
2. Vérifier le thème actif (Quark recommandé au démarrage).
3. Créer une page test et vérifier l'édition.
4. Uploader une image et vérifier le rendu.

### 3.5 Checks de validation locale -> FAIT
```bash
curl -I http://127.0.0.1:8080/
curl -I http://127.0.0.1:8080/admin
```

## 4) Volet B - Installation production via YunoHost (sous-domaine)

### 4.1 Prérequis YunoHost
1. Le sous-domaine cible existe et pointe vers le serveur. -> FAIT -> grav/lesmegeresdelhumus.fr
2. Certificat HTTPS actif sur le sous-domaine. -> FAIT
3. Sauvegarde YunoHost récente avant installation. -> FAIT

### 4.2 Installer Grav -> FAIT
Option interface web YunoHost:
1. Ouvrir le catalogue d'applications.
2. Chercher Grav.
3. Installer sur le sous-domaine dédié (pas sur le domaine principal).

Option CLI YunoHost (exemple):
```bash
sudo yunohost app install grav --domain grav.lesmegeresdelhumus.fr/ --path /grav
```

### 4.3 Vérifier l'installation production -> FAIT
```bash
curl -I https://grav.lesmegeresdelhumus.fr/
curl -I https://grav.lesmegeresdelhumus.fr/admin
```

### 4.4 Initialiser l'administration -> FAIT
1. Se connecter à l'admin Grav.
2. Vérifier thème et plugins de base.
3. Créer une page test non indexée.
4. Vérifier upload media et édition complète.

## 5) Alignement local <-> production
1. Même theme de base (Quark).
2. Même structure de pages (au moins pour le Lot A).
3. Même conventions SEO (title, H1, meta description, slug).
4. Même checklist de validation avant publication.

## 6) Sauvegarde et rollback (minimum)

### 6.1 Sauvegarde YunoHost app Grav
```bash
sudo yunohost backup create --apps grav
```

### 6.2 Restaurer (si nécessaire)
```bash
sudo yunohost backup list
sudo yunohost backup restore <ARCHIVE_NAME> --apps grav
```

## 7) Problèmes frequents
1. Certificat absent/incorrect sur le sous-domaine: corriger DNS et certificat avant de publier.
2. Plugins divergents entre local et production: documenter et aligner les versions.
3. URLs non stables: figer les slugs avant d'activer les redirections 301.

## 8) Définition de done (installation) -> FAIT
1. Local et production accessibles en front et en /admin.
2. Une page test et un upload image valides sur les deux environnements.
3. Sauvegarde YunoHost testée au moins une fois.
4. Runbook de migration Lot A utilisable sans prérequis manquants.

## 9) Liens utiles
- Stratégie vivante: ../../guides/strategie-migration-grav.md
- Checklist production: ./checklist-production.md
- Déploiement général: ./jour-j-mise-en-production.md