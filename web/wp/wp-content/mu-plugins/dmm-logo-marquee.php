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

        /* Attend que Swiper soit vraiment prêt */
        if (!slider.classList.contains('swiper-initialized')) return;

        slider.dataset.dmmDone = '1';

        /* Slides originaux uniquement (pas les clones Swiper) */
        var slides = Array.from(slider.querySelectorAll('.swiper-slide:not(.swiper-slide-duplicate)'));
        if (!slides.length) { delete slider.dataset.dmmDone; return; }

        /* Force le chargement des images lazy (Swiper lazy / srcset) */
        slides.forEach(function (s) {
            s.querySelectorAll('img').forEach(function (img) {
                ['data-lazy', 'data-src', 'data-lazy-src'].forEach(function (attr) {
                    if (img.getAttribute(attr)) img.src = img.getAttribute(attr);
                });
                img.loading = 'eager';
            });
        });

        /* Détruit Swiper sur l'élément racine */
        if (slider.swiper) {
            try { slider.swiper.destroy(true, true); } catch (e) {}
        }

        /* Construit le track : 2 copies pour le loop seamless */
        var track = document.createElement('div');
        track.className = 'dmm-marquee-track';
        [0, 1].forEach(function () {
            slides.forEach(function (s) { track.appendChild(s.cloneNode(true)); });
        });

        /* Remplace seulement le swiper-wrapper, garde le reste intact */
        var wrapper = slider.querySelector('.swiper-wrapper');
        if (wrapper) {
            wrapper.parentNode.replaceChild(track, wrapper);
        } else {
            slider.innerHTML = '';
            slider.appendChild(track);
        }

        slider.style.overflow = 'hidden';

        /* Durée calculée après rendu */
        requestAnimationFrame(function () {
            requestAnimationFrame(function () {
                var w = track.scrollWidth / 2;
                if (w > 50) track.style.animationDuration = (w / 80) + 's';
            });
        });
    }

    function scan() {
        document.querySelectorAll('.elementskit-clients-slider.swiper-initialized').forEach(init);
    }

    var t = setInterval(function () { scan(); }, 400);
    setTimeout(function () { clearInterval(t); }, 15000);

    new MutationObserver(scan).observe(document.body, { childList: true, subtree: true, attributes: true, attributeFilter: ['class'] });
})();
</script>
<?php }, 20 );
