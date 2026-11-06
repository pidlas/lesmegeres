# Guide Serveur Web - Nginx (YunoHost)

Public cible: administrateur systeme et exploitation web
Quand utiliser ce document: maintenir le site sous pile YunoHost/Nginx
Prerequis: acces serveur, droits sudo, connaissance de base Nginx
Temps de lecture: 7 a 9 min

## 1) Contexte
Ce document decrit l'exploitation du site avec Nginx sur le PC local, hors YunoHost.
Le serveur de production auto-heberge utilise une conf Nginx separee et ne releve pas de ce contexte local.

## 2) Configuration et fichiers utiles
- Fichier de conf de reference local (PC): /var/www/lesmegeres/nginx-lesmegeres.conf
- Vhost actif sur le PC local: /etc/nginx/conf.d/megeres.conf
- Vhost de production sur le serveur auto-heberge: /etc/nginx/conf.d/lesmegeresdelhumus.fr.d/my_webapp.conf
- Fichier de test local (placeholder legacy): /var/www/lesmegeres/nginx-test.conf
- Template archive du test vhost: /var/www/lesmegeres/documentation/archives/config/nginx-test.conf
- Snapshot brut legacy (avant nettoyage): /var/www/lesmegeres/documentation/archives/config/nginx-test-legacy.conf
- Diagnostic historique: ../archives/diagnostics/diagnostic-yunohost.md

## 3) Points de controle essentiels
1. Vhost actif et routing attendu.
2. Certificats TLS et redirections HTTP -> HTTPS.
3. Presence des en-tetes de securite si l'on vise la parite avec la production; en local, ils restent optionnels et peuvent etre ajoutes manuellement si besoin.
4. Acces a /.well-known/security.txt et /security.txt.
5. Logs erreur/acces sans anomalies majeures.

## 4) Runbooks relies
- Deploiement: ../runbooks/production/jour-j-mise-en-production.md
- Verification post-deploy: scripts check-post-deploy.sh et check-uptime.sh
- Maintenance: ../runbooks/maintenance/mode-maintenance.md
- Securite: ../runbooks/securite/security-documentation.md

## 5) Procedure de diagnostic rapide
1. Verifier service Nginx actif.
2. Verifier la conf syntaxique et le vhost charge selon la cible: PC local (`/etc/nginx/conf.d/megeres.conf`) ou production (`/etc/nginx/conf.d/lesmegeresdelhumus.fr.d/my_webapp.conf`), pas `nginx-lesmegeres.conf`.
3. Tester les URLs critiques (accueil, pages majeures, security.txt).
4. Inspecter logs Nginx et journaux systeme.
5. Si incident persiste, appliquer rollback et ouvrir une note dans archives/.

## 6) Limites de ce guide
Ce guide est specifique a Nginx sous YunoHost. Pour un serveur Apache hors YunoHost, utiliser:
- guide-serveur-web-apache-hors-yunohost.md
