<?php
/**
 * Site Header Menu Functions.
 *
 * @package TotalTheme
 * @subpackage Functions
 * @version 5.3
 */

defined( 'ABSPATH' ) || exit;

/*-------------------------------------------------------------------------------*/
/* [ Table of contents ]
/*-------------------------------------------------------------------------------*

	# General
	# Search
	# Dropdowns
	# Drop Widgets (cart/search)
	# Mobile

/*-------------------------------------------------------------------------------*/
/* [ General ]
/*-------------------------------------------------------------------------------*/

/**
 * Check if header has a menu.
 *
 * @since 4.0
 */
function wpex_has_header_menu() {

	// Return false if header is disabled or set to custom header.
	if ( ! wpex_has_header() || wpex_has_custom_header() ) {
		return false;
	}

	// Check meta first.
	$meta_check = get_post_meta( wpex_get_current_post_id(), 'wpex_header_menu', true );
	if ( $meta_check ) {
		return wpex_validate_boolean( $meta_check );
	}

	$check = false;

	// Menu Location.
	$menu_location = wpex_header_menu_location();

	// Custom menu.
	$custom_menu = wpex_custom_menu();

	// Multisite global menu.
	$ms_global_menu = (bool) apply_filters( 'wpex_ms_global_menu', false );

	// We have a menu defined so set check to true.
	if ( has_nav_menu( $menu_location ) || $custom_menu || $ms_global_menu ) {
		$check = true;
	}

	// Return check.
	return apply_filters( 'wpex_has_header_menu', $check );

}

/**
 * Returns header menu location.
 *
 * @since 4.0
 */
function wpex_header_menu_location() {
	return apply_filters( 'wpex_main_menu_location', 'main_menu' );
}

/**
 * Return true if the header menu is sticky.
 *
 * @since 4.5.2
 */
function wpex_has_sticky_header_menu() {
	$check = false;
	if ( in_array( wpex_header_style(), array( 'two', 'three', 'four' ) ) ) {
		$check = get_theme_mod( 'fixed_header_menu', true );
	}
	return (bool) apply_filters( 'wpex_has_sticky_header_menu', $check );
}

/**
 * Returns menu classes.
 *
 * @since 2.0.0
 */
