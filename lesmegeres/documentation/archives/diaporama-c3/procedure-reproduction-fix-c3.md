# Procedure reproductible - correction des bandes blanches laterales (cas c3)

Date: 2026-07-18

## Objectif

Fournir une methode reutilisable pour corriger un rendu "blanc lateral" sur une carte de diaporama quand le CSS semble correct, mais que le probleme persiste.

Cette procedure est valable dans d'autres contextes (autres images, autres pages) tant que le rendu repose sur `background-image` + `background-size: cover`.

## Symptome cible

- Une carte/slide affiche des bandes tres claires a gauche et/ou a droite.
- Les autres cartes fonctionnent normalement.
- Les ajustements CSS (`background-position`, `cover`, etc.) ameliorent partiellement mais ne resolvent pas completement.

## Cause reelle identifiee

Dans ce cas, le fichier image source contenait deja des bords tres clairs. Le navigateur affichait donc ces bords, ce qui ressemblait a un "bug CSS" alors que le probleme etait majoritairement dans le contenu image.

## Procedure pas-a-pas

### 1) Sauvegarder les fichiers image avant intervention

```bash
cd /var/www/public/images
cp -a p_ta-grand-mere-sous-le-figuier.jpg  p_ta-grand-mere-sous-le-figuier.jpg.pre-c3fix-YYYYMMDD
cp -a p_ta-grand-mere-sous-le-figuier.webp p_ta-grand-mere-sous-le-figuier.webp.pre-c3fix-YYYYMMDD
```

### 2) Mesurer objectivement les bords de l'image

Mesure de luminance des bandes de 6 px a gauche/droite (0 = noir, 1 = blanc):

```bash
img=/var/www/public/images/p_ta-grand-mere-sous-le-figuier.jpg
for side in left right; do
  if [[ "$side" == "left" ]]; then crop='6x675+0+0'; else crop='6x675+1194+0'; fi
  convert "$img" -crop "$crop" -colorspace Gray -format "%[fx:mean]" info: \
    | awk -v s="$side" '{printf("%s_mean=%0.4f\\n", s, $1)}'
done
```

Interpretation rapide:

- Si les valeurs sont proches de `1.0`, les bords sont quasi blancs dans l'image.
- Si elles sont plus basses, les bords contiennent de la matiere visuelle.

### 3) Appliquer un recadrage centre progressif

Commencer par un recadrage leger, puis augmenter seulement si necessaire.

Exemple applique (version "douce"):

```bash
cd /var/www/public/images
convert p_ta-grand-mere-sous-le-figuier.jpg.pre-c3fix-YYYYMMDD \
  -gravity center -crop 1188x675+0+0 +repage \
  -resize 1200x675! -quality 92 \
  p_ta-grand-mere-sous-le-figuier.jpg

convert p_ta-grand-mere-sous-le-figuier.jpg -quality 88 p_ta-grand-mere-sous-le-figuier.webp
```

Option plus forte (si blanc persistant):

- passer de `1188x675` a `1140x675`, puis `1080x675`.
- verifier visuellement que les sujets en bordure ne sont pas trop coupes.

### 4) Re-mesurer les bords apres recadrage

```bash
for img in /var/www/public/images/p_ta-grand-mere-sous-le-figuier.jpg /var/www/public/images/p_ta-grand-mere-sous-le-figuier.webp; do
  echo "[$img]"
  for side in left right; do
    if [[ "$side" == "left" ]]; then crop='6x675+0+0'; else crop='6x675+1194+0'; fi
    convert "$img" -crop "$crop" -colorspace Gray -format "%[fx:mean]" info: \
      | awk -v s="$side" '{printf("%s_mean=%0.4f\\n", s, $1)}'
  done
  echo
done
```

Critere pratique:

- les valeurs doivent etre nettement inferieures a la mesure initiale proche de blanc,
- et l'equilibre visuel doit etre valide en navigation reelle.

### 5) Valider en front

1. Vider le cache navigateur (rechargement force).
2. Ouvrir la page d'accueil.
3. Ouvrir la carte c3 et verifier:
   - disparition des bandes blanches,
   - conservation des visages/sujets aux bords.

## Checklist de reutilisation (autre contexte)

1. Verifier d'abord le CSS (position, cover, overflow).
2. Mesurer les bords de l'image pour confirmer/refuter l'hypothese image.
3. Travailler par recadrages progressifs et reversibles.
4. Garder des backups nommes par date.
5. Regenerer tous les formats servis (JPG/WebP/AVIF selon projet).
6. Refaire un test visuel + un test metrique.

## Rollback

```bash
cd /var/www/public/images
cp -a p_ta-grand-mere-sous-le-figuier.jpg.pre-c3fix-YYYYMMDD  p_ta-grand-mere-sous-le-figuier.jpg
cp -a p_ta-grand-mere-sous-le-figuier.webp.pre-c3fix-YYYYMMDD p_ta-grand-mere-sous-le-figuier.webp
```
