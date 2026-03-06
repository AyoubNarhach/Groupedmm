<?php
/**
 * Plugin Name: DMM Fix Scroll Issues
 * Description: Corrige le double scrollbar et la molette bloquée causés par Elementor Pro Scroll Snap + trx_addons smooth scroll.
 */

/**
 * 1. CSS : annule le Scroll Snap d'Elementor Pro sur html/body
 *    (Scroll Snap injecte: html{height:100vh;overflow:hidden}, body{height:100vh;overflow:auto;scroll-snap-type:y mandatory}
 *    ce qui crée un double scrollbar et empêche la molette de fonctionner)
 */
add_action( 'wp_head', function () {
    ?>
    <style id="dmm-fix-scroll">
        /* Neutralise Elementor Pro Scroll Snap sur html/body */
        html {
            height: auto !important;
            overflow-y: auto !important;
            overflow-x: hidden !important;
            margin: 0 !important;
        }
        body {
            height: auto !important;
            overflow: visible !important;
            scroll-snap-type: none !important;
        }
        /* Neutralise scroll-snap-align sur les sections Elementor */
        .elementor-section:not(.elementor-inner-section),
        .e-con:not(.e-child) {
            scroll-snap-align: none !important;
            scroll-snap-stop: normal !important;
        }
    </style>
    <?php
}, 999 ); // priorité haute pour surcharger le CSS d'Elementor

/**
 * 2. JS : s'assure que la molette de souris scrolle bien la page,
 *    même si le smooth scroll de trx_addons tente de scroller $window
 *    alors que html a overflow:hidden.
 */
add_action( 'wp_footer', function () {
    ?>
    <script id="dmm-fix-scroll-js">
    (function () {
        'use strict';

        // Attend que la page soit chargée pour vérifier l'état du scroll
        window.addEventListener('load', function () {

            // Détecte si body ou html ont un état scroll anormal (héritage Scroll Snap)
            var html = document.documentElement;
            var body = document.body;

            var htmlStyle = window.getComputedStyle(html);
            var bodyStyle = window.getComputedStyle(body);

            var htmlOverflow = htmlStyle.overflowY;
            var bodyOverflow = bodyStyle.overflowY;

            // Si html est toujours overflow:hidden malgré notre CSS
            // (CSS Elementor en inline peut avoir plus de priorité),
            // forcer via JS
            if (htmlOverflow === 'hidden') {
                html.style.setProperty('overflow-y', 'auto', 'important');
                html.style.setProperty('height', 'auto', 'important');
            }
            if (bodyOverflow === 'auto' || bodyOverflow === 'scroll') {
                var bodyHeight = bodyStyle.height;
                // Si body a une hauteur fixe (100vh), la supprimer
                if (body.style.height && body.style.height !== 'auto') {
                    body.style.setProperty('height', 'auto', 'important');
                }
                body.style.setProperty('overflow', 'visible', 'important');
                body.style.setProperty('scroll-snap-type', 'none', 'important');
            }
        });

    })();
    </script>
    <?php
}, 999 );
