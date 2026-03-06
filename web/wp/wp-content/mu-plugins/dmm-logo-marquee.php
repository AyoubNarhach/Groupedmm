<?php
/**
 * Plugin Name: DMM Logo Marquee
 * Description: Animation continue des logos + section inclinée plein écran.
 */

/* ── CSS ─────────────────────────────────────────────────────────────── */
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

/*
 * ── COUCHE 1 (priority 1) ─────────────────────────────────────────────
 * S'exécute AVANT tous les scripts enqueued (Elementor, ElementsKit)
 * car wp_print_footer_scripts() est à priority 20.
 *
 * On surcharge jQuery.fn.data() dans la ready-queue de jQuery.
 * Notre callback est enregistré EN PREMIER → s'exécute EN PREMIER.
 * Quand ElementsKit appelle ensuite e.find('…').data('config'),
 * notre override est déjà en place et injecte loop:true.
 */
add_action( 'wp_footer', function () { ?>
<script>
jQuery(function ($) {
    var _orig = $.fn.data;
    $.fn.data = function (key) {
        var res = _orig.apply(this, arguments);
        if (
            key === 'config' &&
            arguments.length === 1 &&
            res && typeof res === 'object' &&
            this[0] && this[0].classList &&
            this[0].classList.contains('elementskit-clients-slider')
        ) {
            /* Force loop infini + désactive tous les arrêts */
            res.loop         = true;
            res.pauseOnHover = false;
            if (res.autoplay === true) {
                res.autoplay = { delay: 2000, disableOnInteraction: false, pauseOnMouseEnter: false };
            } else if (res.autoplay && typeof res.autoplay === 'object') {
                res.autoplay.disableOnInteraction = false;
                res.autoplay.pauseOnMouseEnter    = false;
            }
        }
        return res;
    };
});
</script>
<?php }, 1 );  /* ← priority 1, avant wp_print_footer_scripts() à priority 20 */

/*
 * ── COUCHE 2 (priority 25) ────────────────────────────────────────────
 * Filet de sécurité après l'init Swiper :
 * - bloque sw.autoplay.stop() sur l'instance Swiper
 * - redémarre toutes les 500 ms si arrêt inattendu
 */
add_action( 'wp_footer', function () { ?>
<script>
(function () {
    function fix() {
        document.querySelectorAll('.elementskit-clients-slider').forEach(function (slider) {
            var el = slider.querySelector('.ekit-main-swiper, .swiper-container, .swiper');
            if (!el || !el.swiper) return;
            var sw = el.swiper;

            if (!sw._dmmFixed) {
                sw._dmmFixed      = true;
                sw.autoplay.stop  = function () {}; /* noop */
            }
            if (!sw.autoplay.running) sw.autoplay.start();
        });
    }
    /* Démarre dès que Swiper est prêt, tourne 60 s */
    var t = setInterval(fix, 500);
    setTimeout(function () { clearInterval(t); }, 60000);
})();
</script>
<?php }, 25 );
