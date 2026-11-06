<?php require __DIR__ . '/../includes/header.php'; ?>
<main>
  <section>

        <div class="conteneur-champignons">
            <img class="champignons" alt="" src="<?php echo htmlspecialchars(site_path('/images/i_champignons.svg'), ENT_QUOTES, 'UTF-8');?>" loading="lazy">
            <h1>Marie Diel</h1>
        </div><!--fermeture balise .CONTENEUR-CHAMPIGNONS-->

        <article class="g_bio">
            <div class="e_photo"><!-- Élément 1 de la grille-->
                <img class="fade-zoom" onload="this.classList.add('loaded')" src="<?php echo htmlspecialchars(site_path('/images/webRigolote_md.jpg'), ENT_QUOTES, 'UTF-8');?>" alt="Marie Diel, Trésorière">
            </div><!-- balise fermeture E_PHOTO-->
            
            <div class="e_debut_bio"><!-- Élément 2 de la grille-->
                <p>Originaire de Carcassonne, Marie a vécu 19 ans en Angleterre de 2001 à 2020. Elle y a passé un Bachelor Degree en Arts et Médias et a travaillé 
                    comme professeure de français et éducatrice spécialisée auprès d’enfants en difficulté.
                </p>
                <p>
                    Aujourd’hui Marie est rentrée en France. Apprentie naturopathe, elle est salariée dans une Biocoop et s’occupe du rayon Bien-être. 
                    Elle a créé son entreprise <a data-type="external" href="https://fr.mademoiselleyogi.com" 
                    target="_blank" rel="noopener noreferrer">Mademoiselle Yogi</a><span class="lien-e-seul"></span> avec laquelle elle donne des 
                    cours de yoga. C’est la reine du up-cycling et du bricolage&nbsp;! Elle rénove et décore toute seule sa maison et c’est très 
                    joli&nbsp;! Spécialisée dans la cuisine végétalienne, elle propose ses services en qualité de traiteur pour des évènements locaux 
                    et bio. C’est aussi une jardinière autodidacte.
                </p>
                <figure class="bio">
                    <blockquote>
                        <p class="theseasonsbold">&ldquo;&nbsp;L'essentiel c'est d'embrasser 
                            l'entièreté de ce que nous sommes, à la fois notre part d'ombre et 
                            nos lumières.&nbsp;&rdquo;</p>
                    </blockquote>
                    <figcaption>
                        <span class="theseasonsregular">Ne marche pas si tu peux danser, Anne Van Stappen</span>
                    </figcaption>
                </figure>
            </div><!-- balise fermeture E_DEBUT_BIO-->
                
            <div class="e_fin_bio"><!--Élément 3 de la grille-->
                <p>Marie est une vraie petite abeille travailleuse, elle s’intéresse à tout, butine par-ci, par-là, elle est inarrêtable et passionnée&nbsp;!</p>
            </div><!--balise fermeture E_FIN_BIO-->
        </article>
  </section>
</main>
<?php require __DIR__ . '/../includes/footer.php'; ?>