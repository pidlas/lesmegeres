<?php
$appRoot = dirname(__DIR__) . '/lesmegeres-app';
$configuredBasePath = $_SERVER['SITE_BASE_PATH'] ?? getenv('SITE_BASE_PATH') ?: ($_ENV['SITE_BASE_PATH'] ?? '');
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

if ($siteBasePath !== '') {
    $basePathPattern = preg_quote(ltrim($siteBasePath, '/'), '#');
    ob_start(static function (string $buffer) use ($siteBasePath, $basePathPattern): string {
        return preg_replace(
            '#(href|src|action)=(["\'])/(?!' . $basePathPattern . '(?:/|["\']))#',
            '$1=$2' . $siteBasePath . '/',
            $buffer
        );
    });
}

$requestPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
$normalizedPath = preg_replace('#/+#', '/', $requestPath);
$routePath = $normalizedPath;

//if ($siteBasePath === '' && ($routePath === '/site1' || str_starts_with($routePath, '/site1/'))) {
//    $siteBasePath = '/site1';
//}

if ($siteBasePath !== '' && ($routePath === $siteBasePath || str_starts_with($routePath, $siteBasePath . '/'))) {
    $routePath = substr($routePath, strlen($siteBasePath)) ?: '/';
}

$route = trim($routePath, '/');

if ($route === 'index.php') {
    header('Location: ' . site_path('/'), true, 301);
    return;
}

if (preg_match('#^pages/([A-Za-z0-9-]+)\.php$#', $route, $legacyMatch) === 1) {
    header('Location: ' . site_path('/' . $legacyMatch[1]), true, 301);
    return;
}

if ($route !== '' && preg_match('/^[A-Za-z0-9-]+$/', $route) === 1) {
    $pageTemplate = $appRoot . '/pages/' . $route . '.php';

    if (is_file($pageTemplate)) {
        require $pageTemplate;
        return;
    }
}

$isNotFound = $route !== '';

if ($isNotFound) {
    http_response_code(404);
    $pageTitle = "Page introuvable | Les Mégères de l'Humus";
    $metaDescription = "La page demandée n'existe pas ou n'est plus disponible. Retrouvez les contenus de la compagnie via la navigation principale.";
    $canonicalPath = $routePath;
    $metaRobots = 'noindex,follow';
}

require $appRoot . '/includes/header.php';
?>
<!--Injectoin du css dédié uniquement pour cette page-->
<link rel="stylesheet" href="<?php echo htmlspecialchars(site_path('/css/diaporama.css'), ENT_QUOTES, 'UTF-8');?>">

<main>
<?php if ($isNotFound): ?>
    <section class="accueil-intro">
        <div class="conteneur-champignons">
            <img src="<?php echo htmlspecialchars(site_path('/images/i_champignons.svg'), ENT_QUOTES, 'UTF-8');?>" class="champignons" alt="">
            <h1>Un esprit de la fôret vous a trompé ! Page introuvable</h1>
        </div><!--fermeture balise .CONTENEUR-CHAMPIGNONS-->

        <p>La page demandée n'existe pas ou plus, ou n'est plus disponible là où vous pensiez qu'elle était.</p>
        <p>Vous pouvez revenir à <a data-type="internal" href="<?php echo htmlspecialchars(site_path('/'), ENT_QUOTES, 'UTF-8'); ?>">l'accueil</a><span class="lien-i-seul"></span>, consulter <a data-type="internal" href="<?php echo htmlspecialchars(site_path('/compagnie'), ENT_QUOTES, 'UTF-8'); ?>">la compagnie</a><span class="lien-i-seul"></span> ou nous <a data-type="internal" href="<?php echo htmlspecialchars(site_path('/contact'), ENT_QUOTES, 'UTF-8'); ?>">contacter</a><span class="lien-i-seul"></span>.</p>
    </section>
