<?php
/**
 * 'Band' template to display the content
 *
 * Used for index/archive/search.
 *
 * @package MODERNEE
 * @since MODERNEE 1.71.0
 */

$modernee_template_args = get_query_var( 'modernee_template_args' );
if ( ! is_array( $modernee_template_args ) ) {
	$modernee_template_args = array(
								'type'    => 'band',
								'columns' => 1
								);
}

$modernee_columns       = 1;

$modernee_expanded      = ! modernee_sidebar_present() && modernee_get_theme_option( 'expand_content' ) == 'expand';

$modernee_post_format   = get_post_format();
$modernee_post_format   = empty( $modernee_post_format ) ? 'standard' : str_replace( 'post-format-', '', $modernee_post_format );

if ( is_array( $modernee_template_args ) ) {
	$modernee_columns    = empty( $modernee_template_args['columns'] ) ? 1 : max( 1, $modernee_template_args['columns'] );
	$modernee_blog_style = array( $modernee_template_args['type'], $modernee_columns );
	if ( ! empty( $modernee_template_args['slider'] ) ) {
		?><div class="slider-slide swiper-slide">
		<?php
	} elseif ( $modernee_columns > 1 ) {
	    $modernee_columns_class = modernee_get_column_class( 1, $modernee_columns, ! empty( $modernee_template_args['columns_tablet']) ? $modernee_template_args['columns_tablet'] : '', ! empty($modernee_template_args['columns_mobile']) ? $modernee_template_args['columns_mobile'] : '' );
				?><div class="<?php echo esc_attr( $modernee_columns_class ); ?>"><?php
	}
}
?>
<article id="post-<?php the_ID(); ?>" data-post-id="<?php the_ID(); ?>"
	<?php
	post_class( 'post_item post_item_container post_layout_band post_format_' . esc_attr( $modernee_post_format ) );
	modernee_add_blog_animation( $modernee_template_args );
	?>
>
	<?php

	// Sticky label
	if ( is_sticky() && ! is_paged() ) {
		?>
		<span class="post_label label_sticky"></span>
		<?php
	}

	// Featured image
	$modernee_hover      = ! empty( $modernee_template_args['hover'] ) && ! modernee_is_inherit( $modernee_template_args['hover'] )
							? $modernee_template_args['hover']
							: modernee_get_theme_option( 'image_hover' );
	$modernee_components = ! empty( $modernee_template_args['meta_parts'] )
							? ( is_array( $modernee_template_args['meta_parts'] )
								? $modernee_template_args['meta_parts']
								: array_map( 'trim', explode( ',', $modernee_template_args['meta_parts'] ) )
								)
							: modernee_array_get_keys_by_value( modernee_get_theme_option( 'meta_parts' ) );
	modernee_show_post_featured( apply_filters( 'modernee_filter_args_featured',
		array(
			'no_links'   => ! empty( $modernee_template_args['no_links'] ),
			'hover'      => $modernee_hover,
			'meta_parts' => $modernee_components,
			'thumb_bg'   => true,
			'thumb_ratio'   => '1:1',
			'thumb_size' => ! empty( $modernee_template_args['thumb_size'] )
								? $modernee_template_args['thumb_size']
								: modernee_get_thumb_size( 
								in_array( $modernee_post_format, array( 'gallery', 'audio', 'video' ) )
									? ( strpos( modernee_get_theme_option( 'body_style' ), 'full' ) !== false
										? 'full'
										: ( $modernee_expanded 
											? 'big' 
											: 'medium-square'
											)
										)
									: 'masonry-big'
								)
		),
		'content-band',
		$modernee_template_args
	) );

	?><div class="post_content_wrap"><?php

		// Title and post meta
		$modernee_show_title = get_the_title() != '';
		$modernee_show_meta  = count( $modernee_components ) > 0 && ! in_array( $modernee_hover, array( 'border', 'pull', 'slide', 'fade', 'info' ) );
		if ( $modernee_show_title ) {
			?>
			<div class="post_header entry-header">
				<?php
				// Categories
				if ( apply_filters( 'modernee_filter_show_blog_categories', $modernee_show_meta && in_array( 'categories', $modernee_components ), array( 'categories' ), 'band' ) ) {
					do_action( 'modernee_action_before_post_category' );
					?>
					<div class="post_category">
						<?php
						modernee_show_post_meta( apply_filters(
															'modernee_filter_post_meta_args',
															array(
																'components' => 'categories',
																'seo'        => false,
																'echo'       => true,
																'cat_sep'    => false,
																),
															'hover_' . $modernee_hover, 1
															)
											);
						?>
					</div>
					<?php
					$modernee_components = modernee_array_delete_by_value( $modernee_components, 'categories' );
					do_action( 'modernee_action_after_post_category' );
				}
				// Post title
				if ( apply_filters( 'modernee_filter_show_blog_title', true, 'band' ) ) {
					do_action( 'modernee_action_before_post_title' );
					if ( empty( $modernee_template_args['no_links'] ) ) {
						the_title( sprintf( '<h4 class="post_title entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h4>' );
					} else {
						the_title( '<h4 class="post_title entry-title">', '</h4>' );
					}
					do_action( 'modernee_action_after_post_title' );
				}
				?>
			</div><!-- .post_header -->
			<?php
		}

		// Post content
		if ( ! isset( $modernee_template_args['excerpt_length'] ) && ! in_array( $modernee_post_format, array( 'gallery', 'audio', 'video' ) ) ) {
			$modernee_template_args['excerpt_length'] = 13;
		}
		if ( apply_filters( 'modernee_filter_show_blog_excerpt', empty( $modernee_template_args['hide_excerpt'] ) && modernee_get_theme_option( 'excerpt_length' ) > 0, 'band' ) ) {
			?>
			<div class="post_content entry-content">
				<?php
				// Post content area
				modernee_show_post_content( $modernee_template_args, '<div class="post_content_inner">', '</div>' );
				?>
			</div><!-- .entry-content -->
			<?php
		}
		// Post meta
		if ( apply_filters( 'modernee_filter_show_blog_meta', $modernee_show_meta, $modernee_components, 'band' ) ) {
			if ( count( $modernee_components ) > 0 ) {
				do_action( 'modernee_action_before_post_meta' );
				modernee_show_post_meta(
					apply_filters(
						'modernee_filter_post_meta_args', array(
							'components' => join( ',', $modernee_components ),
							'seo'        => false,
							'echo'       => true,
						), 'band', 1
					)
				);
				do_action( 'modernee_action_after_post_meta' );
			}
		}
		// More button
		if ( apply_filters( 'modernee_filter_show_blog_readmore', ! $modernee_show_title || ! empty( $modernee_template_args['more_button'] ), 'band' ) ) {
			if ( empty( $modernee_template_args['no_links'] ) ) {
				do_action( 'modernee_action_before_post_readmore' );
				modernee_show_post_more_link( $modernee_template_args, '<div class="more-wrap">', '</div>' );
				do_action( 'modernee_action_after_post_readmore' );
			}
		}
		?>
	</div>
</article>
<?php

if ( is_array( $modernee_template_args ) ) {
	if ( ! empty( $modernee_template_args['slider'] ) || $modernee_columns > 1 ) {
		?>
		</div>
		<?php
	}
}
