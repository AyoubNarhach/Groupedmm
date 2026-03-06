<?php
/**
 * Plugin Name: DMM Logo Marquee
 * Description: Animation continue des logos + section inclinée plein écran.
 */

/* ── CSS : section inclinée plein écran ─────────────────────────────── */
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

/* ── JS : forcer Swiper en défilement continu ────────────────────────── */
add_action( 'wp_footer', function () { ?>
<script>
(function () {
    function fix(slider) {
        if (slider.dataset.dmmFixed) return;
        if (!slider.classList.contains('swiper-initialized')) return;
        var sw = slider.swiper;
        if (!sw) return;

        slider.dataset.dmmFixed = '1';

        /* Défilement continu : délai 0, vitesse de transition douce */
        sw.params.loop                          = true;
        sw.params.speed                         = 4000;
        sw.params.autoplay                      = sw.params.autoplay || {};
        sw.params.autoplay.delay                = 0;
        sw.params.autoplay.disableOnInteraction = false;
        sw.params.autoplay.pauseOnMouseEnter    = false;

        try { sw.loopDestroy(); sw.loopCreate(); } catch (e) {}
        sw.update();
        sw.autoplay.start();
    }

    function scan() {
        document.querySelectorAll('.elementskit-clients-slider').forEach(fix);
    }

    /* Polling jusqu'à init Swiper */
    var t = setInterval(scan, 300);
    setTimeout(function () { clearInterval(t); }, 15000);

    /* Relance autoplay si jamais il s'arrête */
    setInterval(function () {
        document.querySelectorAll('.elementskit-clients-slider').forEach(function (slider) {
            var sw = slider.swiper;
            if (sw && sw.autoplay && !sw.autoplay.running) sw.autoplay.start();
        });
    }, 1000);
})();
</script>
<?php }, 20 );
