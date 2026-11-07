<?php require __DIR__ . '/../includes/header.php'; ?>
<main>
    <section>
        <div class="conteneur-champignons">
            <img class="champignons" alt="" src="<?php echo htmlspecialchars(site_path('/images/i_champignons.svg'), ENT_QUOTES, 'UTF-8');?>" loading="lazy">
            <h1>Nos Partenaires</h1>
        </div><!--fermeture balise .CONTENEUR-CHAMPIGNONS-->

        <ul class="g_partenaires">
            <li class="partenaire-carte-pro">
                <img class="partenaire-lien-logo" src="<?php echo htmlspecialchars(site_path('/images/p_prefet-region-occitanie.png'), ENT_QUOTES, 'UTF-8');?>" alt="Drac-Occitanie"></a loading="lazy">
                <a class="partenaire-btn-pro" href="https://www.culture.gouv.fr/Regions/Drac-Occitanie" target="_blank" rel="noopener noreferrer">Visiter le site</a>
            </li>
            <li class="partenaire-carte-pro">
                <img class="partenaire-lien-logo" src="<?php echo htmlspecialchars(site_path('/images/p_fdva.png'), ENT_QUOTES, 'UTF-8');?>" alt="MEN - Fond pour le développement de la vie associative"></a loading="lazy">
                <a class="partenaire-btn-pro" href="https://www.education.gouv.fr" target="_blank" rel="noopener noreferrer">Visiter le site</a>
            </li>
            <li class="partenaire-carte-pro">
                <img class="partenaire-lien-logo" src="<?php echo htmlspecialchars(site_path('/images/p_aude.png'), ENT_QUOTES, 'UTF-8');?>" alt="Aude, le département"></a loading="lazy">
                <a class="partenaire-btn-pro" href="https://www.aude.fr" target="_blank" rel="noopener noreferrer">Visiter le site</a>
            </li>
            <li class="partenaire-carte-pro">
                <img class="partenaire-lien-logo" src="<?php echo htmlspecialchars(site_path('/images/p_carcassonne-agglo.png'), ENT_QUOTES, 'UTF-8');?>" alt="Agglomération de Carcassonne"></a loading="lazy">
                <a class="partenaire-btn-pro" href="https://www.carcassonne-agglo.fr/fr/index.html" target="_blank" rel="noopener noreferrer">Visiter le site</a>
            </li>
            <li class="partenaire-carte-pro">
                <img class="partenaire-lien-logo" src="<?php echo htmlspecialchars(site_path('/images/p_cdc-montagne-noire.png'), ENT_QUOTES, 'UTF-8');?>" alt="Communauté de Communnes de la Montagne Noire"></a loading="lazy">
                <a class="partenaire-btn-pro" href="https://www.cdcmontagnenoire.fr" target="_blank" rel="noopener noreferrer">Visiter le site</a>
            </li>
            <li class="partenaire-carte-pro">
                <img class="partenaire-lien-logo" src="<?php echo htmlspecialchars(site_path('/images/p_fsc-theatre.png'), ENT_QUOTES, 'UTF-8');?>" alt="Foyer Socio-Culturel de Moussoulens"></a loading="lazy">
                <a class="partenaire-btn-pro" href="https://www.moussoulens.fr/associations/foyer-socio-culturel-de-moussoulens" target="_blank" rel="noopener noreferrer">Visiter le site</a>
            </li>
            <li class="partenaire-carte-pro">
                <img class="partenaire-lien-logo" src="<?php echo htmlspecialchars(site_path('/images/p_blason-moussoulens.png'), ENT_QUOTES, 'UTF-8');?>" alt="La commune de Moussoulens"></a loading="lazy">
                <a class="partenaire-btn-pro" href="https://www.moussoulens.fr" target="_blank" rel="noopener noreferrer">Visiter le site</a>
            </li>
        </ul>

        <p>
            L'association <em>Les Mégères de l'Humus</em> reçoit, ou a reçu, depuis le début de sa création, 
            le soutien financier de plusieurs collectivités territoriales listées ci-dessus. Ces partenariats 
            soutiennent la création, la transmission et les actions artistiques menées auprès de différents 
            publics, notamment dans l'Aude. Ils renforcent l'ancrage local de l'association et la diffusion de 
            ses projets sur le territoire.
        </p>

        <div class="conteneur-feuilles">
            <img class="feuilles" alt="" src="<?php echo htmlspecialchars(site_path('/images/i_feuilles.svg'), ENT_QUOTES, 'UTF-8');?>" loading="lazy">
        </div><!--fermeture balise .CONTENEUR-FEUILLES-->
    </section>
</main>
<?php require __DIR__ . '/../includes/footer.php'; ?>
