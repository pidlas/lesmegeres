<!DOCTYPE html>
<html lang="fr">
  <head> 
    <?php
      $siteName = "Les Mégères de l'Humus";
      $configuredBaseUrl = $_SERVER['SITE_BASE_URL'] ?? getenv('SITE_BASE_URL') ?: ($_ENV['SITE_BASE_URL'] ?? '');
      $configuredBasePath = $_SERVER['SITE_BASE_PATH'] ?? getenv('SITE_BASE_PATH') ?: ($_ENV['SITE_BASE_PATH'] ?? '');
      $detectedScheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
      $detectedHost = $_SERVER['HTTP_HOST'] ?? '';
      $detectedBaseUrl = $detectedHost && !in_array($detectedHost, ['lesmegeres', 'localhost', '127.0.0.1'], true)
        ? $detectedScheme . '://' . $detectedHost
        : 'https://lesmegeresdelhumus.fr';
      $siteBaseUrl = rtrim($configuredBaseUrl ?: $detectedBaseUrl, '/');
      if (!isset($siteBasePath)) {
        $scriptNameCandidates = [
          $_SERVER['SCRIPT_NAME'] ?? null,
          $_SERVER['PHP_SELF'] ?? null,
          $_SERVER['ORIG_SCRIPT_NAME'] ?? null,
        ];
        $detectedScriptDir = '';

        foreach ($scriptNameCandidates as $candidate) {
          if (!is_string($candidate) || $candidate === '') {
            continue;
          }

          $candidateDir = str_replace('\\', '/', dirname($candidate));

          if ($candidateDir !== '/' && $candidateDir !== '.') {
            $detectedScriptDir = rtrim($candidateDir, '/');
            break;
          }
        }

        $detectedScriptDir = $detectedScriptDir === '' ? '' : $detectedScriptDir;
        $detectedBasePath = ($detectedScriptDir === '/' || $detectedScriptDir === '.') ? '' : rtrim($detectedScriptDir, '/');
        $siteBasePath = $configuredBasePath !== '' ? '/' . trim($configuredBasePath, '/') : $detectedBasePath;
        if ($siteBasePath === '/') {
          $siteBasePath = '';
        }
      }
      if (!function_exists('site_path')) {
        function site_path(string $path = '/'): string
        {
          global $siteBasePath;

          $normalizedPath = '/' . ltrim($path, '/');

          if ($normalizedPath === '//') {
            $normalizedPath = '/';
          }

          return ($siteBasePath === '' ? '' : $siteBasePath) . ($normalizedPath === '/' ? '/' : $normalizedPath);
        }
      }
      $defaultTitle = "Compagnie de théâtre en Occitanie | $siteName";
      $defaultDescription = "Compagnie de théâtre en Occitanie proposant spectacles, théâtre forum, ateliers, cours et stages.";
      $requestPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
      $normalizedPath = preg_replace('#/+#', '/', $requestPath);
      $routePath = $normalizedPath;
      //if ($siteBasePath === '' && ($routePath === '/site1' || str_starts_with($routePath, '/site1/'))) {
      //  $siteBasePath = '/site1';
      //}
      if ($siteBasePath !== '' && ($routePath === $siteBasePath || str_starts_with($routePath, $siteBasePath . '/'))) {
        $routePath = substr($routePath, strlen($siteBasePath)) ?: '/';
      }
      $routeKey = trim($routePath, '/');
      $routeKey = $routeKey === '' ? 'index' : $routeKey;
      $scriptName = basename($_SERVER['SCRIPT_NAME'] ?? 'index.php', '.php');
      $pageMetadata = [
        'index' => [
          'title' => $defaultTitle,
          'description' => $defaultDescription,
          'path' => '/',
          'type' => 'website',
        ],
        'actualites' => [
          'title' => "Actualités théâtre en Aude | $siteName",
          'description' => "Dates, annonces et temps forts des Mégères de l'Humus à Moussoulens et autour de Carcassonne: spectacles, ateliers et théâtre forum.",
          'path' => '/actualites',
        ],
        'compagnie' => [
          'title' => "Compagnie de théâtre dans l'Aude | $siteName",
          'description' => "Qui sommes-nous ? Découvrez l'histoire et les engagements des Mégères de l'Humus, compagnie de théâtre basée à Moussoulens, près de Carcassonne.",
          'path' => '/compagnie',
        ],
        'equipe' => [
          'title' => "Équipe artistique à Moussoulens | $siteName",
          'description' => "Rencontrez les comédiennes, artistes et membres de l'association qui font vivre les projets théâtre des Mégères de l'Humus.",
          'path' => '/equipe',
        ],
        'partenaires' => [
          'title' => "Partenaires culturels et institutionnels | $siteName",
          'description' => "Découvrez les partenaires qui soutiennent les actions artistiques et culturelles des Mégères de l'Humus dans l'Aude.",
          'path' => '/partenaires',
        ],
        'gresilhette' => [
          'title' => "Spectacle jeune public écologie | $siteName",
          'description' => "Grésilhette et la Salimonde: un conte théâtral jeune public autour de l'écologie, en diffusion dans l'Aude et en Occitanie.",
          'path' => '/gresilhette',
        ],
        'theatre-d-ombres' => [
          'title' => "Théâtre d'ombres en Occitanie | $siteName",
          'description' => "Une forme visuelle et poétique de Grésilhette et la Salimonde, proposée en milieu scolaire et culturel dans l'Aude.",
          'path' => '/theatre-d-ombres',
        ],
        'sieste-contee' => [
          'title' => "Sieste contée pour structures culturelles | $siteName",
          'description' => "Une sieste contée sensible et immersive issue de Grésilhette et la Salimonde, adaptable aux publics jeunesse et famille.",
          'path' => '/sieste-contee',
        ],
        'projet-pedagogique' => [
          'title' => "Projet pédagogique théâtre et écologie | $siteName",
          'description' => "Un accompagnement pédagogique pour écoles et structures jeunesse autour du théâtre, du conte et des enjeux écologiques.",
          'path' => '/projet-pedagogique',
        ],
        'tagrand-mere' => [
          'title' => "Ta grand-mère sous le figuier | $siteName",
          'description' => "Un projet de collecte de paroles et de spectacle autour des femmes âgées, de la mémoire et de la transmission intergénérationnelle.",
          'path' => '/tagrand-mere',
        ],
        'le-spectacle' => [
          'title' => "Théâtre forum jeunesse, théâtre forum adulte | $siteName",
          'description' => "Interventions théâtre forum pour enfants, ados et jeunes adultes autour des relations, discriminations et du vivre-ensemble.
          Séances de théâtre forum pour adultes en milieu associatif, culturel ou professionnel, avec interventions dans l'Aude et en Occitanie.",
          'path' => '/le-spectacle',
        ],
        'les-scolaires' => [
          'title' => "Théâtre Forum jeunesse pour les scolaires | $siteName",
          'description' => "Sensibiliser les jeunes de 11 à 18 ans à des problématiques sociales de manière interactive",
          'path' => '/les-scolaires',
        ],
        'le-tout-public' => [
          'title' => "Théâtre forum pour tout public | $siteName",
          'description' => "Sensibilisation interactive par le Théâtre-Forum pour les entreprises, associations, équipes pédagogiques, centres de loisirs, théâtres, festivals, collectivités territoriales, écoles supérieures et toutes autres structures intéressées.",
          'path' => '/le-tout-public',
        ],
        'le-laboratoire' => [
          'title' => "Théâtre forum adulte | $siteName",
          'description' => "",
          'path' => '/le-laboratoire',
        ],
        'cours' => [
          'title' => "Cours de théâtre dans l'Aude | $siteName",
          'description' => "Consultez le programme des cours de théâtre proposés par les Mégères de l'Humus à Moussoulens, près de Carcassonne.",
          'path' => '/cours',
        ],
        'stages' => [
          'title' => "Stages de théâtre en Occitanie | $siteName",
          'description' => "Stages de théâtre pour débutants ou confirmés: corps, voix, improvisation et création, avec sessions organisées dans l'Aude.",
          'path' => '/stages',
        ],
        'ateliers' => [
          'title' => "Ateliers de théâtre à Moussoulens | $siteName",
          'description' => "Ateliers de théâtre pour publics jeunesse et adulte: pratique, expression et création collective avec les Mégères de l'Humus.",
          'path' => '/ateliers',
        ],
        'reglement-interieur' => [
          'title' => "Règlement intérieur | $siteName",
          'description' => "Consultez le règlement intérieur applicable aux cours et stages proposés par la compagnie Les Mégères de l'Humus.",
          'path' => '/reglement-interieur',
        ],
        'contact' => [
          'title' => "Contact compagnie théâtre Aude | $siteName",
          'description' => "Besoin d'un spectacle, d'un atelier ou d'une intervention théâtre forum ? Contactez les Mégères de l'Humus à Moussoulens.",
          'path' => '/contact',
        ],
        'credits' => [
          'title' => "Crédits | $siteName",
          'description' => "Crédits du site et remerciements aux personnes ayant contribué aux photographies, graphismes, contenus et ressources.",
          'path' => '/credits',
        ],
        'mentions-legales' => [
          'title' => "Mentions légales et CGU | $siteName",
          'description' => "Mentions légales et Conditions Générales d'Utilisation du site Les Mégères de l'Humus.",
          'path' => '/mentions-legales',
        ],
        'politique-de-securite' => [
          'title' => "Politique de sécurité | $siteName",
          'description' => "Modalités de signalement des vulnérabilités et engagement de traitement de la sécurité du site.",
          'path' => '/politique-de-securite',
        ],
        'rapport-2024' => [
          'title' => "Rapport d'activités 2024 théâtre",
          'description' => "Consultez le rapport d'activités 2024: créations, actions de territoire, théâtre forum, ateliers et partenariats.",
          'path' => '/rapport-2024_compressed'
        ],
        'rapport-2025' => [
          'title' => "Rapport d'activités 2025 théâtre | $siteName",
          'description' => "Consultez le rapport d'activités 2025: créations, actions de territoire, théâtre forum, ateliers et partenariats.",
          'path' => '/rapport-2025_compressed',
        ],
        'sabine' => [
          'title' => "Sabine Rance | $siteName",
          'description' => "Découvrez le parcours de Sabine Rance, cofondatrice des Mégères de l'Humus, metteuse en scène, autrice et comédienne.",
          'path' => '/sabine',
        ],
        'suzie' => [
          'title' => "Suzie Idlas | $siteName",
          'description' => "Portrait de Suzie Idlas, cofondatrice des Mégères de l'Humus, comédienne, conteuse, autrice et metteuse en scène.",
          'path' => '/suzie',
        ],
        'estelle' => [
          'title' => "Muriel Lamy | $siteName",
          'description' => "Découvrez Muriel Lamy, comédienne associée aux créations et spectacles des Mégères de l'Humus.",
          'path' => '/muriel',
        ],
        'anne' => [
          'title' => "Anne Spielmann | $siteName",
          'description' => "Portrait d'Anne Spielmann, plasticienne, scénographe, metteuse en scène et artiste complice des Mégères de l'Humus.",
          'path' => '/anne',
        ],
        'delphine' => [
          'title' => "Delphine Verniol | $siteName",
          'description' => "Découvrez le parcours de Delphine Verniol, présidente de l'association et soutien essentiel des Mégères de l'Humus.",
          'path' => '/delphine',
        ],
        'marie' => [
          'title' => "Marie Diel | $siteName",
          'description' => "Portrait de Marie Diel, trésorière de l'association et actrice du développement des Mégères de l'Humus.",
          'path' => '/marie',
        ],
        'juliette' => [
          'title' => "Juliette Gaubert | $siteName",
          'description' => "Découvrez Juliette Gaubert, graphiste et créatrice de l'univers visuel des Mégères de l'Humus.",
          'path' => '/juliette',
        ],
        'philippe' => [
          'title' => "Philippe Guerrini | $siteName",
          'description' => "Portrait de Philippe Guerrini, artisan de la partie technique et soutien numérique des Mégères de l'Humus.",
          'path' => '/philippe',
        ],
      ];
      $currentMetadata = $pageMetadata[$routeKey] ?? $pageMetadata[$scriptName] ?? [
        'title' => $defaultTitle,
        'description' => $defaultDescription,
        'path' => $routePath,
        'type' => 'website',
      ];
      if (!empty($pageTitle)) {
        $currentMetadata['title'] = $pageTitle;
      }
      if (!empty($metaDescription)) {
        $currentMetadata['description'] = $metaDescription;
      }
      if (!empty($canonicalPath)) {
        $currentMetadata['path'] = $canonicalPath;
      }
      $metaRobotsOutput = htmlspecialchars($metaRobots ?? 'index,follow', ENT_QUOTES, 'UTF-8');
      $pageTitleOutput = htmlspecialchars($currentMetadata['title'], ENT_QUOTES, 'UTF-8');
      $metaDescriptionOutput = htmlspecialchars($currentMetadata['description'], ENT_QUOTES, 'UTF-8');
      $sitePublicBaseUrl = $siteBaseUrl . ($siteBasePath === '' ? '' : $siteBasePath);
      $canonicalUrl = $sitePublicBaseUrl . ($currentMetadata['path'] === '/' ? '/' : $currentMetadata['path']);
      $canonicalOutput = htmlspecialchars($canonicalUrl, ENT_QUOTES, 'UTF-8');
      $ogType = htmlspecialchars($currentMetadata['type'] ?? 'website', ENT_QUOTES, 'UTF-8');
      $structuredData = [
        '@context' => 'https://schema.org',
        '@graph' => [
          [
            '@type' => 'Organization',
            '@id' => $sitePublicBaseUrl . '/#organization',
            'name' => $siteName,
            'url' => $sitePublicBaseUrl,
            'logo' => $sitePublicBaseUrl . '/images/logohumous-notext-blue.png',
            'email' => 'compagnie@lesmegeresdelhumus.fr',
            'sameAs' => [
              'https://www.facebook.com/people/Cie-Les-M%C3%A9g%C3%A8res-de-lHumus/100093667065923/',
              'https://www.instagram.com/lesmegeresdelhumus/?igshid=NGExMml2YTkyZg%3D%3D'
            ],
            'address' => [
              '@type' => 'PostalAddress',
              'streetAddress' => '7 rue du 19 mars 1962',
              'postalCode' => '11170',
              'addressLocality' => 'Moussoulens',
              'addressCountry' => 'FR'
            ]
          ],
          [
            '@type' => 'WebSite',
            '@id' => $sitePublicBaseUrl . '/#website',
            'url' => $sitePublicBaseUrl,
            'name' => $siteName,
            'publisher' => [
              '@id' => $sitePublicBaseUrl . '/#organization'
            ],
            'inLanguage' => 'fr'
          ]
        ]
      ];
    ?>
    <title><?php echo $pageTitleOutput; ?></title>
    <meta charset="utf-8">
    <meta name="description" content="<?php echo $metaDescriptionOutput; ?>">
    <meta name="robots" content="<?php echo $metaRobotsOutput; ?>">
    <meta name="author" content="Philippe Guerrini Idlas dit Quiligus">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"><!--autoriser la prise en charge des media queries-->
    <link rel="canonical" href="<?php echo $canonicalOutput; ?>">
    <meta property="og:locale" content="fr_FR">
    <meta property="og:site_name" content="<?php echo htmlspecialchars($siteName, ENT_QUOTES, 'UTF-8'); ?>">
    <meta property="og:type" content="<?php echo $ogType; ?>">
    <meta property="og:title" content="<?php echo $pageTitleOutput; ?>">
    <meta property="og:description" content="<?php echo $metaDescriptionOutput; ?>">
    <meta property="og:url" content="<?php echo $canonicalOutput; ?>">
    <meta property="og:image" content="<?php echo htmlspecialchars($sitePublicBaseUrl . '/images/logohumous-notext-blue.png', ENT_QUOTES, 'UTF-8'); ?>">
    <meta name="google-site-verification" content="DFb9uPPgSgaqVnZHAxXTp9H1ZZ63DHs_-R8QXjno9jA" />
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?php echo $pageTitleOutput; ?>">
    <meta name="twitter:description" content="<?php echo $metaDescriptionOutput; ?>">
    <meta name="twitter:image" content="<?php echo htmlspecialchars($sitePublicBaseUrl . '/images/logohumous-notext-blue.png', ENT_QUOTES, 'UTF-8'); ?>">
    
    <link href="<?php echo htmlspecialchars(site_path('/css/style.min.css'), ENT_QUOTES, 'UTF-8'); ?>?v=20260410b" rel="stylesheet">
    <!--<link href="<?php echo htmlspecialchars(site_path('/css/style.css'), ENT_QUOTES, 'UTF-8'); ?>?v=20260410b" rel="stylesheet">-->
    <link href="<?php echo htmlspecialchars(site_path('/css/video.css'), ENT_QUOTES, 'UTF-8'); ?>" rel="stylesheet">
    <link href="<?php echo htmlspecialchars(site_path('/css/audio.css'), ENT_QUOTES, 'UTF-8'); ?>" rel="stylesheet">
       
    <link rel="icon" type="image/png" href="<?php echo htmlspecialchars(site_path('/images/favicon/favicon-48x48.png'), ENT_QUOTES, 'UTF-8'); ?>" sizes="48x48" />
    <link rel="icon" type="image/svg+xml" href="<?php echo htmlspecialchars(site_path('/images/favicon/favicon.svg'), ENT_QUOTES, 'UTF-8'); ?>" />
    <link rel="shortcut icon" href="<?php echo htmlspecialchars(site_path('/images/favicon/favicon.ico'), ENT_QUOTES, 'UTF-8'); ?>" />
    <link rel="apple-touch-icon" sizes="180x180" href="<?php echo htmlspecialchars(site_path('/images/favicon/apple-touch-icon.png'), ENT_QUOTES, 'UTF-8'); ?>" />
    <meta name="apple-mobile-web-app-title" content="Les Mégères de l'Humus" />
    <link rel="manifest" href="<?php echo htmlspecialchars(site_path('/images/favicon/site.webmanifest'), ENT_QUOTES, 'UTF-8'); ?>" />
    <script type="application/ld+json"><?php echo json_encode($structuredData, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE); ?></script>
    <!--Font Awesome 6 auto-hébergé (évite blocage CDN sur mobile)-->
    <link rel="stylesheet" href="<?php echo htmlspecialchars(site_path('/fonts/fontawesome/css/all.min.css'), ENT_QUOTES, 'UTF-8'); ?>">
  </head>

  <body>
  <a href="#main-content" class="skip-link">Aller au contenu principal</a>
  <div class="wrapper">
    <header>
        <div class="morph logo">
          <a href="<?php echo htmlspecialchars(site_path('/'), ENT_QUOTES, 'UTF-8'); ?>">
            <img src="<?php echo htmlspecialchars(site_path('/images/logohumous-notext-blue.png'), ENT_QUOTES, 'UTF-8'); ?>" width="230" height="230" alt="Le logo des Mégères de l'Humus réalisé par Noam"/>
          </a>
          </div>
      <div class="e_titre">
        <div class="titre tan" role="heading" aria-level="1">
          <a href="<?php echo htmlspecialchars(site_path('/'), ENT_QUOTES, 'UTF-8'); ?>">Les Mégères de l'Humus</a>
        </div>
        <p class="sous-titre">Compagnie éco-queer, féministe, arTiviste</p>
      </div>

      <button id="hamburger" class="menu-petit-ecran hamburger box" onclick="myBurger()" aria-label="Ouvrir le menu" aria-controls="nav-principal" aria-expanded="false">
        <div class="container-lignes">
          <div class="ligne"></div>
          <div class="ligne"></div>
          <div class="ligne"></div>
        </div><!--fermeture balise container-lignes-->        
      </button>
    </header>

    <nav id="nav-principal" class="navbar" role="navigation">
      <div class="menu">
        <button type="button" class="menu-trigger tan" aria-expanded="false" aria-controls="submenu-infos" aria-haspopup="true">Infos</button>
        <div id="submenu-infos" class="niv1 coco">
          <a href="<?php echo htmlspecialchars(site_path('/actualites'), ENT_QUOTES, 'UTF-8'); ?>">Actualités</a>
          <a href="<?php echo htmlspecialchars(site_path('/compagnie'), ENT_QUOTES, 'UTF-8'); ?>">Compagnie</a>
          <a href="<?php echo htmlspecialchars(site_path('/equipe'), ENT_QUOTES, 'UTF-8'); ?>">Équipe</a>
          <a href="<?php echo htmlspecialchars(site_path('/partenaires'), ENT_QUOTES, 'UTF-8'); ?>">Partenaires</a>
        </div><!--fermeture balise sous-menu Infos-->
      </div><!--fermeture balise menu Infos-->
      <div class="menu">
        <button type="button" id="menuBtn" class="menu-trigger tan" aria-expanded="false" aria-controls="submenu-spectacles" aria-haspopup="true">Spectacles</button>
        <div id="submenu-spectacles" class="niv1 coco">
          <a href="<?php echo htmlspecialchars(site_path('/gresilhette'), ENT_QUOTES, 'UTF-8'); ?>">Grésilhette et la Salimonde</a>
          <a href="<?php echo htmlspecialchars(site_path('/tagrand-mere'), ENT_QUOTES, 'UTF-8'); ?>">Ta grand-mère sous le figuier</a>
        </div><!--fermeture balise sous-menu Spectacle-->
      </div><!--fermeture balise menu Spectacle-->
      <div class="menu">
        <button type="button" class="menu-trigger tan" aria-expanded="false" aria-controls="submenu-forum" aria-haspopup="true">Théâtre Forum</button>
        <div id="submenu-forum" class="niv1 coco">
          <a href="<?php echo htmlspecialchars(site_path('/le-spectacle'), ENT_QUOTES, 'UTF-8'); ?>">Le spectacle : 0'Pression</a>
          <a href="<?php echo htmlspecialchars(site_path('/le-laboratoire'), ENT_QUOTES, 'UTF-8'); ?>">Le laboratoire</a>
        </div><!--fermeture balise sous-menu Théâtre Forum-->
      </div><!--fermeture balise menu Théâtre Forum-->
      <div class="menu">
        <button type="button" class="menu-trigger tan" aria-expanded="false" aria-controls="submenu-prestations" aria-haspopup="true">Prestations</button>
        <div id="submenu-prestations" class="niv1 coco">
          <a href="<?php echo htmlspecialchars(site_path('/cours'), ENT_QUOTES, 'UTF-8'); ?>">Cours de théâtre</a>
          <a href="<?php echo htmlspecialchars(site_path('/stages'), ENT_QUOTES, 'UTF-8'); ?>">Stages de théâtre</a>
          <a href="<?php echo htmlspecialchars(site_path('/ateliers'), ENT_QUOTES, 'UTF-8'); ?>">Ateliers de théâtre</a>
          <a href="<?php echo htmlspecialchars(site_path('/reglement-interieur'), ENT_QUOTES, 'UTF-8'); ?>" class="cache">Règlement intérieur</a>
        </div><!--fermeture balise sous-menu Cours et stages-->
      </div><!--fermeture balise menu Cours et stages-->
      <div class="menu-contact menu">
        <a href="<?php echo htmlspecialchars(site_path('/contact'), ENT_QUOTES, 'UTF-8'); ?>" class="tan">Contact</a>
      </div><!--fermeture balise menu Contact-->
      <div class="rs menu">
        <a href="https://www.facebook.com/people/Cie-Les-M%C3%A9g%C3%A8res-de-lHumus/100093667065923/" target="_blank" rel="noopener noreferrer" aria-label="Accéder à notre page Facebook"><i class="fa-brands fa-facebook-f" aria-hidden="true"></i></a>
        <a href="https://www.instagram.com/lesmegeresdelhumus/?igshid=NGExMml2YTkyZg%3D%3D" target="_blank" rel="noopener noreferrer" aria-label="Accèder à notre page Instagram"><i class="fa-brands fa-instagram" aria-hidden="true"></i></a>
      </div>
      <div class="menu mobile-only">
        <button id="openHaOverlay-mobile" class="e_faire-un-don">&nbsp;Faire un don</button>
      </div>

    </nav>