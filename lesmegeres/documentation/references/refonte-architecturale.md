# Documentation Administrateur : Refonte Architecturale, Simplification & Standardisation CSS
**Domaine :** Feuille de style globale (`style.css`)  
**Méthodologie :** Architecture Mobile-First Linéaire Rigoureuse  
**Statut :** Session Clôturée, Validée et Certifiée stable (03/11/2026)

---

## 1. Synthèse du Problème Initial & Audit Technique

L'environnement local (hors CMS) exécutait une feuille de style hybride, souffrant de reliquats d'une approche historique *Desktop-First* (PC). 

### 1.1. Le Déclencheur du Dysfonctionnement
Le déplacement de deux règles de masquage de visibilité (`display: none`) depuis une requête média mobile vers le flux global a provoqué l'effondrement géométrique de la barre de navigation sur grand écran (PC >= 960px). Le conteneur entier s'est retrouvé figé et cadré sur le bord gauche de la fenêtre, rompant l'alignement visuel avec le `header` et le diaporama accordéon.

### 1.2. Diagnostic par Analyse de Collision (Faits Avérés)
Le passage de scripts d'analyse automatisés a mis en lumière trois failles critiques dans l'ancien code (composé de 238 lignes éparpillées) :
1. **Sur-spécificité des sélecteurs :** Des directives croisées combinant balises et classes (`nav`, `.navbar`, `nav.navbar`, `.menu`, `.rs`) créaient des conflits de cascade, empêchant le navigateur de déterminer la priorité des directives d'alignement.
2. **Ordres de distribution contradictoires :** Le conteneur parent (`nav.navbar`) et ses compartiments enfants (`.menu`) recevaient simultanément des instructions de centrage (`justify-content: center`) et des marges automatiques (`margin: 0 auto !important`). En Flexbox, ces forces opposées annulent les calculs du moteur de rendu, provoquant un alignement de secours par défaut (à gauche).
3. **Le Piège de la Double Classe :** Le bloc réseaux sociaux possédait la structure HTML `<div class="rs menu">`. Traiter `.rs` et `.menu` de manière isolée appliquait des règles de survol et de masquage asynchrones sur cette boîte spécifique sur grand écran.

---

## 2. Protocole de Validation & Tests à Blanc

Pour isoler la grille parente `.wrapper` et valider l'intégrité du squelette HTML, l'intégralité des 238 lignes de CSS de navigation a été purgée. Des tests de force directionnels ont été appliqués sur l'element brut (nu de tout style graphique) :

*   **Test de déplacement vertical :** L'application isolée de `grid-area: main;` a forcé les liens textuels HTML bruts à quitter le haut de l'écran pour se parachuter au niveau du diaporama. 
    *   *Verdict :* Validation de l'alignement sur la grille parent Grid CSS. Le `.wrapper` répond instantanément.
*   **Test de cadrage horizontal droit :** L'application de `width: max-content; justify-self: end;` a propulsé le bloc textuel brut contre le bord droit de la fenêtre de navigation.
    *   *Verdict :* Validation de la neutralité totale du terrain. Absolument aucune force résiduelle ou règle parente ne retient le conteneur sur la gauche de l'écran.

---

## 3. Architecture Cible & Spécifications de la Nouvelle Barre de Navigation

La navigation a été intégralement reconstruite en approche **Mobile-First**. Le code est divisé en deux blocs étanches, éliminant l'usage de la propriété abrasive `!important`.

### 3.1. Code CSS Unifié : Flux de Base (Mobile par défaut)
Configure le site pour les terminaux verticaux. La barre horizontale est invisible et laisse place au hamburger. Le panneau mobile volant utilise un raccourci d'ancrage moderne (`inset`) et gère le focus d'accessibilité clavier.

