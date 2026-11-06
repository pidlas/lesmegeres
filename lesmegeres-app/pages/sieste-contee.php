<?php require __DIR__ . '/../includes/header.php'; ?>
<main>
    <section>
        <div class="conteneur-champignons">
            <img src="<?php echo htmlspecialchars(site_path('/images/i_champignons.svg'), ENT_QUOTES, 'UTF-8');?>" class="champignons" alt="" loading="lazy">
            <h1>Sieste Contée</h1>
        </div><!--fermeture balise .CONTENEUR-CHAMPIGNONS-->

        <div class="p_sieste-contee">
            <a class="image-wrapper morph" href="<?php echo htmlspecialchars(site_path('/theatre-d-ombres'), ENT_QUOTES, 'UTF-8');?>">
                <img src="<?php echo htmlspecialchars(site_path('/images/sp_theatre-d-ombres.png'), ENT_QUOTES, 'UTF-8');?>" width="200" alt="Le théâtre d'ombres - Grésilhette et la Salimonde">
            </a>
            <a href="<?php echo htmlspecialchars(site_path('/sieste-contee'), ENT_QUOTES, 'UTF-8');?>" title="Voir la sieste contée">
                <img src="<?php echo htmlspecialchars(site_path('/images/sp_sieste-contee.png'), ENT_QUOTES, 'UTF-8');?>" width="200" alt="La sieste contée - Grésilhette et la Salimonde">
            </a>
            <a class="image-wrapper morph" href="<?php echo htmlspecialchars(site_path('/projet-pedagogique'), ENT_QUOTES, 'UTF-8');?>" title="Voir le projet pédagogique">
                <img src="<?php echo htmlspecialchars(site_path('/images/sp_projet-pedagogique.png'), ENT_QUOTES, 'UTF-8');?>" width="200" alt="Le projet pédagogique - Grésilhette et la Salimonde">
            </a>
        </div><!--fermeture balise P_SIESTE-CONTEE-->
        <p class="p_first">La sieste contée propose une expérience douce, sensible et immersive à 
            partir de l'univers de <em>Grésilhette et la Salimonde</em>. Cette forme privilégie 
            l'écoute, le rythme et l'imaginaire, dans une ambiance propice au relâchement et à la 
            rêverie.
        </p>
        <p>Elle peut être programmée en bibliothèque, en école, en festival ou en structure associative 
            sur le territoire audois et en Occitanie et plus largement. Pour organiser une séance adaptée 
            à votre public, vous pouvez nous <a data-type="external" href="mailto:compagnie@lesmegeresdelhumus.fr?subject=Message du site, page Sieste Contée">écrire</a><span class="lien-e-seul"></span>.
        </p>
        <picture>
            <!--version webp-->
            <source type="image/webp"
                srcset="
                    <?php echo htmlspecialchars(site_path('/images/sigean-400w.webp 400w'), ENT_QUOTES, 'UTF-8');?>,
                    <?php echo htmlspecialchars(site_path('/images/sigean-800w.webp 800w'), ENT_QUOTES, 'UTF-8');?>,
                    <?php echo htmlspecialchars(site_path('/images/sigean-1200w.webp 1200w'), ENT_QUOTES, 'UTF-8');?>"
                sizes="
                    (max-width:400px) 33vw,
                    (max-width:800px) 50vw,
                    100vw">
            <!--version jpeg-->
            <source type="image/jpeg"
                srcset="
                    <?php echo htmlspecialchars(site_path('/images/sigean-400w.jpg 400w'), ENT_QUOTES, 'UTF-8');?>,
                    <?php echo htmlspecialchars(site_path('/images/sigean-800w.jpg 800w'), ENT_QUOTES, 'UTF-8');?>,
                    <?php echo htmlspecialchars(site_path('/images/sigean-1200w.jpg 1200w'), ENT_QUOTES, 'UTF-8');?>"
                sizes="
                    (max-width:400px) 33vw,
                    (max-width:800px) 50vw,
                    100vw">
            <!--fallback-->        
            <img class="sigean-sieste-contee" alt="Le théâtre d'ombres - Grésilhette et la Salimonde" src="<?php echo htmlspecialchars(site_path('/images/sigean-1200w.jpg'), ENT_QUOTES, 'UTF-8');?>" loading="lazy">
        </picture>
        <div class="g_art-tech"> 
            <a target="_blank" rel="noopener noreferrer" href="<?php echo htmlspecialchars(site_path('/pdf/fiche-sieste-contee.pdf'), ENT_QUOTES, 'UTF-8');?>" title="Visualiser la fiche technique Sieste Contée">
                <img alt="Fiche technique Sieste Contée de la compagnie Les Mégères de l'Humus" src="<?php echo htmlspecialchars(site_path('/images/btn-tech-sieste.jpg'), ENT_QUOTES, 'UTF-8');?>" width="300" loading="lazy">
            </a>
        </div>
        <div class="conteneur-feuilles">
            <img class="feuilles" alt="" src="<?php echo htmlspecialchars(site_path('/images/i_feuilles.svg'), ENT_QUOTES, 'UTF-8');?>" loading="lazy">
        </div><!--fermeture balise .CONTENEUR-FEUILLES-->
    </section>
</main>
<?php require __DIR__ . '/../includes/footer.php'; ?>