<?php require __DIR__ . '/../includes/header.php'; ?>
<main>
    <section>
        <div class="conteneur-champignons">
            <img class="champignons" alt="" src="<?php echo htmlspecialchars(site_path('/images/i_champignons.svg'), ENT_QUOTES, 'UTF-8');?>" loading="lazy">
            <h1>Cours de théâtre 2026-2027</h1>
        </div><!--fermeture balise .CONTENEUR-CHAMPIGNONS-->
        <div class="wrapper-audio">
            <audio id='audio' preload="metadata">
                <source src="<?php echo htmlspecialchars(site_path('/multimedia/les-enfants-parlent.mp3'), ENT_QUOTES, 'UTF-8');?>" type="audio/mpeg" />
                <source src="<?php echo htmlspecialchars(site_path('/multimedia/les-enfants-parlent.ogg'), ENT_QUOTES, 'UTF-8');?>" type="audio/ogg" />
                <a href="<?php echo htmlspecialchars(site_path('/multimedia/les-enfants-parlent.mp3'), ENT_QUOTES, 'UTF-8');?>"> Télécharger l'audio </a>
            </audio>
            <!-- Colonne 1 : Bouton de lecture / pause -->
            <button id="audioPlayPauseBtn" class="audio-control-btn" aria-label="Lecture/Pause">
                <svg class="play-icon" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                <svg class="pause-icon" viewBox="0 0 24 24"><path d="M6 19h4V5H6v14zm8-14v14h4V5h-4z"/></svg>
            </button>

            <!-- Colonne 2 : Affichage du compteur de temps -->
            <span id="audioTime" class="audio-time-display">0:00 / 0:00</span>
     
            <!-- Colonne 3 : Barre de progression (Optimisée tactile) -->
            <input type="range" id="audioProgress" min="0" max="100" value="0" step="0.1">
    
            <!-- Colonne 4 : Zone Volume : Bouton + Curseur rétractable -->
            <div class="audio-volume-zone">
                <button id="audioMuteBtn" class="audio-control-btn" aria-label="Mute/Unmute">
                    <svg class="sound-icon" viewBox="0 0 24 24"><path d="M3 9v6h4l5 5V4L7 9H3zm13.5 3c0-1.77-1.02-3.29-2.5-4.03v8.05c1.48-.73 2.5-2.25 2.5-4.02zM14 3.23v2.06c2.89.86 5 3.54 5 6.71s-2.11 5.85-5 6.71v2.06c4.01-.91 7-4.49 7-8.77s-2.99-7.86-7-8.77z"/></svg>
                    <svg class="mute-icon" viewBox="0 0 24 24"><path d="M16.5 12c0-1.77-1.02-3.29-2.5-4.03v2.21l2.45 2.45c.03-.21.05-.42.05-.63zm2.5 0c0 .94-.2 1.82-.54 2.64l1.51 1.51C20.63 14.91 21 13.5 21 12c0-4.28-2.99-7.86-7-8.77v2.06c2.89.86 5 3.54 5 6.71zM4.27 3L3 4.27 7.73 9H3v6h4l5 5v-6.73l4.25 4.25c-.67.52-1.42.93-2.25 1.18v2.06c1.38-.31 2.63-.95 3.69-1.81L19.73 21 21 19.73l-9-9L4.27 3zM12 4L9.91 6.09 12 8.18V4z"/></svg>
                </button>
                <input type="range" id="audioVolumeSlider" min="0" max="1" step="0.05" value="1" aria-label="Volume">
            </div>
        </div><!--fermeture de la balise WRAPPER-AUDIO-->
        <div>
            <p>La compagnie organise des cours pour faire découvrir le théâtre aux curieuses et curieux. Dans 
                ces cours toutes les dimensions du travail de la scène sont abordées : le déplacement, la prise de parole, le 
                jeu seul et à plusieurs. Les professeur&middot;e&middot;s sont des professionnel&middot;le&middot;s du métier 
                et abordent les cours avec bienveillance et sérieux. Pour toutes informations complémentaires <a data-type="internal" 
                href="<?php echo htmlspecialchars(site_path('/contact'), ENT_QUOTES, 'UTF-8'); ?>">contactez-nous ici</a><span class="lien-i-seul"></span>.</span>
            </p>
        </div>
        <div class="img-centre">
            <img class="affiche-cours" alt="Le tarif des cours de théâtre" src="<?php echo htmlspecialchars(site_path('/images/les-cours.webp'), ENT_QUOTES, 'UTF-8');?>" loading="lazy">
            <img class="photo-cours" alt="Photo d'un cours de théâtre" src="<?php echo htmlspecialchars(site_path('/images/photo-cours.webp'), ENT_QUOTES, 'UTF-8');?>" loading="lazy">
        </div><!--fermeture balise IMG-CENTRE-->
        <h2 class="display-title">Les objectifs pédagogiques</h2>
        <div class="img-centre">
            <img class="objectifs-pedagogiques" alt="Les objectifs pédagogiques du cours de théâtre" src="<?php echo htmlspecialchars(site_path('/images/objectifs-des-cours.webp'), ENT_QUOTES, 'UTF-8');?>" loading="lazy">
        </div><!--fermeture balise .IMG-CENTRE-->
        <div class="conteneur-feuilles">
            <img class="feuilles" alt="" src="<?php echo htmlspecialchars(site_path('/images/i_feuilles.svg'), ENT_QUOTES, 'UTF-8');?>" loading="lazy">
        </div><!--fermeture balise .CONTENEUR-FEUILLES-->
    </section>
</main>
<?php require __DIR__ . '/../includes/footer.php'; ?>