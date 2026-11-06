<?php require __DIR__ . '/../includes/header.php'; ?>
<main>
    <section class="contact">
        <div class="conteneur-champignons">
            <img class="champignons" alt="" src="<?php echo htmlspecialchars(site_path('/images/i_champignons.svg'), ENT_QUOTES, 'UTF-8');?>" loading="lazy">
            <h1>Nous contacter</h1>
            <p class="sous-titre">&hellip;Pour tout renseignement, fiche technique & tarif du spectacle</p>
        </div><!--fermeture balise .CONTENEUR-CHAMPIGNONS-->
        <div class="telephone">
            <p><img src="<?php echo htmlspecialchars(site_path('/images/iconeTel.png'), ENT_QUOTES, 'UTF-8'); ?>" width="50px" alt=""></p>
            <table class="com">
                <theader>
                    <th colspan="2" class="tresgras">Diffusion</th>
                </theader>
                <tbody>
                    <tr>
                        <td>Maëlle Prévôt</td>
                        <td>06 16 24 21 32</td>
                    </tr>
                    <tr>
                        <td>Yaniss Moutaoukil</td>
                        <td>06 25 59 25 20</td>
                    </tr>
                </tbody>
                <theader>
                    <th colspan="2" class="tresgras sep">Responsables communication et artistique</th>
                </theader>
                <tbody>
                    <tr>
                        <td>Suzie Idlas</td>
                        <td rowspan="2">06 95 19 25 35</td>
                    </tr>
                    <tr>
                        <td>Sabine Rance</td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
        </div>
        
        <div class="infos-contact">
            <img src="<?php echo htmlspecialchars(site_path('/images/iconeMail.png'), ENT_QUOTES, 'UTF-8'); ?>" width="50px" alt="">
            <span><a data-type="external" href="mailto:compagnie@lesmegeresdelhumus.fr?subject=Message du site">Nous écrire</a><span class="lien-e-seul"></span></span>
        </div>
        <div class="infos-contact fb">    
            <a data-type="external" href="https://www.facebook.com/people/Cie-Les-M%C3%A9g%C3%A8res-de-lHumus/100093667065923/" target="_blank" rel="noopener noreferrer">Cie Les Mégères de l'Humus</a><span class="lien-e-seul"></span>
        </div>
        <div class="infos-contact insta">    
            <a data-type="external" href="https://www.instagram.com/lesmegeresdelhumus/?igshid=NGExMml2YTkyZg%3D%3D" target="_blank" rel="noopener noreferrer">@Lesmegeresdelhumus</a><span class="lien-e-seul"></span>
        </div>
        
        <div class="adresse">
            <p><img src="<?php echo htmlspecialchars(site_path('/images/iconeAdresse.png'), ENT_QUOTES, 'UTF-8'); ?>" width="50px" alt=""></p>
            <p>Cie LES MÉGÈRES DE L'HUMUS</p>
            <p>7 rue du 19 mars 1962</p>
            <p>11170 Moussoulens</p>
        </div>
        
        <div class="association">
            <p><img src="<?php echo htmlspecialchars(site_path('/images/connect.png'), ENT_QUOTES, 'UTF-8'); ?>" width="40px" alt=""></p>
            <p>
                Association loi 1901<br>
                APE - 9001Z<br>
                SIRET - 917 798 191 00016<br>
                Licence du spectacle : L-D-22-7087
            </p>
        </div>
        <div class="conteneur-feuilles">
            <img class="feuilles" alt="" src="<?php echo htmlspecialchars(site_path('/images/i_feuilles.svg'), ENT_QUOTES, 'UTF-8');?>" loading="lazy">
        </div><!--fermeture balise .CONTENEUR-FEUILLES-->
    </section>
</main>
<?php require __DIR__ . '/../includes/footer.php'; ?>