function wpex_header_menu_classes( $return = '' ) {

	$classes      = array();
	$header_style = wpex_header_style();
	$has_overlay  = wpex_has_overlay_header();

	// Return wrapper classes.
	if ( 'wrapper' === $return ) {

		// Add Header Style to wrapper.
		$classes[] = 'navbar-style-' . sanitize_html_class( $header_style );

		// Add classes for the sticky header menu.
		if ( wpex_has_sticky_header_menu() ) {
			$classes[] = 'fixed-nav';
		}

		// Style specific classes.
		if ( 'dev' !== $header_style && ! wpex_is_header_menu_custom() ) {

			// Active underline.
			if ( get_theme_mod( 'menu_active_underline', false )
				&& ! in_array( $header_style, array( 'six' ) )
			) {
				$classes[] = 'has-menu-underline';
			}

			// Dropdown caret.
			if ( 'one' === $header_style || 'five' === $header_style || $has_overlay ) {
				$classes[] = 'wpex-dropdowns-caret';
			}

			// Flush Dropdowns.
			if ( 'one' === $header_style && get_theme_mod( 'menu_flush_dropdowns' ) ) {
				$classes[] = 'wpex-flush-dropdowns';
			}

			// Add special class if the dropdown top border option in the admin is enabled.
			if ( get_theme_mod( 'menu_dropdown_top_border' ) ) {
				$classes[] = 'wpex-dropdown-top-border';
			}

			// Disable header menu borders.
			if ( in_array( $header_style, array( 'two', 'six' ) )
				&& get_theme_mod( 'header_menu_disable_borders', false )
			) {
				$classes[] = 'no-borders';
			}

			// Center items.
			if ( 'two' === $header_style && get_theme_mod( 'header_menu_center', false ) ) {
				$classes[] = 'center-items';
			}

			// Stretch items.
			if ( get_theme_mod( 'header_menu_stretch_items', false )
				&& ( 'two' === $header_style || 'three' === $header_style || 'four' === $header_style || 'five' === $header_style )
			) {
				$classes[] = 'wpex-stretch-items';
			}

		}

		// Stretch megamenus.
		if ( 'one' === $header_style && get_theme_mod( 'megamenu_stretch', true ) ) {
			$classes[] = 'wpex-stretch-megamenus';
		}

		// Add breakpoint class.
		if ( wpex_has_header_mobile_menu() ) {
			$classes[] = 'hide-at-mm-breakpoint';
		}

		// Add clearfix.
		$classes[] = 'wpex-clr';

		// Sanitize.
		$classes = array_map( 'esc_attr', $classes );

		// Set keys equal to vals.
		$classes = array_combine( $classes, $classes );

		// Apply filters.
		$classes = (array) apply_filters( 'wpex_header_menu_wrap_classes', $classes );

	}

	// Inner Classes.
	elseif ( 'inner' === $return ) {

		// Core.
		$classes[] = 'navigation';
		$classes[] = 'main-navigation';
		$classes[] = 'main-navigation-' . sanitize_html_class( $header_style );
		$classes[] = 'wpex-clr';

		// Add the container class for specific header styles to center the menu items.
		if ( 'two' === $header_style || 'three' === $header_style || 'four' === $header_style ) {
			$classes[] = 'container';
		}

		// Sanitize.
		$classes = array_map( 'esc_attr', $classes );

		// Set keys equal to vals.
		$classes = array_combine( $classes, $classes );

		// Apply filters.
		$classes = (array) apply_filters( 'wpex_header_menu_classes', $classes );

	}

	// Return classes as string.
	if ( is_array( $classes ) ) {
		return implode( ' ', $classes );
	}

}

/**
 * Returns menu classes.
 *
 * @since 4.6
 */
function wpex_header_menu_ul_classes() {
	$classes = array();

	if ( 'dev' !== wpex_header_style() ) {
		$classes['dropdown-menu'] = 'dropdown-menu';
	}

	$classes['main-navigation-ul'] = 'main-navigation-ul';

	$dropdown_method = wpex_header_menu_dropdown_method();

	switch( $dropdown_method ) {
		case 'sfhover':
			$classes['sf-menu'] = 'sf-menu';
			break;
		case 'click':
			$classes[] = 'wpex-dropdown-menu';
			$classes[] = 'wpex-dropdown-menu--onclick';
			break;
		case 'hover':
		default;
			$classes[] = 'wpex-dropdown-menu';
			$classes[] = 'wpex-dropdown-menu--onhover';
			break;
	}

	if ( get_theme_mod( 'menu_drodown_animate', false ) && ( 'click' === $dropdown_method || 'hover' === $dropdown_method ) ) {
		$classes[] = 'wpex-dropdown-menu--animate';
	}

	/**
	 * Filters the header menu ul classes.
	 *
	 * @param array $classes
	 */
	$classes = (array) apply_filters( 'wpex_header_menu_ul_classes', $classes );

	$classes = array_map( 'esc_attr', $classes );

	return implode( ' ', $classes );
}

/**
 * Custom menu walker.
 *
 * @since 1.3.0
 */