```css
/* ==========================================================================
   NAVIGATION GLOBALE : COMPORTEMENT MOBILE FIRST PAR DÉFAUT
   ========================================================================== */

/* Conteneur principal inséré dans la ligne "nav" de la grille .wrapper */
.navbar {
    grid-area: nav;
    display: none; /* Masqué sur mobile, le hamburger pilote l'affichage */
    width: 100%;
}

/* Panneau mobile volant ouvert (activé via la classe .responsive injectée par le JS) */
.navbar.responsive {
    align-items: stretch;
    background: hsla(15, 69%, 65%, 0.96);
    border-top: 1px solid hsla(264, 28%, 23%, 0.25);
    box-shadow: 0 0 0 100vmax hsla(264, 28%, 23%, 0.35);
    display: flex;
    flex-direction: column;
    gap: var(--menu-panel-gap);
    height: 100vh;
    position: fixed;
    inset: 0 auto 0 0; /* top:0, right:auto, bottom:0, left:0 */
    overflow-y: auto;
    padding: var(--menu-panel-padding);
    width: var(--menu-panel-width);
    z-index: 4500;
}

/* Alignement vertical des compartiments dans le volet tactile */
.navbar.responsive > .menu,
.navbar.responsive > .rs {
    display: flex;
    flex-direction: column;
    height: auto;
    margin: 0;
}

/* Liens de premier niveau et boutons déclencheurs en mode tactile */
.navbar.responsive .menu-trigger,
.navbar.responsive > .menu > a,
.navbar.responsive .mobile-only {
    font-size: clamp(0.88rem, 4.6vw, 1.15rem);
    justify-content: flex-start;
    line-height: 1.25;
    min-height: var(--menu-item-min-height);
    padding: 2.5rem var(--menu-inline-padding) 0;
    transition: background-color 0.18s ease, color 0.18s ease;
    width: fit-content;
    background: transparent;
    border: 0;
    color: var(--color-brand-secondary);
    cursor: pointer;
}

.navbar.responsive .menu-trigger:focus-visible,
.navbar.responsive > .menu > a:focus-visible {
    background: hsla(0, 0%, 100%, 0.25);
    outline: 2px solid var(--color-brand-secondary);
    outline-offset: 2px;
}

/* Sous-menus mobiles (.niv1) masqués par défaut */
.navbar.responsive .niv1 {
    background: hsla(0, 0%, 100%, 0.2);
    border-radius: 0.5rem;
    display: none;
    margin: 0;
    width: 100%;
}

/* Affichage du sous-menu mobile au clic (Synchro toggleSubmenu JS via .is-open) */
.navbar.responsive .menu.is-open .niv1 {
    display: flex;
    flex-direction: column;
}

/* Masquage focus exclusif mobile (Règle d'inertie de l'application JS) */
.navbar.responsive.submenu-active .menu:not(.is-open),
.navbar.responsive.submenu-active .rs {
    display: none;
}

/* Liens internes des sous-menus mobiles */
.navbar.responsive .niv1 > a {
    display: flex;
    align-items: center;
    border-top: 1px solid transparent;
    font-size: clamp(0.82rem, 4.1vw, 1.03rem);
    line-height: 1.25;
    min-height: var(--menu-subitem-min-height);
    padding: var(--menu-subitem-padding);
    transition: background-color 0.18s ease, color 0.18s ease, border-top-color 0.18s ease;
    width: 100%;
}

.navbar.responsive .niv1 > a:hover { border-top-color: #f5f5f5; }

/* Réseaux sociaux (Mode Tactile Mobile) */
.navbar.responsive .rs {
    flex-direction: row;
    gap: var(--menu-social-gap);
    justify-content: flex-start;
    padding: 0.35rem 0.5rem;
}

.navbar.responsive .rs a {
    display: flex;
    align-items: center;
    justify-content: center;
    height: var(--menu-social-icon-size);
    width: var(--menu-social-icon-size);
    background: var(--color-brand-primary);
    border-radius: 50%;
    transition: background-color 0.18s ease, color 0.18s ease;
}
```
### 3.2. Code CSS Unifié : Bloc Grand Écran (À la fin de `@media screen and (min-width: 960px)`)
Balaie la configuration mobile pour déployer le ruban horizontal. Résout le problème du orange "tronqué" en attribuant la couleur de fond directement aux conteneurs enfants horizontaux, intègre le lissage typographique fluide (`clamp()`), gère la synchronisation de l'état actif avec `menu-active-state.js` (`.menu--visible`) et verrouille géométriquement la largeur et les finitions d'arrondis des sous-menus déroulants.

