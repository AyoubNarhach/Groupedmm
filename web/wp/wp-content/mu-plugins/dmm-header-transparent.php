<?php
/**
 * Plugin Name: DMM Header Transparent on Top
 * Description: Header transparent at page top, solid black when scrolled.
 */

add_action( 'wp_head', function () {
    ?>
    <style id="dmm-header-transparent">
        /* Header: transparent au top, noir solide au scroll */
        .top_panel .sc_layouts_row:not(.sc_layouts_row_fixed_on) {
            background-color: transparent !important;
            -webkit-transition: background-color 0.3s ease;
            transition: background-color 0.3s ease;
        }
        .sc_layouts_row_fixed_on {
            background-color: #000000 !important;
        }
        /* Assure que le top_panel lui-même n'a pas de fond opaque au top */
        body:not(.trx_addons_page_scrolled) .top_panel {
            background-color: transparent !important;
        }
    </style>
    <?php
} );