if ( ! class_exists( 'WPEX_Dropdown_Walker_Nav_Menu' ) ) {
	class WPEX_Dropdown_Walker_Nav_Menu extends Walker_Nav_Menu {
		function display_element( $element, &$children_elements, $max_depth, $depth, $args, &$output ) {

			// Define vars.
			$id_field     = $this->db_fields['id'];
			$header_style = wpex_header_style();

			// Down Arrows.
			if ( ! empty( $children_elements[$element->$id_field] ) && ( $depth == 0 ) ) {

				$element->classes[] = 'dropdown';

				if ( get_theme_mod( 'menu_arrow_down' ) ) {

					$arrow_type = ( $arrow_type = get_theme_mod( 'menu_arrow' ) ) ? $arrow_type : 'angle';

					if ( 'plus' === $arrow_type ) {
						$arrow_class = 'ticon ticon-plus';
					} else {
						$arrow_dir = 'six' === $header_style ? 'right' : 'down';
						$arrow_class = 'ticon ticon-' . sanitize_html_class( $arrow_type ) . '-' . sanitize_html_class( $arrow_dir );
					}

					$element->title .= ' <span class="nav-arrow top-level ' . $arrow_class . '" aria-hidden="true"></span>';

				}

			}

			// Right/Left Arrows.
			if ( ! empty( $children_elements[$element->$id_field] ) && ( $depth > 0 ) ) {

				$element->classes[] = 'dropdown';

				if ( get_theme_mod( 'menu_arrow_side', true ) ) {

					$arrow_type = ( $arrow_type = get_theme_mod( 'menu_arrow' ) ) ? $arrow_type : 'angle';

					if ( 'plus' === $arrow_type ) {
						$arrow_class = 'ticon ticon-plus';
					} else {
						$arrow_dir = is_rtl() ? 'left' : 'right';
						$arrow_class = 'ticon ticon-' . sanitize_html_class( $arrow_type ) . '-' . sanitize_html_class( $arrow_dir );
					}

					if ( is_rtl() ) {
						$element->title .= '<span class="nav-arrow second-level ' . $arrow_class . '" aria-hidden="true"></span>';
					} else {
						$element->title .= '<span class="nav-arrow second-level ' . $arrow_class . '" aria-hidden="true"></span>';
					}

				}

			}

			// Remove current menu item when using local-scroll class
			if ( is_array( $element->classes )
				&& in_array( 'local-scroll', $element->classes )
				&& in_array( 'current-menu-item', $element->classes )
			) {
				$key = array_search( 'current-menu-item', $element->classes );
				unset( $element->classes[$key] );
			}

			// Define walker
			Walker_Nav_Menu::display_element( $element, $children_elements, $max_depth, $depth, $args, $output );

		}
	}
}

/**
 * Checks for custom menus.
 *
 * @since 1.3.0
 */
function wpex_custom_menu() {
	$menu = get_post_meta( wpex_get_current_post_id(), 'wpex_custom_menu', true );
	if ( 'default' === $menu ) {
		$menu = '';
	}
	return apply_filters( 'wpex_custom_menu', $menu ); // Accepts a menu ID, slug, name, or object.
}

/*-------------------------------------------------------------------------------*/
/* [ # Search ]
/*-------------------------------------------------------------------------------*/

/**
 * Check if current header menu style supports search icon.
 *
 * @since 4.0
 */
function wpex_header_menu_supports_search() {
	if ( wpex_has_header() ) { // @todo is this extra check needed?
		return (bool) apply_filters( 'wpex_has_menu_search', true );
	}
}

/**
 * Returns header menu search style.
 *
 * @since 4.0
 */
function wpex_header_menu_search_style() {

	// Return if not allowed.
	if ( ! wpex_header_menu_supports_search() ) {
		return false;
	}

	// Get style as set in the customizer.
	$style = get_theme_mod( 'menu_search_style', 'drop_down' );

	// Don't allow header replace on overlay header
	if ( 'header_replace' === $style && wpex_has_overlay_header() ) {
		$style = 'overlay';
	}

	// Get style and apply filters.
	$style = apply_filters( 'wpex_menu_search_style', $style );

	// Sanitize output so it's not empty and return.
	$style = $style ? $style : 'drop_down';

	// Return style
	return $style;

}

/**
 * Returns header menu search form.
 *
 * @since 4.5.2
 */
