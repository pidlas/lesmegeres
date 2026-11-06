# Ajouter une actualité rapidement

Public cible: proprietaires et responsables de contenu
Quand utiliser ce document: ajouter une actualite sans modifier la structure du site
Prerequis: acces au fichier actualites.php et au script de deploiement
Temps de lecture: 3 a 5 min

## 1) Copier-coller un bloc dans le tableau $actualites
## 2) Règles rapides
## 3) Vérification
## 4) Déploiement (commande recommandée)

Fichier à modifier:
- ../lesmegeres-app/pages/actualites.php

## 1) Copier-coller un bloc dans le tableau $actualites

```php
[
    'date' => '2026-04-15',
    'titre' => 'Titre de l actualite',
    'resume' => 'Resume court: lieu, horaire, public, info pratique.',
    'plateforme' => 'facebook', // ou 'instagram'
    'lien' => 'https://www.facebook.com/...', // lien du post ou du profil
    'image' => null, // ou '/images/mon-visuel.jpg'
],
```
Pour le lien du profil, il est déjà présent dans le menu. Pour le lien vers le post facebook, utiliser : https://www.facebook.com/permalink.php?qtory-fid=XXXXXXX.

## 2) Règles rapides

1. Date au format AAAA-MM-JJ.
2. Lien public valide (post ou profil).
3. image = null si pas de visuel local.
4. Pour une image locale, placer le fichier dans public/images puis renseigner le chemin (ex: /images/mon-visuel.jpg).

## 3) Vérification

```bash
php -l /var/www/lesmegeres-app/pages/actualites.php
```

## 4) Déploiement (commande recommandée)

```bash
./scripts/deploy.sh --apply --verify-post-owners
```

Compatibilite legacy: `./deploy.sh` reste disponible via wrapper.
