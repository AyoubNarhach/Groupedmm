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

<!-- Patch data-config AVANT que jQuery/ElementsKit le mette en cache -->
<script>
(function patchEarlyConfig() {
    function patch() {
        document.querySelectorAll('.elementskit-clients-slider[data-config]').forEach(function (el) {
            if (el.dataset.dmmPatched) return;
            try {
                var cfg = JSON.parse(el.getAttribute('data-config'));
                cfg.loop         = true;
                cfg.pauseOnHover = false;
                if (!cfg.autoplay || cfg.autoplay === true) {
                    cfg.autoplay = { delay: 0, disableOnInteraction: false, pauseOnMouseEnter: false };
                } else {
                    cfg.autoplay.disableOnInteraction = false;
                    cfg.autoplay.pauseOnMouseEnter    = false;
                }
                el.setAttribute('data-config', JSON.stringify(cfg));
                el.dataset.dmmPatched = '1';
            } catch (e) {}
        });
    }
    /* Enregistré dans <head>, avant tout script footer → first in queue */
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', patch);
    } else {
        patch();
    }
})();
</script>
<?php }, 1 );

/* ── JS footer : filet de sécurité si Swiper est quand même arrêté ──── */
add_action( 'wp_footer', function () { ?>
<script>
(function () {
    function ensureRunning() {
        document.querySelectorAll('.elementskit-clients-slider').forEach(function (slider) {
            var el = slider.querySelector('.ekit-main-swiper, .swiper-container, .swiper');
            if (!el || !el.swiper) return;
            var sw = el.swiper;

            /* Bloque stop() une seule fois */
            if (!sw._dmmNoStop) {
                sw._dmmNoStop = true;
                var origStop   = sw.autoplay.stop.bind(sw.autoplay);
                sw.autoplay.stop = function () {
                    /* Autorise l'arrêt uniquement si Swiper est détruit */
                    if (sw.destroyed) origStop();
                };
            }

            if (sw.autoplay && !sw.autoplay.running) {
                sw.autoplay.start();
            }
        });
    }

    /* Lance dès que Swiper est prêt, puis surveille toutes les 500 ms */
    var t = setInterval(ensureRunning, 500);
    setTimeout(function () { clearInterval(t); }, 30000);
})();
</script>
<?php }, 20 );
