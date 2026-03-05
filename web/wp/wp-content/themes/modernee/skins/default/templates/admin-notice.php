<?php
/**
 * The template to display Admin notices
 *
 * @package MODERNEE
 * @since MODERNEE 1.0.1
 */

$modernee_theme_slug = get_option( 'template' );
$modernee_theme_obj  = wp_get_theme( $modernee_theme_slug );
?>
<div class="modernee_admin_notice modernee_welcome_notice notice notice-info is-dismissible" data-notice="admin">
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
		<?php
		echo esc_html(
			sprintf(
				// Translators: Add theme name and version to the 'Welcome' message
				__( 'Welcome to %1$s v.%2$s', 'modernee' ),
				$modernee_theme_obj->get( 'Name' ) . ( MODERNEE_THEME_FREE ? ' ' . __( 'Free', 'modernee' ) : '' ),
				$modernee_theme_obj->get( 'Version' )
			)
		);
		?>
	</h3>
	<?php

	// Description
	?>
	<div class="modernee_notice_text">
		<p class="modernee_notice_text_description">
			<?php
			echo str_replace( '. ', '.<br>', wp_kses_data( $modernee_theme_obj->description ) );
			?>
		</p>
		<p class="modernee_notice_text_info">
			<?php
			echo wp_kses_data( __( 'Attention! Plugin "ThemeREX Addons" is required! Please, install and activate it!', 'modernee' ) );
			?>
		</p>
	</div>
	<?php

	// Buttons
	?>
	<div class="modernee_notice_buttons">
		<?php
		// Link to the page 'About Theme'
		?>
		<a href="<?php echo esc_url( admin_url() . 'themes.php?page=modernee_about' ); ?>" class="button button-primary"><i class="dashicons dashicons-nametag"></i> 
			<?php
			echo esc_html__( 'Install plugin "ThemeREX Addons"', 'modernee' );
			?>
		</a>
	</div>
</div>
