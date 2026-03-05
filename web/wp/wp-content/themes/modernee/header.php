<?php
/**
 * The Header: Logo and main menu
 *
 * @package MODERNEE
 * @since MODERNEE 1.0
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js<?php
	// Class scheme_xxx need in the <html> as context for the <body>!
	echo ' scheme_' . esc_attr( modernee_get_theme_option( 'color_scheme' ) );
?>">

<head>
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

	<?php
	if ( function_exists( 'wp_body_open' ) ) {
		wp_body_open();
	} else {
		do_action( 'wp_body_open' );
	}
	do_action( 'modernee_action_before_body' );
	?>

	<div class="<?php echo esc_attr( apply_filters( 'modernee_filter_body_wrap_class', 'body_wrap' ) ); ?>" <?php do_action('modernee_action_body_wrap_attributes'); ?>>

		<?php do_action( 'modernee_action_before_page_wrap' ); ?>

		<div class="<?php echo esc_attr( apply_filters( 'modernee_filter_page_wrap_class', 'page_wrap' ) ); ?>" <?php do_action('modernee_action_page_wrap_attributes'); ?>>

			<?php do_action( 'modernee_action_page_wrap_start' ); ?>

			<?php
			$modernee_full_post_loading = ( modernee_is_singular( 'post' ) || modernee_is_singular( 'attachment' ) ) && modernee_get_value_gp( 'action' ) == 'full_post_loading';
			$modernee_prev_post_loading = ( modernee_is_singular( 'post' ) || modernee_is_singular( 'attachment' ) ) && modernee_get_value_gp( 'action' ) == 'prev_post_loading';

			// Don't display the header elements while actions 'full_post_loading' and 'prev_post_loading'
			if ( ! $modernee_full_post_loading && ! $modernee_prev_post_loading ) {

				// Short links to fast access to the content, sidebar and footer from the keyboard
				?>
				<a class="modernee_skip_link skip_to_content_link" href="#content_skip_link_anchor" tabindex="<?php echo esc_attr( apply_filters( 'modernee_filter_skip_links_tabindex', 1 ) ); ?>"><?php esc_html_e( "Skip to content", 'modernee' ); ?></a>
				<?php if ( modernee_sidebar_present() ) { ?>
				<a class="modernee_skip_link skip_to_sidebar_link" href="#sidebar_skip_link_anchor" tabindex="<?php echo esc_attr( apply_filters( 'modernee_filter_skip_links_tabindex', 1 ) ); ?>"><?php esc_html_e( "Skip to sidebar", 'modernee' ); ?></a>
				<?php } ?>
				<a class="modernee_skip_link skip_to_footer_link" href="#footer_skip_link_anchor" tabindex="<?php echo esc_attr( apply_filters( 'modernee_filter_skip_links_tabindex', 1 ) ); ?>"><?php esc_html_e( "Skip to footer", 'modernee' ); ?></a>

				<?php
				do_action( 'modernee_action_before_header' );

				// Header
				$modernee_header_type = modernee_get_theme_option( 'header_type' );
				if ( 'custom' == $modernee_header_type && ! modernee_is_layouts_available() ) {
					$modernee_header_type = 'default';
				}
				get_template_part( apply_filters( 'modernee_filter_get_template_part', "templates/header-" . sanitize_file_name( $modernee_header_type ) ) );

				// Side menu
				if ( in_array( modernee_get_theme_option( 'menu_side', 'none' ), array( 'left', 'right' ) ) ) {
					get_template_part( apply_filters( 'modernee_filter_get_template_part', 'templates/header-navi-side' ) );
				}

				// Mobile menu
				if ( apply_filters( 'modernee_filter_use_navi_mobile', true ) ) {
					get_template_part( apply_filters( 'modernee_filter_get_template_part', 'templates/header-navi-mobile' ) );
				}

				do_action( 'modernee_action_after_header' );

			}
			?>

			<?php do_action( 'modernee_action_before_page_content_wrap' ); ?>

			<div class="page_content_wrap<?php
				if ( modernee_is_off( modernee_get_theme_option( 'remove_margins' ) ) ) {
					if ( empty( $modernee_header_type ) ) {
						$modernee_header_type = modernee_get_theme_option( 'header_type' );
					}
					if ( 'custom' == $modernee_header_type && modernee_is_layouts_available() ) {
						$modernee_header_id = modernee_get_custom_header_id();
						if ( $modernee_header_id > 0 ) {
							$modernee_header_meta = modernee_get_custom_layout_meta( $modernee_header_id );
							if ( ! empty( $modernee_header_meta['margin'] ) ) {
								?> page_content_wrap_custom_header_margin<?php
							}
						}
					}
					$modernee_footer_type = modernee_get_theme_option( 'footer_type' );
					if ( 'custom' == $modernee_footer_type && modernee_is_layouts_available() ) {
						$modernee_footer_id = modernee_get_custom_footer_id();
						if ( $modernee_footer_id ) {
							$modernee_footer_meta = modernee_get_custom_layout_meta( $modernee_footer_id );
							if ( ! empty( $modernee_footer_meta['margin'] ) ) {
								?> page_content_wrap_custom_footer_margin<?php
							}
						}
					}
				}
				do_action( 'modernee_action_page_content_wrap_class', $modernee_prev_post_loading );
				?>"<?php
				if ( apply_filters( 'modernee_filter_is_prev_post_loading', $modernee_prev_post_loading ) ) {
					?> data-single-style="<?php echo esc_attr( modernee_get_theme_option( 'single_style' ) ); ?>"<?php
				}
				do_action( 'modernee_action_page_content_wrap_data', $modernee_prev_post_loading );
			?>>
				<?php
				do_action( 'modernee_action_page_content_wrap', $modernee_full_post_loading || $modernee_prev_post_loading );

				// Single posts banner
				if ( apply_filters( 'modernee_filter_single_post_header', modernee_is_singular( 'post' ) || modernee_is_singular( 'attachment' ) ) ) {
					if ( $modernee_prev_post_loading ) {
						if ( modernee_get_theme_option( 'posts_navigation_scroll_which_block', 'article' ) != 'article' ) {
							do_action( 'modernee_action_between_posts' );
						}
					}
					// Single post thumbnail and title
					$modernee_path = apply_filters( 'modernee_filter_get_template_part', 'templates/single-styles/' . modernee_get_theme_option( 'single_style' ) );
					if ( modernee_get_file_dir( $modernee_path . '.php' ) != '' ) {
						get_template_part( $modernee_path );
					}
				}

				// Widgets area above page
				$modernee_body_style   = modernee_get_theme_option( 'body_style' );
				$modernee_widgets_name = modernee_get_theme_option( 'widgets_above_page', 'hide' );
				$modernee_show_widgets = ! modernee_is_off( $modernee_widgets_name ) && is_active_sidebar( $modernee_widgets_name );
				if ( $modernee_show_widgets ) {
					if ( 'fullscreen' != $modernee_body_style ) {
						?>
						<div class="content_wrap">
							<?php
					}
					modernee_create_widgets_area( 'widgets_above_page' );
					if ( 'fullscreen' != $modernee_body_style ) {
						?>
						</div>
						<?php
					}
				}

				// Content area
				do_action( 'modernee_action_before_content_wrap' );
				?>
				<div class="content_wrap<?php echo 'fullscreen' == $modernee_body_style ? '_fullscreen' : ''; ?>">

					<?php do_action( 'modernee_action_content_wrap_start' ); ?>

					<div class="content">
						<?php
						do_action( 'modernee_action_page_content_start' );

						// Skip link anchor to fast access to the content from keyboard
						?>
						<a id="content_skip_link_anchor" class="modernee_skip_link_anchor" href="#"></a>
						<?php
						// Single posts banner between prev/next posts
						if ( ( modernee_is_singular( 'post' ) || modernee_is_singular( 'attachment' ) )
							&& $modernee_prev_post_loading 
							&& modernee_get_theme_option( 'posts_navigation_scroll_which_block', 'article' ) == 'article'
						) {
							do_action( 'modernee_action_between_posts' );
						}

						// Widgets area above content
						modernee_create_widgets_area( 'widgets_above_content' );

						do_action( 'modernee_action_page_content_start_text' );
