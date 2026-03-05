<?php
/**
 * Required plugins
 *
 * @package MODERNEE
 * @since MODERNEE 1.76.0
 */

// THEME-SUPPORTED PLUGINS
// If plugin not need - remove its settings from next array
//----------------------------------------------------------
$modernee_theme_required_plugins_groups = array(
	'core'          => esc_html__( 'Core', 'modernee' ),
	'page_builders' => esc_html__( 'Page Builders', 'modernee' ),
	'ecommerce'     => esc_html__( 'E-Commerce & Donations', 'modernee' ),
	'socials'       => esc_html__( 'Socials and Communities', 'modernee' ),
	'events'        => esc_html__( 'Events and Appointments', 'modernee' ),
	'content'       => esc_html__( 'Content', 'modernee' ),
	'other'         => esc_html__( 'Other', 'modernee' ),
);
$modernee_theme_required_plugins        = array(
	'trx_addons'                 => array(
		'title'       => esc_html__( 'ThemeREX Addons', 'modernee' ),
		'description' => esc_html__( "Will allow you to install recommended plugins, demo content, and improve the theme's functionality overall with multiple theme options", 'modernee' ),
		'required'    => true,
		'logo'        => 'trx_addons.png',
		'group'       => $modernee_theme_required_plugins_groups['core'],
	),
	'elementor'                  => array(
		'title'       => esc_html__( 'Elementor', 'modernee' ),
		'description' => esc_html__( "Is a beautiful PageBuilder, even the free version of which allows you to create great pages using a variety of modules.", 'modernee' ),
		'required'    => false,
		'logo'        => 'elementor.png',
		'group'       => $modernee_theme_required_plugins_groups['page_builders'],
	),
	'gutenberg'                  => array(
		'title'       => esc_html__( 'Gutenberg', 'modernee' ),
		'description' => esc_html__( "It's a posts editor coming in place of the classic TinyMCE. Can be installed and used in parallel with Elementor", 'modernee' ),
		'required'    => false,
		'install'     => false,          // Do not offer installation of the plugin in the Theme Dashboard and TGMPA
		'logo'        => 'gutenberg.png',
		'group'       => $modernee_theme_required_plugins_groups['page_builders'],
	),
	'js_composer'                => array(
		'title'       => esc_html__( 'WPBakery PageBuilder', 'modernee' ),
		'description' => esc_html__( "Popular PageBuilder which allows you to create excellent pages", 'modernee' ),
		'required'    => false,
		'install'     => false,          // Do not offer installation of the plugin in the Theme Dashboard and TGMPA
		'logo'        => 'js_composer.jpg',
		'group'       => $modernee_theme_required_plugins_groups['page_builders'],
	),
	'woocommerce'                => array(
		'title'       => esc_html__( 'WooCommerce', 'modernee' ),
		'description' => esc_html__( "Connect the store to your website and start selling now", 'modernee' ),
		'required'    => false,
                'install'     => false,
		'logo'        => 'woocommerce.png',
		'group'       => $modernee_theme_required_plugins_groups['ecommerce'],
	),
	'elegro-payment'             => array(
		'title'       => esc_html__( 'Elegro Crypto Payment', 'modernee' ),
		'description' => esc_html__( "Extends WooCommerce Payment Gateways with an elegro Crypto Payment", 'modernee' ),
		'required'    => false,
		'install'     => false, // TRX_addons has marked the "Elegro Crypto Payment" plugin as obsolete and no longer recommends it for installation, even if it had been previously recommended by the theme
		'logo'        => 'elegro-payment.png',
		'group'       => $modernee_theme_required_plugins_groups['ecommerce'],
	),
	'instagram-feed'             => array(
		'title'       => esc_html__( 'Instagram Feed', 'modernee' ),
		'description' => esc_html__( "Displays the latest photos from your profile on Instagram", 'modernee' ),
		'required'    => false,
		'logo'        => 'instagram-feed.png',
		'group'       => $modernee_theme_required_plugins_groups['socials'],
	),
	'mailchimp-for-wp'           => array(
		'title'       => esc_html__( 'MailChimp for WP', 'modernee' ),
		'description' => esc_html__( "Allows visitors to subscribe to newsletters", 'modernee' ),
		'required'    => false,
		'logo'        => 'mailchimp-for-wp.png',
		'group'       => $modernee_theme_required_plugins_groups['socials'],
	),
	'booked'                     => array(
		'title'       => esc_html__( 'Booked Appointments', 'modernee' ),
		'description' => '',
		'required'    => false,
		'install'     => false,
		'logo'        => 'booked.png',
		'group'       => $modernee_theme_required_plugins_groups['events'],
	),
	'quickcal'                     => array(
		'title'       => esc_html__( 'QuickCal', 'modernee' ),
		'description' => '',
		'required'    => false,
                'install'     => false,
		'logo'        => 'quickcal.png',
		'group'       => $modernee_theme_required_plugins_groups['events'],
	),
	'the-events-calendar'        => array(
		'title'       => esc_html__( 'The Events Calendar', 'modernee' ),
		'description' => '',
		'required'    => false,
                'install'     => false,
		'logo'        => 'the-events-calendar.png',
		'group'       => $modernee_theme_required_plugins_groups['events'],
	),
	'contact-form-7'             => array(
		'title'       => esc_html__( 'Contact Form 7', 'modernee' ),
		'description' => esc_html__( "CF7 allows you to create an unlimited number of contact forms", 'modernee' ),
		'required'    => false,
		'logo'        => 'contact-form-7.png',
		'group'       => $modernee_theme_required_plugins_groups['content'],
	),

	'latepoint'                  => array(
		'title'       => esc_html__( 'LatePoint', 'modernee' ),
		'description' => '',
		'required'    => false,
                'install'     => false,
		'logo'        => modernee_get_file_url( 'plugins/latepoint/latepoint.png' ),
		'group'       => $modernee_theme_required_plugins_groups['events'],
	),
	'advanced-popups'                  => array(
		'title'       => esc_html__( 'Advanced Popups', 'modernee' ),
		'description' => '',
		'required'    => false,
		'logo'        => modernee_get_file_url( 'plugins/advanced-popups/advanced-popups.jpg' ),
		'group'       => $modernee_theme_required_plugins_groups['content'],
	),
	'devvn-image-hotspot'                  => array(
		'title'       => esc_html__( 'Image Hotspot by DevVN', 'modernee' ),
		'description' => '',
		'required'    => false,
                'install'     => false,
		'logo'        => modernee_get_file_url( 'plugins/devvn-image-hotspot/devvn-image-hotspot.png' ),
		'group'       => $modernee_theme_required_plugins_groups['content'],
	),
	'ti-woocommerce-wishlist'                  => array(
		'title'       => esc_html__( 'TI WooCommerce Wishlist', 'modernee' ),
		'description' => '',
		'required'    => false,
                'install'     => false,
		'logo'        => modernee_get_file_url( 'plugins/ti-woocommerce-wishlist/ti-woocommerce-wishlist.png' ),
		'group'       => $modernee_theme_required_plugins_groups['ecommerce'],
	),
	'woo-smart-quick-view'                  => array(
		'title'       => esc_html__( 'WPC Smart Quick View for WooCommerce', 'modernee' ),
		'description' => '',
		'required'    => false,
                'install'     => false,
		'logo'        => modernee_get_file_url( 'plugins/woo-smart-quick-view/woo-smart-quick-view.png' ),
		'group'       => $modernee_theme_required_plugins_groups['ecommerce'],
	),
	'twenty20'                  => array(
		'title'       => esc_html__( 'Twenty20 Image Before-After', 'modernee' ),
		'description' => '',
		'required'    => false,
        	'install'     => false,
		'logo'        => modernee_get_file_url( 'plugins/twenty20/twenty20.png' ),
		'group'       => $modernee_theme_required_plugins_groups['content'],
	),
	'essential-grid'             => array(
		'title'       => esc_html__( 'Essential Grid', 'modernee' ),
		'description' => '',
		'required'    => false,
		'install'     => false,
		'logo'        => 'essential-grid.png',
		'group'       => $modernee_theme_required_plugins_groups['content'],
	),
	'revslider'                  => array(
		'title'       => esc_html__( 'Revolution Slider', 'modernee' ),
		'description' => '',
		'required'    => false,
        	'install'     => false,
		'logo'        => 'revslider.png',
		'group'       => $modernee_theme_required_plugins_groups['content'],
	),
	'sitepress-multilingual-cms' => array(
		'title'       => esc_html__( 'WPML - Sitepress Multilingual CMS', 'modernee' ),
		'description' => esc_html__( "Allows you to make your website multilingual", 'modernee' ),
		'required'    => false,
		'install'     => false,      // Do not offer installation of the plugin in the Theme Dashboard and TGMPA
		'logo'        => 'sitepress-multilingual-cms.png',
		'group'       => $modernee_theme_required_plugins_groups['content'],
	),
	'wp-gdpr-compliance'         => array(
		'title'       => esc_html__( 'Cookie Information', 'modernee' ),
		'description' => esc_html__( "Allow visitors to decide for themselves what personal data they want to store on your site", 'modernee' ),
		'required'    => false,
		'install'     => false,
		'logo'        => 'wp-gdpr-compliance.png',
		'group'       => $modernee_theme_required_plugins_groups['other'],
	),
	'gdpr-framework'         => array(
		'title'       => esc_html__( 'The GDPR Framework', 'modernee' ),
		'description' => esc_html__( "Tools to help make your website GDPR-compliant. Fully documented, extendable and developer-friendly.", 'modernee' ),
		'required'    => false,
		'install'     => false,
		'logo'        => 'gdpr-framework.png',
		'group'       => $modernee_theme_required_plugins_groups['other'],
	),
	'trx_updater'                => array(
		'title'       => esc_html__( 'ThemeREX Updater', 'modernee' ),
		'description' => esc_html__( "Update theme and theme-specific plugins from developer's upgrade server.", 'modernee' ),
		'required'    => false,
		'logo'        => 'trx_updater.png',
		'group'       => $modernee_theme_required_plugins_groups['other'],
	),
);

if ( MODERNEE_THEME_FREE ) {
	unset( $modernee_theme_required_plugins['js_composer'] );
	unset( $modernee_theme_required_plugins['booked'] );
	unset( $modernee_theme_required_plugins['quickcal'] );
	unset( $modernee_theme_required_plugins['the-events-calendar'] );
	unset( $modernee_theme_required_plugins['calculated-fields-form'] );
	unset( $modernee_theme_required_plugins['essential-grid'] );
	unset( $modernee_theme_required_plugins['revslider'] );
	unset( $modernee_theme_required_plugins['sitepress-multilingual-cms'] );
	unset( $modernee_theme_required_plugins['trx_updater'] );
	unset( $modernee_theme_required_plugins['trx_popup'] );
}

// Add plugins list to the global storage
modernee_storage_set( 'required_plugins', $modernee_theme_required_plugins );
