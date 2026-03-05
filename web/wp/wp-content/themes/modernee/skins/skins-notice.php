<?php
/**
 * The template to display Admin notices
 *
 * @package MODERNEE
 * @since MODERNEE 1.0.64
 */

$modernee_skins_url  = get_admin_url( null, 'admin.php?page=trx_addons_theme_panel#trx_addons_theme_panel_section_skins' );
$modernee_skins_args = get_query_var( 'modernee_skins_notice_args' );
?>
<div class="modernee_admin_notice modernee_skins_notice notice notice-info is-dismissible" data-notice="skins">
	<?php
	// Theme image
	$modernee_theme_img = modernee_get_file_url( 'screenshot.jpg' );
	if ( '' != $modernee_theme_img ) {
		?>
		<div class="modernee_notice_image"><img src="<?php echo esc_url( $modernee_theme_img ); ?>" alt="<?php esc_attr_e( 'Theme screenshot', 'modernee' ); ?>"></div>
		<?php
	}

	// Title
	?>
	<h3 class="modernee_notice_title">
		<?php esc_html_e( 'New skins are available', 'modernee' ); ?>
	</h3>
	<?php

	// Description
	$modernee_total      = $modernee_skins_args['update'];	// Store value to the separate variable to avoid warnings from ThemeCheck plugin!
	$modernee_skins_msg  = $modernee_total > 0
							// Translators: Add new skins number
							? '<strong>' . sprintf( _n( '%d new version', '%d new versions', $modernee_total, 'modernee' ), $modernee_total ) . '</strong>'
							: '';
	$modernee_total      = $modernee_skins_args['free'];
	$modernee_skins_msg .= $modernee_total > 0
							? ( ! empty( $modernee_skins_msg ) ? ' ' . esc_html__( 'and', 'modernee' ) . ' ' : '' )
								// Translators: Add new skins number
								. '<strong>' . sprintf( _n( '%d free skin', '%d free skins', $modernee_total, 'modernee' ), $modernee_total ) . '</strong>'
							: '';
	$modernee_total      = $modernee_skins_args['pay'];
	$modernee_skins_msg .= $modernee_skins_args['pay'] > 0
							? ( ! empty( $modernee_skins_msg ) ? ' ' . esc_html__( 'and', 'modernee' ) . ' ' : '' )
								// Translators: Add new skins number
								. '<strong>' . sprintf( _n( '%d paid skin', '%d paid skins', $modernee_total, 'modernee' ), $modernee_total ) . '</strong>'
							: '';
	?>
	<div class="modernee_notice_text">
		<p>
			<?php
			// Translators: Add new skins info
			echo wp_kses_data( sprintf( __( "We are pleased to announce that %s are available for your theme", 'modernee' ), $modernee_skins_msg ) );
			?>
		</p>
	</div>
	<?php

	// Buttons
	?>
	<div class="modernee_notice_buttons">
		<?php
		// Link to the theme dashboard page
		?>
		<a href="<?php echo esc_url( $modernee_skins_url ); ?>" class="button button-primary"><i class="dashicons dashicons-update"></i> 
			<?php
			// Translators: Add theme name
			esc_html_e( 'Go to Skins manager', 'modernee' );
			?>
		</a>
	</div>
</div>
