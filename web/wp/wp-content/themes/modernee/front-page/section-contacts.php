<div class="front_page_section front_page_section_contacts<?php
	$modernee_scheme = modernee_get_theme_option( 'front_page_contacts_scheme' );
	if ( ! empty( $modernee_scheme ) && ! modernee_is_inherit( $modernee_scheme ) ) {
		echo ' scheme_' . esc_attr( $modernee_scheme );
	}
	echo ' front_page_section_paddings_' . esc_attr( modernee_get_theme_option( 'front_page_contacts_paddings' ) );
	if ( modernee_get_theme_option( 'front_page_contacts_stack' ) ) {
		echo ' sc_stack_section_on';
	}
?>"
		<?php
		$modernee_css      = '';
		$modernee_bg_image = modernee_get_theme_option( 'front_page_contacts_bg_image' );
		if ( ! empty( $modernee_bg_image ) ) {
			$modernee_css .= 'background-image: url(' . esc_url( modernee_get_attachment_url( $modernee_bg_image ) ) . ');';
		}
		if ( ! empty( $modernee_css ) ) {
			echo ' style="' . esc_attr( $modernee_css ) . '"';
		}
		?>
>
<?php
	// Add anchor
	$modernee_anchor_icon = modernee_get_theme_option( 'front_page_contacts_anchor_icon' );
	$modernee_anchor_text = modernee_get_theme_option( 'front_page_contacts_anchor_text' );
if ( ( ! empty( $modernee_anchor_icon ) || ! empty( $modernee_anchor_text ) ) && shortcode_exists( 'trx_sc_anchor' ) ) {
	echo do_shortcode(
		'[trx_sc_anchor id="front_page_section_contacts"'
									. ( ! empty( $modernee_anchor_icon ) ? ' icon="' . esc_attr( $modernee_anchor_icon ) . '"' : '' )
									. ( ! empty( $modernee_anchor_text ) ? ' title="' . esc_attr( $modernee_anchor_text ) . '"' : '' )
									. ']'
	);
}
?>
	<div class="front_page_section_inner front_page_section_contacts_inner
	<?php
	if ( modernee_get_theme_option( 'front_page_contacts_fullheight' ) ) {
		echo ' modernee-full-height sc_layouts_flex sc_layouts_columns_middle';
	}
	?>
			"
			<?php
			$modernee_css      = '';
			$modernee_bg_mask  = modernee_get_theme_option( 'front_page_contacts_bg_mask' );
			$modernee_bg_color_type = modernee_get_theme_option( 'front_page_contacts_bg_color_type' );
			if ( 'custom' == $modernee_bg_color_type ) {
				$modernee_bg_color = modernee_get_theme_option( 'front_page_contacts_bg_color' );
			} elseif ( 'scheme_bg_color' == $modernee_bg_color_type ) {
				$modernee_bg_color = modernee_get_scheme_color( 'bg_color', $modernee_scheme );
			} else {
				$modernee_bg_color = '';
			}
			if ( ! empty( $modernee_bg_color ) && $modernee_bg_mask > 0 ) {
				$modernee_css .= 'background-color: ' . esc_attr(
					1 == $modernee_bg_mask ? $modernee_bg_color : modernee_hex2rgba( $modernee_bg_color, $modernee_bg_mask )
				) . ';';
			}
			if ( ! empty( $modernee_css ) ) {
				echo ' style="' . esc_attr( $modernee_css ) . '"';
			}
			?>
	>
		<div class="front_page_section_content_wrap front_page_section_contacts_content_wrap content_wrap">
			<?php

			// Title and description
			$modernee_caption     = modernee_get_theme_option( 'front_page_contacts_caption' );
			$modernee_description = modernee_get_theme_option( 'front_page_contacts_description' );
			if ( ! empty( $modernee_caption ) || ! empty( $modernee_description ) || ( current_user_can( 'edit_theme_options' ) && is_customize_preview() ) ) {
				// Caption
				if ( ! empty( $modernee_caption ) || ( current_user_can( 'edit_theme_options' ) && is_customize_preview() ) ) {
					?>
					<h2 class="front_page_section_caption front_page_section_contacts_caption front_page_block_<?php echo ! empty( $modernee_caption ) ? 'filled' : 'empty'; ?>">
					<?php
						echo wp_kses( $modernee_caption, 'modernee_kses_content' );
					?>
					</h2>
					<?php
				}

				// Description
				if ( ! empty( $modernee_description ) || ( current_user_can( 'edit_theme_options' ) && is_customize_preview() ) ) {
					?>
					<div class="front_page_section_description front_page_section_contacts_description front_page_block_<?php echo ! empty( $modernee_description ) ? 'filled' : 'empty'; ?>">
					<?php
						echo wp_kses( wpautop( $modernee_description ), 'modernee_kses_content' );
					?>
					</div>
					<?php
				}
			}

			// Content (text)
			$modernee_content = modernee_get_theme_option( 'front_page_contacts_content' );
			$modernee_layout  = modernee_get_theme_option( 'front_page_contacts_layout' );
			if ( 'columns' == $modernee_layout && ( ! empty( $modernee_content ) || ( current_user_can( 'edit_theme_options' ) && is_customize_preview() ) ) ) {
				?>
				<div class="front_page_section_columns front_page_section_contacts_columns columns_wrap">
					<div class="column-1_3">
				<?php
			}

			if ( ( ! empty( $modernee_content ) || ( current_user_can( 'edit_theme_options' ) && is_customize_preview() ) ) ) {
				?>
				<div class="front_page_section_content front_page_section_contacts_content front_page_block_<?php echo ! empty( $modernee_content ) ? 'filled' : 'empty'; ?>">
					<?php
					echo wp_kses( $modernee_content, 'modernee_kses_content' );
					?>
				</div>
				<?php
			}

			if ( 'columns' == $modernee_layout && ( ! empty( $modernee_content ) || ( current_user_can( 'edit_theme_options' ) && is_customize_preview() ) ) ) {
				?>
				</div><div class="column-2_3">
				<?php
			}

			// Shortcode output
			$modernee_sc = modernee_get_theme_option( 'front_page_contacts_shortcode' );
			if ( ! empty( $modernee_sc ) || ( current_user_can( 'edit_theme_options' ) && is_customize_preview() ) ) {
				?>
				<div class="front_page_section_output front_page_section_contacts_output front_page_block_<?php echo ! empty( $modernee_sc ) ? 'filled' : 'empty'; ?>">
					<?php
					modernee_show_layout( do_shortcode( $modernee_sc ) );
					?>
				</div>
				<?php
			}

			if ( 'columns' == $modernee_layout && ( ! empty( $modernee_content ) || ( current_user_can( 'edit_theme_options' ) && is_customize_preview() ) ) ) {
				?>
				</div></div>
				<?php
			}
			?>

		</div>
	</div>
</div>
