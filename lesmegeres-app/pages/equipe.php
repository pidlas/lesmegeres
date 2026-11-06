<?php require __DIR__ . '/../includes/header.php'; ?>
<main>
    <section>
       <div class="conteneur-champignons">
        <img class="champignons" alt="" src="<?php echo htmlspecialchars(site_path('/images/i_champignons.svg'), ENT_QUOTES, 'UTF-8');?>" loading="lazy">
            <h1>Notre équipe</h1>
        </div><!--fermeture balise .CONTENEUR-CHAMPIGNONS-->
        <div class="page_equipe">
            <!-- GROUPE 1 : Les Fondatrices (Ligne 1) -->
            <ul class="g_equipe g_fondatrices">
                <li class="tan item">SUZIE IDLAS<p class="coco gras">Responsable Communication<br/>Metteuse en scène, comédienne</p>
                    <div class="anime_hover">
                        <p class="montre coco">Plus sur Suzie</p>
                        <a href="<?php echo htmlspecialchars(site_path('/suzie'), ENT_QUOTES, 'UTF-8');?>">
                            <img alt="Suzie Idlas, la responsable communication" src="<?php echo htmlspecialchars(site_path('/images/webJolie_si.jpg'), ENT_QUOTES, 'UTF-8');?>">
                        </a>
                    </div>
                </li>
                <li class="tan item">SABINE RANCE<p class="coco gras">Responsable Artistique<br/>Metteuse en scène, comédienne</p>
                    <div class="anime_hover">
                        <p class="montre coco">Plus sur Sabine</p>
                        <a href="<?php echo htmlspecialchars(site_path('/sabine'), ENT_QUOTES, 'UTF-8');?>">
                            <img alt="Sabine Rance, la responsable artistique" src="<?php echo htmlspecialchars(site_path('/images/webJolie_sr.jpg'), ENT_QUOTES, 'UTF-8');?>">
                        </a>
                    </div>
                </li>
            </ul>

            <!-- GROUPE 2 : Les Comédiens (Ligne 2 - Évolutif) -->
            <ul class="g_equipe g_comediens">
                <li class="tan item">MURIEL LAMY<p class="coco gras">Comédienne</p>
                    <div class="anime_hover posImage">
                        <p class="montre coco">Plus sur Estelle</p>
                            <a href="<?php echo htmlspecialchars(site_path('/muriel'), ENT_QUOTES, 'UTF-8');?>">
                                <img alt="Muriel Lamy, comédienne" src="<?php echo htmlspecialchars(site_path('/images/webJolie_ml.jpg'), ENT_QUOTES, 'UTF-8');?>" loading="lazy">
                            </a>
                    </div>
                </li>
                <li class="tan item">ANNE SPIELMANN<p class="coco gras">Metteuse en scène, comédienne<br/>Plasticienne, scénographe</p>
                    <div class="anime_hover">
                        <p class="montre coco">Plus sur Anne</p>
                        <a href="<?php echo htmlspecialchars(site_path('/anne'), ENT_QUOTES, 'UTF-8');?>">
                            <img alt="Anne Spielmann, plasticienne" src="<?php echo htmlspecialchars(site_path('/images/webJolie_as.jpg'), ENT_QUOTES, 'UTF-8');?>" loading="lazy">
                        </a>
                    </div>
                </li>
            </ul>
            
            <!-- GROUPE 3 : Les Permanents (Ligne 3) -->
            <ul class="g_equipe g_permanents">
                <li class="tan item">DELPHINE VERNIOL<p class="coco gras">Présidente</p>
                    <div class="anime_hover">
                        <p class="montre coco">Plus sur Delphine</p>
                        <a href="<?php echo htmlspecialchars(site_path('/delphine'), ENT_QUOTES, 'UTF-8');?>">
                            <img alt="Delphine Verniol, la présidente" src="<?php echo htmlspecialchars(site_path('/images/webJolie_dv.jpg'), ENT_QUOTES, 'UTF-8');?>" loading="lazy">
                        </a>
                    </div>
                </li>
                <li class="tan item">MARIE DIEL<br/><p class="coco gras">Trésorière</p>
                    <div class="anime_hover">
                        <p class="montre coco">Plus sur Marie</p>
                        <a href="<?php echo htmlspecialchars(site_path('/marie'), ENT_QUOTES, 'UTF-8');?>">
                            <img alt="Marie Diel, la trésorière" src="<?php echo htmlspecialchars(site_path('/images/webJolie_md.jpg'), ENT_QUOTES, 'UTF-8');?>" loading="lazy">
                        </a>
                    </div>
                </li>
                <li class="tan item">JULIETTE GAUBERT<p class="coco gras">Graphiste</p>
                    <div class="anime_hover">
                        <p class="montre coco">Plus sur Juliette</p>
                        <a href="<?php echo htmlspecialchars(site_path('/juliette'), ENT_QUOTES, 'UTF-8');?>">
                            <img alt="Juliette Gaubert, la graphiste" src="<?php echo htmlspecialchars(site_path('/images/webJolie_jg.jpg'), ENT_QUOTES, 'UTF-8');?>" loading="lazy">
                        </a>
                    </div>
                </li>
                <li class="tan item">PHILIPPE GUERRINI<p class="coco gras">Informaticien</p>
                    <div class="anime_hover">
                        <p class="montre coco">Plus sur Philippe</p>
                        <a href="<?php echo htmlspecialchars(site_path('/philippe'), ENT_QUOTES, 'UTF-8');?>">
                            <img alt="Philippe Guerrini, l'informaticien" src="<?php echo htmlspecialchars(site_path('/images/webJolie_pi.jpg'), ENT_QUOTES, 'UTF-8');?>" loading="lazy">
                        </a>
                    </div>
                </li>
            </ul>
        </div><!--fermeture balise .PAGE_EQUIPE-->
        <div class="conteneur-feuilles">
            <img class="feuilles" alt="" src="<?php echo htmlspecialchars(site_path('/images/i_feuilles.svg'), ENT_QUOTES, 'UTF-8');?>" loading="lazy">
        </div><!--fermeture balise .CONTENEUR-FEUILLES-->
    </section>
</main>
<?php require __DIR__ . '/../includes/footer.php'; ?>