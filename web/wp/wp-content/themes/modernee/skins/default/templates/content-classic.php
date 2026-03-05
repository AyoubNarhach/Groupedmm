<?php
/**
 * The Classic template to display the content
 *
 * Used for index/archive/search.
 *
 * @package MODERNEE
 * @since MODERNEE 1.0
 */

$modernee_template_args = get_query_var( 'modernee_template_args' );

if ( is_array( $modernee_template_args ) ) {
	$modernee_columns    = empty( $modernee_template_args['columns'] ) ? 2 : max( 1, $modernee_template_args['columns'] );
	$modernee_blog_style = array( $modernee_template_args['type'], $modernee_columns );
    $modernee_columns_class = modernee_get_column_class( 1, $modernee_columns, ! empty( $modernee_template_args['columns_tablet']) ? $modernee_template_args['columns_tablet'] : '', ! empty($modernee_template_args['columns_mobile']) ? $modernee_template_args['columns_mobile'] : '' );
} else {
	$modernee_template_args = array();
	$modernee_blog_style = explode( '_', modernee_get_theme_option( 'blog_style' ) );
	$modernee_columns    = empty( $modernee_blog_style[1] ) ? 2 : max( 1, $modernee_blog_style[1] );
    $modernee_columns_class = modernee_get_column_class( 1, $modernee_columns );
}
$modernee_expanded   = ! modernee_sidebar_present() && modernee_get_theme_option( 'expand_content' ) == 'expand';

$modernee_post_format = get_post_format();
$modernee_post_format = empty( $modernee_post_format ) ? 'standard' : str_replace( 'post-format-', '', $modernee_post_format );

?><div class="<?php
	if ( ! empty( $modernee_template_args['slider'] ) ) {
		echo ' slider-slide swiper-slide';
	} else {
		echo ( modernee_is_blog_style_use_masonry( $modernee_blog_style[0] ) ? 'masonry_item masonry_item-1_' . esc_attr( $modernee_columns ) : esc_attr( $modernee_columns_class ) );
	}
?>"><article id="post-<?php the_ID(); ?>" data-post-id="<?php the_ID(); ?>"
	<?php
	post_class(
		'post_item post_item_container post_format_' . esc_attr( $modernee_post_format )
				. ' post_layout_classic post_layout_classic_' . esc_attr( $modernee_columns )
				. ' post_layout_' . esc_attr( $modernee_blog_style[0] )
				. ' post_layout_' . esc_attr( $modernee_blog_style[0] ) . '_' . esc_attr( $modernee_columns )
	);
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
								: explode( ',', $modernee_template_args['meta_parts'] )
								)
							: modernee_array_get_keys_by_value( modernee_get_theme_option( 'meta_parts' ) );

	modernee_show_post_featured( apply_filters( 'modernee_filter_args_featured',
		array(
			'thumb_size' => ! empty( $modernee_template_args['thumb_size'] )
				? $modernee_template_args['thumb_size']
				: modernee_get_thumb_size(
					'classic' == $modernee_blog_style[0]
						? ( strpos( modernee_get_theme_option( 'body_style' ), 'full' ) !== false
								? ( $modernee_columns > 2 ? 'big' : 'huge' )
								: ( $modernee_columns > 2
									? ( $modernee_expanded ? 'square' : 'square' )
									: ($modernee_columns > 1 ? 'square' : ( $modernee_expanded ? 'huge' : 'big' ))
									)
							)
						: ( strpos( modernee_get_theme_option( 'body_style' ), 'full' ) !== false
								? ( $modernee_columns > 2 ? 'masonry-big' : 'full' )
								: ($modernee_columns === 1 ? ( $modernee_expanded ? 'huge' : 'big' ) : ( $modernee_columns <= 2 && $modernee_expanded ? 'masonry-big' : 'masonry' ))
							)
			),
			'hover'      => $modernee_hover,
			'meta_parts' => $modernee_components,
			'no_links'   => ! empty( $modernee_template_args['no_links'] ),
        ),
        'content-classic',
        $modernee_template_args
    ) );

	// Title and post meta
	$modernee_show_title = get_the_title() != '';
	$modernee_show_meta  = count( $modernee_components ) > 0 && ! in_array( $modernee_hover, array( 'border', 'pull', 'slide', 'fade', 'info' ) );

	if ( $modernee_show_title ) {
		?>
		<div class="post_header entry-header">
			<?php

			// Post meta
			if ( apply_filters( 'modernee_filter_show_blog_meta', $modernee_show_meta, $modernee_components, 'classic' ) ) {
				if ( count( $modernee_components ) > 0 ) {
					do_action( 'modernee_action_before_post_meta' );
					modernee_show_post_meta(
						apply_filters(
							'modernee_filter_post_meta_args', array(
							'components' => join( ',', $modernee_components ),
							'seo'        => false,
							'echo'       => true,
						), $modernee_blog_style[0], $modernee_columns
						)
					);
					do_action( 'modernee_action_after_post_meta' );
				}
			}

			// Post title
			if ( apply_filters( 'modernee_filter_show_blog_title', true, 'classic' ) ) {
				do_action( 'modernee_action_before_post_title' );
				if ( empty( $modernee_template_args['no_links'] ) ) {
					the_title( sprintf( '<h4 class="post_title entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h4>' );
				} else {
					the_title( '<h4 class="post_title entry-title">', '</h4>' );
				}
				do_action( 'modernee_action_after_post_title' );
			}

			if( !in_array( $modernee_post_format, array( 'quote', 'aside', 'link', 'status' ) ) ) {
				// More button
				if ( apply_filters( 'modernee_filter_show_blog_readmore', ! $modernee_show_title || ! empty( $modernee_template_args['more_button'] ), 'classic' ) ) {
					if ( empty( $modernee_template_args['no_links'] ) ) {
						do_action( 'modernee_action_before_post_readmore' );
						modernee_show_post_more_link( $modernee_template_args, '<div class="more-wrap">', '</div>' );
						do_action( 'modernee_action_after_post_readmore' );
					}
				}
			}
			?>
		</div><!-- .entry-header -->
		<?php
	}

	// Post content
	if( in_array( $modernee_post_format, array( 'quote', 'aside', 'link', 'status' ) ) ) {
		ob_start();
		if (apply_filters('modernee_filter_show_blog_excerpt', empty($modernee_template_args['hide_excerpt']) && modernee_get_theme_option('excerpt_length') > 0, 'classic')) {
			modernee_show_post_content($modernee_template_args, '<div class="post_content_inner">', '</div>');
		}
		// More button
		if(! empty( $modernee_template_args['more_button'] )) {
			if ( empty( $modernee_template_args['no_links'] ) ) {
				do_action( 'modernee_action_before_post_readmore' );
				modernee_show_post_more_link( $modernee_template_args, '<div class="more-wrap">', '</div>' );
				do_action( 'modernee_action_after_post_readmore' );
			}
		}
		$modernee_content = ob_get_contents();
		ob_end_clean();
		modernee_show_layout($modernee_content, '<div class="post_content entry-content">', '</div><!-- .entry-content -->');
	}
	?>

</article></div><?php
// Need opening PHP-tag above, because <div> is a inline-block element (used as column)!
