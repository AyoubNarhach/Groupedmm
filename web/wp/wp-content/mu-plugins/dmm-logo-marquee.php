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

.dmm-marquee-wrap {
    overflow: hidden;
    width: 100%;
}
.dmm-marquee-track {
    display: flex;
    width: max-content;
    animation: dmm-scroll linear infinite;
}
@keyframes dmm-scroll {
    from { transform: translateX(0); }
    to   { transform: translateX(-50%); }
}
.dmm-marquee-track .swiper-slide {
    flex-shrink: 0;
    padding: 0 24px;
}
.dmm-marquee-track img {
    height: 70px !important;
    width: auto !important;
    object-fit: contain !important;
}
</style>
<?php }, 5 );

/* ── JS ─────────────────────────────────────────────────────────────── */
add_action( 'wp_footer', function () { ?>
<script>
(function () {
    function init(slider) {
        if (slider.dataset.dmmDone) return;
        slider.dataset.dmmDone = '1';

        /* Récupère les slides originaux (sans les clones Swiper) */
        var slides = Array.from(slider.querySelectorAll('.swiper-slide:not(.swiper-slide-duplicate)'));
        if (!slides.length) { delete slider.dataset.dmmDone; return; }

        /* Détruit Swiper */
        var swiperEl = slider.querySelector('.ekit-main-swiper, .swiper-container');
        if (swiperEl && swiperEl.swiper) {
            try { swiperEl.swiper.destroy(true, true); } catch(e) {}
        }

        /* Construit le conteneur marquee */
        var wrap = document.createElement('div');
        wrap.className = 'dmm-marquee-wrap';

        var track = document.createElement('div');
        track.className = 'dmm-marquee-track';

        /* 2 copies identiques pour le loop CSS seamless */
        [0, 1].forEach(function () {
            slides.forEach(function (s) {
                var clone = s.cloneNode(true);
                track.appendChild(clone);
            });
        });

        wrap.appendChild(track);
        slider.innerHTML = '';
        slider.appendChild(wrap);

        /* Calcule la durée en fonction de la largeur d'une copie */
        requestAnimationFrame(function () {
            var w = track.scrollWidth / 2;
            track.style.animationDuration = (w / 80) + 's';
        });
    }

    function scan() {
        document.querySelectorAll('.elementskit-clients-slider').forEach(function (el) {
            /* Attend que Swiper soit initialisé */
            if (el.querySelector('.swiper-initialized, .swiper-slide')) init(el);
        });
    }

    /* Polling léger jusqu'à init réussie */
    var t = setInterval(function () { scan(); }, 400);
    setTimeout(function () { clearInterval(t); }, 15000);

    /* Observe aussi les changements de DOM (lazy load) */
    new MutationObserver(scan).observe(document.body, { childList: true, subtree: true });
})();
</script>
<?php }, 20 );
