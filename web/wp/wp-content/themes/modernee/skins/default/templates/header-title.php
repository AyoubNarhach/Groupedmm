<?php
/**
 * The template to display the page title and breadcrumbs
 *
 * @package MODERNEE
 * @since MODERNEE 1.0
 */

// Page (category, tag, archive, author) title

if ( modernee_need_page_title() ) {
	modernee_sc_layouts_showed( 'title', true );
	modernee_sc_layouts_showed( 'postmeta', true );
	?>
	<div class="top_panel_title sc_layouts_row sc_layouts_row_type_normal">
		<div class="content_wrap">
			<div class="sc_layouts_column sc_layouts_column_align_center">
				<div class="sc_layouts_item">
					<div class="sc_layouts_title sc_align_center">
						<?php
						// Post meta on the single post
						if ( is_single() ) {
							?>
							<div class="sc_layouts_title_meta">
							<?php
								modernee_show_post_meta(
									apply_filters(
										'modernee_filter_post_meta_args', array(
											'components' => join( ',', modernee_array_get_keys_by_value( modernee_get_theme_option( 'meta_parts' ) ) ),
											'counters'   => join( ',', modernee_array_get_keys_by_value( modernee_get_theme_option( 'counters' ) ) ),
											'seo'        => modernee_is_on( modernee_get_theme_option( 'seo_snippets' ) ),
										), 'header', 1
									)
								);
							?>
							</div>
							<?php
						}

						// Blog/Post title
						?>
						<div class="sc_layouts_title_title">
							<?php
							$modernee_blog_title           = modernee_get_blog_title();
							$modernee_blog_title_text      = '';
							$modernee_blog_title_class     = '';
							$modernee_blog_title_link      = '';
							$modernee_blog_title_link_text = '';
							if ( is_array( $modernee_blog_title ) ) {
								$modernee_blog_title_text      = $modernee_blog_title['text'];
								$modernee_blog_title_class     = ! empty( $modernee_blog_title['class'] ) ? ' ' . $modernee_blog_title['class'] : '';
								$modernee_blog_title_link      = ! empty( $modernee_blog_title['link'] ) ? $modernee_blog_title['link'] : '';
								$modernee_blog_title_link_text = ! empty( $modernee_blog_title['link_text'] ) ? $modernee_blog_title['link_text'] : '';
							} else {
								$modernee_blog_title_text = $modernee_blog_title;
							}
							?>
							<h1 itemprop="headline" class="sc_layouts_title_caption<?php echo esc_attr( $modernee_blog_title_class ); ?>">
								<?php
								$modernee_top_icon = modernee_get_term_image_small();
								if ( ! empty( $modernee_top_icon ) ) {
									$modernee_attr = modernee_getimagesize( $modernee_top_icon );
									?>
									<img src="<?php echo esc_url( $modernee_top_icon ); ?>" alt="<?php esc_attr_e( 'Site icon', 'modernee' ); ?>"
										<?php
										if ( ! empty( $modernee_attr[3] ) ) {
											modernee_show_layout( $modernee_attr[3] );
										}
										?>
									>
									<?php
								}
								echo wp_kses_data( $modernee_blog_title_text );
								?>
							</h1>
							<?php
							if ( ! empty( $modernee_blog_title_link ) && ! empty( $modernee_blog_title_link_text ) ) {
								?>
								<a href="<?php echo esc_url( $modernee_blog_title_link ); ?>" class="theme_button theme_button_small sc_layouts_title_link"><?php echo esc_html( $modernee_blog_title_link_text ); ?></a>
								<?php
							}

							// Category/Tag description
							if ( ! is_paged() && ( is_category() || is_tag() || is_tax() ) ) {
								the_archive_description( '<div class="sc_layouts_title_description">', '</div>' );
							}

							?>
						</div>
						<?php

						// Breadcrumbs
						ob_start();
						do_action( 'modernee_action_breadcrumbs' );
						$modernee_breadcrumbs = ob_get_contents();
						ob_end_clean();
						modernee_show_layout( $modernee_breadcrumbs, '<div class="sc_layouts_title_breadcrumbs">', '</div>' );
						?>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php
}
