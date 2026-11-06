# Guide Serveur Web - Apache (hors YunoHost)

Public cible: administrateur systeme et exploitation web
Quand utiliser ce document: heberger le site sans YunoHost sur une pile Apache
Prerequis: acces serveur, droits sudo, modules Apache standards actifs
Temps de lecture: 4 a 6 min

## 1) Contexte
Ce document sert de guide de passation pour un hebergement Apache hors YunoHost.
La procedure pas-a-pas est dans [deploiement-apache-hors-yunohost.md](../runbooks/production/deploiement-apache-hors-yunohost.md).

## 2) Ce qu'il faut retenir

- Reference HTTP: [apache-lesmegeres.conf](../../apache-lesmegeres.conf)
- Reference HTTPS: [apache-lesmegeres-ssl.conf](../../apache-lesmegeres-ssl.conf)
- Vhost cible: `/etc/apache2/sites-available/<site>.conf`
- Activation: `a2ensite`, puis `apachectl configtest`, puis reload Apache
- Modules courants: `rewrite`, `headers`, `ssl`, `expires`, `proxy`, `proxy_fcgi`, `setenvif`

## 3) Points de controle avant remise a quelqu'un

1. Le vhost Apache doit couvrir HTTP et HTTPS.
2. Les certificats SSL doivent etre adaptes au serveur cible.
3. Le front-controller, les redirections legacy et les protections .well-known doivent etre verifies.
4. La maintenance, les headers et le cache statique doivent etre valides avant passation.
5. Si la parite doit etre maximale, utiliser le runbook detaille et le vhost dedie plutot que `.htaccess` seul.

## 4) Limites a garder en tete

- Apache hors YunoHost est une cible equivalente en comportement utilisateur, pas une copie binaire de la conf Nginx.
- La logique `.htaccess` rapproche Apache de Nginx, mais le VirtualHost reste le meilleur endroit pour la configuration de production.
- Les details operatoires (rollback, tests, modules) vivent dans le runbook deploiement Apache.

## 5) Runbooks relies

- Deploiement Apache: ../runbooks/production/deploiement-apache-hors-yunohost.md
- Deploiement classique: ../runbooks/production/jour-j-mise-en-production.md
- Maintenance: ../runbooks/maintenance/mode-maintenance.md
- Securite: ../runbooks/securite/security-documentation.md
- SEO/validation: ../runbooks/seo/seo-checklist-proprietaires.md (section technique admin)
