<div class="front_page_section front_page_section_about<?php
	$modernee_scheme = modernee_get_theme_option( 'front_page_about_scheme' );
	if ( ! empty( $modernee_scheme ) && ! modernee_is_inherit( $modernee_scheme ) ) {
		echo ' scheme_' . esc_attr( $modernee_scheme );
	}
	echo ' front_page_section_paddings_' . esc_attr( modernee_get_theme_option( 'front_page_about_paddings' ) );
	if ( modernee_get_theme_option( 'front_page_about_stack' ) ) {
		echo ' sc_stack_section_on';
	}
?>"
		<?php
		$modernee_css      = '';
		$modernee_bg_image = modernee_get_theme_option( 'front_page_about_bg_image' );
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
	$modernee_anchor_icon = modernee_get_theme_option( 'front_page_about_anchor_icon' );
	$modernee_anchor_text = modernee_get_theme_option( 'front_page_about_anchor_text' );
if ( ( ! empty( $modernee_anchor_icon ) || ! empty( $modernee_anchor_text ) ) && shortcode_exists( 'trx_sc_anchor' ) ) {
	echo do_shortcode(
		'[trx_sc_anchor id="front_page_section_about"'
									. ( ! empty( $modernee_anchor_icon ) ? ' icon="' . esc_attr( $modernee_anchor_icon ) . '"' : '' )
									. ( ! empty( $modernee_anchor_text ) ? ' title="' . esc_attr( $modernee_anchor_text ) . '"' : '' )
									. ']'
	);
}
?>
	<div class="front_page_section_inner front_page_section_about_inner
	<?php
	if ( modernee_get_theme_option( 'front_page_about_fullheight' ) ) {
		echo ' modernee-full-height sc_layouts_flex sc_layouts_columns_middle';
	}
	?>
			"
			<?php
			$modernee_css           = '';
			$modernee_bg_mask       = modernee_get_theme_option( 'front_page_about_bg_mask' );
			$modernee_bg_color_type = modernee_get_theme_option( 'front_page_about_bg_color_type' );
			if ( 'custom' == $modernee_bg_color_type ) {
				$modernee_bg_color = modernee_get_theme_option( 'front_page_about_bg_color' );
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
		<div class="front_page_section_content_wrap front_page_section_about_content_wrap content_wrap">
			<?php
			// Caption
			$modernee_caption = modernee_get_theme_option( 'front_page_about_caption' );
			if ( ! empty( $modernee_caption ) || ( current_user_can( 'edit_theme_options' ) && is_customize_preview() ) ) {
				?>
				<h2 class="front_page_section_caption front_page_section_about_caption front_page_block_<?php echo ! empty( $modernee_caption ) ? 'filled' : 'empty'; ?>"><?php echo wp_kses( $modernee_caption, 'modernee_kses_content' ); ?></h2>
				<?php
			}

			// Description (text)
			$modernee_description = modernee_get_theme_option( 'front_page_about_description' );
			if ( ! empty( $modernee_description ) || ( current_user_can( 'edit_theme_options' ) && is_customize_preview() ) ) {
				?>
				<div class="front_page_section_description front_page_section_about_description front_page_block_<?php echo ! empty( $modernee_description ) ? 'filled' : 'empty'; ?>"><?php echo wp_kses( wpautop( $modernee_description ), 'modernee_kses_content' ); ?></div>
				<?php
			}

			// Content
			$modernee_content = modernee_get_theme_option( 'front_page_about_content' );
			if ( ! empty( $modernee_content ) || ( current_user_can( 'edit_theme_options' ) && is_customize_preview() ) ) {
				?>
				<div class="front_page_section_content front_page_section_about_content front_page_block_<?php echo ! empty( $modernee_content ) ? 'filled' : 'empty'; ?>">
					<?php
					$modernee_page_content_mask = '%%CONTENT%%';
					if ( strpos( $modernee_content, $modernee_page_content_mask ) !== false ) {
						$modernee_content = preg_replace(
							'/(\<p\>\s*)?' . $modernee_page_content_mask . '(\s*\<\/p\>)/i',
							sprintf(
								'<div class="front_page_section_about_source">%s</div>',
								apply_filters( 'the_content', get_the_content() )
							),
							$modernee_content
						);
					}
					modernee_show_layout( $modernee_content );
					?>
				</div>
				<?php
			}
			?>
		</div>
	</div>
</div>
