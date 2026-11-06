# Guide Administrateur

Public cible: administrateur technique du site
Quand utiliser ce document: exploitation quotidienne, maintenance, passation
Prerequis: acces shell, droits sur le projet, acces serveur web
Temps de lecture: 8 a 10 min

## 1) Perimetre et responsabilites
Ce guide couvre:
- structure du projet web
- cycle de deploiement et rollback
- maintenance et securite
- suivi SEO technique
- passation a un nouvel administrateur

## 2) Structure du projet
Racines principales:
- /var/www/lesmegeres : assets, scripts de deploiement, configuration web locale
- /var/www/lesmegeres-app : includes et pages PHP
- /var/www/lesmegeres/documentation : documentation operationnelle

Scripts importants (dans /var/www/lesmegeres):
- scripts/deploy.sh
- scripts/rollback.sh
- scripts/check-post-deploy.sh
- scripts/check-uptime.sh
- scripts/add_security_headers.py

Index scripts:
- ../references/scripts-index.md

## 3) Operations recurrentes

### Deploiement
- Procedure detaillee: ../runbooks/production/jour-j-mise-en-production.md
- Checklist canonique: ../runbooks/production/checklist-production.md
- Alias legacy conserves: checklist-production-5-lignes.md et checklist-production-10-lignes.md

### Maintenance
- Mode maintenance: ../runbooks/maintenance/mode-maintenance.md

### Securite et acces
- SSH interne/externe: ../runbooks/securite/guide-ssh-acces-interne-externe.md
- Dossier securite: ../runbooks/securite/security-documentation.md

### Contenu et SEO
- Publication rapide d'actualite: ../runbooks/contenu/ajouter-une-actu-rapide.md
- Documentation SEO: ../runbooks/seo/seo-documentation.md
- Strategie Grav (document vivant): ./strategie-migration-grav.md

## 4) Passation administrateur (checklist)
1. Verifier les acces SSH et sudo.
2. Verifier les chemins projet et permissions de fichiers.
3. Executer un dry-run de deploiement.
4. Verifier les logs web et les checks post-deploy.
5. Relire les runbooks production, maintenance, securite.
6. Verifier le dossier archives pour comprendre les choix historiques.

### Nginx vs Apache

| Sujet | Nginx (YunoHost) | Apache hors YunoHost |
|---|---|---|
| Reference de conf | `nginx-lesmegeres.conf` (modele local), `megeres.conf` sur le PC local, `my_webapp.conf` sur la production auto-hebergee | `apache-lesmegeres.conf` et `apache-lesmegeres-ssl.conf` |
| Fichiers speciaux | `location = /security.txt`, `.well-known` explicite | `Alias` et `RewriteRule` equivalents |
| Maintenance | Redirection par `.maintenance` | Meme logique via rewrite ou VirtualHost |
| Headers / cache | Centralises dans le vhost | Via `mod_headers` et `mod_expires` |
| Niveau recommande | Reference actuelle de prod | Equivalent fonctionnel hors YunoHost |

Regle pratique:
- pour Nginx/YunoHost, suivre les confs et runbooks Nginx; `nginx-lesmegeres.conf` est un modele, `megeres.conf` est le vhost du PC local, et `my_webapp.conf` est le vhost de production auto-heberge;
- pour Apache, suivre les vhosts de reference et le runbook deploiement Apache;
- ne pas melanger les deux modeles sans documenter l'ecart.

## 5) Historique technique
Les decisions historiques et corrections ponctuelles sont dans:
- ../archives/diagnostics/
- ../archives/refactoring/
- ../archives/diaporama-c3/
- ../archives/historique/

## 6) Politique d'evolution documentaire
- Toute nouvelle procedure stable doit aller dans runbooks/.
- Toute analyse ponctuelle doit aller dans archives/.
- Ce guide doit rester court et pointer vers les documents d'action.

## 7) Fiabiliser l'exploitation
Priorite 1 pour la suite: rendre l'exploitation plus previsible et plus facile a reprendre.

Checklist minimale:
1. Verifier l'uptime et les erreurs 5xx apres chaque deploy.
2. Controler le certificat TLS et sa date d'expiration.
3. Noter la derniere mise en prod dans un endroit unique.
4. Garder un rollback court, teste, et accessible en une seule procedure.
5. Faire un controle rapide des redirections et pages sensibles apres modification.

Mini runbook d'exploitation:
1. Etat du service et des logs:
```bash
systemctl status nginx
journalctl -u nginx -n 50 --no-pager
```
2. Verification des endpoints sensibles:
```bash
curl -I https://lesmegeresdelhumus.fr/
curl -I https://lesmegeresdelhumus.fr/security.txt
curl -I https://lesmegeresdelhumus.fr/.well-known/security.txt
```
3. Checks post-deploiement:
```bash
./scripts/check-post-deploy.sh
```
4. Retour arriere si besoin:
```bash
./scripts/rollback.sh
```
5. Verification finale apres retour arriere:
```bash
curl -I https://lesmegeresdelhumus.fr/
```

## 8) Ameliorer l'experience utilisateur
Priorite 2 pour la suite: rendre le site plus simple, plus rapide et plus rassurant pour les visiteurs.

Checklist minimale:
1. Verifier la lisibilite sur mobile des pages principales.
2. Confirmer que le parcours accueil -> spectacle -> contact reste clair.
3. Controler les pages d'erreur 404 et la page maintenance.
4. Surveiller le poids des images et le temps de chargement visible.
5. Garder les informations de contact, de securite et de mentions faciles a trouver.

Mini controle UX:
1. Ouvrir les pages principales sur mobile et desktop.
2. Tester les liens importants et les points d'entree depuis l'accueil.
3. Relever les elements qui demandent trop de scroll, de clics ou de lecture.
4. Noter les pages qui meritent une simplification ou une remise en forme.
5. Prioriser les corrections qui reduisent la friction avant les ajouts visuels.
