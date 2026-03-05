<?php
/**
 * The template to display Admin notices
 *
 * @package MODERNEE
 * @since MODERNEE 1.98.0
 */

$modernee_skins_url   = get_admin_url( null, 'admin.php?page=trx_addons_theme_panel#trx_addons_theme_panel_section_skins' );
$modernee_active_skin = modernee_skins_get_active_skin_name();
?>
<div class="modernee_admin_notice modernee_skins_notice notice notice-error">
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
		<?php esc_html_e( 'Active skin is missing!', 'modernee' ); ?>
	</h3>
	<div class="modernee_notice_text">
		<p>
			<?php
			// Translators: Add a current skin name to the message
			echo wp_kses_data( sprintf( __( "Your active skin <b>'%s'</b> is missing. Usually this happens when the theme is updated directly through the server or FTP.", 'modernee' ), ucfirst( $modernee_active_skin ) ) );
			?>
		</p>
		<p>
			<?php
			echo wp_kses_data( __( "Please use only <b>'ThemeREX Updater v.1.6.0+'</b> plugin for your future updates.", 'modernee' ) );
			?>
		</p>
		<p>
			<?php
			echo wp_kses_data( __( "But no worries! You can re-download the skin via 'Skins Manager' ( Theme Panel - Theme Dashboard - Skins ).", 'modernee' ) );
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
