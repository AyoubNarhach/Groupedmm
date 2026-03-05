<?php
/**
 * The template to display the widgets area in the footer
 *
 * @package MODERNEE
 * @since MODERNEE 1.0.10
 */

// Footer sidebar
$modernee_footer_name    = modernee_get_theme_option( 'footer_widgets' );
$modernee_footer_present = ! modernee_is_off( $modernee_footer_name ) && is_active_sidebar( $modernee_footer_name );
if ( $modernee_footer_present ) {
	modernee_storage_set( 'current_sidebar', 'footer' );
	$modernee_footer_wide = modernee_get_theme_option( 'footer_wide' );
	ob_start();
	if ( is_active_sidebar( $modernee_footer_name ) ) {
		dynamic_sidebar( $modernee_footer_name );
	}
	$modernee_out = trim( ob_get_contents() );
	ob_end_clean();
	if ( ! empty( $modernee_out ) ) {
		$modernee_out          = preg_replace( "/<\\/aside>[\r\n\s]*<aside/", '</aside><aside', $modernee_out );
		$modernee_need_columns = true;   //or check: strpos($modernee_out, 'columns_wrap')===false;
		if ( $modernee_need_columns ) {
			$modernee_columns = max( 0, (int) modernee_get_theme_option( 'footer_columns' ) );			
			if ( 0 == $modernee_columns ) {
				$modernee_columns = min( 4, max( 1, modernee_tags_count( $modernee_out, 'aside' ) ) );
			}
			if ( $modernee_columns > 1 ) {
				$modernee_out = preg_replace( '/<aside([^>]*)class="widget/', '<aside$1class="column-1_' . esc_attr( $modernee_columns ) . ' widget', $modernee_out );
			} else {
				$modernee_need_columns = false;
			}
		}
		?>
		<div class="footer_widgets_wrap widget_area<?php echo ! empty( $modernee_footer_wide ) ? ' footer_fullwidth' : ''; ?> sc_layouts_row sc_layouts_row_type_normal">
			<?php do_action( 'modernee_action_before_sidebar_wrap', 'footer' ); ?>
			<div class="footer_widgets_inner widget_area_inner">
				<?php
				if ( ! $modernee_footer_wide ) {
					?>
					<div class="content_wrap">
					<?php
				}
				if ( $modernee_need_columns ) {
					?>
					<div class="columns_wrap">
					<?php
				}
				do_action( 'modernee_action_before_sidebar', 'footer' );
				modernee_show_layout( $modernee_out );
				do_action( 'modernee_action_after_sidebar', 'footer' );
				if ( $modernee_need_columns ) {
					?>
					</div><!-- /.columns_wrap -->
					<?php
				}
				if ( ! $modernee_footer_wide ) {
					?>
					</div><!-- /.content_wrap -->
					<?php
				}
				?>
			</div><!-- /.footer_widgets_inner -->
			<?php do_action( 'modernee_action_after_sidebar_wrap', 'footer' ); ?>
		</div><!-- /.footer_widgets_wrap -->
		<?php
	}
}