function wpex_get_header_menu_search_form() {
	if ( WPEX_WOOCOMMERCE_ACTIVE && get_theme_mod( 'woo_header_product_searchform', false ) ) {
		$form = get_product_search_form( false );
	} else {
		$form = get_search_form( false );
	}
	return apply_filters( 'wpex_get_header_menu_search_form', $form );
}

/**
 * Returns header menu search form placeholder.
 *
 * @since 4.5.2
 */
function wpex_get_header_menu_search_form_placeholder() {
	$style = wpex_header_menu_search_style();
	$placeholder = esc_html__( 'Search', 'total' );
	if ( 'overlay' === $style || 'header_replace' === $style ) {
		$placeholder = esc_html__( 'Type then hit enter to search&hellip;', 'total' );
	}
	return apply_filters( 'wpex_get_header_menu_search_form_placeholder', $placeholder );
}

/**
 * Adds the search icon to the menu items.
 *
 * @since 1.0.0
 */
function wpex_add_search_to_menu( $items, $args ) {

	$header_style = wpex_header_style();

	if ( 'seven' === $header_style ) {
		return $items;
	}

	$aria_controls = '';
	$search_icon_theme_locations = apply_filters( 'wpex_menu_search_icon_theme_locations', array( 'main_menu' ) );

	// Check menu location.
	if ( ! in_array( $args->theme_location, $search_icon_theme_locations ) ) {
		return $items;
	}

	// Get search style.
	$search_style = wpex_header_menu_search_style();

	// Return if disabled
	if ( ! $search_style || 'disabled' === $search_style ) {
		return $items;
	}

	// Define classes.
	$li_classes = 'search-toggle-li menu-item wpex-menu-extra';
	$a_classes  = 'site-search-toggle';

	// Get header style.
	$header_style = wpex_header_style();

	// Define vars based on search style.
	switch ( $search_style ) {
		case 'overlay':
			$a_classes .= ' search-overlay-toggle';
			$aria_controls = 'wpex-searchform-overlay';
			break;
		case 'drop_down':
			$a_classes .= ' search-dropdown-toggle';
			$aria_controls = 'searchform-dropdown';
			break;
		case 'header_replace':
			$a_classes .= ' search-header-replace-toggle';
			$aria_controls = 'searchform-header-replace';
			break;
	}

	// Ubermenu integration.
	if ( class_exists( 'UberMenu' ) && apply_filters( 'wpex_add_search_toggle_ubermenu_classes', true ) ) {
		$li_classes .= ' ubermenu-item-level-0 ubermenu-item';
		$a_classes  .= ' ubermenu-target ubermenu-item-layout-default ubermenu-item-layout-text_only';
	}

	// Max Mega Menu integration.
	if ( function_exists( 'max_mega_menu_is_enabled' ) && max_mega_menu_is_enabled( $args->theme_location ) ) {
		$li_classes .= ' mega-menu-item';
		$a_classes  .= ' mega-menu-link';
	}

	// Add search icon and dropdown style.
	$menu_search = '';
	$menu_search .= '<li class="' . esc_attr( $li_classes ) . '">';

		$a_attributes = array(
			'href'          => '#',
			'class'         => esc_attr( $a_classes ),
			'role'          => 'button',
			'aria-expanded' => 'false',
			'aria-controls' => $aria_controls,
		);

		$a_aria = wpex_get_aria_label( 'search' );
		if ( $a_aria ) {
			$a_attributes['aria-label'] = esc_attr( $a_aria );
		}

		$menu_search .= '<a ' . wpex_parse_attrs( $a_attributes ) . '>';

			$menu_search .= '<span class="link-inner">';

				$text = apply_filters( 'wpex_header_search_text', esc_html__( 'Search', 'total' ) );

				if ( 'six' === $header_style ) {
					$menu_search .= '<span class="wpex-menu-search-icon ticon ticon-search"></span>';
					$menu_search .= '<span class="wpex-menu-search-text">' . esc_html( $text ) . '</span>';
				} else {
					$menu_search .= '<span class="wpex-menu-search-text">' . esc_html( $text ) . '</span>';
					$menu_search .= '<span class="wpex-menu-search-icon ticon ticon-search" aria-hidden="true"></span>';
				}

			$menu_search .= '</span>';

		$menu_search .= '</a>';

		if ( 'drop_down' === $search_style && true === wpex_maybe_add_header_drop_widget_inline( 'search' ) ) {
			ob_start();
			wpex_get_template_part( 'header_search_dropdown' );
			$menu_search .= ob_get_clean();
		}

	$menu_search .= '</li>';

	// Check search item position.
	$menu_search_position = apply_filters( 'wpex_header_menu_search_position', 'end' );

	// Insert menu search into correct position.
	switch ( $menu_search_position ) {
		case 'start':
			$items = $menu_search . $items;
			break;
		case 'end':
		default;
			$items = $items . $menu_search;
			break;
	}

	// Return nav $items
	return $items;

}
add_filter( 'wp_nav_menu_items', 'wpex_add_search_to_menu', 11, 2 );

