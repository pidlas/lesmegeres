# Mode Maintenance — Documentation

Public cible: administrateur technique
Quand utiliser ce document: activer ou desactiver le mode maintenance en local et en production
Prerequis: acces shell et droits de modification Nginx/YunoHost
Temps de lecture: 10 a 12 min
Statut: mixte (local actif, section production partiellement historique)

---

## 1) [Vue d'ensemble](#1-vue-densemble-1)
## 2) [Environnement local](#2-environnement-local-1)
## 3) [Environnement de Production](#3-environnement-de-production-1)
## 4) [Architecture globale pendant la période de construction](#4-architecture-globale-pendant-la-période-de-construction-1)

---

## 1) Vue d'ensemble

Deux environnements, deux mécanismes distincts :

| Environnement | URL                             | Mécanisme                                         | Activer/Désactiver        |
|---------------|---------------------------------|---------------------------------------------------|---------------------------|
| Local         | `http://lesmegeres`             | Fichier-drapeau + Nginx                           | `touch` / `rm`            |
| Production    | `https://lesmegeresdelhumus.fr` | App YunoHost `my_webapp` au chemin `/maintenance` | Permanent jusqu'au Jour J |

L'objectif est de faire fonctionner le site en maintenance dans l'environnement de production comme en local ou sur le site https://paysaflore.fr. L'app Yunohost installée sur my_webapp sur le chemin Maintenance a été désinstallée pour pouvoir installer le site des Mégères de l'Humus sur la racine.

## 2) Environnement local

### Mécanisme

Un fichier-drapeau `/var/www/lesmegeres/.maintenance` contrôle la redirection.  
La config Nginx (`/etc/nginx/conf.d/megeres.conf`) détecte ce fichier et redirige tout le trafic vers `/maintenance/index.html`.

Les assets de la page (fonts, image de fond, logo, favicon) sont exemptés de la redirection.

### Activer le mode maintenance

```bash
touch /var/www/lesmegeres/.maintenance
```

### Désactiver le mode maintenance

```bash
rm /var/www/lesmegeres/.maintenance
```

### Bloc Nginx concerné

Fichier : `/etc/nginx/conf.d/megeres.conf`

```nginx
set $maint 0;
if (-f /var/www/lesmegeres/.maintenance) { set $maint 1; }
if ($uri ~* "^/(maintenance/|images/favicon/|images/logohumous)") { set $maint 0; }
if ($maint = 1) {
    return 302 /maintenance/index.html;
}
```

Le même bloc est présent dans `/var/www/lesmegeres/nginx-lesmegeres.conf` (référence versionnée).

## 3) Environnement de Production

**Mécanisme obsolète**

L'application YunoHost `my_webapp` était installée sur le chemin `/maintenance`. SSOWAT (le SSO de YunoHost) redirige toute requête vers `/` en `302 /maintenance`, car `my_webapp` était déclarée application par défaut du domaine.

Cette configuration n'est plus opérationnelle. Pour réinstaller le site https://lesmegertesdelhumus.fr sur la racine, j'ai dû supprimer les trois applications installées sur le domaine : webapp qui gérait la page de maintenance, webapp__3 qui gérait le site sur le chemin site1/ et nextcloud que j'ai réinstallé sur un sous-domaine nextcloud.lesmegeresdelhumus.fr.

### Assets de la page de maintenance (local)

| Fichier       | Chemin local                                                       |
|---------------|--------------------------------------------------------------------|
| Page HTML     | `/var/www/lesmegeres/maintenance/index.html`                           |
| Image de fond | `/var/www/lesmegeres/maintenance/bg.jpg`                               |
| Logo          | `/var/www/lesmegeres/images/logohumous-notext-blue.png` (logo du site) |
| Favicon       | `/var/www/lesmegeres/images/favicon/` (favicons du site)               |
| Fonts Belleza | `/var/www/lesmegeres/maintenance/fonts/Belleza-Regular.*`              |

> **Note**
En local la page utilise les assets du site principal via des chemins relatifs (`../images/...`).  
Sur le serveur, `logo.jpg` et `favicon.png` sont des copies autonomes dans `/var/www/my_webapp/www/`.

### Mettre à jour la page de maintenance sur le serveur

```bash
ssh -p 1000 root@<ip_serveur>
# Éditer directement :
nano /var/www/my_webapp/www/index.html
```

Ou transférer depuis la version locale :

```bash
rsync -avz -e "ssh -p 1000" \
  /var/www/lesmegeres/maintenance/index.html \
  root@<ip_serveur>:/var/www/my_webapp/www/index.html
```