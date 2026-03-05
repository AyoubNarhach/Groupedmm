<?php
/**
 * The template to display custom header from the ThemeREX Addons Layouts
 *
 * @package MODERNEE
 * @since MODERNEE 1.0.06
 */

$modernee_header_css   = '';
$modernee_header_image = get_header_image();
$modernee_header_video = modernee_get_header_video();
if ( ! empty( $modernee_header_image ) && modernee_trx_addons_featured_image_override( is_singular() || modernee_storage_isset( 'blog_archive' ) || is_category() ) ) {
	$modernee_header_image = modernee_get_current_mode_image( $modernee_header_image );
}

$modernee_header_id = modernee_get_custom_header_id();
$modernee_header_meta = get_post_meta( $modernee_header_id, 'trx_addons_options', true );
if ( ! empty( $modernee_header_meta['margin'] ) ) {
	modernee_add_inline_css( sprintf( '.page_content_wrap{padding-top:%s}', esc_attr( modernee_prepare_css_value( $modernee_header_meta['margin'] ) ) ) );
}

?><header class="top_panel top_panel_custom top_panel_custom_<?php echo esc_attr( $modernee_header_id ); ?> top_panel_custom_<?php echo esc_attr( sanitize_title( get_the_title( $modernee_header_id ) ) ); ?>
				<?php
				echo ! empty( $modernee_header_image ) || ! empty( $modernee_header_video )
					? ' with_bg_image'
					: ' without_bg_image';
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

	// Custom header's layout
	do_action( 'modernee_action_show_layout', $modernee_header_id );

	// Header widgets area
	get_template_part( apply_filters( 'modernee_filter_get_template_part', 'templates/header-widgets' ) );

	?>
</header>
