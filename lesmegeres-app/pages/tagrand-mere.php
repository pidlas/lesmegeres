<?php require __DIR__ . '/../includes/header.php'; ?>
<main>
    <section>
        <div class="conteneur-champignons">
            <img src="<?php echo htmlspecialchars(site_path('/images/i_champignons.svg'), ENT_QUOTES, 'UTF-8');?>" class="champignons" alt="" loading="lazy">
            <h1>Ta grand-mère sous le figuier</h1>
        </div><!--fermeture balise .CONTENEUR-CHAMPIGNONS-->

        <div class="wrapper-audio">
            <!-- Balise audio native masquée au profit de nos contrôles -->
            <audio id="audio" preload="metadata">
                <source src="<?php echo htmlspecialchars(site_path('/multimedia/spectacle5EHPAD.mp3'), ENT_QUOTES, 'UTF-8');?>" type="audio/mpeg" />
                <source src="<?php echo htmlspecialchars(site_path('/multimedia/spectacle5EHPAD.ogg'), ENT_QUOTES, 'UTF-8');?>" type="audio/ogg" />
                <a href="<?php echo htmlspecialchars(site_path('/multimedia/spectacle5EHPAD.mp3'), ENT_QUOTES, 'UTF-8');?>"> Télécharger l'audio </a>
                Votre navigateur ne prend pas en charge la lecture audio.
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
        </div>

        <p>Le spectacle <em>Ta grand-mère sous le figuier</em> est le tout premier projet de la 
            compagnie.
        </p>
        <p>Sabine et Suzie décident de découvrir leur nouvelle terre d'accueil qu’est l’Aude. 
            Elles se sont promenées dans ses forêts, baignées dans ses rivières et ont visité ses 
            villages. C’est comme ça qu’elles se sont rendues compte qu’il y avait un fort pourcentage 
            de personnes âgé&middot;e&middot;s et isolé&middot;e&middot;s, notamment des femmes.
        </p>
        <div class="lampe-wrapper">
            <img class="forme-lampe" alt="Lampe et horloge, les contes occitans" src="<?php echo htmlspecialchars(site_path('/images/lampe-horloge.webp'), ENT_QUOTES, 'UTF-8');?>" loading="lazy">
            <p>
                Sous le prisme du féminisme, une des valeurs phare de la compagnie, Sabine et Suzie sont donc 
                parties à la rencontre des vieilles dames de plus de 80 ans pour récolter leur témoignage de 
                vie et enregistrer leur voix dans le but de créer un spectacle en leur honneur.
                Nous tenons à faire écouter les paroles des femmes en priorité car elles ont trop souvent 
                été silenciées. Nous leur rendons la place centrale en leur offrant un espace unique d’expression. 
                À partir de l’été 2022, les deux comédiennes ont interviewé 22 dames. Pour cela la compagnie 
                est en étroite collaboration avec l’<em>Ehpad Saint-Vincent-de-Paul</em> à Montolieu depuis 
                maintenant 3 ans. Une séance dure environ 1h. Nous nous déplaçons chez la personne accompagnées de notre micro. 
                Nous lui faisons signer une autorisation de droit à l’enregistrement et à l’image et nous commençons 
                l’entretien. Nous guidons la personne avec une liste de questions que nous avons peaufiné avec le temps.
            </p>
        </div>
        <ul class="jolie-ombres slide-list">
            <li>Avez-vous un surnom&nbsp;?</li>
            <li>Partiez-vous en vacances quand vous étiez enfant&nbsp;?</li>
            <li>Quels étaient les métiers de vos parents&nbsp;?</li>
            <li>Et le vôtre&nbsp;?</li>
            <li>Quelles bêtises faisiez-vous quand vous étiez adolescente&nbsp;?</li>
            <li>Vous souvenez-vous d’un moment en particulier où vous avez ressenti un bonheur intense&nbsp;?</li>
            <li>Voulez-vous nous le partager&nbsp;? etc.</li>
        </ul>
        <div class="texte">
            <p>Nous finissons l’entretien par une petite séance photo de la personne. Nous offrons les photos 
                aux dames et/ou à leur famille pour qu’iels aient un joli souvenir de ce moment convivial.
            </p>
            <p>Dans notre démarche artistique il y a toute une dimension de soin. Pendant <span class="nombre">1</span>h 
                nous nous rendons entièrement disponible. C’est plus qu’un témoignage, c’est un vrai temps 
                de rencontre. Toutes les femmes que nous avons enregistrées nous ont exprimées leur joie d’avoir 
                passé des moments intimes et d'empouvoirement en notre compagnie. Elles pouvaient enfin déposer 
                leurs expériences de vie en étant considérées et valorisées.
            </p>
            <p>Nous sommes toujours à la recherche de nouvelles rencontres. Plus nous avons de témoignages, 
                plus notre propos sera juste et universel. Si vous connaissez des dames âgées susceptibles d'être intéressées par notre projet, n'hésitez 
                pas à nous <a data-type="internal" href="<?php echo htmlspecialchars(site_path('/contact'), ENT_QUOTES, 'UTF-8');?>" 
                alt="Pour contacter les Mégères de l'Humus">contacter</a><span class="lien-i-seul"></span>.
            </p>
        </div>
    <div class="conteneur-feuilles">
        <img src="<?php echo htmlspecialchars(site_path('/images/i_feuilles.svg'), ENT_QUOTES, 'UTF-8');?>" class="feuilles" alt="" loading="lazy">
    </div><!--fermeture balise .CONTENEUR-FEUILLES-->
    </section>
</main>
<?php require __DIR__ . '/../includes/footer.php'; ?>