<?php
/**
 * Plugin Name: DMM Logo Marquee Fix
 * Description: Corrige le bandeau logos ElementsKit : (1) fond blanc plein écran, (2) défilement continu sans saut, (3) logos taille uniforme.
 */

add_action( 'wp_head', function () {
    ?>
    <style id="dmm-marquee-css">

        /* ── Empêche le scroll horizontal causé par l'extension ── */
        body { overflow-x: hidden !important; }

        /* ── Extension plein écran de la section inclinée ──
         * 50vw de chaque côté pour couvrir tout cas (clip-path ou transform).
         */
        .incline2 {
            margin-left:  -50vw !important;
            margin-right: -50vw !important;
            width:        calc(100% + 100vw) !important;
            box-sizing:   border-box !important;
            padding-left:  50vw !important;
            padding-right: 50vw !important;
            max-width:    none !important;
        }

        /* ── Logos : hauteur uniforme ── */
        .elementskit-clients-slider .content-image img,
        .elementskit-clients-slider .single-client img {
            height: 80px !important;
            width: auto !important;
            max-height: 80px !important;
            max-width: 200px !important;
            object-fit: contain !important;
        }

        /* ── Marquee CSS (remplace Swiper) ── */
        .elementskit-clients-slider.dmm-marquee-ready {
            overflow: hidden !important;
            width: 100% !important;
        }
        .elementskit-clients-slider.dmm-marquee-ready .ekit-main-swiper {
            overflow: hidden !important;
            width: 100% !important;
        }
        .dmm-marquee-track {
            display: flex !important;
            flex-wrap: nowrap !important;
            align-items: center !important;
            width: max-content !important;
        }
        .dmm-marquee-set {
            display: flex !important;
            flex-wrap: nowrap !important;
            align-items: center !important;
        }
        .dmm-slide-item {
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0 20px;
        }
    </style>
    <?php
}, 5 );

add_action( 'wp_footer', function () {
    ?>
    <script id="dmm-marquee-js">
    (function () {
        'use strict';

        /* ─────────────────────────────────────────────
         * Fix clip-path si la section inclinée en utilise un
         * ───────────────────────────────────────────── */
        function fixClipPath(sliderEl) {
            var el = sliderEl.closest ? sliderEl.closest('.incline2') : null;
            if (!el) return;

            var cs   = window.getComputedStyle(el);
            var clip = cs.clipPath || cs.webkitClipPath || 'none';
            if (!clip || clip === 'none' || clip.indexOf('polygon') === -1) return;

            var vw    = window.innerWidth || 1440;
            var extra = Math.round(vw * 0.5);

            var m = clip.match(/polygon\((.+)\)/);
            if (!m) return;

            var points  = m[1].split(',').map(function (p) { return p.trim(); });
            var changed = false;
            var newPoints = points.map(function (p) {
                var parts = p.match(/^(\S+)\s+(.+)$/);
                if (!parts) return p;
                var x = parts[1], y = parts[2];
                if (/^-?[\d.]+px$/.test(x)) {
                    var xv = parseFloat(x);
                    if (xv <= 0) {
                        x = (xv - extra) + 'px'; changed = true;
                    } else if (xv >= vw * 0.8) {
                        x = (xv + extra) + 'px'; changed = true;
                    }
                }
                return x + ' ' + y;
            });

            if (changed) {
                el.style.setProperty('clip-path', 'polygon(' + newPoints.join(', ') + ')', 'important');
            }
        }

        /* ─────────────────────────────────────────────
         * Remplace le Swiper par une animation CSS continue
         * ───────────────────────────────────────────── */
        function makeMarquee(sliderEl) {
            if (sliderEl.classList.contains('dmm-marquee-ready')) return;

            var swiperEl = sliderEl.querySelector('.ekit-main-swiper');
            if (!swiperEl) return;

            var originalSlides = Array.from(
                sliderEl.querySelectorAll('.swiper-slide:not(.swiper-slide-duplicate)')
            );
            if (originalSlides.length === 0) return;

            if (swiperEl.swiper) {
                try { swiperEl.swiper.destroy(true, true); } catch (e) {}
            }

            /* ── Mesure la largeur d'un set AVANT insertion dans le DOM ── */
            var probe = document.createElement('div');
            probe.style.cssText = 'position:absolute;top:-9999px;left:-9999px;display:flex;flex-wrap:nowrap;visibility:hidden;';
            originalSlides.forEach(function (slide) {
                var item  = document.createElement('div');
                item.style.cssText = 'flex-shrink:0;display:flex;align-items:center;justify-content:center;padding:0 20px;';
                var inner = slide.querySelector('.swiper-slide-inner');
                if (inner) item.innerHTML = inner.innerHTML;
                probe.appendChild(item);
            });
            document.body.appendChild(probe);
            var setWidth = probe.scrollWidth || 2000;
            document.body.removeChild(probe);
            if (setWidth < 100) setWidth = 2000;

            /* ── Calcule le nombre de copies pour couvrir 3× la largeur visible ── */
            var containerWidth = swiperEl.offsetWidth || window.innerWidth || 1440;
            var copies = Math.max(3, Math.ceil((containerWidth * 3) / setWidth) + 1);

            /* ── Construit le track ── */
            var track = document.createElement('div');
            track.className = 'dmm-marquee-track';

            for (var c = 0; c < copies; c++) {
                var set = document.createElement('div');
                set.className = 'dmm-marquee-set';
                originalSlides.forEach(function (slide) {
                    var item  = document.createElement('div');
                    item.className = 'dmm-slide-item';
                    var inner = slide.querySelector('.swiper-slide-inner');
                    if (inner) item.innerHTML = inner.innerHTML;
                    set.appendChild(item);
                });
                track.appendChild(set);
            }

            swiperEl.innerHTML = '';
            swiperEl.appendChild(track);
            swiperEl.style.overflow = 'hidden';
            swiperEl.style.width    = '100%';
            sliderEl.classList.add('dmm-marquee-ready');

            /* ── Anime : translateX(0) → translateX(-setWidthpx) pour un loop exact ── */
            var kfName = 'dmmEkitLoop' + Date.now();
            var style  = document.createElement('style');
            style.textContent =
                '@keyframes ' + kfName + ' {' +
                '  from { transform: translateX(0px); }' +
                '  to   { transform: translateX(-' + setWidth + 'px); }' +
                '}';
            document.head.appendChild(style);

            var duration = (setWidth / 80).toFixed(2);
            track.style.animation = kfName + ' ' + duration + 's linear infinite';

            fixClipPath(sliderEl);
        }

        function scanAndFix() {
            document.querySelectorAll(
                '.elementskit-clients-slider:not(.dmm-marquee-ready)'
            ).forEach(function (el) {
                if (el.querySelector('.swiper-initialized')) {
                    makeMarquee(el);
                }
            });
        }

        var attempts = 0;
        var poll = setInterval(function () {
            scanAndFix();
            if (++attempts >= 40) clearInterval(poll);
        }, 500);

        new MutationObserver(function () {
            scanAndFix();
        }).observe(document.body, {
            subtree: true, attributes: true, attributeFilter: ['class']
        });

    })();
    </script>
    <?php
}, 20 );