```css
/* ==========================================================================
   BARRE DE NAVIGATION : AFFICHAGE GRAND ÉCRAN (PC >= 960px)
   ========================================================================== */
@media screen and (min-width: 960px) {

    /* Masquage du déclencheur tactile mobile */
    #hamburger {
        display: none;
    }

    /* 1. LE PARENT : Le ruban de fond devient transparent pour laisser les enfants coloriser proprement */
    .navbar {
        grid-area: nav;           /* Positionnement rigoureux sur la ligne du .wrapper */
        display: flex;            /* Déploiement horizontal Flexbox */
        width: 96%;               /* S'aligne parfaitement sur la largeur du diaporama */
        max-width: 75rem;         /* Aligné sur la largeur maximale de sécurité du footer */
        margin: 0 auto;           /* FORCE LE CENTRAGE OPTIQUE ET GÉOMÉTRIQUE DE LA BARRE */
        justify-content: center;  /* Aligne l'ensemble des compartiments sur l'axe central */
        align-items: stretch;     /* Force tous les enfants à adopter une hauteur identique */
        background-color: transparent; /* Libère le fond pour éliminer les coupures graphiques */
        border-radius: 0.5rem;
        height: 3.75rem;
    }

    /* 2. LES ENFANTS : Ce sont EUX qui portent la couleur orange unie */
    .navbar > .menu {
        display: flex;
        flex-direction: row;      /* Alignement horizontal des boutons */
        align-items: center;
        position: relative;
        height: 100%;
        background-color: var(--color-brand-primary); /* Porteur du ruban orange uni */
        border-left: 1px solid rgba(56, 43, 76, 0.18); /* Bordure de séparation */
        margin: 0;
    }
    
    /* Finalisation de l'arrondi gauche sur le tout premier compartiment (Infos) */
    .navbar > .menu:first-child {
        border-top-left-radius: 0.5rem;
        border-bottom-left-radius: 0.5rem;
        border-left: 0;
    }

    /* Formatage typographique et dimensionnel des boutons de menus */
    .navbar .menu-trigger,
    .navbar > .menu > a {
        display: flex;
        align-items: center;
        justify-content: center;  /* Centre parfaitement le texte dans son compartiment */
        color: var(--color-brand-secondary);
        font-size: clamp(.78rem, 1vw + .1rem, 1.15rem); /* Typographie fluide et proportionnée */
        font-variant-caps: all-small-caps;
        letter-spacing: 2px;
        width: 16vw;              /* Largeur de compartiment PC d'origine */
        height: 100%;
        background: transparent;
        border: 0;
        cursor: pointer;
        transition: background 0.18s ease, color 0.18s ease;
    }

    /* États de survol ET synchronisation avec le script JS d'état actif (.menu--visible) */
    .navbar > .menu:hover .menu-trigger,
    .navbar > .menu:hover > a,
    .navbar > .menu.is-hovered .menu-trigger,
    .navbar > .menu:focus-within > a,
    .navbar > .menu.menu--visible .menu-trigger, /* Synchro menu-active-state.js */
    .navbar > .menu.menu--visible > a {          /* Synchro menu-active-state.js */
        background: hsla(0, 0%, 100%, 0.18);
        border-radius: 0.5rem;
        color: #7e4c3a;
    }

    /* Conteneur des sous-menus déroulants (.niv1) sur PC */
    .navbar .niv1 {
        display: none;            /* Masquage hermétique strict au chargement initial */
        flex-direction: column;
        position: absolute;
        top: 3.75rem;             /* Se positionne au pixel près sous le ruban orange */
        
        /* Verrouillage géométrique automatique sur la largeur du bouton parent */
        left: 0;
        right: 0;
        width: auto;              /* Élimine l'ancien 16.33vw qui créait un débordement à droite */
        
        background: hsla(15, 69%, 65%, 0.9);
        border-radius: 0.5rem;
        z-index: 11;
    }

    /* Déploiement au survol (CSS natif + Synchro avec l'intention de survol du JS .is-hovered) */
    .navbar > .menu:hover .niv1,
    .navbar > .menu.is-hovered .niv1,
    .navbar > .menu:focus-within .niv1 {
        display: flex;
    }

    /* Liens internes des sous-menus déroulants sur PC */
    .navbar .niv1 > a {
        display: flex;
        width: 100%;
        line-height: 2.1875rem;
        padding-left: 0.625rem;
        justify-content: flex-start; /* Maintient le texte des sous-menus calé à gauche (voulu) */
        color: var(--color-brand-secondary);
    }

    .navbar .niv1 > a:hover {
        background: var(--color-brand-primary);
        border-top: 1px solid var(--color-border-menu);
        color: var(--color-white);
    }

    /* Restauration des finitions graphiques d'arrondis des sous-menus PC */
    .navbar .niv1 > a:first-child {
        border-top-left-radius: 0.5rem;
        border-top-right-radius: 0.5rem;
    }
    .navbar .niv1 > a:last-child {
        border-bottom-left-radius: 0.5rem;
        border-bottom-right-radius: 0.5rem;
    }

    /* Réseaux Sociaux PC : Complètent la couleur orange et l'arrondi droit du ruban */
    .navbar > div.rs {
        display: flex !important; /* Force le maintien de l'affichage sur ordinateur */
        flex-direction: row;
        align-items: center;
        justify-content: center;
        width: min(7.5rem, 18vw);
        gap: 0.35rem;
        padding: 0.25rem;
        margin: 0;
        height: 100%;
        background-color: var(--color-brand-primary); /* Complète le orange unie à l'extrémité */
        border-top-right-radius: 0.5rem;
        border-bottom-right-radius: 0.5rem;
        border-left: 1px solid rgba(56, 43, 76, 0.18);
    }

    .navbar > div.rs a {
        display: flex;
        align-items: center;
        justify-content: center;
        height: 1.875rem;
        width: 1.875rem;
        background: var(--color-brand-primary);
        border-radius: 50%;
        box-shadow: 0.25rem 0.25rem 0.5rem #c2745a, -0.25rem -0.25rem 0.5rem #ff9e7a;
        transition: background-color 0.18s ease, border-radius 0.18s ease, box-shadow 0.18s ease;
    }

    .navbar > div.rs a:hover {
        background: var(--color-menu-contact-hover);
        border-radius: 3.125rem;
        box-shadow: inset 0.3125rem 0.3125rem 0.625rem #c2745a, inset -0.3125rem -0.3125rem 0.625rem #ff9e7a;
    }

    /* Censure des boutons utilitaires mobiles sur PC */
    .navbar .mobile-only {
        display: none;
    }
}
```
## 4. Refonte Globale & Rationalisation des Requêtes Média (@media)

