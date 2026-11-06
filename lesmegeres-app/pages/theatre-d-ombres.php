<?php require __DIR__ . '/../includes/header.php'; ?>
<main>
    <section>
        <div class="conteneur-champignons">
            <img src="<?php echo htmlspecialchars(site_path('/images/i_champignons.svg'), ENT_QUOTES, 'UTF-8');?>" class="champignons" alt="" loading="lazy">
            <h1>Théâtre d'ombres</h1>
        </div><!--fermeture balise .CONTENEUR-CHAMPIGNONS-->
            
        <div class="p_theatre-d-ombres">
            <a href="<?php echo htmlspecialchars(site_path('/theatre-d-ombres'), ENT_QUOTES, 'UTF-8');?>">
                <img src="<?php echo htmlspecialchars(site_path('/images/sp_theatre-d-ombres.png'), ENT_QUOTES, 'UTF-8');?>" width="200" alt="Le théâtre d'ombres - Grésilhette et la Salimonde">
            </a>
            <a class="image-wrapper morph" href="<?php echo htmlspecialchars(site_path('/sieste-contee'), ENT_QUOTES, 'UTF-8');?>" title="Voir la sieste contée">
                <img src="<?php echo htmlspecialchars(site_path('/images/sp_sieste-contee.png'), ENT_QUOTES, 'UTF-8');?>" width="200" alt="La sieste contée - Grésilhette et la Salimonde">
            </a>
            <a class="image-wrapper morph" href="<?php echo htmlspecialchars(site_path('/projet-pedagogique'), ENT_QUOTES, 'UTF-8');?>" title="Voir le projet pédagogique">
                <img src="<?php echo htmlspecialchars(site_path('/images/sp_projet-pedagogique.png'), ENT_QUOTES, 'UTF-8');?>" width="200" alt="Le projet pédagogique - Grésilhette et la Salimonde">
            </a>
        </div><!--fermeture balise P_THEATRE-D-OMBRES-->
       
        <div class="montage-wrapper">
            <h2 class="display-title">Nos sources d'inspiration</h2>
            <img class="forme-montage" alt="Photos donnant de l'inspiration" src="<?php echo htmlspecialchars(site_path('/images/montage-inspiration.jpg'), ENT_QUOTES, 'UTF-8');?>" loading="lazy">
            <p class="p_first">
                Pendant notre enfance, notre adolescence et notre vie de jeunes adultes nous nous 
                sommes rendu&middot;e&middot;s compte que nous avions des références communes dans plusieurs 
                domaines artistiques. Nous avons décidé de réunir ses inspirations qui nous ressemblent 
                et nous rassemblent. Pour cette version avec le théâtre d’ombres nous avons été influencées 
                par les univers artistiques, politiques et sociaux de plusieurs artistes et plusieurs 
                œuvres. Parmi elleux :
            </p>
            <ul class="jolie-ombres slide-list">
                <li>la poésie d’Anne Sylvestre</li>
                <li>l’univers d’Hayao Miyazaki</li>
                <li>Persépolis de Marjane Satrapi</li>
                <li>le militantisme d’Adèle Haenel</li>
                <li>Le Roi et l’Oiseau de P. Grimault</li>
                <li>Princes et Princesses de M. Ocelot</li>
                <li>Et à la fin, ils meurent de Lou Lubie</li>
                <li>Les Triplettes de Belleville de S. Chomet</li>
            </ul>
            <p>
                Pour jouer en intérieur, nous vous proposons le format théâtre d’ombres. Celui-ci apporte 
                une dimension magique et surnaturelle aux deux personnages du conte. Il a été conçu pour 
                recréer l’ambiance des veillées que vivaient nos grands-parents.
                Cette version de <em>Grésilhette et la Salimonde</em> est pensée comme une forme visuelle 
                et poétique autour des silhouettes, de la lumière et de la narration.
            </p>
            <p>
                Le théâtre d'ombres ouvre un imaginaire accessible pour tout les âges et tout les goûts. La 
                compagnie adapte la représentation selon le lieu et le public. Elle peut autant jouer en milieu 
                scolaire qu'en programmation culturelle. Pour connaître les conditions techniques, la durée et 
                les modalités d'intervention, vous pouvez nous <a data-type="internal" href="<?php echo htmlspecialchars(site_path('/contact'), ENT_QUOTES, 'UTF-8');?>">contacter</a><span class="lien-i-seul"></span>.
            </p>
        </div><!--fermeture balise MONTAGE-WRAPPER-->
        <p>Voir ci-dessous la fiche technique.</p>
        <div class="g_art-tech">
            <a target="_blank" rel="noopener noreferrer" href="<?php echo htmlspecialchars(site_path('/pdf/fiche-theatre-d-ombres.pdf'), ENT_QUOTES, 'UTF-8');?>" title="Visualiser la fiche technique théâtre d'ombres">
                <img alt="Fiche technique théâtre d'ombres de la compagnie Les Mégères de l'Humus" src="<?php echo htmlspecialchars(site_path('/images/btn-tech-ombres.jpg'), ENT_QUOTES, 'UTF-8');?>" width="300" loading="lazy">
            </a>
        </div>
        <div class="conteneur-feuilles">
            <img class="feuilles" alt="" src="<?php echo htmlspecialchars(site_path('/images/i_feuilles.svg'), ENT_QUOTES, 'UTF-8');?>" loading="lazy">
        </div><!--fermeture balise .CONTENEUR-FEUILLES-->
    </section>
</main>
<?php require __DIR__ . '/../includes/footer.php'; ?>