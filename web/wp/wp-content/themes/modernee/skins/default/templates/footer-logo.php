<?php
/**
 * The template to display the site logo in the footer
 *
 * @package MODERNEE
 * @since MODERNEE 1.0.10
 */

// Logo
if ( modernee_is_on( modernee_get_theme_option( 'logo_in_footer' ) ) ) {
	$modernee_logo_image = modernee_get_logo_image( 'footer' );
	$modernee_logo_text  = get_bloginfo( 'name' );
	if ( ! empty( $modernee_logo_image['logo'] ) || ! empty( $modernee_logo_text ) ) {
		?>
		<div class="footer_logo_wrap">
			<div class="footer_logo_inner">
				<?php
				if ( ! empty( $modernee_logo_image['logo'] ) ) {
					$modernee_attr = modernee_getimagesize( $modernee_logo_image['logo'] );
					echo '<a href="' . esc_url( home_url( '/' ) ) . '">'
							. '<img src="' . esc_url( $modernee_logo_image['logo'] ) . '"'
								. ( ! empty( $modernee_logo_image['logo_retina'] ) ? ' srcset="' . esc_url( $modernee_logo_image['logo_retina'] ) . ' 2x"' : '' )
								. ' class="logo_footer_image"'
								. ' alt="' . esc_attr__( 'Site logo', 'modernee' ) . '"'
								. ( ! empty( $modernee_attr[3] ) ? ' ' . wp_kses_data( $modernee_attr[3] ) : '' )
							. '>'
						. '</a>';
				} elseif ( ! empty( $modernee_logo_text ) ) {
					echo '<h1 class="logo_footer_text">'
							. '<a href="' . esc_url( home_url( '/' ) ) . '">'
								. esc_html( $modernee_logo_text )
							. '</a>'
						. '</h1>';
				}
				?>
			</div>
		</div>
		<?php
	}
}
