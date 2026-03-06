<?php
/**
 * Plugin Name: DMM Header Transparent on Top
 * Description: Header transparent at page top, solid black when scrolled.
 */

add_action( 'wp_head', function () {
    ?>
    <style id="dmm-header-transparent">
        /*
         * DMM Header:
         * - Transparent au sommet de la page (classe .header = pas encore scrollé)
         * - Fond noir #000 quand scrollé (classe .she-header = sticky actif)
         *
         * Le plugin "Sticky Header Effects for Elementor" bascule entre
         * .header (top) et .she-header (scrollé) sur .she-header-yes
         */

        /* Au top : transparent, toutes les couches Elementor et Modernee */
        .she-header-yes:not(.she-header),
        .she-header-yes.header,
        .she-header-yes.header .elementor-section,
        .she-header-yes.header .e-con,
        .top_panel .sc_layouts_row:not(.sc_layouts_row_fixed_on),
        body:not(.trx_addons_page_scrolled) .top_panel {
            background-color: transparent !important;
            background-image: none !important;
        }

        /* Quand scrollé : fond noir solide */
        .she-header-yes.she-header,
        .sc_layouts_row_fixed_on {
            background-color: #000000 !important;
        }
    </style>
    <?php
} );
