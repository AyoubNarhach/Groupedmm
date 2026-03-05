<?php
$modernee_woocommerce_sc = modernee_get_theme_option( 'front_page_woocommerce_products' );
if ( ! empty( $modernee_woocommerce_sc ) ) {
	?><div class="front_page_section front_page_section_woocommerce<?php
		$modernee_scheme = modernee_get_theme_option( 'front_page_woocommerce_scheme' );
		if ( ! empty( $modernee_scheme ) && ! modernee_is_inherit( $modernee_scheme ) ) {
			echo ' scheme_' . esc_attr( $modernee_scheme );
		}
		echo ' front_page_section_paddings_' . esc_attr( modernee_get_theme_option( 'front_page_woocommerce_paddings' ) );
		if ( modernee_get_theme_option( 'front_page_woocommerce_stack' ) ) {
			echo ' sc_stack_section_on';
		}
	?>"
			<?php
			$modernee_css      = '';
			$modernee_bg_image = modernee_get_theme_option( 'front_page_woocommerce_bg_image' );
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
		$modernee_anchor_icon = modernee_get_theme_option( 'front_page_woocommerce_anchor_icon' );
		$modernee_anchor_text = modernee_get_theme_option( 'front_page_woocommerce_anchor_text' );
		if ( ( ! empty( $modernee_anchor_icon ) || ! empty( $modernee_anchor_text ) ) && shortcode_exists( 'trx_sc_anchor' ) ) {
			echo do_shortcode(
				'[trx_sc_anchor id="front_page_section_woocommerce"'
											. ( ! empty( $modernee_anchor_icon ) ? ' icon="' . esc_attr( $modernee_anchor_icon ) . '"' : '' )
											. ( ! empty( $modernee_anchor_text ) ? ' title="' . esc_attr( $modernee_anchor_text ) . '"' : '' )
											. ']'
			);
		}
	?>
		<div class="front_page_section_inner front_page_section_woocommerce_inner
			<?php
			if ( modernee_get_theme_option( 'front_page_woocommerce_fullheight' ) ) {
				echo ' modernee-full-height sc_layouts_flex sc_layouts_columns_middle';
			}
			?>
				"
				<?php
				$modernee_css      = '';
				$modernee_bg_mask  = modernee_get_theme_option( 'front_page_woocommerce_bg_mask' );
				$modernee_bg_color_type = modernee_get_theme_option( 'front_page_woocommerce_bg_color_type' );
				if ( 'custom' == $modernee_bg_color_type ) {
					$modernee_bg_color = modernee_get_theme_option( 'front_page_woocommerce_bg_color' );
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
			<div class="front_page_section_content_wrap front_page_section_woocommerce_content_wrap content_wrap woocommerce">
				<?php
				// Content wrap with title and description
				$modernee_caption     = modernee_get_theme_option( 'front_page_woocommerce_caption' );
				$modernee_description = modernee_get_theme_option( 'front_page_woocommerce_description' );
				if ( ! empty( $modernee_caption ) || ! empty( $modernee_description ) || ( current_user_can( 'edit_theme_options' ) && is_customize_preview() ) ) {
					// Caption
					if ( ! empty( $modernee_caption ) || ( current_user_can( 'edit_theme_options' ) && is_customize_preview() ) ) {
						?>
						<h2 class="front_page_section_caption front_page_section_woocommerce_caption front_page_block_<?php echo ! empty( $modernee_caption ) ? 'filled' : 'empty'; ?>">
						<?php
							echo wp_kses( $modernee_caption, 'modernee_kses_content' );
						?>
						</h2>
						<?php
					}

					// Description (text)
					if ( ! empty( $modernee_description ) || ( current_user_can( 'edit_theme_options' ) && is_customize_preview() ) ) {
						?>
						<div class="front_page_section_description front_page_section_woocommerce_description front_page_block_<?php echo ! empty( $modernee_description ) ? 'filled' : 'empty'; ?>">
						<?php
							echo wp_kses( wpautop( $modernee_description ), 'modernee_kses_content' );
						?>
						</div>
						<?php
					}
				}

				// Content (widgets)
				?>
				<div class="front_page_section_output front_page_section_woocommerce_output list_products shop_mode_thumbs">
					<?php
					if ( 'products' == $modernee_woocommerce_sc ) {
						$modernee_woocommerce_sc_ids      = modernee_get_theme_option( 'front_page_woocommerce_products_per_page' );
						$modernee_woocommerce_sc_per_page = count( explode( ',', $modernee_woocommerce_sc_ids ) );
					} else {
						$modernee_woocommerce_sc_per_page = max( 1, (int) modernee_get_theme_option( 'front_page_woocommerce_products_per_page' ) );
					}
					$modernee_woocommerce_sc_columns = max( 1, min( $modernee_woocommerce_sc_per_page, (int) modernee_get_theme_option( 'front_page_woocommerce_products_columns' ) ) );
					echo do_shortcode(
						"[{$modernee_woocommerce_sc}"
										. ( 'products' == $modernee_woocommerce_sc
												? ' ids="' . esc_attr( $modernee_woocommerce_sc_ids ) . '"'
												: '' )
										. ( 'product_category' == $modernee_woocommerce_sc
												? ' category="' . esc_attr( modernee_get_theme_option( 'front_page_woocommerce_products_categories' ) ) . '"'
												: '' )
										. ( 'best_selling_products' != $modernee_woocommerce_sc
												? ' orderby="' . esc_attr( modernee_get_theme_option( 'front_page_woocommerce_products_orderby' ) ) . '"'
													. ' order="' . esc_attr( modernee_get_theme_option( 'front_page_woocommerce_products_order' ) ) . '"'
												: '' )
										. ' per_page="' . esc_attr( $modernee_woocommerce_sc_per_page ) . '"'
										. ' columns="' . esc_attr( $modernee_woocommerce_sc_columns ) . '"'
						. ']'
					);
					?>
				</div>
			</div>
		</div>
	</div>
	<?php
}
