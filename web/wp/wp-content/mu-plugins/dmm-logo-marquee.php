<?php
/**
 * Plugin Name: DMM Logo Marquee Fix
 * Description: Corrige le bandeau logos ElementsKit : (1) fond blanc plein écran, (2) défilement continu sans saut, (3) logos taille uniforme.
 */

add_action( 'wp_head', function () {
    ?>
    <style id="dmm-marquee-css">
        /* === Logos : hauteur uniforme === */
        .elementskit-clients-slider .content-image img,
        .elementskit-clients-slider .single-client img {
            height: 55px !important;
            width: auto !important;
            max-height: 55px !important;
            max-width: 160px !important;
            object-fit: contain !important;
        }

        /* === Marquee CSS (remplace Swiper) === */
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
         * 1. Correction du fond incliné : étendre la section
         *    parente skewée pour couvrir toute la largeur
         * ───────────────────────────────────────────── */
        function fixInclinedBg(sliderEl) {
            // Cherche d'abord la section avec classe incline*
            var el = null;
            if (sliderEl.closest) {
                el = sliderEl.closest('.incline2') ||
                     sliderEl.closest('.incline1') ||
                     sliderEl.closest('[class*="incline"]');
            }

            // Sinon, cherche un ancêtre avec un transform skew
            if (!el) {
                var cur = sliderEl.parentElement;
                var depth = 0;
                while (cur && cur !== document.body && depth++ < 12) {
                    var cs = window.getComputedStyle(cur);
                    var t = cs.transform || '';
                    if (t && t !== 'none') {
                        var m = t.match(/matrix\(([^)]+)\)/);
                        if (m) {
                            var v = m[1].split(',').map(parseFloat);
                            if (Math.abs(v[1]) > 0.01 || Math.abs(v[2]) > 0.01) {
                                el = cur;
                                break;
                            }
                        }
                    }
                    cur = cur.parentElement;
                }
            }

            if (!el || el === document.body) return;

            // Calculer l'extension nécessaire selon le skew
            var extra = 120; // valeur sécurisée par défaut
            var cs = window.getComputedStyle(el);
            var t = cs.transform || '';
            if (t && t !== 'none') {
                var m = t.match(/matrix\(([^)]+)\)/);
                if (m) {
                    var v = m[1].split(',').map(parseFloat);
                    var h = el.offsetHeight;
                    // b = tan(skewY), c = tan(skewX)
                    extra = Math.ceil(Math.max(Math.abs(v[1]), Math.abs(v[2])) * h) + 60;
                }
            }

            el.style.setProperty('margin-left',  '-' + extra + 'px', 'important');
            el.style.setProperty('margin-right', '-' + extra + 'px', 'important');
            el.style.setProperty('width', 'calc(100% + ' + (extra * 2) + 'px)', 'important');
            el.style.setProperty('box-sizing', 'border-box', 'important');
            el.style.setProperty('padding-left',  extra + 'px', 'important');
            el.style.setProperty('padding-right', extra + 'px', 'important');

            if (el.parentElement) {
                el.parentElement.style.overflow = 'hidden';
            }
        }

        /* ─────────────────────────────────────────────
         * 2. Remplace le Swiper par une animation CSS
         *    continue et sans saut (2 copies du contenu
         *    → translateX 0% à -50% en boucle infinie)
         * ───────────────────────────────────────────── */
        function makeMarquee(sliderEl) {
            if (sliderEl.classList.contains('dmm-marquee-ready')) return;

            var swiperEl = sliderEl.querySelector('.ekit-main-swiper');
            if (!swiperEl) return;

            // Récupérer uniquement les slides originaux (sans les duplicatas Swiper)
            var originalSlides = Array.from(
                sliderEl.querySelectorAll('.swiper-slide:not(.swiper-slide-duplicate)')
            );
            if (originalSlides.length === 0) return;

            // Détruire l'instance Swiper
            if (swiperEl.swiper) {
                try { swiperEl.swiper.destroy(true, true); } catch (e) {}
            }

            // Construire le track avec 2 copies pour boucle seamless
            var track = document.createElement('div');
            track.className = 'dmm-marquee-track';

            for (var copy = 0; copy < 2; copy++) {
                var set = document.createElement('div');
                set.className = 'dmm-marquee-set';
                originalSlides.forEach(function (slide) {
                    var item = document.createElement('div');
                    item.className = 'dmm-slide-item';
                    var inner = slide.querySelector('.swiper-slide-inner');
                    if (inner) {
                        item.innerHTML = inner.innerHTML;
                    }
                    set.appendChild(item);
                });
                track.appendChild(set);
            }

            // Remplacer le contenu Swiper
            swiperEl.innerHTML = '';
            swiperEl.appendChild(track);
            swiperEl.style.overflow = 'hidden';
            swiperEl.style.width = '100%';

            sliderEl.classList.add('dmm-marquee-ready');

            // Calculer la durée après rendu réel du DOM
            requestAnimationFrame(function () {
                requestAnimationFrame(function () {
                    var set1 = track.querySelector('.dmm-marquee-set');
                    var setWidth = set1 ? set1.scrollWidth : 2000;
                    if (!setWidth || setWidth < 100) setWidth = 2000;

                    // Keyframes uniques pour éviter les conflits
                    var kfName = 'dmmEkitLoop' + Date.now();
                    var style = document.createElement('style');
                    style.textContent =
                        '@keyframes ' + kfName + ' {' +
                        '  from { transform: translateX(0); }' +
                        '  to   { transform: translateX(-50%); }' +
                        '}';
                    document.head.appendChild(style);

                    // Vitesse ~80 px/s — naturelle pour un bandeau logos
                    var duration = (setWidth / 80).toFixed(2);
                    track.style.animation = kfName + ' ' + duration + 's linear infinite';

                    // Corriger le fond incliné après reconstruction du DOM
                    fixInclinedBg(sliderEl);
                });
            });
        }

        /* ─────────────────────────────────────────────
         * Surveillance : déclencher dès que le Swiper
         * ElementsKit est initialisé
         * ───────────────────────────────────────────── */
        function scanAndFix() {
            document.querySelectorAll(
                '.elementskit-clients-slider:not(.dmm-marquee-ready)'
            ).forEach(function (el) {
                // Attendre que Swiper ait créé les classes swiper-initialized
                if (el.querySelector('.swiper-initialized')) {
                    makeMarquee(el);
                }
            });
        }

        // Polling pendant 20s max
        var attempts = 0;
        var poll = setInterval(function () {
            scanAndFix();
            if (++attempts >= 40) clearInterval(poll);
        }, 500);

        // Observer les changements de classe (Elementor lazy-load)
        new MutationObserver(function () {
            scanAndFix();
        }).observe(document.body, {
            subtree: true,
            attributes: true,
            attributeFilter: ['class']
        });

    })();
    </script>
    <?php
}, 20 );
