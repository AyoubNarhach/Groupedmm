<?php
/**
 * The template to display the socials in the footer
 *
 * @package MODERNEE
 * @since MODERNEE 1.0.10
 */


// Socials
if ( modernee_is_on( modernee_get_theme_option( 'socials_in_footer' ) ) ) {
	$modernee_output = modernee_get_socials_links();
	if ( '' != $modernee_output ) {
		?>
		<div class="footer_socials_wrap socials_wrap">
			<div class="footer_socials_inner">
				<?php modernee_show_layout( $modernee_output ); ?>
			</div>
		</div>
		<?php
	}
}
