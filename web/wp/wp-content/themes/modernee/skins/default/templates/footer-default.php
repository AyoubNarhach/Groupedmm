<?php
/**
 * The template to display default site footer
 *
 * @package MODERNEE
 * @since MODERNEE 1.0.10
 */

?>
<footer class="footer_wrap footer_default
<?php
$modernee_footer_scheme = modernee_get_theme_option( 'footer_scheme' );
if ( ! empty( $modernee_footer_scheme ) && ! modernee_is_inherit( $modernee_footer_scheme  ) ) {
	echo ' scheme_' . esc_attr( $modernee_footer_scheme );
}
?>
				">
	<?php

	// Footer widgets area
	get_template_part( apply_filters( 'modernee_filter_get_template_part', 'templates/footer-widgets' ) );

	// Logo
	get_template_part( apply_filters( 'modernee_filter_get_template_part', 'templates/footer-logo' ) );

	// Socials
	get_template_part( apply_filters( 'modernee_filter_get_template_part', 'templates/footer-socials' ) );

	// Copyright area
	get_template_part( apply_filters( 'modernee_filter_get_template_part', 'templates/footer-copyright' ) );

	?>
</footer><!-- /.footer_wrap -->
