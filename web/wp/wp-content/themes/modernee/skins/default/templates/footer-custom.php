<?php
/**
 * The template to display default site footer
 *
 * @package MODERNEE
 * @since MODERNEE 1.0.10
 */

$modernee_footer_id = modernee_get_custom_footer_id();
$modernee_footer_meta = get_post_meta( $modernee_footer_id, 'trx_addons_options', true );
if ( ! empty( $modernee_footer_meta['margin'] ) ) {
	modernee_add_inline_css( sprintf( '.page_content_wrap{padding-bottom:%s}', esc_attr( modernee_prepare_css_value( $modernee_footer_meta['margin'] ) ) ) );
}
?>
<footer class="footer_wrap footer_custom footer_custom_<?php echo esc_attr( $modernee_footer_id ); ?> footer_custom_<?php echo esc_attr( sanitize_title( get_the_title( $modernee_footer_id ) ) ); ?>
						<?php
						$modernee_footer_scheme = modernee_get_theme_option( 'footer_scheme' );
						if ( ! empty( $modernee_footer_scheme ) && ! modernee_is_inherit( $modernee_footer_scheme  ) ) {
							echo ' scheme_' . esc_attr( $modernee_footer_scheme );
						}
						?>
						">
	<?php
	// Custom footer's layout
	do_action( 'modernee_action_show_layout', $modernee_footer_id );
	?>
</footer><!-- /.footer_wrap -->
