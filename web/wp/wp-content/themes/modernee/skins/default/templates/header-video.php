<?php
/**
 * The template to display the background video in the header
 *
 * @package MODERNEE
 * @since MODERNEE 1.0.14
 */
$modernee_header_video = modernee_get_header_video();
$modernee_embed_video  = '';
if ( ! empty( $modernee_header_video ) && ! modernee_is_from_uploads( $modernee_header_video ) ) {
	if ( modernee_is_youtube_url( $modernee_header_video ) && preg_match( '/[=\/]([^=\/]*)$/', $modernee_header_video, $matches ) && ! empty( $matches[1] ) ) {
		?><div id="background_video" data-youtube-code="<?php echo esc_attr( $matches[1] ); ?>"></div>
		<?php
	} else {
		?>
		<div id="background_video"><?php modernee_show_layout( modernee_get_embed_video( $modernee_header_video ) ); ?></div>
		<?php
	}
}
