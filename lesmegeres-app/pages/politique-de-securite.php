<?php require __DIR__ . '/../includes/header.php'; ?>
<main>
    <section>
        <div class="conteneur-champignons">
            <img class="champignons" alt="" src="<?php echo htmlspecialchars(site_path('/images/i_champignons.svg'), ENT_QUOTES, 'UTF-8');?>" loading="lazy">
            <h1>Politique de sécurité</h1>
        </div><!--fermeture balise .CONTENEUR-CHAMPIGNONS-->

        <p>Merci de signaler toute vulnérabilité de sécurité à l'adresse 
            <a data-type="external" href="mailto:security@lesmegeresdelhumus.fr">security@lesmegeresdelhumus.fr</a>
            <span class="lien-e-seul"></span>.
        </p>
        <p>Nous vous remercions de ne pas publier publiquement la vulnérabilité avant que nous ayons 
            eu le temps d'y répondre.
        </p>

        <h2>Ce que nous demandons</h2>
        <ul class="jolie-ombres">
            <li>de ne pas perturber les services ou les données</li>
            <li>de ne pas accéder à davantage de données que nécessaire</li>
            <li>de ne pas modifier ou supprimer des contenus sans autorisation</li>
            <li>de nous fournir un rapport détaillé avec les étapes de reproduction</li>
        </ul>

        <h2>Ce que vous pouvez attendre de nous</h2>
        <ul class="jolie-ombres">
            <li>une confirmation rapide de réception</li>
            <li>une prise en compte sérieuse du signalement</li>
            <li>une réponse raisonnable dans un delai adapté</li>
        </ul>

        <div class="conteneur-feuilles">
            <img class="feuilles" alt="" src="<?php echo htmlspecialchars(site_path('/images/i_feuilles.svg'), ENT_QUOTES, 'UTF-8');?>" loading="lazy">
        </div><!--fermeture balise .CONTENEUR-FEUILLES-->
    </section>
</main>
<?php require __DIR__ . '/../includes/footer.php'; ?>