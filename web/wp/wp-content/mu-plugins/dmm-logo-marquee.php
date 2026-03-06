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

    /* ElementsKit initialise Swiper sur .ekit-main-swiper (pas sur .elementskit-clients-slider).
     * On récupère l'instance via swiperEl.swiper, on bloque stop(), et on active le loop. */
    function fix(slider) {
        if (slider.dataset.dmmFixed) return;

        var swiperEl = slider.querySelector('.ekit-main-swiper') ||
                       slider.querySelector('.swiper-container') ||
                       slider.querySelector('.swiper');
        if (!swiperEl || !swiperEl.classList.contains('swiper-initialized')) return;

        var sw = swiperEl.swiper;
        if (!sw) return;

        slider.dataset.dmmFixed = '1';

        /* 1. Bloquer tout appel externe à autoplay.stop() (hover, fin de liste, etc.) */
        sw.autoplay.stop = function () {};

        /* 2. Activer le loop pour éviter l'arrêt en fin de liste */
        if (!sw.params.loop) {
            sw.params.loop = true;
            try { sw.loopCreate(); sw.update(); } catch (e) {}
        }

        /* 3. Démarrer / relancer l'autoplay */
        sw.autoplay.start();
    }

    function scan() {
        document.querySelectorAll('.elementskit-clients-slider').forEach(fix);
    }

    var t = setInterval(scan, 300);
    setTimeout(function () { clearInterval(t); }, 15000);

    new MutationObserver(scan).observe(document.body, {
        childList: true, subtree: true, attributes: true, attributeFilter: ['class']
    });
})();
</script>
<?php }, 20 );