/*-------------------------------------------------------------------------------*/
/* [ Dropdowns ]
/*-------------------------------------------------------------------------------*/

/**
 * Get header Menu dropdown style.
 *
 * @since 5.0
 */
function wpex_header_menu_dropdown_method() {

	$method = get_theme_mod( 'menu_dropdown_method' );

	/**
	 * Filters the header menu dropdown method.
	 *
	 * @param string $method.
	 */
	$style = apply_filters( 'wpex_header_menu_dropdown_method', $method );

	if ( ! $method ) {
		$method = 'sfhover';
	}

	return $method;
}

/**
 * Get header Menu dropdown style.
 *
 * @since 5.0
 */
function wpex_get_header_menu_dropdown_style() {
	$style = get_theme_mod( 'menu_dropdown_style' );
	$post_id = wpex_get_current_post_id();
	if ( $post_id && wpex_has_overlay_header() && wpex_has_post_meta( 'wpex_overlay_header' ) ) {
		$meta = get_post_meta( $post_id, 'wpex_overlay_header_dropdown_style', true );
		if ( $meta ) {
			$style = $meta;
		}
	}

	/**
	 * Filters the header menu dropdown style.
	 *
	 * @param string $style.
	 */
	$style = apply_filters( 'wpex_header_menu_dropdown_style', $style );

	return $style;
}

/*-------------------------------------------------------------------------------*/
/* [ Drop Widgets (cart/search) ]
/*-------------------------------------------------------------------------------*/

/**
 * Get header Menu dropdown widget class.
 *
 * @since 5.0
 */
function wpex_get_header_drop_widget_class() {

	$has_border = true;

	$class = array(
		'header-drop-widget',
		'wpex-invisible',
		'wpex-opacity-0',
		'wpex-absolute',
		'wpex-shadow',
		'wpex-transition-all',
		'wpex-duration-200',
		'wpex-translate-Z-0',
		'wpex-text-initial',
		'wpex-z-10000',
	);

	if ( wpex_has_vertical_header() ) {
		$class[] = 'wpex-top-0';
		$class[] = 'wpex-left-100';
	} else {
		$class[] = 'wpex-top-100';
		$class[] = 'wpex-right-0';
	}

	$dropdown_style = wpex_get_header_menu_dropdown_style();

	switch ( $dropdown_style ) {
		case 'black':
			$class[] = 'wpex-bg-black';
			$class[] = 'wpex-text-gray-600';
			$has_border = get_theme_mod( 'menu_dropdown_top_border' ) ? true : false;
			break;
		default:
			$class[] = 'wpex-bg-white';
			$class[] = 'wpex-text-gray-600';
			break;
	}

	if ( $has_border ) {
		$class[] = 'wpex-border-accent';
		$class[] = 'wpex-border-solid';
		$class[] = 'wpex-border-t-3';
	}

	return (array) apply_filters( 'wpex_get_header_drop_widget_class', $class );

}

