<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 * Learn more: //codex.wordpress.org/Template_Hierarchy
 *
 * @package MODERNEE
 * @since MODERNEE 1.0
 */

$modernee_template = apply_filters( 'modernee_filter_get_template_part', modernee_blog_archive_get_template() );

if ( ! empty( $modernee_template ) && 'index' != $modernee_template ) {

	get_template_part( $modernee_template );

} else {

	modernee_storage_set( 'blog_archive', true );

	get_header();

	if ( have_posts() ) {

		// Query params
		$modernee_stickies   = is_home()
								|| ( in_array( modernee_get_theme_option( 'post_type' ), array( '', 'post' ) )
									&& (int) modernee_get_theme_option( 'parent_cat' ) == 0
									)
										? get_option( 'sticky_posts' )
										: false;
		$modernee_post_type  = modernee_get_theme_option( 'post_type' );
		$modernee_args       = array(
								'blog_style'     => modernee_get_theme_option( 'blog_style' ),
								'post_type'      => $modernee_post_type,
								'taxonomy'       => modernee_get_post_type_taxonomy( $modernee_post_type ),
								'parent_cat'     => modernee_get_theme_option( 'parent_cat' ),
								'posts_per_page' => modernee_get_theme_option( 'posts_per_page' ),
								'sticky'         => modernee_get_theme_option( 'sticky_style', 'inherit' ) == 'columns'
															&& is_array( $modernee_stickies )
															&& count( $modernee_stickies ) > 0
															&& get_query_var( 'paged' ) < 1
								);

		modernee_blog_archive_start();

		do_action( 'modernee_action_blog_archive_start' );

		if ( is_author() ) {
			do_action( 'modernee_action_before_page_author' );
			get_template_part( apply_filters( 'modernee_filter_get_template_part', 'templates/author-page' ) );
			do_action( 'modernee_action_after_page_author' );
		}

		if ( modernee_get_theme_option( 'show_filters', 0 ) ) {
			do_action( 'modernee_action_before_page_filters' );
			modernee_show_filters( $modernee_args );
			do_action( 'modernee_action_after_page_filters' );
		} else {
			do_action( 'modernee_action_before_page_posts' );
			modernee_show_posts( array_merge( $modernee_args, array( 'cat' => $modernee_args['parent_cat'] ) ) );
			do_action( 'modernee_action_after_page_posts' );
		}

		do_action( 'modernee_action_blog_archive_end' );

		modernee_blog_archive_end();

	} else {

		if ( is_search() ) {
			get_template_part( apply_filters( 'modernee_filter_get_template_part', 'templates/content', 'none-search' ), 'none-search' );
		} else {
			get_template_part( apply_filters( 'modernee_filter_get_template_part', 'templates/content', 'none-archive' ), 'none-archive' );
		}
	}

	get_footer();
}
