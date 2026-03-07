<?php
/**
 * Plugin Name: DMM Formation Search
 * Description: Recherche dynamique + filtres thématiques pour les formations (sans rechargement de page).
 */

/* ── AJAX : recherche de formations ────────────────────────────────── */
add_action( 'wp_ajax_dmm_search_formations',        'dmm_search_formations_cb' );
add_action( 'wp_ajax_nopriv_dmm_search_formations', 'dmm_search_formations_cb' );

function dmm_search_formations_cb() {
	check_ajax_referer( 'dmm_formations', 'nonce' );

	$search = sanitize_text_field( $_POST['search'] ?? '' );
	$term   = sanitize_text_field( $_POST['term']   ?? '' );

	$args = [
		'post_type'      => 'cpt_courses',
		'post_status'    => 'publish',
		'posts_per_page' => -1,
		'orderby'        => 'date',
		'order'          => 'DESC',
	];

	if ( $search ) {
		$args['s'] = $search;
	}

	if ( $term && 'all' !== $term ) {
		$args['tax_query'] = [ [
			'taxonomy' => 'cpt_courses_group',
			'field'    => 'slug',
			'terms'    => $term,
		] ];
	}

	$q = new WP_Query( $args );

	ob_start();

	if ( $q->have_posts() ) {
		while ( $q->have_posts() ) {
			$q->the_post();
			$img   = get_the_post_thumbnail_url( null, 'medium_large' );
			$title = get_the_title();
			$link  = get_permalink();
			?>
			<div class="dmm-f-col">
				<div class="sc_courses_item sc_item_container post_container sc_courses_default">
					<?php if ( $img ) : ?>
					<div class="sc_courses_item_image">
						<div class="sc_courses_item_thumb trx_addons_hover trx_addons_hover_style_zoomin">
							<img src="<?php echo esc_url( $img ); ?>"
							     alt="<?php echo esc_attr( $title ); ?>"
							     style="width:100%;height:auto;display:block;">
						</div>
					</div>
					<?php endif; ?>
					<div class="sc_courses_item_info">
						<div class="sc_courses_item_header">
							<h4 class="sc_courses_item_title entry-title">
								<a href="<?php echo esc_url( $link ); ?>"><?php echo esc_html( $title ); ?></a>
							</h4>
						</div>
						<a href="<?php echo esc_url( $link ); ?>"
						   class="sc_button sc_button_simple sc_courses_item_button">
							VOIR PLUS
						</a>
					</div>
				</div>
			</div>
			<?php
		}
		wp_reset_postdata();
	} else {
		echo '<p class="dmm-no-results">Aucune formation trouvée.</p>';
	}

	$html = ob_get_clean();

	wp_send_json_success( [
		'html'  => $html,
		'count' => $q->found_posts,
	] );
}

/* ── AJAX : récupérer les termes de taxonomie ───────────────────────── */
add_action( 'wp_ajax_dmm_get_formation_terms',        'dmm_get_formation_terms_cb' );
add_action( 'wp_ajax_nopriv_dmm_get_formation_terms', 'dmm_get_formation_terms_cb' );

function dmm_get_formation_terms_cb() {
	$terms = get_terms( [
		'taxonomy'   => 'cpt_courses_group',
		'hide_empty' => true,
		'orderby'    => 'name',
		'order'      => 'ASC',
	] );

	if ( is_wp_error( $terms ) ) {
		wp_send_json_error();
	}

	wp_send_json_success( array_map( fn( $t ) => [
		'slug'  => $t->slug,
		'name'  => $t->name,
		'count' => $t->count,
	], $terms ) );
}

/* ── CSS + JS inline (footer) ───────────────────────────────────────── */
add_action( 'wp_footer', 'dmm_formation_search_output', 5 );

