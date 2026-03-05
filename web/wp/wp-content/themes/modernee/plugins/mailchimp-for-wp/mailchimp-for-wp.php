<?php
/* Mail Chimp support functions
------------------------------------------------------------------------------- */

// Theme init priorities:
// 9 - register other filters (for installer, etc.)
if ( ! function_exists( 'modernee_mailchimp_theme_setup9' ) ) {
	add_action( 'after_setup_theme', 'modernee_mailchimp_theme_setup9', 9 );
	function modernee_mailchimp_theme_setup9() {
		if ( modernee_exists_mailchimp() ) {
			add_action( 'wp_enqueue_scripts', 'modernee_mailchimp_frontend_scripts', 1100 );
			add_action( 'trx_addons_action_load_scripts_front_mailchimp', 'modernee_mailchimp_frontend_scripts', 10, 1 );
			add_filter( 'modernee_filter_merge_styles', 'modernee_mailchimp_merge_styles' );
		}
		if ( is_admin() ) {
			add_filter( 'modernee_filter_tgmpa_required_plugins', 'modernee_mailchimp_tgmpa_required_plugins' );
		}
	}
}

// Filter to add in the required plugins list
if ( ! function_exists( 'modernee_mailchimp_tgmpa_required_plugins' ) ) {
	//Handler of the add_filter('modernee_filter_tgmpa_required_plugins',	'modernee_mailchimp_tgmpa_required_plugins');
	function modernee_mailchimp_tgmpa_required_plugins( $list = array() ) {
		if ( modernee_storage_isset( 'required_plugins', 'mailchimp-for-wp' ) && modernee_storage_get_array( 'required_plugins', 'mailchimp-for-wp', 'install' ) !== false ) {
			$list[] = array(
				'name'     => modernee_storage_get_array( 'required_plugins', 'mailchimp-for-wp', 'title' ),
				'slug'     => 'mailchimp-for-wp',
				'required' => false,
			);
		}
		return $list;
	}
}

// Check if plugin installed and activated
if ( ! function_exists( 'modernee_exists_mailchimp' ) ) {
	function modernee_exists_mailchimp() {
		return function_exists( '__mc4wp_load_plugin' ) || defined( 'MC4WP_VERSION' );
	}
}



// Custom styles and scripts
//------------------------------------------------------------------------

// Enqueue styles for frontend
if ( ! function_exists( 'modernee_mailchimp_frontend_scripts' ) ) {
	//Handler of the add_action( 'wp_enqueue_scripts', 'modernee_mailchimp_frontend_scripts', 1100 );
	//Handler of the add_action( 'trx_addons_action_load_scripts_front_mailchimp', 'modernee_mailchimp_frontend_scripts', 10, 1 );
	function modernee_mailchimp_frontend_scripts( $force = false ) {
		modernee_enqueue_optimized( 'mailchimp', $force, array(
			'css' => array(
				'modernee-mailchimp-for-wp' => array( 'src' => 'plugins/mailchimp-for-wp/mailchimp-for-wp.css' ),
			)
		) );
	}
}

// Merge custom styles
if ( ! function_exists( 'modernee_mailchimp_merge_styles' ) ) {
	//Handler of the add_filter( 'modernee_filter_merge_styles', 'modernee_mailchimp_merge_styles');
	function modernee_mailchimp_merge_styles( $list ) {
		$list[ 'plugins/mailchimp-for-wp/mailchimp-for-wp.css' ] = false;
		return $list;
	}
}


// Add plugin-specific colors and fonts to the custom CSS
if ( modernee_exists_mailchimp() ) {
	$modernee_fdir = modernee_get_file_dir( 'plugins/mailchimp-for-wp/mailchimp-for-wp-style.php' );
	if ( ! empty( $modernee_fdir ) ) {
		require_once $modernee_fdir;
	}
}

