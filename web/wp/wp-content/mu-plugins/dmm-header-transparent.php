<?php
/**
 * Plugin Name: DMM Header Transparent on Top
 * Description: Header transparent at page top, solid black when scrolled.
 */

add_action( 'wp_head', function () {
    ?>
    <style id="dmm-header-zindex">
        /* Garantit que le header sticky est toujours au-dessus du contenu de page */
        .elementor-element-26b0e58.elementor-sticky,
        .elementor-element-26b0e58.elementor-sticky--active {
            z-index: 9999 !important;
        }
    </style>
    <?php
} );

add_action( 'wp_footer', function () {
    ?>
    <script>
    (function () {
        'use strict';

        // L'element Elementor du header sticky (data-id="26b0e58")
        // Le background Elementor est appliqué sur le container et son .e-con-inner
        var header = document.querySelector('.elementor-element-26b0e58');
        if (!header) return;

        var inner = header.querySelector(':scope > .e-con-inner');

        function apply(bgColor) {
            header.style.setProperty('background-color', bgColor, 'important');
            header.style.setProperty('background-image', 'none', 'important');
            if (inner) {
                inner.style.setProperty('background-color', bgColor, 'important');
                inner.style.setProperty('background-image', 'none', 'important');
            }
        }

        function onScroll() {
            apply(window.pageYOffset > 10 ? '#000000' : 'transparent');
        }

        onScroll();
        window.addEventListener('scroll', onScroll, { passive: true });
    })();
    </script>
    <?php
} );
