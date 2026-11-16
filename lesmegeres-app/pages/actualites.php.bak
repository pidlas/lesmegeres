<?php
// ---- 1. DÉCLARATION DES DONNÉES ENTRANTES (100% DYNAMIQUE) ----
$actualites = [
  // Événement 1
  [
    'type' => 'evenement',
    'date' => '2026-11-19 16:00:00',
    'titre' => "Spectacle de Théâtre Forum <em>0'Pression</em>",
    'resume' => "Réservé aux étudiant·e·s et personnels de l'académie de Montpellier. En partenariat avec la Faculté d’Éducation. À l'occasion de la semaine de lutte contre les LGBTQIA+phobies. Pour plus d'informations, <a data-type='external' target='_blank' rel='noopener noreferrer' href='https://umontpellier.fr'>cliquez ici.</a><span class='lien-e-seul'></span>",
    'image' => '',
    'lien' => '',
    'plateforme' => ''
  ],
  // Événement 2
  [
    'type' => 'evenement',
    'date' => '2026-12-16',
    'titre' => "Spectacle jeune public <em>Grésilhette et la Salimonde</em>",
    'resume' => "À l'intention des élèves de l'école primaire de Moussoulens.",
    'image' => '/images/actualite-visuel-portrait.webp',
    'lien' => '',
    'plateforme' => ''
  ],
  // Publication Réseau Social
  [
    'type' => 'reseau',
    'date' => '',
    'titre' => "Ateliers de théâtre à la FDE de Carcassonne <em>Libérer la parole et le corps</em>",
    'resume' => "Réservés aux étudiant·e·s et personnels de l'académie de Montpellier qui souhaitent développer leurs capacités vocales et posturales devant un public d’enfants.",
    'image' => '/images/actualite-ateliers.webp',
    'lien' => '<iframe src="https://facebook.com" width="500" height="396" class="fb-embed-iframe" style="border:none;overflow:hidden;max-width:100%;display:block;margin:0 auto;" scrolling="no" frameborder="0" allowfullscreen="true" allow="autoplay; clipboard-write; encrypted-media; picture-in-picture; web-share"></iframe>',
    'plateforme' => 'facebook'
  ]
];

// ---- 2. FORMATAGE INTELLIGENT DE LA DATE ET DE L'HEURE ----
function formaterDateActu(string $dateRaw): string {
    if ($dateRaw === '') {
        return '';
    }
    $dateObj = date_create($dateRaw);
    if ($dateObj === false) {
        return '';
    }
    
    $jours = ['dim', 'lun', 'mar', 'mer', 'jeu', 'ven', 'sam'];
    $mois = ['', 'janvier', 'février', 'mars', 'avril', 'mai', 'juin', 'juillet', 'août', 'septembre', 'octobre', 'novembre', 'décembre'];
    
    $j = $jours[(int)$dateObj->format('w')];
    $d = $dateObj->format('d');
    $m = $mois[(int)$dateObj->format('n')];
    $y = $dateObj->format('Y');
    
    if ($dateObj->format('H:i') !== '00:00') {
        $h = $dateObj->format('G\hi');
        return $j . '. ' . $d . ' ' . $m . ' ' . $y . ' à ' . $h;
    }
    
    return $j . '. ' . $d . ' ' . $m . ' ' . $y;
}

// ---- 3. TRI GLOBAL PAR DATE DÉCROISSANTE ----
usort($actualites, static function (array $a, array $b): int {
  return strcmp((string) ($b['date'] ?? ''), (string) ($a['date'] ?? ''));
});

// ---- 4. REGROUPEMENT STRUCTURÉ PAR ENVELOPPE GRAPHIQUE ----
$articlesGroupes = [
    'evenement' => [
        'h2' => 'Les événements',
        'image' => '/images/actu-event.webp',
        'contents' => []
    ],
    'reseau' => [
        'h2' => 'Les réseaux sociaux',
        'image' => '/images/actu-reseaux-sociaux.webp',
        'contents' => []
    ]
];

foreach ($actualites as $actu) {
    $type = ($actu['type'] ?? '') === 'evenement' ? 'evenement' : 'reseau';
    $plateforme = strtolower(trim((string) ($actu['plateforme'] ?? '')));
    
    $articlesGroupes[$type]['contents'][] = [
        'dateAffichee' => formaterDateActu((string) ($actu['date'] ?? '')),
        'image' => trim((string) ($actu['image'] ?? '')),
        'titre' => trim((string) ($actu['titre'] ?? '')),
        'resume' => html_entity_decode(trim((string) ($actu['resume'] ?? '')), ENT_NOQUOTES, 'UTF-8'),
        'lien' => trim((string) ($actu['lien'] ?? '')),
        'plateforme' => $plateforme,
        'plateformeLabel' => ($plateforme === 'facebook') ? 'Facebook' : 'Réseaux sociaux'
    ];
}

