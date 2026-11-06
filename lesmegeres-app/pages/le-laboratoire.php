<?php require __DIR__ . '/../includes/header.php'; ?>
<main>
    <section>

      <div class="conteneur-champignons">
        <img src="<?php echo htmlspecialchars(site_path('/images/i_champignons.svg'), ENT_QUOTES, 'UTF-8');?>" class="champignons" alt="" loading="lazy">
        <h1>Le laboratoire</h1>
      </div><!--fermeture balise .CONTENEUR-CHAMPIGNONS-->
      
      <p class="p_first">
        Toute l'équipe de la compagnie <em>Les Mégères de l'Humus</em> travaille d'arrache-pieds, ne vous en faîtes pas, personne ne vas souffrir, pour vous 
        apporter dans les délais les plus brefs un contenu adapté à cette page qui pour l'instant semble bien vide ! Mais cela va changer... 
        prochainement.  
      </p>
      
      <div class="conteneur-feuilles">
        <img src="<?php echo htmlspecialchars(site_path('/images/i_feuilles.svg'), ENT_QUOTES, 'UTF-8');?>" class="feuilles" alt="" loading="lazy">
      </div><!--fermeture balise .CONTENEUR-FEUILLES-->

    </section>
</main>
<?php require __DIR__ . '/../includes/footer.php'; ?>
