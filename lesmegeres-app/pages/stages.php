<?php require __DIR__ . '/../includes/header.php'; ?>
<main>
    <section>
        <div class="conteneur-champignons">
            <img src="<?php echo htmlspecialchars(site_path('/images/i_champignons.svg'), ENT_QUOTES, 'UTF-8');?>" class="champignons" alt="" loading="lazy">
            <h1>Stages de théâtre 2025-2026</h1>
        </div><!--fermeture balise .CONTENEUR-CHAMPIGNONS-->
        <div>
            <div class="img-centre">
                <picture>
                    <source srcset="<?php echo htmlspecialchars(site_path('/images/stages.webp'), ENT_QUOTES, 'UTF-8');?>" type="image/webp">
                    <img class="photo-stages-ateliers" src="<?php echo htmlspecialchars(site_path('/images/stages.jpg'), ENT_QUOTES, 'UTF-8');?>" alt="Un stage de théâtre de la Compagnie Les Mégères de l'Humus" loading="lazy">
                </picture>
            </div>
            <p>Ces stages sont pensés pour progresser en confiance, découvrir différents langages scéniques et vivre une aventure collective. La compagnie 
                intervient auprès de publics débutants ou déjà pratiquants. Pour connaître les prochaines dates, les lieux d'accueil et les modalités 
                d'inscription autour de Carcassonne et de Moussoulens, rendez-vous sur la page <a data-type="internal" title="Accès à notre page Contact"
                href="<?php echo htmlspecialchars(site_path('/contact'), ENT_QUOTES, 'UTF-8');?>">Contact</a>.
            </p>
        </div>
        <div class="img-centre">
            <picture>
                <source srcset="<?php echo htmlspecialchars(site_path('/images/les-stages-recto.webp'), ENT_QUOTES, 'UTF-8');?>" type="image/webp">
                <img class="affiche-stage" src="<?php echo htmlspecialchars(site_path('/images/les-stages-recto.jpg'), ENT_QUOTES, 'UTF-8');?>" alt="Le tarif des stages de théâtre" loading="lazy">
            </picture>
            <picture>
                <source srcset="<?php echo htmlspecialchars(site_path('/images/les-stages-verso.webp'), ENT_QUOTES, 'UTF-8');?>" type="image/webp">
                <img class="affiche-stage" src="<?php echo htmlspecialchars(site_path('/images/les-stages-verso.jpg'), ENT_QUOTES, 'UTF-8');?>" alt="Les objectifs pédagogiques des stages de théâtre" loading="lazy">
            </picture>
        </div>

        <div class="conteneur-feuilles">
            <img src="<?php echo htmlspecialchars(site_path('/images/i_feuilles.svg'), ENT_QUOTES, 'UTF-8');?>" class="feuilles" alt="" loading="lazy">
        </div><!--fermeture balise .CONTENEUR-FEUILLES-->
    </section>
</main>
<?php require __DIR__ . '/../includes/footer.php'; ?>