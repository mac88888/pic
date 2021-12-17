<?php
defined( 'ABSPATH' ) || exit;

/**
 * Returns array of data for the global js wpex_theme_params object.
 *
 * @package TotalTheme
 * @subpackage Functions
 *
 * @version 5.3
 */
function wpex_js_localize_data() {
	$header_style = wpex_header_style();
	$mm_style = wpex_header_menu_mobile_style();
	$mm_toggle_style = wpex_header_menu_mobile_toggle_style();
	$mm_breakpoint = wpex_header_menu_mobile_breakpoint(); // @todo should we add +1px to this value?
	$has_custom_header = wpex_has_custom_header();

	$array = array(
		'menuWidgetAccordion'       => true,
		'mobileMenuBreakpoint'      => wp_strip_all_tags( $mm_breakpoint ),
		'mobileMenuStyle'           => wp_strip_all_tags( $mm_style ),
		'mobileMenuToggleStyle'     => wp_strip_all_tags( $mm_toggle_style ),
		'mobileMenuAriaLabel'       => esc_attr( wpex_get_aria_label( 'mobile_menu' ) ),
		'mobileMenuCloseAriaLabel'  => esc_attr( wpex_get_aria_label( 'mobile_menu_close' ) ),

		// Translatable strings.
		'i18n' => array(
			'openSubmenu'  => esc_html__( 'Open submenu of %s', 'total' ),
			'closeSubmenu' => esc_html__( 'Close submenu of %s', 'total' ),
		),

	);

	/**** Header params ****/
	if ( 'disabled' !== $header_style ) {

		// Sticky Header.
		if ( wpex_has_sticky_header() ) {

			$array['stickyHeaderStyle'] = wp_strip_all_tags( wpex_sticky_header_style() );

			if ( $has_custom_header ) {
				$array['hasStickyMobileHeader'] = true;
			} else {
				$array['hasStickyMobileHeader'] = wp_validate_boolean( get_theme_mod( 'fixed_header_mobile' ) );
			}

			// Sticky header breakpoint.
			if ( absint( $mm_breakpoint ) >= 9999 ) {
				$array['stickyHeaderBreakPoint'] = 960;
			} else {
				$array['stickyHeaderBreakPoint'] = $mm_breakpoint + 1;
			}

			// Sticky header start position.
			$fixed_startp = wpex_sticky_header_start_position();
			if ( $fixed_startp ) {
				$fixed_startp = str_replace( 'px', '', $fixed_startp );
				$array['stickyHeaderStartPosition'] = wp_strip_all_tags( $fixed_startp ); // can be int or element class/id
			}

			// Make sure sticky is always enabled if responsive is disabled.
			if ( ! wpex_is_layout_responsive() ) {
				$array['hasStickyMobileHeader'] = true;
			}

			// Shrink sticky header > used for local-scroll offset.
			if ( wpex_has_shrink_sticky_header() ) {
				$array['hasStickyHeaderShrink'] = true;
				$array['hasStickyMobileHeaderShrink'] = wpex_has_shrink_sticky_header_mobile();
				$height_escaped = intval( get_theme_mod( 'fixed_header_shrink_end_height' ) );
				$height_escaped = $height_escaped ? $height_escaped + 20 : 70;
				$array['shrinkHeaderHeight'] = $height_escaped;
			}

		}

		// Sticky Navbar.
		if ( wpex_has_sticky_header_menu() ) {
			$array['stickyNavbarBreakPoint'] = 960;
		}

		// Header five.
		if ( 'five' === $header_style ) {
			$array['headerFiveSplitOffset'] = 1;
		}

	} // End header params.

	// Toggle mobile menu position.
	$navbar_position = get_theme_mod( 'mobile_menu_navbar_position' );
	if ( 'toggle' === $mm_style ) {
		$array['animateMobileToggle'] = get_theme_mod( 'mobile_menu_toggle_animate', true );
		if ( get_theme_mod( 'fixed_header_mobile', false ) ) {
			$mobileToggleMenuPosition = 'absolute'; // Must be absolute for sticky header when inside it.
		} elseif ( 'fixedTopNav' !== $mm_toggle_style && wpex_has_overlay_header() ) {
			if ( 'navbar' === $mm_toggle_style ) {
				$mobileToggleMenuPosition = 'afterself';
			} else {
				$mobileToggleMenuPosition = 'absolute';
			}
		} elseif ( 'outer_wrap_before' === $navbar_position && 'navbar' === $mm_toggle_style ) {
			$mobileToggleMenuPosition = 'afterself';
		} else {
			$mobileToggleMenuPosition = 'afterheader';
		}

		// Fix for navbar position.
		if ( 'navbar' == $mm_toggle_style
			&& 'absolute' === $mobileToggleMenuPosition
			&& 'outer_wrap_before' === $navbar_position ) {
			$mobileToggleMenuPosition = 'afterself';
		}

		// Update array.
		$array['mobileToggleMenuPosition'] = $mobileToggleMenuPosition;
	}

	// Sidr settings.
	if ( 'sidr' === $mm_style ) {
		$sidr_side = get_theme_mod( 'mobile_menu_sidr_direction' );
		$sidr_side = $sidr_side ? $sidr_side : 'right'; // Fallback is crucial
		$array['sidrSource']       = wpex_sidr_menu_source();
		$array['sidrDisplace']     = wp_validate_boolean( get_theme_mod( 'mobile_menu_sidr_displace', false ) );
		$array['sidrSide']         = wp_strip_all_tags( $sidr_side );
		$array['sidrBodyNoScroll'] = false;
		$array['sidrSpeed']        = 300;
	}

	// Mobile menu toggles style.
	if ( ( 'toggle' === $mm_style || 'sidr' === $mm_style ) && get_theme_mod( 'mobile_menu_dropdowns_arrow_toggle', false ) ) {
		$array['mobileMenuDropdownsArrowToggle'] = true;
	}

	// Sticky topBar.
	if ( ! wpex_vc_is_inline()
		&& apply_filters( 'wpex_has_sticky_topbar', get_theme_mod( 'top_bar_sticky' ) )
	) {
		$array['stickyTopBarBreakPoint'] = 960;
		$array['hasStickyTopBarMobile']  = wp_validate_boolean( get_theme_mod( 'top_bar_sticky_mobile', true ) );
	}

	// Full screen mobile menu style.
	if ( 'full_screen' === $mm_style ) {
		$array['fullScreenMobileMenuStyle'] = wp_strip_all_tags( get_theme_mod( 'full_screen_mobile_menu_style', 'white' ) );
	}

	// Auto lightbox. @todo convert filter to an array.
	if ( get_theme_mod( 'lightbox_auto', false ) ) {
		$auto_lightbox_targets = '.wpb_text_column a:has(img), body.no-composer .entry a:has(img)';
		$array['autoLightbox'] = apply_filters( 'wpex_auto_lightbox_targets', $auto_lightbox_targets );
	}

	// Custom selects.
	if ( apply_filters( 'wpex_custom_selects_js', true ) ) {
		$array['customSelects'] = '.widget_categories form,.widget_archive select,.vcex-form-shortcode select';
		if ( WPEX_WOOCOMMERCE_ACTIVE && wpex_has_woo_mods() ) {
			$array['customSelects'] .= ',.woocommerce-ordering .orderby,#dropdown_product_cat,.single-product .variations_form .variations select';
		}
	}

	/**** Local Scroll args ****/
	$array['scrollToHash']          = wpex_has_local_scroll_on_load();
	$array['scrollToHashTimeout']   = wpex_get_local_scroll_on_load_timeout();
	$array['localScrollTargets']    = wpex_get_local_scroll_targets();
	$array['localScrollUpdateHash'] = wpex_has_local_scroll_hash_update();
	$array['localScrollHighlight']  = wpex_has_local_scroll_menu_highlight();
	$array['localScrollSpeed']      = wpex_get_local_scroll_speed();

	if ( $local_scroll_easing = wpex_get_local_scroll_easing() ) {
		$array['localScrollEasing'] = esc_attr( $local_scroll_easing );
	}

	// Apply filters.
	// @todo rename to wpex_theme_js_params or wpex_theme_js_l10n
	$array = apply_filters( 'wpex_localize_array', $array );

	// Return array.
	return $array;

}