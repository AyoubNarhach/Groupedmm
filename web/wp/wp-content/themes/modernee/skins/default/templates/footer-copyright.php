<?php
/**
 * The template to display the copyright info in the footer
 *
 * @package MODERNEE
 * @since MODERNEE 1.0.10
 */

// Copyright area
?> 
<div class="footer_copyright_wrap
<?php
$modernee_copyright_scheme = modernee_get_theme_option( 'copyright_scheme' );
if ( ! empty( $modernee_copyright_scheme ) && ! modernee_is_inherit( $modernee_copyright_scheme  ) ) {
	echo ' scheme_' . esc_attr( $modernee_copyright_scheme );
}
?>
				">
	<div class="footer_copyright_inner">
		<div class="content_wrap">
			<div class="copyright_text">
			<?php
				$modernee_copyright = modernee_get_theme_option( 'copyright' );
			if ( ! empty( $modernee_copyright ) ) {
				// Replace {{Y}} or {Y} with the current year
				$modernee_copyright = str_replace( array( '{{Y}}', '{Y}' ), date( 'Y' ), $modernee_copyright );
				// Replace {{...}} and ((...)) on the <i>...</i> and <b>...</b>
				$modernee_copyright = modernee_prepare_macros( $modernee_copyright );
				// Display copyright
				echo wp_kses( nl2br( $modernee_copyright ), 'modernee_kses_content' );
			}
			?>
			</div>
		</div>
	</div>
</div>
