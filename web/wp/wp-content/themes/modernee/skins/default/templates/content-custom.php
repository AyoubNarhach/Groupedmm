<?php
/**
 * The custom template to display the content
 *
 * Used for index/archive/search.
 *
 * @package MODERNEE
 * @since MODERNEE 1.0.50
 */

$modernee_template_args = get_query_var( 'modernee_template_args' );
if ( is_array( $modernee_template_args ) ) {
	$modernee_columns    = empty( $modernee_template_args['columns'] ) ? 2 : max( 1, $modernee_template_args['columns'] );
	$modernee_blog_style = array( $modernee_template_args['type'], $modernee_columns );
} else {
	$modernee_template_args = array();
	$modernee_blog_style = explode( '_', modernee_get_theme_option( 'blog_style' ) );
	$modernee_columns    = empty( $modernee_blog_style[1] ) ? 2 : max( 1, $modernee_blog_style[1] );
}
$modernee_blog_id       = modernee_get_custom_blog_id( join( '_', $modernee_blog_style ) );
$modernee_blog_style[0] = str_replace( 'blog-custom-', '', $modernee_blog_style[0] );
$modernee_expanded      = ! modernee_sidebar_present() && modernee_get_theme_option( 'expand_content' ) == 'expand';
$modernee_components    = ! empty( $modernee_template_args['meta_parts'] )
							? ( is_array( $modernee_template_args['meta_parts'] )
								? join( ',', $modernee_template_args['meta_parts'] )
								: $modernee_template_args['meta_parts']
								)
							: modernee_array_get_keys_by_value( modernee_get_theme_option( 'meta_parts' ) );
$modernee_post_format   = get_post_format();
$modernee_post_format   = empty( $modernee_post_format ) ? 'standard' : str_replace( 'post-format-', '', $modernee_post_format );

$modernee_blog_meta     = modernee_get_custom_layout_meta( $modernee_blog_id );
$modernee_custom_style  = ! empty( $modernee_blog_meta['scripts_required'] ) ? $modernee_blog_meta['scripts_required'] : 'none';

if ( ! empty( $modernee_template_args['slider'] ) || $modernee_columns > 1 || ! modernee_is_off( $modernee_custom_style ) ) {
	?><div class="
		<?php
		if ( ! empty( $modernee_template_args['slider'] ) ) {
			echo 'slider-slide swiper-slide';
		} else {
			echo esc_attr( ( modernee_is_off( $modernee_custom_style ) ? 'column' : sprintf( '%1$s_item %1$s_item', $modernee_custom_style ) ) . "-1_{$modernee_columns}" );
		}
		?>
	">
	<?php
}
?>
<article id="post-<?php the_ID(); ?>" data-post-id="<?php the_ID(); ?>"
	<?php
	post_class(
			'post_item post_item_container post_format_' . esc_attr( $modernee_post_format )
					. ' post_layout_custom post_layout_custom_' . esc_attr( $modernee_columns )
					. ' post_layout_' . esc_attr( $modernee_blog_style[0] )
					. ' post_layout_' . esc_attr( $modernee_blog_style[0] ) . '_' . esc_attr( $modernee_columns )
					. ( ! modernee_is_off( $modernee_custom_style )
						? ' post_layout_' . esc_attr( $modernee_custom_style )
							. ' post_layout_' . esc_attr( $modernee_custom_style ) . '_' . esc_attr( $modernee_columns )
						: ''
						)
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
	// Custom layout
	do_action( 'modernee_action_show_layout', $modernee_blog_id, get_the_ID() );
	?>
</article><?php
if ( ! empty( $modernee_template_args['slider'] ) || $modernee_columns > 1 || ! modernee_is_off( $modernee_custom_style ) ) {
	?></div><?php
	// Need opening PHP-tag above just after </div>, because <div> is a inline-block element (used as column)!
}
