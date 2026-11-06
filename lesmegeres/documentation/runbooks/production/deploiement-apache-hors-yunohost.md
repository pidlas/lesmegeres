# Checklist de mise en production Apache hors YunoHost

Public cible: administrateur système
Quand utiliser ce document: basculer le site sur Apache hors YunoHost avec un VirtualHost équivalent à nginx-lesmegeres.conf
Prérequis: accès sudo, Apache installé, modules rewrite, headers, expires, ssl, proxy, proxy_fcgi, setenvif disponibles
Temps de lecture: 5 à 7 min
Statut: procédure opérationnelle

## 1) Avant de commencer

Vérifier les modèles de référence:
- [apache-lesmegeres.conf](../../../apache-lesmegeres.conf)
- [apache-lesmegeres-ssl.conf](../../../apache-lesmegeres-ssl.conf)

Vérifier avant bascule:
- le domaine cible est bon
- les certificats SSL sont disponibles si HTTPS
- PHP-FPM pointe vers la bonne socket
- les modules Apache requis sont disponibles

```bash
sudo apachectl -M | grep -E 'rewrite|headers|expires|ssl|proxy|proxy_fcgi|setenvif'
```

## 2) Mise en service HTTP

```bash
sudo cp /var/www/lesmegeres/apache-lesmegeres.conf /etc/apache2/sites-available/megeres.conf
sudo a2enmod rewrite headers expires proxy proxy_fcgi setenvif
sudo a2ensite megeres.conf
sudo apachectl configtest
sudo systemctl reload apache2
```

## 3) Mise en service HTTPS

```bash
sudo cp /var/www/lesmegeres/apache-lesmegeres-ssl.conf /etc/apache2/sites-available/megeres-ssl.conf
sudo a2enmod rewrite headers expires ssl proxy proxy_fcgi setenvif
sudo a2ensite megeres-ssl.conf
sudo apachectl configtest
sudo systemctl reload apache2
```

## 4) Vérifications après mise en service

```bash
curl -I http://lesmegeres/
curl -I http://lesmegeres/security.txt
curl -I http://lesmegeres/.well-known/security.txt
curl -I http://lesmegeres/.well-known/pgp-key.txt
curl -I https://lesmegeresdelhumus.fr/
curl -I https://lesmegeresdelhumus.fr/security.txt
curl -I https://lesmegeresdelhumus.fr/.well-known/security.txt
curl -I https://lesmegeresdelhumus.fr/.well-known/pgp-key.txt
```

## 5) Points a adapter si besoin

1. Remplacer les chemins de certificats SSL dans [apache-lesmegeres-ssl.conf](../../../apache-lesmegeres-ssl.conf).
2. Adapter ServerName/ServerAlias au domaine cible si necessaire.
3. Verifier que PHP-FPM pointe vers la bonne version socket.
4. Activer ou confirmer les modules Apache requis.
5. Si le site doit etre servi sous une autre racine, adapter DocumentRoot et les Alias.

## 6) Incident ou rollback

Si la mise en service echoue:

1. Desactiver le vhost Apache:
```bash
sudo a2dissite megeres.conf
sudo a2dissite megeres-ssl.conf
sudo systemctl reload apache2
```
2. Restaurer l'ancien vhost si necessaire.
3. Repasser les tests HTTP apres rollback.

## 7) Compatibilite avec la documentation

- Guide de reference: [guide-serveur-web-apache-hors-yunohost.md](../../guides/guide-serveur-web-apache-hors-yunohost.md)
- Reference Nginx: [nginx-lesmegeres.conf](../../../nginx-lesmegeres.conf)
