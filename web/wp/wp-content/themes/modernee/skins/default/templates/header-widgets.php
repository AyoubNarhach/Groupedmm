<?php
/**
 * The template to display the widgets area in the header
 *
 * @package MODERNEE
 * @since MODERNEE 1.0
 */

// Header sidebar
$modernee_header_name    = modernee_get_theme_option( 'header_widgets' );
$modernee_header_present = ! modernee_is_off( $modernee_header_name ) && is_active_sidebar( $modernee_header_name );
if ( $modernee_header_present ) {
	modernee_storage_set( 'current_sidebar', 'header' );
	$modernee_header_wide = modernee_get_theme_option( 'header_wide' );
	ob_start();
	if ( is_active_sidebar( $modernee_header_name ) ) {
		dynamic_sidebar( $modernee_header_name );
	}
	$modernee_widgets_output = ob_get_contents();
	ob_end_clean();
	if ( ! empty( $modernee_widgets_output ) ) {
		$modernee_widgets_output = preg_replace( "/<\/aside>[\r\n\s]*<aside/", '</aside><aside', $modernee_widgets_output );
		$modernee_need_columns   = strpos( $modernee_widgets_output, 'columns_wrap' ) === false;
		if ( $modernee_need_columns ) {
			$modernee_columns = max( 0, (int) modernee_get_theme_option( 'header_columns' ) );
			if ( 0 == $modernee_columns ) {
				$modernee_columns = min( 6, max( 1, modernee_tags_count( $modernee_widgets_output, 'aside' ) ) );
			}
			if ( $modernee_columns > 1 ) {
				$modernee_widgets_output = preg_replace( '/<aside([^>]*)class="widget/', '<aside$1class="column-1_' . esc_attr( $modernee_columns ) . ' widget', $modernee_widgets_output );
			} else {
				$modernee_need_columns = false;
			}
		}
		?>
		<div class="header_widgets_wrap widget_area<?php echo ! empty( $modernee_header_wide ) ? ' header_fullwidth' : ' header_boxed'; ?>">
			<?php do_action( 'modernee_action_before_sidebar_wrap', 'header' ); ?>
			<div class="header_widgets_inner widget_area_inner">
				<?php
				if ( ! $modernee_header_wide ) {
					?>
					<div class="content_wrap">
					<?php
				}
				if ( $modernee_need_columns ) {
					?>
					<div class="columns_wrap">
					<?php
				}
				do_action( 'modernee_action_before_sidebar', 'header' );
				modernee_show_layout( $modernee_widgets_output );
				do_action( 'modernee_action_after_sidebar', 'header' );
				if ( $modernee_need_columns ) {
					?>
					</div>	<!-- /.columns_wrap -->
					<?php
				}
				if ( ! $modernee_header_wide ) {
					?>
					</div>	<!-- /.content_wrap -->
					<?php
				}
				?>
			</div>	<!-- /.header_widgets_inner -->
			<?php do_action( 'modernee_action_after_sidebar_wrap', 'header' ); ?>
		</div>	<!-- /.header_widgets_wrap -->
		<?php
	}
}
