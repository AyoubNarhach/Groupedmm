<?php
/**
 * Plugin Name: DMM Logo Marquee Fix
 * Description: Corrige le bandeau logos : (1) fond blanc plein écran, (2) défilement continu sans saut, (3) logos taille uniforme.
 */

/**
 * CSS : taille uniforme des logos
 */
add_action( 'wp_head', function () {
    ?>
    <style id="dmm-marquee-css">
        /* === Logos : hauteur uniforme === */
        .trx-addons-marquee-item-image img,
        .trx-addons-marquee-item-gallery img {
            height: 55px !important;
            width: auto !important;
            max-height: 55px !important;
            max-width: 160px !important;
            object-fit: contain !important;
        }

        /* === Conteneur du marquee réécrit par notre JS === */
        .trx-addons-marquee-wrap.dmm-marquee-ready {
            overflow: hidden !important;
            display: block !important;
            white-space: nowrap !important;
            width: 100% !important;
        }
        .dmm-marquee-inner {
            display: inline-flex !important;
            flex-wrap: nowrap !important;
            align-items: center !important;
            width: max-content !important;
            white-space: nowrap !important;
        }
        .dmm-marquee-set {
            display: inline-flex !important;
            flex-wrap: nowrap !important;
            align-items: center !important;
        }
    </style>
    <?php
}, 5 );

/**
 * JS : animation continue + correction du fond
 */
add_action( 'wp_footer', function () {
    ?>
    <script id="dmm-marquee-js">
    (function () {
        'use strict';

        /* ─────────────────────────────────────────────
         * 1. Correction du fond blanc : étendre la section
         *    parent skewée pour couvrir toute la largeur
         * ───────────────────────────────────────────── */
        function fixSkewedParent(marqueeWrap) {
            var el = marqueeWrap.parentElement;
            var depth = 0;
            while (el && el !== document.body && depth++ < 8) {
                var cs = window.getComputedStyle(el);
                var transform = cs.transform || cs.webkitTransform || '';
                var hasSkew = false;

                // Détecter un skew via la matrix CSS
                if (transform && transform !== 'none' && transform.indexOf('matrix') === 0) {
                    // matrix(a, b, c, d, tx, ty) — b ou c non nuls = skew
                    var m = transform.match(/matrix\(([^)]+)\)/);
                    if (m) {
                        var vals = m[1].split(',').map(parseFloat);
                        // vals[1]=b (skewY contribution), vals[2]=c (skewX contribution)
                        if (Math.abs(vals[1]) > 0.01 || Math.abs(vals[2]) > 0.01) {
                            hasSkew = true;
                        }
                    }
                }
                // Détecter un skew dans le style inline direct
                if (!hasSkew && el.style.transform && /skew/i.test(el.style.transform)) {
                    hasSkew = true;
                }

                if (hasSkew) {
                    // Calculer l'extension nécessaire pour couvrir les coins
                    var h = el.offsetHeight;
                    var angle = 0;
                    if (transform.indexOf('matrix') === 0) {
                        var mVals = transform.match(/matrix\(([^)]+)\)/)[1].split(',').map(parseFloat);
                        // L'angle de skewY est Math.atan(b) en radians
                        angle = Math.atan(Math.abs(mVals[1]));
                    }
                    var extra = Math.ceil(Math.abs(Math.tan(angle)) * h) + 40;
                    el.style.setProperty('margin-left', '-' + extra + 'px', 'important');
                    el.style.setProperty('margin-right', '-' + extra + 'px', 'important');
                    el.style.setProperty('width', 'calc(100% + ' + (extra * 2) + 'px)', 'important');
                    // S'assurer que le parent masque le débordement
                    if (el.parentElement) {
                        el.parentElement.style.overflow = 'hidden';
                    }
                    return true;
                }
                el = el.parentElement;
            }
            return false;
        }

        /* ─────────────────────────────────────────────
         * 2. Animation CSS continue et seamless
         *    Remplace le TweenMax (qui crée un saut visible)
         *    par une animation CSS à boucle infinie.
         *
         *    Principe : dupliquer le contenu (2x) dans un
         *    inner flex container, puis animer translateX
         *    de 0% à -50% en boucle → les deux copies se
         *    suivent sans interruption.
         * ───────────────────────────────────────────── */
        function makeContinuous(wrap) {
            if (wrap.classList.contains('dmm-marquee-ready')) return;

            var $w = jQuery(wrap);
            var $elements = $w.find('.trx_addons_marquee_element');
            if ($elements.length === 0) return;

            // ── Récupérer le HTML du premier bloc (les logos originaux)
            var $first = $elements.eq(0);
            var logoHTML = $first.html();

            // ── Tuer tous les tweens TweenMax
            if (typeof TweenMax !== 'undefined') {
                $elements.each(function () {
                    try { TweenMax.killTweensOf(this); } catch (e) {}
                });
            }

            // ── Vider le wrap et reconstruire avec 2 copies
            $elements.remove();

            var $inner = jQuery('<div class="dmm-marquee-inner" aria-hidden="true"></div>');
            var $s1 = jQuery('<div class="dmm-marquee-set"></div>').html(logoHTML);
            var $s2 = jQuery('<div class="dmm-marquee-set"></div>').html(logoHTML);
            $inner.append($s1).append($s2);
            $w.append($inner);

            wrap.classList.add('dmm-marquee-ready');

            // ── Lire direction & vitesse depuis data-marquee
            var data = $w.data('marquee') || {};
            var speed = parseInt(data.speed, 10) || 5;
            var dir   = (parseInt(data.dir, 10) || -1) < 0 ? '-' : '';

            // ── Calculer la durée après que le rendu ait eu lieu
            requestAnimationFrame(function () {
                requestAnimationFrame(function () {
                    var setWidth = $s1[0].scrollWidth || 2000;

                    // Pixels par seconde proportionnel à la vitesse Elementor
                    var pxPerSec = 30 + speed * 20;
                    var duration = (setWidth / pxPerSec).toFixed(2);

                    // Nom unique pour les keyframes (évite les conflits)
                    var kfName = 'dmmMqLoop' + Date.now();
                    var style  = document.createElement('style');
                    style.textContent =
                        '@keyframes ' + kfName + ' {' +
                        '  from { transform: translateX(0); }' +
                        '  to   { transform: translateX(' + dir + '50%); }' +
                        '}';
                    document.head.appendChild(style);

                    $inner[0].style.animation =
                        kfName + ' ' + duration + 's linear infinite';

                    // ── Fix du fond incliné (après rebuild DOM)
                    fixSkewedParent(wrap);
                });
            });
        }

        /* ─────────────────────────────────────────────
         * Surveillance : déclencher dès que trx_addons
         * initialise un marquee (classe trx-addons-marquee-inited)
         * ───────────────────────────────────────────── */
        function scanAndFix() {
            document.querySelectorAll(
                '.trx-addons-marquee-wrap.trx-addons-marquee-inited:not(.dmm-marquee-ready)'
            ).forEach(function (el) {
                makeContinuous(el);
            });
        }

        // Poll pendant 15 secondes (max 30 tentatives toutes les 500ms)
        var attempts = 0;
        var poll = setInterval(function () {
            scanAndFix();
            if (++attempts >= 30) clearInterval(poll);
        }, 500);

        // Observer les changements de classe (cas Elementor lazy-load)
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