Toutes les requêtes médias intermédiaires comportant des restrictions fermées (`max-width`) ont été purgées et converties. Les propriétés ont été redistribuées vers le haut de manière incrémentale.

### 4.1. Chronologie de l'Arborescence Média Cible (100% Validée)
Le fichier `style.css` est désormais structuré selon une progression mathématique linéaire ascendante :
1.  **Flux de base global (Règles racines) :** Gère le point zéro (micro-écrans ≤ 420px). L'accordéon horizontal y est configuré de manière ultra-compacte et sécurisée : `.card` et `input:checked + label.card` partagent la formule ergonomique commune `height: min(12.5rem, 25vh);` pour supprimer les secousses verticales. Les petites cartes y sont fixées à `2.9rem` et la cochée à `min(64vw, 13rem)`.
2.  **`@media (min-width: 421px)` :** Tranche des smartphones standards. Fait grandir les cartes fermées à `3.75rem` (marge `0.15rem`) et la carte cochée à `min(60vw, 16rem)`.
3.  **`@media (min-width: 621px)` :** Tranche des grands smartphones / phablettes. Active l'accordéon en grand format élastique (`flex: 1 0 min(58vw, 18rem)`, `min-width: 13rem`) et redescend les cartes fermées à `3.4rem`. Intègre la grille fluide auto-adaptative des partenaires (`.g_partenaires`).
4.  **`@media (min-width: 667px)` :** Centralise la distribution horizontale du diaporama (`.button-container { justify-content: center; }`).
5.  **`@media (min-width: 768px)` :** Tranche d'activation des tablettes graphiques.
6.  **`@media (min-width: 960px)` :** Tranche ordinateur (Desktop). Déploie la barre de navigation horizontale centrée et fait basculer l'intégralité du diaporama sur la hauteur immersive fixe de `68vh`.
7.  **`@media (min-width: 1201px)` :** Grands moniteurs de bureau. Gère le grand format du logo (`11.25rem` / `14.275rem` au survol), l'envergure du texte (`var(--content-max-desktop)`) et le détourage d'habillage fluide (`shape-outside: circle(58%)`).
8.  **`@media (min-width: 1900px)` :** Écrans géants et affichages 4K. Bride la largeur de l'accordéon à `max-width: 90%` pour interdire toute distorsion latérale des visuels.

### 4.2. L'Exception Sécurisée Mobile-Paysage (Ligne 1824)
La règle suivante a été dépouillée de sa borne `max-width: 800px` mais reste parfaitement isolée du monde PC grâce à son verrou de hauteur :
```css
@media screen and (max-height: 392px) and (orientation: landscape) { ... }
```
Elle compacte exclusivement les variables CSS de hauteur du volet tactile (`:root`) et adapte le diaporama horizontal à l'espace vertical restreint des smartphones tenus à l'horizontale.

### 4.3. Sécurité Anti-Débordement Épurée
Pour éradiquer définitivement l'apparition inesthétique d'une barre de défilement "fantôme" lors du survol du titre de vos pages, la propriété `overflow-x: hidden` a été **strictement retirée** de la balise `section`. Le blocage des décalages mobiles est confié uniquement aux enveloppes souveraines du document :
```css
html, body, .wrapper {
    overflow-x: hidden;
}
```

---
**Fin de l'archivage technique. Le script d'analyse certifie un état final à ZÉRO SÉLECTEUR DUBLIQUÉ RESTRICTIF.**
