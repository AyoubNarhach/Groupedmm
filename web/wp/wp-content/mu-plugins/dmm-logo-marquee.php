<?php
/**
 * Plugin Name: DMM Styles
 * Description: Section inclinée plein écran.
 */
add_action( 'wp_head', function () { ?>
<style>
body { overflow-x: hidden !important; }
.incline2 {
    margin-left:  -50vw !important;
    margin-right: -50vw !important;
    width:        calc(100% + 100vw) !important;
    box-sizing:   border-box !important;
    padding-left:  50vw !important;
    padding-right: 50vw !important;
    max-width:    none !important;
}
</style>
<?php }, 5 );
