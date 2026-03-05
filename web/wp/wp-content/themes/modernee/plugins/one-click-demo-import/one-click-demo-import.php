<?php
/* One Click Demo Import support functions
------------------------------------------------------------------------------- */

// Theme init priorities:
// 9 - register other filters (for installer, etc.)
if ( ! function_exists( 'modernee_ocdi_theme_setup9' ) ) {
	add_action( 'after_setup_theme', 'modernee_ocdi_theme_setup9', 9 );
	function modernee_ocdi_theme_setup9() {
		if ( is_admin() ) {
			add_filter( 'modernee_filter_tgmpa_required_plugins', 'modernee_ocdi_tgmpa_required_plugins' );
		}
	}
}

// Filter to add in the required plugins list
if ( ! function_exists( 'modernee_ocdi_tgmpa_required_plugins' ) ) {
	//Handler of the add_filter('modernee_filter_tgmpa_required_plugins',	'modernee_ocdi_tgmpa_required_plugins');
	function modernee_ocdi_tgmpa_required_plugins( $list = array() ) {
		if ( modernee_storage_isset( 'required_plugins', 'one-click-demo-import' ) && modernee_storage_get_array( 'required_plugins', 'one-click-demo-import', 'install' ) !== false ) {
			$list[] = array(
				'name'     => modernee_storage_get_array( 'required_plugins', 'one-click-demo-import', 'title' ),
				'slug'     => 'one-click-demo-import',
				'required' => false,
			);
		}
		return $list;
	}
}

// Check if plugin is installed and activated
if ( ! function_exists( 'modernee_exists_ocdi' ) ) {
	function modernee_exists_ocdi() {
		return class_exists( 'OCDI_Plugin' );
	}
}

