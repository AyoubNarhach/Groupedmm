<?php
/**
 * The default template to display the content
 *
 * Used for index/archive/search.
 *
 * @package MODERNEE
 * @since MODERNEE 1.0
 */

$modernee_template_args = get_query_var( 'modernee_template_args' );
$modernee_columns = 1;
if ( is_array( $modernee_template_args ) ) {
	$modernee_columns    = empty( $modernee_template_args['columns'] ) ? 1 : max( 1, $modernee_template_args['columns'] );
	$modernee_blog_style = array( $modernee_template_args['type'], $modernee_columns );
	if ( ! empty( $modernee_template_args['slider'] ) ) {
		?><div class="slider-slide swiper-slide">
		<?php
	} elseif ( $modernee_columns > 1 ) {
	    $modernee_columns_class = modernee_get_column_class( 1, $modernee_columns, ! empty( $modernee_template_args['columns_tablet']) ? $modernee_template_args['columns_tablet'] : '', ! empty($modernee_template_args['columns_mobile']) ? $modernee_template_args['columns_mobile'] : '' );
		?>
		<div class="<?php echo esc_attr( $modernee_columns_class ); ?>">
		<?php
	}
} else {
	$modernee_template_args = array();
}
$modernee_expanded    = ! modernee_sidebar_present() && modernee_get_theme_option( 'expand_content' ) == 'expand';
$modernee_post_format = get_post_format();
$modernee_post_format = empty( $modernee_post_format ) ? 'standard' : str_replace( 'post-format-', '', $modernee_post_format );
?>
<article id="post-<?php the_ID(); ?>" data-post-id="<?php the_ID(); ?>"
	<?php
	post_class( 'post_item post_item_container post_layout_excerpt post_format_' . esc_attr( $modernee_post_format ) );
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
			'thumb_size' => ! empty( $modernee_template_args['thumb_size'] )
							? $modernee_template_args['thumb_size']
							: modernee_get_thumb_size( strpos( modernee_get_theme_option( 'body_style' ), 'full' ) !== false
								? 'full'
								: ( $modernee_expanded 
									? 'huge' 
									: 'big' 
									)
								),
		),
		'content-excerpt',
		$modernee_template_args
	) );

	// Title and post meta
	$modernee_show_title = get_the_title() != '';
	$modernee_show_meta  = count( $modernee_components ) > 0 && ! in_array( $modernee_hover, array( 'border', 'pull', 'slide', 'fade', 'info' ) );

	if ( $modernee_show_title ) {
		?>
		<div class="post_header entry-header">
			<?php
			// Post title
			if ( apply_filters( 'modernee_filter_show_blog_title', true, 'excerpt' ) ) {
				do_action( 'modernee_action_before_post_title' );
				if ( empty( $modernee_template_args['no_links'] ) ) {
					the_title( sprintf( '<h3 class="post_title entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h3>' );
				} else {
					the_title( '<h3 class="post_title entry-title">', '</h3>' );
				}
				do_action( 'modernee_action_after_post_title' );
			}
			?>
		</div><!-- .post_header -->
		<?php
	}

	// Post content
	if ( apply_filters( 'modernee_filter_show_blog_excerpt', empty( $modernee_template_args['hide_excerpt'] ) && modernee_get_theme_option( 'excerpt_length' ) > 0, 'excerpt' ) ) {
		?>
		<div class="post_content entry-content">
			<?php

			// Post meta
			if ( apply_filters( 'modernee_filter_show_blog_meta', $modernee_show_meta, $modernee_components, 'excerpt' ) ) {
				if ( count( $modernee_components ) > 0 ) {
					do_action( 'modernee_action_before_post_meta' );
					modernee_show_post_meta(
						apply_filters(
							'modernee_filter_post_meta_args', array(
								'components' => join( ',', $modernee_components ),
								'seo'        => false,
								'echo'       => true,
							), 'excerpt', 1
						)
					);
					do_action( 'modernee_action_after_post_meta' );
				}
			}

			if ( modernee_get_theme_option( 'blog_content' ) == 'fullpost' ) {
				// Post content area
				?>
				<div class="post_content_inner">
					<?php
					do_action( 'modernee_action_before_full_post_content' );
					the_content( '' );
					do_action( 'modernee_action_after_full_post_content' );
					?>
				</div>
				<?php
				// Inner pages
				wp_link_pages(
					array(
						'before'      => '<div class="page_links"><span class="page_links_title">' . esc_html__( 'Pages:', 'modernee' ) . '</span>',
						'after'       => '</div>',
						'link_before' => '<span>',
						'link_after'  => '</span>',
						'pagelink'    => '<span class="screen-reader-text">' . esc_html__( 'Page', 'modernee' ) . ' </span>%',
						'separator'   => '<span class="screen-reader-text">, </span>',
					)
				);
			} else {
				// Post content area
				modernee_show_post_content( $modernee_template_args, '<div class="post_content_inner">', '</div>' );
			}

			// More button
			if ( apply_filters( 'modernee_filter_show_blog_readmore',  ! isset( $modernee_template_args['more_button'] ) || ! empty( $modernee_template_args['more_button'] ), 'excerpt' ) ) {
				if ( empty( $modernee_template_args['no_links'] ) ) {
					do_action( 'modernee_action_before_post_readmore' );
					if ( modernee_get_theme_option( 'blog_content' ) != 'fullpost' ) {
						modernee_show_post_more_link( $modernee_template_args, '<p>', '</p>' );
					} else {
						modernee_show_post_comments_link( $modernee_template_args, '<p>', '</p>' );
					}
					do_action( 'modernee_action_after_post_readmore' );
				}
			}

			?>
		</div><!-- .entry-content -->
		<?php
	}
	?>
</article>
<?php

if ( is_array( $modernee_template_args ) ) {
	if ( ! empty( $modernee_template_args['slider'] ) || $modernee_columns > 1 ) {
		?>
		</div>
		<?php
	}
}