function dmm_formation_search_output() {
	$ajax_url = esc_js( admin_url( 'admin-ajax.php' ) );
	$nonce    = wp_create_nonce( 'dmm_formations' );
	?>
<style id="dmm-formation-search-css">
/* ── Filtres thématiques ── */
.dmm-filters {
	display: flex;
	flex-wrap: wrap;
	gap: 8px;
	margin: 18px 0 24px;
}
.dmm-filter-btn {
	padding: 7px 16px;
	border: 2px solid #ccc;
	background: transparent;
	cursor: pointer;
	font-size: 12px;
	font-weight: 700;
	text-transform: uppercase;
	letter-spacing: .06em;
	border-radius: 3px;
	transition: background .2s, border-color .2s, color .2s;
	line-height: 1.4;
}
.dmm-filter-btn:hover {
	border-color: #e05029;
	color: #e05029;
}
.dmm-filter-btn.active {
	background: #e05029;
	border-color: #e05029;
	color: #fff;
}
/* ── Grid des cartes ── */
.sc_courses_columns_wrap.dmm-ready {
	display: flex;
	flex-wrap: wrap;
	gap: 20px;
}
.dmm-f-col {
	flex: 0 0 calc(33.333% - 14px);
	max-width: calc(33.333% - 14px);
}
@media (max-width: 1023px) {
	.dmm-f-col { flex: 0 0 calc(50% - 10px); max-width: calc(50% - 10px); }
}
@media (max-width: 599px) {
	.dmm-f-col { flex: 0 0 100%; max-width: 100%; }
}
/* ── État chargement ── */
.sc_item_posts_container.dmm-loading {
	opacity: .35;
	pointer-events: none;
	transition: opacity .25s;
}
/* ── Pas de résultats ── */
.dmm-no-results {
	width: 100%;
	text-align: center;
	padding: 2.5em 0;
	color: #999;
	font-style: italic;
}
</style>

<script>
(function ($) {
	'use strict';

	var $input, $form, $container;
	var searchTimer  = null;
	var currentTerm  = 'all';
	var initialized  = false;
	var ajaxurl      = '<?php echo $ajax_url; ?>';
	var nonce        = '<?php echo esc_js( $nonce ); ?>';

	/* ── Détection du champ de recherche (multi-plugins) ──────────── */
	function findInput() {
		// 1. Filter Everything
		var $el = $( '.wpc-search-field' ).first();
		if ( $el.length ) return $el;
		// 2. WordPress standard search (name="s")
		$el = $( 'input[name="s"]' ).first();
		if ( $el.length ) return $el;
		// 3. Elementor Search Form
		$el = $( '.elementor-search-form__input' ).first();
		if ( $el.length ) return $el;
		// 4. Fallback : placeholder contenant "formation"
		return $( 'input' ).filter(function () {
			return /formation/i.test( $( this ).attr( 'placeholder' ) || '' );
		}).first();
	}

	/* ── Init ─────────────────────────────────────────────────────── */
	function init() {
		if ( initialized ) return;

		$input = findInput();
		if ( ! $input.length ) return;

		$form      = $input.closest( 'form' );
		$container = $( '.sc_item_posts_container' ).first();
		if ( ! $container.length ) return;

		initialized = true;

		/*
		 * Interception en phase de CAPTURE sur document.
		 * Cela s'exécute avant tous les handlers jQuery (phase bubble),
		 * y compris ceux de Filter Everything qui appelle form.submit().
		 */
		document.addEventListener( 'submit', function ( e ) {
			if ( e.target === $form[0] ) {
				e.preventDefault();
				e.stopPropagation();
				doSearch();
			}
		}, true /* capture */ );

		/*
		 * Filter Everything intercepte aussi le 'change' sur .wpc-search-field
		 * puis appelle form.submit() – on bloque ça aussi en capture.
		 */
		document.addEventListener( 'change', function ( e ) {
			if ( $( e.target ).hasClass( 'wpc-search-field' ) ) {
				e.stopPropagation(); // empêche FE de soumettre le form
			}
		}, true );

		/* Recherche live (debounce 400 ms) */
		$input.on( 'input keyup', function () {
			clearTimeout( searchTimer );
			searchTimer = setTimeout( doSearch, 400 );
		} );

		/* Charge les filtres thématiques */
		loadFilters();
	}

	/* ── Filtres thématiques ───────────────────────────────────────── */
	function loadFilters() {
		$.post( ajaxurl, { action: 'dmm_get_formation_terms' }, function (res) {
			if ( ! res.success || ! res.data.length ) return;

			var html = '<div class="dmm-filters">'
				+ '<button class="dmm-filter-btn active" data-term="all">Toutes les thématiques</button>';

			res.data.forEach(function (t) {
				html += '<button class="dmm-filter-btn" data-term="'
					+ t.slug + '">' + t.name + '</button>';
			});

			html += '</div>';

			/* Insère juste au-dessus du bloc formations */
			var $widget = $container.closest('[data-element_type="widget"]');
			if ( $widget.length ) {
				$( html ).insertBefore( $widget );
			} else {
				$( html ).insertBefore( $container );
			}

			/* Clic sur un filtre */
			$( document ).on('click', '.dmm-filter-btn', function () {
				$('.dmm-filter-btn').removeClass('active');
				$( this ).addClass('active');
				currentTerm = $( this ).data('term');
				doSearch();
			});
		});
	}

	/* ── Appel AJAX de recherche ───────────────────────────────────── */
	function doSearch() {
		$container.addClass('dmm-loading');

		$.post( ajaxurl, {
			action : 'dmm_search_formations',
			nonce  : nonce,
			search : $input.val().trim(),
			term   : currentTerm,
		}, function (res) {
			$container.removeClass('dmm-loading');
			if ( res.success ) {
				/* Ajoute la classe dmm-ready pour activer le layout flex */
				$container.addClass('dmm-ready').html( res.data.html );
			}
		});
	}

	$( document ).ready( init );

})(jQuery);
</script>
	<?php
}
