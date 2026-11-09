        <footer>
            <p><a href="<?php echo htmlspecialchars(site_path('/credits'), ENT_QUOTES, 'UTF-8'); ?>">Crédits</a></p>
            <p><a href="<?php echo htmlspecialchars(site_path('/mentions-legales'), ENT_QUOTES, 'UTF-8'); ?>">Mentions Légales</a></p>
            <p><a href="<?php echo htmlspecialchars(site_path('/politique-de-securite'), ENT_QUOTES, 'UTF-8'); ?>">Politique de sécurité</a></p>
            <button id="openHaOverlay" class="e_faire-un-don">&nbsp;Faire un don</button>
            <div id="haWidgetModal" style="position: fixed; inset: 0; display: none; align-items: center; justify-content: center; backdrop-filter: blur(.9375rem) brightness(0.5); z-index: 2147483647;">
                <button id="closeHaWidgetBtn" aria-label="Accéder à notre page de don" style="position: absolute; top: .5rem; right: 1.5rem; z-index: 2147483648; background: #EFEFF4; border: none; border-radius: 50%; width: 48px; height: 48px; display: flex; align-items: center; justify-content: center; cursor: pointer; box-shadow: -2px 2px 8px rgba(228,137,106,.6);">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                </button>
                <div class="modal-content" style="position: relative; width: 100%; max-width: 950px; height: 100%; overflow: hidden;">
                    <iframe
                        id="haWidget"
                        src="https://www.helloasso.com/associations/les-megeres-de-l-humus/formulaires/1/widget?view=overlay"
                        style="width: 100%; height: 100%; border: none; border-radius: 8px;">
                    </iframe>
                </div>
            </div>
        </footer>
        <div class="sub-footer-copyright">
            <p><i class="fa-regular fa-copyright" aria-hidden="true"></i>&nbsp;2026&nbsp;Les Mégères de l'Humus. Tous droits réservés</p>
        </div> 
    </div><!--fermeture de la balise wrapper-->
        <!--Hamburger accessibilité-->
        <script src="<?php echo htmlspecialchars(site_path('/script/app.js'), ENT_QUOTES, 'UTF-8'); ?>?v=20260406" defer></script>
        <!-- Script pour les liens internes -->
        <script src="<?php echo htmlspecialchars(site_path('/script/menu-active-state.js'), ENT_QUOTES, 'UTF-8'); ?>" defer></script>
        <script src="<?php echo htmlspecialchars(site_path('/script/audio.js'), ENT_QUOTES, 'UTF-8'); ?>" defer></script>
        <script src="<?php echo htmlspecialchars(site_path('/script/video.js'), ENT_QUOTES, 'UTF-8'); ?>" defer></script>
        <script src="<?php echo htmlspecialchars(site_path('/script/don-ha.js'), ENT_QUOTES, 'UTF-8'); ?>" defer></script>
    </body>
</html>