/**
 * Header Menu dropdown widget class.
 *
 * @since 5.0
 */
function wpex_header_drop_widget_class() {

	$class = wpex_get_header_drop_widget_class();

	if ( $class ) {
		echo 'class="' . esc_attr( implode( ' ', $class ) ) . '"';
	}

}

/**
 * Header Menu search dropdown widget class.
 *
 * @since 5.0
 */
function wpex_header_drop_widget_search_class() {

	$class = array(
		'header-searchform-wrap',
	);

	$widget_class = wpex_get_header_drop_widget_class();

	if ( $widget_class ) {
		$class = array_merge( $class, $widget_class );
	}

	$class[] = 'wpex-p-15';

	if ( $class ) {
		echo 'class="' . esc_attr( implode( ' ', $class ) ) . '"';
	}

}

/**
 * Check if header menu dropdown widgets should be added inline (insite the li element).
 *
 * @since 5.1
 */
function wpex_maybe_add_header_drop_widget_inline( $widget = '' ) {
	$check = true;
	$header_style = wpex_header_style();
	if ( 'one' === $header_style ) {
		$check = false; // all widgets in header 1 are added at the bottom of the header so they are "flush".
	}
	if ( function_exists( 'max_mega_menu_is_enabled' ) && max_mega_menu_is_enabled( 'main_menu' ) ) {
		$check = true;
	}
	return (bool) apply_filters( 'wpex_maybe_add_header_drop_widget_inline', $check, $widget );
}

/*-------------------------------------------------------------------------------*/
/* [ Mobile ]
/*-------------------------------------------------------------------------------*/

/**
 * Return header menu mobile style.
 *
 * @since 4.0
 */
function wpex_header_menu_mobile_style() {

	$style = wpex_is_layout_responsive() ? get_theme_mod( 'mobile_menu_style' ) : 'disabled';

	if ( empty( $style ) ) {
		$style = 'sidr';
	}

	if ( wpex_is_header_menu_custom() ) {
		$style = 'disabled';
	}

	$style = apply_filters( 'wpex_mobile_menu_style', $style ); // @todo deprecate.

	return apply_filters( 'wpex_header_menu_mobile_style', $style );

}

/**
 * Check if mobile menu is enabled.
 *
 * @since 4.0
 * @todo rename to wpex_has_header_menu_mobile()
 */
function wpex_has_header_mobile_menu() {

	if ( wpex_has_custom_header() ) {
		return false; // always false for custom header.
	}

	$check = false;

	if ( ( wpex_has_mobile_menu_alt() || wpex_has_header_menu() )
		&& wpex_is_layout_responsive()
		&& 'disabled' !== wpex_header_menu_mobile_style()
	) {
		$check = true;
	}

	$check = apply_filters( 'wpex_has_mobile_menu', $check ); // @todo deprecate

	return (bool) apply_filters( 'wpex_has_header_menu_mobile', $check );

}

/**
 * Conditional check for alternative menu.
 *
 * @since 4.0
 * @todo rename to wpex_has_header_menu_mobile_alt
 */
function wpex_has_mobile_menu_alt() {
	return (bool) apply_filters( 'wpex_has_mobile_menu_alt', has_nav_menu( 'mobile_menu_alt' ) );
}

/**
 * Return header mobile menu classes.
 *
 * @since 4.0
 * @todo rename to wpex_mobile_menu_toggle_classes and deprecate
 */
