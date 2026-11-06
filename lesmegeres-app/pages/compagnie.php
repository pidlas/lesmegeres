<?php require __DIR__ . '/../includes/header.php'; ?>
<!--Injectoin du css dédié uniquement pour cette page-->
<link rel="stylesheet" href="<?php echo htmlspecialchars(site_path('/css/compagnie.css'), ENT_QUOTES, 'UTF-8');?>">

<main>
    <section>
        <div class="conteneur-champignons">
            <img class="champignons" alt="" src="<?php echo htmlspecialchars(site_path('/images/i_champignons.svg'), ENT_QUOTES, 'UTF-8');?>" loading="lazy">
            <h1>La compagnie</h1>
        </div><!--fermeture balise .CONTENEUR-CHAMPIGNONS-->
        <div class="g_compagnie">
            <div class="g_co-item1">
                <p ><em>Les Mégères de l’Humus</em> est une compagnie de théâtre éco-queer, féministe et artiviste. Elle voit le jour en 
                    2022 sur le territoire audois grâce à la rencontre fortuite de Sabine, Suzie, Delphine et Marie. Toutes les quatre
                    sont en constante réflexion, elles observent et questionnent le rapport qu’elles ont au monde.
                </p>
                <p>Sabine et Suzie sont comédiennes et sont à l’origine du projet <em>Les Mégères de l’Humus</em>. Elles pratiquent un théâtre de l’intime et proposent aux 
                    spectateur&middot;ice&middot;s un voyage qui l'implique dans l'acte théâtral. C'est autour d'une envie forte d'ancrage, d’échange, d’indépendance et de beauté, 
                    qu'elles se sont retrouvées.
                </p>
                <p>Delphine et Marie, elles, sont deux amoureuses de la culture et ont décidé de soutenir Sabine et Suzie en créant la 
                    structure d'accueil adaptée, l’association loi 1901 Les Mégères de l'Humus.
                </p>
                <picture>
                    <!--version webp-->
                    <source type="image/webp"
                        srcset="
                            <?php echo htmlspecialchars(site_path('/images/co_dossier-prez-400w.webp 400w'), ENT_QUOTES, 'UTF-8');?>,
                            <?php echo htmlspecialchars(site_path('/images/co_dossier-prez-800w.webp 800w'), ENT_QUOTES, 'UTF-8');?>,
                            <?php echo htmlspecialchars(site_path('/images/co_dossier-prez-1200w.webp 1200w'), ENT_QUOTES, 'UTF-8');?>"
                        sizes="
                            (max-width:400px) 100vw,
                            (max-width:800px) 50vw,
                            33vw">
                    <!--version jpeg-->
                        <source type="image/jpeg"
                        srcset="
                            <?php echo htmlspecialchars(site_path('/images/co_dossier-prez-400w.jpg 400w'), ENT_QUOTES, 'UTF-8');?>,
                            <?php echo htmlspecialchars(site_path('/images/co_dossier-prez-800w.jpg 800w'), ENT_QUOTES, 'UTF-8');?>,
                            <?php echo htmlspecialchars(site_path('/images/co_dossier-prez-1200w.jpg 1200w'), ENT_QUOTES, 'UTF-8');?>"
                        sizes="
                            (max-width:400px) 100vw,
                            (max-width:800px) 50vw,
                            33vw">
                    <!--fallback-->        
                    <img alt="Le théâtre d'ombres - Grésilhette et la Salimonde" src="<?php echo htmlspecialchars(site_path('/images/co_dossier-prez-1200w.jpg'), ENT_QUOTES, 'UTF-8');?>" loading="lazy">
                </picture>
            </div><!--fermeture balise .G_CO-ITEM1-->
        </div><!--fermeture balise .G_COMPAGNIE-->
        <div class="g_compagnie2">    
            <!--<div class="g_co-item2">-->
                <p>La compagnie a été créée pour deux raisons essentielles : travailler à notre rythme et faire nos propres créations. En effet, nous ne nous 
                    sommes jamais identifiées à la dynamique de cette société capitaliste et patriarcale qui valorise la productivité, la richesse et la domination.
                </p>
            <!--</div>fermeture balise .G_CO-ITEM2
            <div class="g_co-item3">-->
                <p>Pour protéger notre santé mentale et physique nous avons décidé de nous extraire de ces schémas en valorisant plutôt la créativité, le soin et la 
                    lenteur. Faire du spectacle vivant est notre manière à nous de militer dans la joie et la bonne humeur. Sur scène, nous voulons parler des 
                    sujets qui nous tiennent à cœur en toute liberté. Et ce qui nous tient à cœur c’est de dénoncer et lutter contre toutes les oppressions 
                    systémiques.
                </p>
            <!--</div>fermeture balise .G_CO_E-ITEM3
            <div class="g_co-item4">-->
                <figure>
                    <blockquote>
                        <p class="theseasonsbold">&ldquo;&nbsp;Il est essentiel de prendre le temps de prendre le temps.&nbsp;&rdquo;</p>
                    </blockquote>
                    <figcaption>
                        <span class="theseasonsregular">La compagnie</span>
                    </figcaption>
                </figure>
            <!--</div>fermeture balise .G_CO-ITEM4
            <div class="g_co-item5">-->
                <p>Rien que ça&nbsp;! Alors nous nous nourrissons intellectuellement, politiquement, 
                    socialement, humainement pour transmettre tout ce que nous apprenons 
                    aux petit&middot;e&middot;s et aux plus grand&middot;e&middot;s. Nous nous éduquons 
                    avec l’aide de notre public et nous réinventons des espaces dédiés aux relations 
                    intergénérationnelles. Enfin, nous osons construire le monde professionnel et 
                    artistique bienveillant dont nous rêvions depuis toujours. Alliant fantaisie et 
                    poésie, humour et sensibilité, <em>Les Mégères de l'Humus</em> cultivent un univers politisé, 
                    touchant et merveilleux.
                </p>
            <!--</div>fermeture balise .G_CO-ITEM5
            <div class="g_co-item6">-->
                <p>Nous faisons partie intégrante de la nature et nous l’oublions si vite, toustes 
                    accaparé&middot;e&middot;s par nos rythmes effrénés. Mais ne vous inquiétez 
                    pas car nous sommes là pour vous faire ralentir la cadence, vous apporter réconfort, 
                    joie et de quoi vous remplumer pour vos futures luttes.
                </p>
                <p>— Et oui car nous sommes <em>Les Mégères de l’Humus</em> ; nous papotons et œuvrons pour un monde 
                    chaque jour un peu plus doux et juste. Enfin, nous faisons de notre mieux et à notre échelle. — Delphine •
                    Marie • Sabine • Suzie
                </p>
            <!--</div>fermeture balise .G_CO-ITEM6-->
        </div><!--fermeture balise .G_COMPAGNIE2-->
        <div class="g_compagnie3">
            <!--<h2 class="display-title titre-rapports">Découvrez les coulisses de notre aventure !</h2>-->
            <div class="g_co-item7">
                <a target="_blank" rel="nopener noreferrer" href="<?php echo htmlspecialchars(site_path('/pdf/rapport-2024_compressed.pdf'), ENT_QUOTES, 'UTF-8');?>" title="Visualiser le rapport 2024">
                    <img alt="Rapport d'activités 2024 de la compagnie Les Mégères de l'Humus" src="<?php echo htmlspecialchars(site_path('/images/rapport2024.png'), ENT_QUOTES, 'UTF-8');?>" loading="lazy">
                </a>
            </div><!--fermeture balise .G_CO-ITEM7-->
            <div class="g_co-item8">
                <a target="_blank" rel="noopener noreferrer" href="<?php echo htmlspecialchars(site_path('/pdf/rapport-2025_compressed.pdf'), ENT_QUOTES, 'UTF-8');?>" title="Visualiser le rapport 2025">
                    <img alt="Rapport d'activités 2025 de la compagnie Les Mégères de l'Humus" src="<?php echo htmlspecialchars(site_path('/images/rapport2025.png'), ENT_QUOTES, 'UTF-8');?>" loading="lazy">
                </a>
            </div><!--fermeture balise .G_CO-ITEM8-->
            <div class="g_co-item9">
                <h2 class="display-title">Et pourquoi les Mégères ?</h2>
            </div><!--fermeture balise .G_CO-ITEM9-->
            <div class="g_co-item10">
                <picture>
                    <!--version webp-->
                    <source type="image/webp"
                        srcset="
                            <?php echo htmlspecialchars(site_path('/images/co_masque-400w.webp 400w'), ENT_QUOTES, 'UTF-8');?>,
                            <?php echo htmlspecialchars(site_path('/images/co_masque-800w.webp 800w'), ENT_QUOTES, 'UTF-8');?>,
                            <?php echo htmlspecialchars(site_path('/images/co_masque-1200w.webp 1200w'), ENT_QUOTES, 'UTF-8');?>"
                        sizes="
                            (min-width:0px) 150px,
                            (min-width:768px) 200px,
                            (min-width:960px) 250px,
                            (min-width:1200px) 300px,
                            350px">
                    <!--version jpeg-->
                        <source type="image/jpeg"
                        srcset="
                            <?php echo htmlspecialchars(site_path('/images/co_masque-400w.jpg 400w'), ENT_QUOTES, 'UTF-8');?>,
                            <?php echo htmlspecialchars(site_path('/images/co_masque-800w.jpg 800w'), ENT_QUOTES, 'UTF-8');?>,
                            <?php echo htmlspecialchars(site_path('/images/co_masque-1200w.jpg 1200w'), ENT_QUOTES, 'UTF-8');?>"
                        sizes="
                            (min-width:0px) 150px,
                            (min-width:768px) 200px,
                            (min-width:960px) 250px,
                            (min-width:1200px) 300px, 
                            350px">
                    <!--fallback-->        
                    <img class="forme-masque" alt="Masque de Gorgone du théâtre antique" src="<?php echo htmlspecialchars(site_path('/images/co_masque-400w.jpg'), ENT_QUOTES, 'UTF-8');?>" loading="lazy">
                </picture>
                <p>
                    D’après le dictionnaire Le Robert, une mégère est “une femme méchante et criarde”. Dans le langage 
                    familier, les mégères sont des femmes qui discutent entre elles et qui alimentent les potins de leur 
                    quartier. Nous appelons aussi ces femmes des commères ou des rombières. Ces jolis noms d’oiseaux 
                    sont peu avantageux et ont une connotation péjorative. Par curiosité, nous avons cherché s’il 
                    existait des adjectifs similaires pour parler des hommes qui discutent entre eux. Et bien figurez-vous 
                    qu’il n’en existe pas&nbsp;! Compère signifie camarade, rombier est un synonyme de soldat et le mot 
                    mégère n’a pas son ascendant masculin&nbsp;! Et oui, il ne faut pas oublier que nous vivons dans un 
                    monde où le patriarcat règne en maître et où les afab, "assigned female at birth", "femme assignée à 
                    leur genre par la naissance", sont soumises à des jugements dévalorisants juste parce qu’elles sont 
                    nées et reconnues comme femmes. Et cela les poursuit jusque dans le dictionnaire. Alors nous avons 
                    décidé de remettre au goût du jour ses mots si indélicats, se les réapproprier et être fières d'être de 
                    terribles Mégères&nbsp;! Car ces avec les mots que l'on pense le monde. Et alors pourquoi ne pas 
                    avoir choisi de s'appeler les Commères de l'Humus ou les Rombières de l'Humus&nbsp;?
                </p>
            </div><!--fermeture balise .G_CO-ITEM10-->
            <div class="g_co-item11">
                <img class="forme-erinyes" alt="Les Erynies, peinture moderne" src="<?php echo htmlspecialchars(site_path('/images/co_les-erynies.jpg'), ENT_QUOTES, 'UTF-8');?>" loading="lazy">
                <p>Lorsque nous étions à la recherche d’une figure symbolique de femmes puissantes nous sommes revenues à 
                    notre amour premier du théâtre autour des histoires et de la mythologie. C'était important pour nous de 
                    toujours faire appel à nos racines. Nous avons alors redécouvert avec plaisir le mythe des Érinyes. Et oui ! 
                    La mégère en est une référence directe. Les Érinyes sont de très anciennes déesses persécutrices et nous en 
                    connaissons trois. Il y a <em>Tisiphone</em> - la vengeance, <em>Alecto</em> - l’implacable et <em>Mégère</em>
                     - la haine. Elles représentaient les lois de l’ordre moral, les faisaient respecter et châtiaient les 
                     humain&middot;e&middot;s qui les transgressaient. Les Érinyes étaient redoutables, intransigeantes et 
                     cruelles. Rien ne pouvait les écarter de leur mission et elles avaient tous les droits. Craintes des divinités 
                     et des humain&middot;e&middot;s, elles étaient marginalisées et vivaient dans l’Érèbe, les ténèbres des Enfers. 
                     Les Érinyes sont des êtres avec une symbolique puissante que nous avons décidé de nous approprier librement sous 
                     le prisme du féminisme.
                </p>
            </div><!--fermeture balise .G_CO-ITEM11-->
            
            <div class="g_co-item12">
                <figure>
                    <img alt="Les Erynies et Oreste, peinture classique" src="<?php echo htmlspecialchars(site_path('/images/co_les-erinyes-et-oreste.jpg'), ENT_QUOTES, 'UTF-8');?>" loading="lazy">
                    <figcaption class="p_centrer legende">Les Érynies poursuivent Oreste pour avoir tué Clytemnestre
                    </figcaption>
                </figure>
            </div><!--fermeture balise .G_CO-ITEM12-->
            
            <div class="g_co-item13">
                <h2 class="display-title">Et l'humus dans tout ça ?</h2>
                <p>Et bien c’est un peu le phénix des forêts. L’humus c'est cette couche de terre noire et granuleuse qui sent bon la 
                    fraîcheur et les sous-bois quand on en prend dans nos mains. Il se crée par la décomposition des matières 
                    organiques - feuilles, écorces, carcasses, excréments. Il est entretenu par l'action combinée des animaux, des 
                    bactéries et des champignons du sol. C’est donc un sol vivant qui se régénère grâce aux déchets de son 
                    environnement. Et ses bienfaits sont considérables&nbsp;! L’humus apporte beaucoup de nutriments essentiels pour 
                    toute la faune et la flore d’une forêt. Le mot humain&middot;e vient d’ailleurs du latin «humus», qui signifie 
                    terre. Il y a de la vie dans la mort et de la mort dans la vie. Contrairement à ce que notre société occidentale 
                    veut nous faire croire, l’une est toujours accompagnée de l’autre. Si l’une advient, l’autre adviendra quoi qu’il 
                    arrive. La vie ouvre à la certitude de la mort et c’est parce que nous sommes mortel&middot;le&middot;s que la 
                    création survient. Nous voulons célébrer la mort autant que la vie, créer des liens intergénérationnels pour 
                    retrouver notre humanité.
                </p>
                <figure>
                    <img alt="L'humus, peinture stylisée" src="<?php echo htmlspecialchars(site_path('/images/co_l-humus.jpg'), ENT_QUOTES, 'UTF-8');?>" loading="lazy">
                    <figcaption class="p_centrer legende">
                    Emerson Munduruku est une biologiste munduruku. Elle utilise le drag, sous le nom d'Uýra Sodoma afin de militer pour la préservation de 
                    l'Amazonie.
                    </figcaption>
                </figure>
            </div><!--fermeture balise .G_CO-ITEM13-->
        </div><!--fermeture balise .G_COMPAGNIE3-->
        
        <div class="conteneur-feuilles">
            <img class="feuilles" alt="" src="<?php echo htmlspecialchars(site_path('/images/i_feuilles.svg'), ENT_QUOTES, 'UTF-8');?>" loading="lazy">
        </div><!--fermeture balise .CONTENEUR-FEUILLES-->
    </section>
</main>
<?php require __DIR__ . '/../includes/footer.php'; ?>
