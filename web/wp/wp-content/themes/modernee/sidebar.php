<?php
/**
 * The Sidebar containing the main widget areas.
 *
 * @package MODERNEE
 * @since MODERNEE 1.0
 */

if ( modernee_sidebar_present() ) {
	
	$modernee_sidebar_type = modernee_get_theme_option( 'sidebar_type' );
	if ( 'custom' == $modernee_sidebar_type && ! modernee_is_layouts_available() ) {
		$modernee_sidebar_type = 'default';
	}
	
	// Catch output to the buffer
	ob_start();
	if ( 'default' == $modernee_sidebar_type ) {
		// Default sidebar with widgets
		$modernee_sidebar_name = modernee_get_theme_option( 'sidebar_widgets' );
		modernee_storage_set( 'current_sidebar', 'sidebar' );
		if ( is_active_sidebar( $modernee_sidebar_name ) ) {
			dynamic_sidebar( $modernee_sidebar_name );
		}
	} else {
		// Custom sidebar from Layouts Builder
		$modernee_sidebar_id = modernee_get_custom_sidebar_id();
		do_action( 'modernee_action_show_layout', $modernee_sidebar_id );
	}
	$modernee_out = trim( ob_get_contents() );
	ob_end_clean();
	
	// If any html is present - display it
	if ( ! empty( $modernee_out ) ) {
		$modernee_sidebar_position    = modernee_get_theme_option( 'sidebar_position' );
		$modernee_sidebar_position_ss = modernee_get_theme_option( 'sidebar_position_ss', 'below' );
		?>
		<div class="sidebar widget_area
			<?php
			echo ' ' . esc_attr( $modernee_sidebar_position );
			echo ' sidebar_' . esc_attr( $modernee_sidebar_position_ss );
			echo ' sidebar_' . esc_attr( $modernee_sidebar_type );

			$modernee_sidebar_scheme = apply_filters( 'modernee_filter_sidebar_scheme', modernee_get_theme_option( 'sidebar_scheme', 'inherit' ) );
			if ( ! empty( $modernee_sidebar_scheme ) && ! modernee_is_inherit( $modernee_sidebar_scheme ) && 'custom' != $modernee_sidebar_type ) {
				echo ' scheme_' . esc_attr( $modernee_sidebar_scheme );
			}
			?>
		" role="complementary">
			<?php

			// Skip link anchor to fast access to the sidebar from keyboard
			?>
			<a id="sidebar_skip_link_anchor" class="modernee_skip_link_anchor" href="#"></a>
			<?php

			do_action( 'modernee_action_before_sidebar_wrap', 'sidebar' );

			// Button to show/hide sidebar on mobile
			if ( in_array( $modernee_sidebar_position_ss, array( 'above', 'float' ) ) ) {
				$modernee_title = apply_filters( 'modernee_filter_sidebar_control_title', 'float' == $modernee_sidebar_position_ss ? esc_html__( 'Show Sidebar', 'modernee' ) : '' );
				$modernee_text  = apply_filters( 'modernee_filter_sidebar_control_text', 'above' == $modernee_sidebar_position_ss ? esc_html__( 'Show Sidebar', 'modernee' ) : '' );
				?>
				<a href="#" class="sidebar_control" title="<?php echo esc_attr( $modernee_title ); ?>"><?php echo esc_html( $modernee_text ); ?></a>
				<?php
			}
			?>
			<div class="sidebar_inner">
				<?php
				do_action( 'modernee_action_before_sidebar', 'sidebar' );
				modernee_show_layout( preg_replace( "/<\/aside>[\r\n\s]*<aside/", '</aside><aside', $modernee_out ) );
				do_action( 'modernee_action_after_sidebar', 'sidebar' );
				?>
			</div>
			<?php

			do_action( 'modernee_action_after_sidebar_wrap', 'sidebar' );

			?>
		</div>
		<div class="clearfix"></div>
		<?php
	}
}
