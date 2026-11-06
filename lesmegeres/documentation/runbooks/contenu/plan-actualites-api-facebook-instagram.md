# Plan : alimentation automatique de /actualites via API Facebook/Instagram

Public cible: administrateur technique
Quand utiliser ce document: evaluer ou preparer l'automatisation des actualites
Prerequis: acces Facebook/Instagram Developper et connaissance basique API Graph
Temps de lecture: 12 a 15 min
Statut: plan d'evolution (non deploye en production)

Date: 2026-04-05
Périmètre: page /actualites du site Les Mégères de l'Humus
Objectif: remplacer la saisie manuelle du tableau PHP par un fetch automatique des posts

---

## 1) Architecture cible (Niveau 1 recommandé)

```
fetch-actualites.php  (script local, non deployé)
  |-- appelle Facebook Graph API → posts de la Page
  |-- appelle Instagram Graph API → posts du compte Instagram Business
  └-- écrit /var/www/lesmegeres/data/actualites.json

actualites.php  (modifié pour lire le JSON)
  └-- lit /var/www/lesmegeres/data/actualites.json
  └-- fallback sur tableau statique si JSON absent
```

Le JSON est généré localement puis déployé via deploy.sh comme n'importe quel fichier.
Aucun token ne vit sur le serveur de production.

---

## 2) Prérequis — obtenir les tokens API

### 2.1 Créer une app Facebook Developpeur

1. Aller sur https://developers.facebook.com
2. Cliquer "Mes apps" > "Créer une app"
3. Choisir le type: "Professionnel" ou "Entreprise"
4. Donner un nom (ex: "Les Mégères Actualites")
5. Dans "Produits", ajouter: **Facebook Login** et **Instagram Graph API**

### 2.2 Obtenir un Page Access Token long-lived (Facebook)

Étape 1: token à courte duree (valable 1h)
- Dans l'Explorateur de l API Graph: https://developers.facebook.com/tools/explorer
- Sélectionner votre app
- Cliquer "Générer un token d'accès utilisateur"
- Cocher les permissions: `pages_read_engagement`, `pages_show_list`
- Copier le token obtenu (USER_SHORT_LIVED_TOKEN)

Étape 2: échange contre un token long-lived utilisateur (valable 60 jours)
```
GET https://graph.facebook.com/oauth/access_token
  ?grant_type=fb_exchange_token
  &client_id=VOTRE_APP_ID
  &client_secret=VOTRE_APP_SECRET
  &fb_exchange_token=USER_SHORT_LIVED_TOKEN
```
→ Réponse: `{"access_token": "USER_LONG_LIVED_TOKEN", ...}`

