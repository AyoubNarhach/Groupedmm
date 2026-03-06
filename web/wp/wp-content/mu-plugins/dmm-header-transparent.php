<?php
/**
 * Plugin Name: DMM Header Transparent on Top
 * Description: Header transparent at page top, solid black when scrolled.
 */

add_action( 'wp_footer', function () {
    ?>
    <script>
    (function () {
        'use strict';

        function dmmInitHeader() {
            // Selectionne le header (top_panel = Modernee, .she-header-yes = SHE plugin)
            var header = document.querySelector('header.top_panel, .she-header-yes');
            if (!header) return;

            function setTransparent() {
                header.style.setProperty('background-color', 'transparent', 'important');
                header.style.setProperty('background-image', 'none', 'important');
                // Aussi les sections Elementor internes
                var sections = header.querySelectorAll('.elementor-section, .e-con');
                sections.forEach(function(el) {
                    el.style.setProperty('background-color', 'transparent', 'important');
                    el.style.setProperty('background-image', 'none', 'important');
                });
            }

            function setSolidBlack() {
                header.style.setProperty('background-color', '#000000', 'important');
                // Les sections internes peuvent rester transparentes (le header porte la couleur)
                var sections = header.querySelectorAll('.elementor-section, .e-con');
                sections.forEach(function(el) {
                    el.style.removeProperty('background-color');
                    el.style.removeProperty('background-image');
                });
            }

            function onScroll() {
                if (window.pageYOffset > 10) {
                    setSolidBlack();
                } else {
                    setTransparent();
                }
            }

            // Appliquer immédiatement
            onScroll();

            // Écouter le scroll
            window.addEventListener('scroll', onScroll, { passive: true });
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', dmmInitHeader);
        } else {
            dmmInitHeader();
        }
    })();
    </script>
    <?php
} );