require __DIR__ . '/../includes/header.php';
?>
<main>
  <section>
      
    <div class="conteneur-champignons">
      <img class="champignons" alt="" src="<?php echo htmlspecialchars(site_path('/images/i_champignons.svg'), ENT_QUOTES, 'UTF-8'); ?>" loading="lazy">
      <h1>Actualités</h1>
    </div>

    <p>
      Retrouvez ici les actualités de la compagnie <em>Les Mégères de l'Humus</em> : prochaines représentations, sorties de création, 
      rendez-vous de théâtre forum, ateliers et temps forts de la compagnie.
    </p>
    <p>
      Cette page a vocation à rassembler les annonces importantes de la saison ainsi que les informations pratiques liées aux spectacles, rencontres 
      et actions culturelles menées dans l'Aude et en Occitanie.
    </p>

    <div class="actus-list" aria-live="polite">
      <?php foreach ($articlesGroupes as $typeKey => $articleItem) : ?>
        <?php if (!empty($articleItem['contents'])) : ?>
          
          <!-- NIVEAU ARTICLE : Une seule bordure légère par grand type d'actualité -->
          <article class="actu-item">
            <img 
              class="actu-item-image" 
              src="<?php echo htmlspecialchars(site_path($articleItem['image']), ENT_QUOTES, 'UTF-8'); ?>" 
              alt="<?php echo htmlspecialchars($articleItem['h2'], ENT_QUOTES, 'UTF-8'); ?>" 
              loading="lazy" 
              decoding="async"
            >
            <h2 class="display-title"><?php echo htmlspecialchars($articleItem['h2'], ENT_QUOTES, 'UTF-8'); ?></h2>
            
            <!-- NIVEAU DIV.ACTU-CONTENT : Répétition des contenus à l'intérieur -->
            <?php foreach ($articleItem['contents'] as $content) : ?>
              <div class="actu-content">
                
                <!-- Date optionnelle -->
                <!-- Gestion de la date ou du texte alternatif pour les Réseaux Sociaux -->
                <?php if ($content['dateAffichee'] !== '') : ?>
                  <p class="actu-date"><?php echo htmlspecialchars($content['dateAffichee'], ENT_QUOTES, 'UTF-8'); ?></p>
                <?php elseif ($content['plateforme'] !== '') : ?>
                  <!-- Si la date est vide ET qu'il s'agit d'une actualité Réseaux Sociaux -->
                  <p class="actu-date">Information disponible sur place</p>
                <?php endif; ?>

                <div class="actu-bloc">
                  <!-- Image interne (.actu-bloc img) -->
                  <?php if ($content['image'] !== '') : ?>
                    <img src="<?php echo htmlspecialchars(site_path($content['image']), ENT_QUOTES, 'UTF-8'); ?>" alt="Visuel de l'actualité">
                  <?php endif; ?>
                  
                  <div class="actu-wrapper">
                    <h3 class="actu-titre"><?php echo nl2br($content['titre']); ?></h3>
                    <p class="actu-resume"><?php echo $content['resume']; ?></p>
                  </div>
                </div>
                
                <!-- Section Liens et iframe Facebook -->
                <?php if ($content['lien'] !== '') : ?>
                  <div class="actu-action">
                    <?php if (stripos($content['lien'], '<iframe') !== false) : ?> 
                      <div class="fb-iframe-wrapper" style="position:relative;">
                        <div class="fb-fallback" style="margin-bottom:15px;">
                          <a data-type="external" href="https://www.facebook.com/share/p/1Cf7UvAuLQ/" target="_blank" rel="noopener noreferrer">
                            Voir directement la publication sur Facebook
                          </a>
                          <span class="lien-e-seul"></span>
                        </div>
                        <?php echo str_replace('<iframe', '<iframe class="fb-embed-iframe"', $content['lien']); ?> 
                      </div>
                    <?php else: ?> 
                      <p style="margin-bottom:15px;">
                        <a data-type="external" href="<?php echo htmlspecialchars($content['lien'], ENT_QUOTES, 'UTF-8'); ?>" target="_blank" rel="noopener noreferrer">
                          Voir la publication sur <?php echo htmlspecialchars($content['plateformeLabel'], ENT_QUOTES, 'UTF-8'); ?>
                        </a>
                        <span class="lien-e-seul"></span>
                      </p>
                    <?php endif; ?>
                  </div>
                  
                  <!-- Variable plateforme : uniquement pour les actualités Réseaux Sociaux -->
                  <?php if ($content['plateforme'] !== '') : ?>
                    <p class="actu-action" style="margin-top:15px;">
                      <a data-type="external" href="<?php echo htmlspecialchars('https://www.instagram.com/p/De7EDnCtFiB/?utm_source=ig_web_copy_link&cplk=NTc4MTIwNjQ2YQ==', ENT_QUOTES, 'UTF-8'); ?>" target="_blank" rel="noopener noreferrer">Voir la publication sur Instagram</a>
                      <span class="lien-e-seul"></span>
                    </p>
                  <?php endif; ?>
                <?php endif; ?>

              </div><!-- fermeture balise .ACTU-CONTENT-->
            <?php endforeach; ?>

          </article>
        <?php endif; ?>
      <?php endforeach; ?>

    </div><!-- fermeture balise .ACTUS-LIST -->

    <div class="conteneur-feuilles">
      <img class="feuilles" alt="" src="<?php echo htmlspecialchars(site_path('/images/i_feuilles.svg'), ENT_QUOTES, 'UTF-8'); ?>" loading="lazy">
    </div>

  </section>
</main>

<script>
document.addEventListener("DOMContentLoaded", function() {
    var iframes = document.querySelectorAll('.fb-embed-iframe');
    iframes.forEach(function(iframe) {
        var hasLoaded = false;
        iframe.onload = function() {
            hasLoaded = true;
        };
        setTimeout(function() {
            if (!hasLoaded) {
                iframe.style.height = '0px';
                iframe.style.display = 'none';
            }
        }, 2500);
    });
});
</script>

<?php require __DIR__ . '/../includes/footer.php'; ?>