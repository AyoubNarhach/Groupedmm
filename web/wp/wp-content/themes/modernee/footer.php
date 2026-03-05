<?php
/**
 * The Footer: widgets area, logo, footer menu and socials
 *
 * @package MODERNEE
 * @since MODERNEE 1.0
 */

							do_action( 'modernee_action_page_content_end_text' );
							
							// Widgets area below the content
							modernee_create_widgets_area( 'widgets_below_content' );
						
							do_action( 'modernee_action_page_content_end' );
							?>
						</div>
						<?php
						
						do_action( 'modernee_action_after_page_content' );

						// Show main sidebar
						get_sidebar();

						do_action( 'modernee_action_content_wrap_end' );
						?>
					</div>
					<?php

					do_action( 'modernee_action_after_content_wrap' );

					// Widgets area below the page and related posts below the page
					$modernee_body_style = modernee_get_theme_option( 'body_style' );
					$modernee_widgets_name = modernee_get_theme_option( 'widgets_below_page', 'hide' );
					$modernee_show_widgets = ! modernee_is_off( $modernee_widgets_name ) && is_active_sidebar( $modernee_widgets_name );
					$modernee_show_related = modernee_is_single() && modernee_get_theme_option( 'related_position', 'below_content' ) == 'below_page';
					if ( $modernee_show_widgets || $modernee_show_related ) {
						if ( 'fullscreen' != $modernee_body_style ) {
							?>
							<div class="content_wrap">
							<?php
						}
						// Show related posts before footer
						if ( $modernee_show_related ) {
							do_action( 'modernee_action_related_posts' );
						}

						// Widgets area below page content
						if ( $modernee_show_widgets ) {
							modernee_create_widgets_area( 'widgets_below_page' );
						}
						if ( 'fullscreen' != $modernee_body_style ) {
							?>
							</div>
							<?php
						}
					}
					do_action( 'modernee_action_page_content_wrap_end' );
					?>
			</div>
			<?php
			do_action( 'modernee_action_after_page_content_wrap' );

			// Don't display the footer elements while actions 'full_post_loading' and 'prev_post_loading'
			if ( ( ! modernee_is_singular( 'post' ) && ! modernee_is_singular( 'attachment' ) ) || ! in_array ( modernee_get_value_gp( 'action' ), array( 'full_post_loading', 'prev_post_loading' ) ) ) {
				
				// Skip link anchor to fast access to the footer from keyboard
				?>
				<a id="footer_skip_link_anchor" class="modernee_skip_link_anchor" href="#"></a>
				<?php

				do_action( 'modernee_action_before_footer' );

				// Footer
				$modernee_footer_type = modernee_get_theme_option( 'footer_type' );
				if ( 'custom' == $modernee_footer_type && ! modernee_is_layouts_available() ) {
					$modernee_footer_type = 'default';
				}
				get_template_part( apply_filters( 'modernee_filter_get_template_part', "templates/footer-" . sanitize_file_name( $modernee_footer_type ) ) );

				do_action( 'modernee_action_after_footer' );

			}
			?>

			<?php do_action( 'modernee_action_page_wrap_end' ); ?>

		</div>

		<?php do_action( 'modernee_action_after_page_wrap' ); ?>

	</div>

	<?php do_action( 'modernee_action_after_body' ); ?>

	<?php wp_footer(); ?>

</body>
</html>