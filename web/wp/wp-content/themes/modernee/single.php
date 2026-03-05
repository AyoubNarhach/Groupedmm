<?php
/**
 * The template to display single post
 *
 * @package MODERNEE
 * @since MODERNEE 1.0
 */

// Full post loading
$full_post_loading          = modernee_get_value_gp( 'action' ) == 'full_post_loading';

// Prev post loading
$prev_post_loading          = modernee_get_value_gp( 'action' ) == 'prev_post_loading';
$prev_post_loading_type     = modernee_get_theme_option( 'posts_navigation_scroll_which_block', 'article' );

// Position of the related posts
$modernee_related_position   = modernee_get_theme_option( 'related_position', 'below_content' );

// Type of the prev/next post navigation
$modernee_posts_navigation   = modernee_get_theme_option( 'posts_navigation' );
$modernee_prev_post          = false;
$modernee_prev_post_same_cat = (int)modernee_get_theme_option( 'posts_navigation_scroll_same_cat', 1 );

// Rewrite style of the single post if current post loading via AJAX and featured image and title is not in the content
if ( ( $full_post_loading 
		|| 
		( $prev_post_loading && 'article' == $prev_post_loading_type )
	) 
	&& 
	! in_array( modernee_get_theme_option( 'single_style' ), array( 'style-6' ) )
) {
	modernee_storage_set_array( 'options_meta', 'single_style', 'style-6' );
}

do_action( 'modernee_action_prev_post_loading', $prev_post_loading, $prev_post_loading_type );

get_header();

while ( have_posts() ) {

	the_post();

	// Type of the prev/next post navigation
	if ( 'scroll' == $modernee_posts_navigation ) {
		$modernee_prev_post = get_previous_post( $modernee_prev_post_same_cat );  // Get post from same category
		if ( ! $modernee_prev_post && $modernee_prev_post_same_cat ) {
			$modernee_prev_post = get_previous_post( false );                    // Get post from any category
		}
		if ( ! $modernee_prev_post ) {
			$modernee_posts_navigation = 'links';
		}
	}

	// Override some theme options to display featured image, title and post meta in the dynamic loaded posts
	if ( $full_post_loading || ( $prev_post_loading && $modernee_prev_post ) ) {
		modernee_sc_layouts_showed( 'featured', false );
		modernee_sc_layouts_showed( 'title', false );
		modernee_sc_layouts_showed( 'postmeta', false );
	}

	// If related posts should be inside the content
	if ( strpos( $modernee_related_position, 'inside' ) === 0 ) {
		ob_start();
	}

	// Display post's content
	get_template_part( apply_filters( 'modernee_filter_get_template_part', 'templates/content', 'single-' . modernee_get_theme_option( 'single_style' ) ), 'single-' . modernee_get_theme_option( 'single_style' ) );

	// If related posts should be inside the content
	if ( strpos( $modernee_related_position, 'inside' ) === 0 ) {
		$modernee_content = ob_get_contents();
		ob_end_clean();

		ob_start();
		do_action( 'modernee_action_related_posts' );
		$modernee_related_content = ob_get_contents();
		ob_end_clean();

		if ( ! empty( $modernee_related_content ) ) {
			$modernee_related_position_inside = max( 0, min( 9, modernee_get_theme_option( 'related_position_inside' ) ) );
			if ( 0 == $modernee_related_position_inside ) {
				$modernee_related_position_inside = mt_rand( 1, 9 );
			}

			$modernee_p_number         = 0;
			$modernee_related_inserted = false;
			$modernee_in_block         = false;
			$modernee_content_start    = strpos( $modernee_content, '<div class="post_content' );
			$modernee_content_end      = strrpos( $modernee_content, '</div>' );

			for ( $i = max( 0, $modernee_content_start ); $i < min( strlen( $modernee_content ) - 3, $modernee_content_end ); $i++ ) {
				if ( $modernee_content[ $i ] != '<' ) {
					continue;
				}
				if ( $modernee_in_block ) {
					if ( strtolower( substr( $modernee_content, $i + 1, 12 ) ) == '/blockquote>' ) {
						$modernee_in_block = false;
						$i += 12;
					}
					continue;
				} else if ( strtolower( substr( $modernee_content, $i + 1, 10 ) ) == 'blockquote' && in_array( $modernee_content[ $i + 11 ], array( '>', ' ' ) ) ) {
					$modernee_in_block = true;
					$i += 11;
					continue;
				} else if ( 'p' == $modernee_content[ $i + 1 ] && in_array( $modernee_content[ $i + 2 ], array( '>', ' ' ) ) ) {
					$modernee_p_number++;
					if ( $modernee_related_position_inside == $modernee_p_number ) {
						$modernee_related_inserted = true;
						$modernee_content = ( $i > 0 ? substr( $modernee_content, 0, $i ) : '' )
											. $modernee_related_content
											. substr( $modernee_content, $i );
					}
				}
			}
			if ( ! $modernee_related_inserted ) {
				if ( $modernee_content_end > 0 ) {
					$modernee_content = substr( $modernee_content, 0, $modernee_content_end ) . $modernee_related_content . substr( $modernee_content, $modernee_content_end );
				} else {
					$modernee_content .= $modernee_related_content;
				}
			}
		}

		modernee_show_layout( $modernee_content );
	}

	// Comments
	do_action( 'modernee_action_before_comments' );
	comments_template();
	do_action( 'modernee_action_after_comments' );

	// Related posts
	if ( 'below_content' == $modernee_related_position
		&& ( 'scroll' != $modernee_posts_navigation || (int)modernee_get_theme_option( 'posts_navigation_scroll_hide_related', 0 ) == 0 )
		&& ( ! $full_post_loading || (int)modernee_get_theme_option( 'open_full_post_hide_related', 1 ) == 0 )
	) {
		do_action( 'modernee_action_related_posts' );
	}

	// Post navigation: type 'scroll'
	if ( 'scroll' == $modernee_posts_navigation && ! $full_post_loading ) {
		?>
		<div class="nav-links-single-scroll"
			data-post-id="<?php echo esc_attr( get_the_ID( $modernee_prev_post ) ); ?>"
			data-post-link="<?php echo esc_attr( get_permalink( $modernee_prev_post ) ); ?>"
			data-post-title="<?php the_title_attribute( array( 'post' => $modernee_prev_post ) ); ?>"
			data-cur-post-link="<?php echo esc_attr( get_permalink() ); ?>"
			data-cur-post-title="<?php the_title_attribute(); ?>"
			<?php do_action( 'modernee_action_nav_links_single_scroll_data', $modernee_prev_post ); ?>
		></div>
		<?php
	}
}

get_footer();
