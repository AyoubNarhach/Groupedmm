<?php
/* WPC Smart Quick View for WooCommerce support functions
------------------------------------------------------------------------------- */

// Theme init priorities:
// 9 - register other filters (for installer, etc.)
if (!function_exists('modernee_quick_view_theme_setup9')) {
	add_action( 'after_setup_theme', 'modernee_quick_view_theme_setup9', 9 );
	function modernee_quick_view_theme_setup9() {
		if (modernee_exists_quick_view()) {
			add_action( 'wp_enqueue_scripts', 'modernee_quick_view_frontend_scripts', 1100 );
			add_filter( 'modernee_filter_merge_styles', 'modernee_quick_view_merge_styles' );
		}
		if (is_admin()) {
			add_filter( 'modernee_filter_tgmpa_required_plugins',		'modernee_quick_view_tgmpa_required_plugins' );
		}
	}
}

// Filter to add in the required plugins list
if ( !function_exists( 'modernee_quick_view_tgmpa_required_plugins' ) ) {
	function modernee_quick_view_tgmpa_required_plugins($list=array()) {
		if (modernee_storage_isset( 'required_plugins', 'woocommerce' ) && modernee_storage_get_array( 'required_plugins', 'woocommerce', 'install' ) !== false &&
			modernee_storage_isset('required_plugins', 'woo-smart-quick-view') && modernee_storage_get_array( 'required_plugins', 'woo-smart-quick-view', 'install' ) !== false) {
			$list[] = array(
				'name' 		=> modernee_storage_get_array('required_plugins', 'woo-smart-quick-view', 'title'),
				'slug' 		=> 'woo-smart-quick-view',
				'required' 	=> false
			);
		}
		return $list;
	}
}

// Check if plugin installed and activated
if ( !function_exists( 'modernee_exists_quick_view' ) ) {
	function modernee_exists_quick_view() {
		return function_exists('woosq_init');
	}
}

// Enqueue custom scripts
if ( ! function_exists( 'modernee_quick_view_frontend_scripts' ) ) {
	function modernee_quick_view_frontend_scripts() {
		if ( modernee_is_on( modernee_get_theme_option( 'debug_mode' ) ) ) {
			$modernee_url = modernee_get_file_url( 'plugins/woo-smart-quick-view/woo-smart-quick-view.css' );
			if ( '' != $modernee_url ) {
				wp_enqueue_style( 'modernee-woo-smart-quick-view', $modernee_url, array(), null );
			}
		}
	}
}

// Merge custom styles
if ( ! function_exists( 'modernee_quick_view_merge_styles' ) ) {
	function modernee_quick_view_merge_styles( $list ) {
		$list['plugins/woo-smart-quick-view/woo-smart-quick-view.css'] = true;
		return $list;
	}
}

// Add plugin-specific colors and fonts to the custom CSS
if ( modernee_exists_quick_view() ) {
	require_once modernee_get_file_dir( 'plugins/woo-smart-quick-view/woo-smart-quick-view-style.php' );
}


// One-click import support
//------------------------------------------------------------------------

// Check plugin in the required plugins
if ( !function_exists( 'modernee_quick_view_importer_required_plugins' ) ) {
    if (is_admin()) add_filter( 'trx_addons_filter_importer_required_plugins',	'modernee_quick_view_importer_required_plugins', 10, 2 );
    function modernee_quick_view_importer_required_plugins($not_installed='', $list='') {
        if (strpos($list, 'woo-smart-quick-view')!==false && !modernee_exists_quick_view() )
            $not_installed .= '<br>' . esc_html__('WPC Smart Quick View for WooCommerce', 'modernee');
        return $not_installed;
    }
}

// Set plugin's specific importer options
if ( !function_exists( 'modernee_quick_view_importer_set_options' ) ) {
    if (is_admin()) add_filter( 'trx_addons_filter_importer_options',	'modernee_quick_view_importer_set_options' );
    function modernee_quick_view_importer_set_options($options=array()) {
        if ( modernee_exists_quick_view() && in_array('woo-smart-quick-view', $options['required_plugins']) ) {
            $options['additional_options'][] = 'woosq_%';
        }
        return $options;
    }
}