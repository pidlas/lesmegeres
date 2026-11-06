<?php require __DIR__ . '/../includes/header.php'; ?>
<main>
    <section>
        
        <div class="conteneur-champignons">
            <img class="champignons" alt="" src="<?php echo htmlspecialchars(site_path('/images/i_champignons.svg'), ENT_QUOTES, 'UTF-8');?>" loading="lazy">
            <h1>Ateliers de théâtre</h1>
        </div><!--fermeture balise .CONTENEUR-CHAMPIGNONS-->
       
        <div class="img-centre">
            <picture>
                <source srcset="<?php echo htmlspecialchars(site_path('/images/ateliers.webp'), ENT_QUOTES, 'UTF-8');?>" type="image/webp">
                <img class="photo-stages-ateliers" src="<?php echo htmlspecialchars(site_path('/images/ateliers.jpg'), ENT_QUOTES, 'UTF-8');?>" alt="Un atelier de théâtre de la Compagnie Les Mégères de l'Humus" loading="lazy">
            </picture>
        </div>

        <p class="p_first">
            Parmis les prestations, les ateliers des <em>Mégères de l'Humus</em> sont conçus comme des espaces 
            de pratique, d'expression et de création collective. Ces ateliers peuvent s'adresser aux enfants, 
            aux adolescent·e·s, aux adultes ou à des groupes intergénérationnels selon les projets.
        </p>
        <p>
            À travers le jeu, l'improvisation, l'écoute et la prise de parole, la compagnie propose des formats 
            adaptés aux contextes scolaires, associatifs, culturels ou territoriaux.
        </p>
        <p>
            Ces ateliers sont personnalisés en fonction de vos attentes et de vos objectifs. N'hésitez pas à nous 
            <a data-type="internal" href="<?php echo htmlspecialchars(site_path('/contact'), ENT_QUOTES, 'UTF-8'); ?>">contacter</a><span class="lien-i-seul"></span>&nbsp;!
        </p>
        
        <div class="conteneur-feuilles">
            <img class="feuilles" alt="" src="<?php echo htmlspecialchars(site_path('/images/i_feuilles.svg'), ENT_QUOTES, 'UTF-8');?>" loading="lazy">
        </div><!--fermeture balise .CONTENEUR-FEUILLES-->

    </section>
</main>
<?php require __DIR__ . '/../includes/footer.php'; ?>