<?php else: ?>
    <section class="accueil-intro">
        <div class="conteneur-champignons">
            <img src="<?php echo htmlspecialchars(site_path('/images/i_champignons.svg'), ENT_QUOTES, 'UTF-8');?>" class="champignons" alt="">
            <h1>Compagnie de théâtre en Occitanie</h1>
        </div><!--fermeture balise .CONTENEUR-CHAMPIGNONS-->
    </section>
    <fieldset class="diaporama-fieldset">
        <legend class="visually-hidden">Diaporama de visualisation des spectacles</legend>
        <div class="diaporama-zone-maitre">
            <div class="button-container">
                <input type="radio" name="slide" id="c1" checked>
                    <label for="c1" class="card">
                        <div class="row">
                            <div class="description">
                                <a href="<?php echo htmlspecialchars(site_path('/compagnie'), ENT_QUOTES, 'UTF-8'); ?>">
                                    <h3 class="radio-title">À propos</h3>
                                </a>
                            </div>
                        </div>
                    </label>
                <input type="radio" name="slide" id="c2">
                    <label for="c2" class="card">
                        <div class="row">
                            <div class="description">
                                <a href="<?php echo htmlspecialchars(site_path('/gresilhette'), ENT_QUOTES, 'UTF-8'); ?>">
                                    <h3 class="radio-title">Grésilhette & la Salimonde</h3>
                                </a>
                            </div>
                        </div>
                    </label>
                <input type="radio" name="slide" id="c3">
                    <label for="c3" class="card">
                        <div class="row">
                            <div class="description">
                                <a href="<?php echo htmlspecialchars(site_path('/tagrand-mere'), ENT_QUOTES, 'UTF-8'); ?>">
                                    <h3 class="radio-title">Ta grand-mère sous le figuier</h3>
                                </a>
                            </div>
                        </div>
                    </label>
                <input type="radio" name="slide" id="c4">
                    <label for="c4" class="card">
                        <div class="row">
                            <div class="description">
                                <a href="<?php echo htmlspecialchars(site_path('/le-spectacle'), ENT_QUOTES, 'UTF-8'); ?>">
                                    <h3 class="radio-title">Théâtre Forum</h3>
                                </a>
                            </div>
                        </div>
                    </label>
                <input type="radio" name="slide" id="c5">
                    <label for="c5" class="card">
                        <div class="row">
                            <div class="description">
                                <a href="<?php echo htmlspecialchars(site_path('/cours'), ENT_QUOTES, 'UTF-8'); ?>">
                                    <h3 class="radio-title">Les cours</h3>
                                </a>
                            </div>
                        </div>
                    </label>
                <input type="radio" name="slide" id="c6">
                    <label for="c6" class="card">
                        <div class="row">
                            <div class="description">
                                <a href="<?php echo htmlspecialchars(site_path('/stages'), ENT_QUOTES, 'UTF-8'); ?>">
                                    <h3 class="radio-title">Les stages</h3>
                                </a>
                            </div>
                        </div>
                    </label>
                <input type="radio" name="slide" id="c7">
                    <label for="c7" class="card">
                        <div class="row">
                            <div class="description">
                                <a href="<?php echo htmlspecialchars(site_path('/ateliers'), ENT_QUOTES, 'UTF-8'); ?>">
                                    <h3 class="radio-title">Les ateliers</h3>
                                </a>
                            </div>
                        </div>
                    </label>
            </div><!--balise de fermeture de button-container-->
            <div class="diaporama-masque-fondu"></div>
        </div><!--fermeture balise diaporama-zone-maitre-->
    </fieldset>    
    <section class="index">
        <p>
            Les Mégères de l'Humus est une <a data-type="internal" href="<?php echo htmlspecialchars(site_path('/compagnie'), ENT_QUOTES, 'UTF-8'); ?>">compagnie</a><span class="lien-i-seul"></span> 
            de théâtre éco-queer, féministe et artiviste installée à Moussoulens, dans l'Aude. Nous créons des 
            <a data-type="internal" href="<?php echo htmlspecialchars(site_path('/gresilhette'), ENT_QUOTES, 'UTF-8'); ?>">spectacles</a><span class="lien-i-seul"></span>,
            menons des actions de <a data-type="internal" href="<?php echo htmlspecialchars(site_path('/le-spectacle'), ENT_QUOTES, 'UTF-8'); ?>">théâtre forum</a><span class="lien-i-seul"></span> 
            et proposons des <a data-type="internal" href="<?php echo htmlspecialchars(site_path('/ateliers'), ENT_QUOTES, 'UTF-8'); ?>">ateliers</a><span class="lien-i-seul"></span>, 
            <a data-type="internal" href="<?php echo htmlspecialchars(site_path('/cours'), ENT_QUOTES, 'UTF-8'); ?>">cours</a><span class="lien-i-seul"></span> et 
            <a data-type="internal" href="<?php echo htmlspecialchars(site_path('/stages'), ENT_QUOTES, 'UTF-8'); ?>">stages</a><span class="lien-i-seul"></span> 
            pour différents publics. Nous sommes joignables 
            <a data-type="internal" href="<?php echo htmlspecialchars(site_path('/contact'), ENT_QUOTES, 'UTF-8'); ?>">ici</a><span class="lien-i-seul"></span>.
        </p>

        <div class="conteneur-feuilles">
            <img class="feuilles" alt="" src="<?php echo htmlspecialchars(site_path('/images/i_feuilles.svg'), ENT_QUOTES, 'UTF-8'); ?>" loading="lazy">
        </div><!--fermeture balise .CONTENEUR-FEUILLES-->

    </section>
<?php endif; ?>
</main>
<?php require $appRoot . '/includes/footer.php'; ?>