function wpex_header_mobile_menu_classes() {

	$classes = array(
		'wpex-mobile-menu-toggle',
		'show-at-mm-breakpoint',
	);

	$style = wpex_header_menu_mobile_toggle_style();

	switch ( $style ) {
		case 'icon_buttons':
			$classes[] = 'wpex-absolute wpex-top-50 wpex-right-0';
			break;
		case 'icon_buttons_under_logo':
			$classes[] = 'wpex-mt-10';
			$classes[] = 'wpex-text-center'; // check with toggle styles and such.
			break;
		case 'navbar':
			$classes[] = 'wpex-bg-gray-A900';
			break;
		case 'fixed_top':
			$classes[] = 'wpex-fixed';
			$classes[] = 'wpex-top-0';
			$classes[] = 'wpex-inset-x-0';
			$classes[] = 'wpex-bg-gray-A900';
			break;
	}

	$classes = apply_filters( 'wpex_mobile_menu_toggle_class', $classes, $style );

	return esc_attr( implode( ' ', $classes ) );

}

/**
 * Returns classes for the header menu mobile toggle.
 *
 * @since 5.0
 */
function wpex_mobile_menu_toggle_class() {

	$classes = wpex_header_mobile_menu_classes(); // @todo rename

	if ( $classes ) {
		echo 'class="' . esc_attr( $classes ) . '"';
	}

}

/**
 * Return correct mobile menu toggle style for the header.
 *
 * @since 4.0
 */
function wpex_header_menu_mobile_toggle_style() {

	if ( 'disabled' === wpex_header_menu_mobile_style() ) {
		return false;
	}

	$toggle_style = get_theme_mod( 'mobile_menu_toggle_style' );

	if ( ! $toggle_style ) {
		$toggle_style = 'icon_buttons';
	}

	return apply_filters( 'wpex_mobile_menu_toggle_style', $toggle_style );

}

/**
 * Return correct header menu mobile toggle style.
 *
 * @since 4.0
 */
function wpex_header_menu_mobile_breakpoint() {
	$breakpoint = get_theme_mod( 'mobile_menu_breakpoint' );
	$breakpoint = apply_filters( 'wpex_header_menu_mobile_breakpoint', $breakpoint );
	$breakpoint = absint( $breakpoint );
	if ( empty( $breakpoint ) ) {
		$breakpoint = 959; // can't be empty since Total 5.0
	}
	return absint( $breakpoint );
}

/**
 * Return sidr menu source.
 *
 * @since 4.0
 * @todo rename to something better maybe wpex_header_menu_mobile_sidr_source or remove sidr completely.
 */
function wpex_sidr_menu_source( $deprecated = '' ) {

	// Define array of items.
	$items = array();

	// Add mobile menu alternative if defined.
	if ( wpex_has_mobile_menu_alt() ) {
		$items['nav'] = '#mobile-menu-alternative';
	}

	// If mobile menu alternative is not defined add main navigation.
	else {
		$items['nav'] = '#site-navigation';
	}

	// Add search form.
	if ( get_theme_mod( 'mobile_menu_search', true ) ) {
		$items['search'] = '#mobile-menu-search';
	}

	/**
	 * Filters the mobile menu sidr source.
	 *
	 * @since 4.0
	 *
	 * @param array $items The array of elements (ids) to include in the sidebar mobile menu.
	 */
	$items = (array) apply_filters( 'wpex_mobile_menu_source', $items );

	// Turn items into comma seperated list and return.
	return implode( ', ', $items );

}

/**
 * Return mobile toggle icon html.
 *
 * @since 4.9
 */
function wpex_get_mobile_menu_toggle_icon() {

	$html = '<a href="#" class="mobile-menu-toggle" role="button" aria-label="' . esc_attr( wpex_get_aria_label( 'mobile_menu_toggle' ) ) .'" aria-expanded="false">';

		$html .= apply_filters( 'wpex_mobile_menu_open_button_text', '<span class="wpex-bars" aria-hidden="true"><span></span></span>' );

	$html .= '</a>';

	$html = apply_filters( 'wpex_get_mobile_menu_toggle_icon', $html ); // old

	/**
	 * Filters the mobile menu toggle icon html.
	 *
	 * @since 5.0.7
	 *
	 * @param string $html The post thumbnail HTML.
	 */
	$html = apply_filters( 'wpex_mobile_menu_toggle_icon', $html );

	return $html;

}