Étape 3: récuperer le Page Access Token (n'expire pas)
```
GET https://graph.facebook.com/me/accounts
  ?access_token=USER_LONG_LIVED_TOKEN
```
→ Dans la réponse, trouver la page "Les Mégères de l'Humus"
→ Copier son `access_token` → PAGE_ACCESS_TOKEN (permanent)

### 2.3 Lier Instagram à la Page Facebook

1. Sur Facebook, aller dans Parametres de la Page > Instagram
2. Connecter le compte Instagram de la compagnie
3. Le compte doit etre en mode "Professionnel" ou "Business" (pas particulier)

### 2.4 Récuperer l'ID du compte Instagram Business

```
GET https://graph.facebook.com/me?fields=instagram_business_account&access_token=PAGE_ACCESS_TOKEN
```
→ Réponse: `{"instagram_business_account": {"id": "INSTAGRAM_ACCOUNT_ID"}}`

### 2.5 Vérifier les permissions
Dans l'Explorateur API Graph, vérifier que ces permissions sont accordées:
- `pages_read_engagement`
- `pages_show_list`
- `instagram_basic`
- `instagram_content_publish` (inutile ici, juste pour lire: non)

---

## 3) Structure du fichier JSON cible

```json
[
  {
    "date": "2026-04-10",
    "titre": "Stage de theatre en avril",
    "resume": "Infos pratiques: lieu, horaire, public...",
    "plateforme": "facebook",
    "lien": "https://www.facebook.com/.../posts/...",
    "image": null
  },
  {
    "date": "2026-04-08",
    "titre": "Atelier du samedi",
    "resume": "...",
    "plateforme": "instagram",
    "lien": "https://www.instagram.com/p/...",
    "image": null
  }
]
```

Chemin cible: `/var/www/lesmegeres/data/actualites.json`
Ce fichier est deployé sur le serveur via deploy.sh (ajouter "data" dans PUBLIC_ITEMS si absent).

---

## 4) Script fetch-actualites.php (à creer localement)

À placer dans `/var/www/lesmegeres/` (ou un dossier local hors repo si préféré).
NE PAS déployer ce fichier (ajouter dans RSYNC_EXCLUDES de deploy.sh).

```php
<?php
/**
 * fetch-actualites.php
 * Usage: php fetch-actualites.php
 * Genere /var/www/lesmegeres/data/actualites.json
 */

define('PAGE_ACCESS_TOKEN', 'REMPLACER_PAR_VOTRE_TOKEN');
define('FACEBOOK_PAGE_ID',  'REMPLACER_PAR_PAGE_ID');      // ex: 123456789
define('INSTAGRAM_ACCOUNT_ID', 'REMPLACER_PAR_IG_ID');     // ex: 987654321
define('MAX_POSTS', 10);
define('OUTPUT_FILE', __DIR__ . '/data/actualites.json');

function fetch_url(string $url): ?array {
    $ctx = stream_context_create(['http' => ['timeout' => 10]]);
    $raw = @file_get_contents($url, false, $ctx);
    if ($raw === false) return null;
    return json_decode($raw, true);
}

$posts = [];

// --- Facebook ---
$fb_url = sprintf(
    'https://graph.facebook.com/v19.0/%s/posts?fields=message,story,created_time,permalink_url&limit=%d&access_token=%s',
    FACEBOOK_PAGE_ID, MAX_POSTS, PAGE_ACCESS_TOKEN
);
$fb = fetch_url($fb_url);
if ($fb && isset($fb['data'])) {
    foreach ($fb['data'] as $p) {
        $texte = $p['message'] ?? $p['story'] ?? '';
        if (empty($texte)) continue;
        $lignes = explode("\n", trim($texte));
        $titre  = mb_substr($lignes[0], 0, 80);
        $resume = mb_substr($texte, 0, 200);
        $posts[] = [
            'date'        => substr($p['created_time'], 0, 10),
            'titre'       => $titre,
            'resume'      => $resume,
            'plateforme'  => 'facebook',
            'lien'        => $p['permalink_url'],
            'image'       => null,
        ];
    }
}

// --- Instagram ---
$ig_url = sprintf(
    'https://graph.facebook.com/v19.0/%s/media?fields=caption,timestamp,permalink&limit=%d&access_token=%s',
    INSTAGRAM_ACCOUNT_ID, MAX_POSTS, PAGE_ACCESS_TOKEN
);
$ig = fetch_url($ig_url);
if ($ig && isset($ig['data'])) {
    foreach ($ig['data'] as $p) {
        $texte = $p['caption'] ?? '';
        if (empty($texte)) continue;
        $lignes = explode("\n", trim($texte));
        $titre  = mb_substr($lignes[0], 0, 80);
        $resume = mb_substr($texte, 0, 200);
        $posts[] = [
            'date'        => substr($p['timestamp'], 0, 10),
            'titre'       => $titre,
            'resume'      => $resume,
            'plateforme'  => 'instagram',
            'lien'        => $p['permalink'],
            'image'       => null,
        ];
    }
}

// Trier par date descendante
usort($posts, fn($a, $b) => strcmp($b['date'], $a['date']));

// Garder les MAX_POSTS plus recents toutes plateformes confondues
$posts = array_slice($posts, 0, MAX_POSTS);

// Écrire le JSON
if (!is_dir(dirname(OUTPUT_FILE))) {
    mkdir(dirname(OUTPUT_FILE), 0755, true);
}
file_put_contents(OUTPUT_FILE, json_encode($posts, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
echo 'OK: ' . count($posts) . " posts ecrits dans " . OUTPUT_FILE . "\n";
```

---

## 5) Modifier actualites.php pour lire le JSON

Remplacer le tableau statique `$actualites = [...]` par:

```php
$json_path = site_root('/data/actualites.json');  // adapter selon la fonction site_root
$actualites = [];

if (file_exists($json_path)) {
    $raw = file_get_contents($json_path);
    $parsed = json_decode($raw, true);
    if (is_array($parsed)) {
        $actualites = $parsed;
    }
}

// Fallback: si JSON absent ou vide, afficher un message
if (empty($actualites)) {
    $actualites = [
        [
            'date'       => date('Y-m-d'),
            'titre'      => 'Actualites bientot disponibles',
            'resume'     => 'Revenez bientot.',
            'plateforme' => 'facebook',
            'lien'       => 'https://www.facebook.com/lesmegeresdelhumus',
            'image'      => null,
        ]
    ];
}
```

---

## 6) Ajouter data/ au deploiement

Dans [deploy.sh](deploy.sh), ajouter `"data"` dans le tableau `PUBLIC_ITEMS`:

```bash
PUBLIC_ITEMS=(
  "index.php"
  "archive"
  "audio"
  "css"
  "data"          # ← ajouter cette ligne
  "fonts"
  ...
)
```

Et exclure le script de fetch du déploiement dans `RSYNC_EXCLUDES`:

```bash
  "fetch-actualites.php"
```

---

## 7) Workflow de mise a jour

```bash
# 1. Lancer le fetch (génération du JSON)
php /var/www/lesmegeres/fetch-actualites.php

# 2. Vérifier le resultat
cat /var/www/lesmegeres/data/actualites.json

# 3. Déployer
./deploy.sh --apply
```

---

## 8) Renouvellement du token (tous les 60 jours max)

Le PAGE_ACCESS_TOKEN Facebook est permanent (n'expire pas) si obtenu à partir d'un token utilisateur long-lived. Mais le token utilisateur intermediaire expire tous les 60 jours.

Si les posts ne se recuperent plus (erreur "OAuthException" dans les logs):
1. Retourner sur https://developers.facebook.com/tools/explorer
2. Régénérer un token utilisateur, re-faire l'échange (section 2.2 etapes 1 et 2)
3. Re-récuperer le Page Access Token (section 2.2 étape 3)
4. Mettre-à-jour PAGE_ACCESS_TOKEN dans fetch-actualites.php

---

## 9) Évolutions possibles (Niveau 2)

Si on veut que le JSON se régénère tout seul sans intervention manuelle:
- Mettre fetch-actualites.php sur le serveur dans un dossier hors webroot (ex: `/var/www/private/`)
- Le token est stocké dans un fichier de config hors webroot
- Un cron YunoHost appelle `php /var/www/private/fetch-actualites.php` toutes les 6h
- Ou: actualites.php vérifie l'âge du JSON et le régénère silencieusement si > 6h

Cette évolution nécessite de sécuriser le stockage du token sur le serveur.
