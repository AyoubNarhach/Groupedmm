<?php
/**
 * The template to display default site header
 *
 * @package MODERNEE
 * @since MODERNEE 1.0
 */

$modernee_header_css   = '';
$modernee_header_image = get_header_image();
$modernee_header_video = modernee_get_header_video();
if ( ! empty( $modernee_header_image ) && modernee_trx_addons_featured_image_override( is_singular() || modernee_storage_isset( 'blog_archive' ) || is_category() ) ) {
	$modernee_header_image = modernee_get_current_mode_image( $modernee_header_image );
}

?><header class="top_panel top_panel_default
	<?php
	echo ! empty( $modernee_header_image ) || ! empty( $modernee_header_video ) ? ' with_bg_image' : ' without_bg_image';
	if ( '' != $modernee_header_video ) {
		echo ' with_bg_video';
	}
	if ( '' != $modernee_header_image ) {
		echo ' ' . esc_attr( modernee_add_inline_css_class( 'background-image: url(' . esc_url( $modernee_header_image ) . ');' ) );
	}
	if ( is_single() && has_post_thumbnail() ) {
		echo ' with_featured_image';
	}
	if ( modernee_is_on( modernee_get_theme_option( 'header_fullheight' ) ) ) {
		echo ' header_fullheight modernee-full-height';
	}
	$modernee_header_scheme = modernee_get_theme_option( 'header_scheme' );
	if ( ! empty( $modernee_header_scheme ) && ! modernee_is_inherit( $modernee_header_scheme  ) ) {
		echo ' scheme_' . esc_attr( $modernee_header_scheme );
	}
	?>
">
	<?php

	// Background video
	if ( ! empty( $modernee_header_video ) ) {
		get_template_part( apply_filters( 'modernee_filter_get_template_part', 'templates/header-video' ) );
	}

	// Main menu
	get_template_part( apply_filters( 'modernee_filter_get_template_part', 'templates/header-navi' ) );

	// Mobile header
	if ( modernee_is_on( modernee_get_theme_option( 'header_mobile_enabled' ) ) ) {
		get_template_part( apply_filters( 'modernee_filter_get_template_part', 'templates/header-mobile' ) );
	}

	// Page title and breadcrumbs area
	if ( ! is_single() ) {
		get_template_part( apply_filters( 'modernee_filter_get_template_part', 'templates/header-title' ) );
	}

	// Header widgets area
	get_template_part( apply_filters( 'modernee_filter_get_template_part', 'templates/header-widgets' ) );
	?>
</header>
