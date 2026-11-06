<?php require __DIR__ . '/../includes/header.php'; ?>
<main>
    <section>
        <div class="conteneur-champignons">
            <img src="<?php echo htmlspecialchars(site_path('/images/i_champignons.svg'), ENT_QUOTES, 'UTF-8');?>" class="champignons" alt="" loading="lazy">
            <h1>Projet pédagogique</h1>
        </div><!--fermeture balise .CONTENEUR-CHAMPIGNONS-->
        <div class="p_projet-pedagogique">
            <a class="image-wrapper morph" href="<?php echo htmlspecialchars(site_path('/theatre-d-ombres'), ENT_QUOTES, 'UTF-8');?>">
                <img src="<?php echo htmlspecialchars(site_path('/images/sp_theatre-d-ombres.png'), ENT_QUOTES, 'UTF-8');?>" width="200" alt="Le théâtre d'ombres - Grésilhette et la Salimonde">
            </a>
            <a class="image-wrapper morph" href="<?php echo htmlspecialchars(site_path('/sieste-contee'), ENT_QUOTES, 'UTF-8');?>" title="Voir la sieste contée">
                <img src="<?php echo htmlspecialchars(site_path('/images/sp_sieste-contee.png'), ENT_QUOTES, 'UTF-8');?>" width="200" alt="La sieste contée - Grésilhette et la Salimonde">
            </a>
            <a href="<?php echo htmlspecialchars(site_path('/projet-pedagogique'), ENT_QUOTES, 'UTF-8');?>" title="Voir le projet pédagogique">
                <img src="<?php echo htmlspecialchars(site_path('/images/sp_projet-pedagogique.png'), ENT_QUOTES, 'UTF-8');?>" width="200" alt="Le projet pédagogique - Grésilhette et la Salimonde">
            </a>
        </div><!--fermeture balise P_PROJET-PEDAGOGIQUE-->
        <p class="p_first">
            Le projet pédagogique accompagne les spectacles de la compagnie avec 
            des outils concrets pour préparer, prolonger et approfondir l'expérience artistique. 
            Il s'appuie sur l'expression orale, l'esprit critique, la coopération et la créativité.
        </p>
        <p>
            Ces propositions s'adressent aux établissements scolaires, aux structures jeunesse et aux 
            acteurs culturels de l'Aude et d'Occitanie. Elles peuvent prendre la forme d'ateliers, de 
            bord de scène ou de parcours sur mesure autour de l'écologie, du conte et du théâtre.
        </p>
        <div class="g_art-tech"> 
            <a href="#" title="Visualiser la fiche technique Projet pédagogique">
                <img alt="Fiche technique Projet Pédagogique de la compagnie Les Mégères de l'Humus" src="<?php echo htmlspecialchars(site_path('/images/btn-tech-projets.jpg'), ENT_QUOTES, 'UTF-8');?>" width="300" loading="lazy">
            </a>
        </div>
        <div class="conteneur-feuilles">
            <img class="feuilles" alt="" src="<?php echo htmlspecialchars(site_path('/images/i_feuilles.svg'), ENT_QUOTES, 'UTF-8');?>" loading="lazy">
        </div><!--fermeture balise .CONTENEUR-FEUILLES-->
    </section>
</main>
<?php require __DIR__ . '/../includes/footer.php'; ?>