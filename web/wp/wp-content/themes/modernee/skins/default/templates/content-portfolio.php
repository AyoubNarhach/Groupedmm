<?php
/**
 * The Portfolio template to display the content
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

$modernee_post_format = get_post_format();
$modernee_post_format = empty( $modernee_post_format ) ? 'standard' : str_replace( 'post-format-', '', $modernee_post_format );

?><div class="
<?php
if ( ! empty( $modernee_template_args['slider'] ) ) {
	echo ' slider-slide swiper-slide';
} else {
	echo ( modernee_is_blog_style_use_masonry( $modernee_blog_style[0] ) ? 'masonry_item masonry_item-1_' . esc_attr( $modernee_columns ) : esc_attr( $modernee_columns_class ));
}
?>
"><article id="post-<?php the_ID(); ?>" 
	<?php
	post_class(
		'post_item post_item_container post_format_' . esc_attr( $modernee_post_format )
		. ' post_layout_portfolio'
		. ' post_layout_portfolio_' . esc_attr( $modernee_columns )
		. ( 'portfolio' != $modernee_blog_style[0] ? ' ' . esc_attr( $modernee_blog_style[0] )  . '_' . esc_attr( $modernee_columns ) : '' )
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

	$modernee_hover   = ! empty( $modernee_template_args['hover'] ) && ! modernee_is_inherit( $modernee_template_args['hover'] )
								? $modernee_template_args['hover']
								: modernee_get_theme_option( 'image_hover' );

	if ( 'dots' == $modernee_hover ) {
		$modernee_post_link = empty( $modernee_template_args['no_links'] )
								? ( ! empty( $modernee_template_args['link'] )
									? $modernee_template_args['link']
									: get_permalink()
									)
								: '';
		$modernee_target    = ! empty( $modernee_post_link ) && modernee_is_external_url( $modernee_post_link )
								? ' target="_blank" rel="nofollow"'
								: '';
	}
	
	// Meta parts
	$modernee_components = ! empty( $modernee_template_args['meta_parts'] )
							? ( is_array( $modernee_template_args['meta_parts'] )
								? $modernee_template_args['meta_parts']
								: explode( ',', $modernee_template_args['meta_parts'] )
								)
							: modernee_array_get_keys_by_value( modernee_get_theme_option( 'meta_parts' ) );

	// Featured image
	modernee_show_post_featured( apply_filters( 'modernee_filter_args_featured',
        array(
			'hover'         => $modernee_hover,
			'no_links'      => ! empty( $modernee_template_args['no_links'] ),
			'thumb_size'    => ! empty( $modernee_template_args['thumb_size'] )
								? $modernee_template_args['thumb_size']
								: modernee_get_thumb_size(
									modernee_is_blog_style_use_masonry( $modernee_blog_style[0] )
										? (	strpos( modernee_get_theme_option( 'body_style' ), 'full' ) !== false || $modernee_columns < 3
											? 'masonry-big'
											: 'masonry'
											)
										: (	strpos( modernee_get_theme_option( 'body_style' ), 'full' ) !== false || $modernee_columns < 3
											? 'square'
											: 'square'
											)
								),
			'thumb_bg' => modernee_is_blog_style_use_masonry( $modernee_blog_style[0] ) ? false : true,
			'show_no_image' => true,
			'meta_parts'    => $modernee_components,
			'class'         => 'dots' == $modernee_hover ? 'hover_with_info' : '',
			'post_info'     => 'dots' == $modernee_hover
										? '<div class="post_info"><h5 class="post_title">'
											. ( ! empty( $modernee_post_link )
												? '<a href="' . esc_url( $modernee_post_link ) . '"' . ( ! empty( $target ) ? $target : '' ) . '>'
												: ''
												)
												. esc_html( get_the_title() ) 
											. ( ! empty( $modernee_post_link )
												? '</a>'
												: ''
												)
											. '</h5></div>'
										: '',
            'thumb_ratio'   => 'info' == $modernee_hover ?  '100:102' : '',
        ),
        'content-portfolio',
        $modernee_template_args
    ) );
	?>
</article></div><?php
// Need opening PHP-tag above, because <article> is a inline-block element (used as column)!