/**
 * Return mobile menu extra icons.
 *
 * @since 5.0
 * @todo rename menu area to mobile_toggle_icons.
 */
function wpex_mobile_menu_toggle_extra_icons() {

	$icons_escaped = '';

	if ( ( $locations = get_nav_menu_locations() ) && isset( $locations[ 'mobile_menu' ] ) ) {

		$menu = wp_get_nav_menu_object( $locations[ 'mobile_menu' ] );

		if ( ! empty( $menu ) ) {

			$menu_items = wp_get_nav_menu_items( $menu->term_id );

			if ( $menu_items ) {

				$ticons = wpex_ticons_list();
				$toggle_style = wpex_header_menu_mobile_toggle_style();

				foreach ( $menu_items as $key => $menu_item ) {

					// Only add items if a correct font icon is added for the menu item label.
					if ( in_array( $menu_item->title, $ticons ) ) {

						$title = $menu_item->title;

						// Don't allow search icon on fixed mobile menu style.
						if ( 'fixed_top' === $toggle_style && 'search' === $title ) {
							continue;
						}

						$reader_text = $title;
						$attr_title  = $menu_item->attr_title;
						$desc        = '';
						$link_icon   = wpex_get_theme_icon_html( $title );

						$a_class = array(
							'mobile-menu-extra-icons',
							'mobile-menu-' . sanitize_html_class( $title ),
							'wpex-inline-block',
							'wpex-no-underline',
						);

						if ( 'icon_buttons' === $toggle_style
							|| 'icon_buttons_under_logo' === $toggle_style
						) {
							$a_class[] = 'wpex-mr-20';
						} else {
							$a_class[] = 'wpex-ml-10';
						}

						if ( function_exists( 'wpex_mobile_menu_cart_count' )
							&& ( 'icon_buttons' === $toggle_style || 'icon_buttons_under_logo' === $toggle_style )
							&& ( 'shopping-cart' === $title || 'shopping-bag' === $title || 'shopping-basket' === $title )
						) {

							$link_icon = '<span class="wpex-relative wpex-inline-block">' . $link_icon . wpex_mobile_menu_cart_count() . '</span>';

						}

						if ( ! empty( $menu_item->classes ) && is_array( $menu_item->classes ) ) {
							$a_class = array_merge( $a_class, $menu_item->classes );
						}

						$link_attrs = array(
							'href'  => esc_url( $menu_item->url ),
							'class' => implode( ' ', array_map( 'esc_attr', $a_class ) ),
						);

						if ( '#search' === esc_url( $menu_item->url ) ) {
							$link_attrs['role'] = 'button';
							$link_attrs['aria-expanded'] = 'false';
						}

						if ( ! empty( $menu_item->description ) ) {
							$desc = '<span class="wpex-icon-label wpex-ml-5">' . esc_html( $menu_item->description ) . '</span>';
							$reader_text = '';
						} else {
							if ( ! empty( $attr_title ) ) {
								$link_attrs['title'] = esc_attr( $attr_title );
								$reader_text = $attr_title;
							}
							if ( $reader_text ) {
								$reader_text = '<span class="screen-reader-text">' . esc_html( $reader_text ) . '</span>';
							}
						}

						$inner_html = $link_icon . $reader_text . $desc;

						$icons_escaped .= wpex_parse_html( 'a', $link_attrs, $inner_html ); ?>

				<?php }

				} // End foreach.

			}

		}

	} // End menu check.

	$icons_escaped = apply_filters( 'wpex_get_mobile_menu_extra_icons', $icons_escaped ); // @todo deprecate legacy filter

	$icons_escaped = apply_filters( 'wpex_header_menu_mobile_toggle_icons', $icons_escaped );

	if ( $icons_escaped ) {

		echo '<div class="wpex-mobile-menu-toggle-extra-icons">' . $icons_escaped . '</div>';

	}

}