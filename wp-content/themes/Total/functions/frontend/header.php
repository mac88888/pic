<?php
/**
 * Site Header Helper Functions.
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
	# Logo
	# Overlay/Transparent Header
	# Sticky
	# Header Aside

/*-------------------------------------------------------------------------------*/
/* [ General ]
/*-------------------------------------------------------------------------------*/

/**
 * Check if site header is enabled.
 *
 * @since 4.0
 */
function wpex_has_header( $post_id = '' ) {

	// Check if enabled by default
	if ( wpex_has_custom_header() || wpex_elementor_location_exists( 'header' ) ) {
		$check = true;
	} else {
		$check = get_theme_mod( 'enable_header', true );
	}

	// Get current post ID
	$post_id = $post_id ? $post_id : wpex_get_current_post_id();

	// Check meta
	if ( $post_id && $meta = get_post_meta( $post_id, 'wpex_disable_header', true ) ) {
		if ( 'on' === $meta ) {
			$check = false;
		} elseif ( 'enable' === $meta ) {
			$check = true;
		}
	}

	// Apply filters and bool value
	return (bool) apply_filters( 'wpex_display_header', $check ); // @todo rename to wpex_has_header for consistency

}

/**
 * Get header style.
 *
 * @since 4.0
 */
function wpex_header_style( $post_id = '' ) {

	// Return if header is disabled
	if ( ! wpex_has_header() ) {
		return 'disabled';
	}

	// Check if builder is enabled
	if ( wpex_header_builder_id() ) {
		return 'builder';
	}

	// Get header style from customizer setting
	$style = get_theme_mod( 'header_style', 'one' );

	// Overlay Header supported styles
	$excluded_overlay_header_styles = apply_filters( 'wpex_overlay_header_excluded_header_styles', array( 'seven' ) );

	// Overlay header supports certain header styles only
	if ( in_array( $style, $excluded_overlay_header_styles ) && wpex_has_overlay_header() ) {
		$style = 'one';
	}

	// Get current post ID
	if ( ! $post_id ) {
		$post_id = wpex_get_current_post_id();
	}

	// Check for custom header style defined in meta options => Overrides all
	if ( 'dev' !== $style
		&& $post_id
		&& $meta = get_post_meta( $post_id, 'wpex_header_style', true ) ) {
		$style = $meta;
	}

	// Set default style if style is empty.
	if ( ! $style ) {
		$style = 'one';
	}

	// Apply filters and return
	return apply_filters( 'wpex_header_style', $style );

}

/**
 * Check if the header style is in dev mode.
 *
 * @since 4.9.4
 */
function wpex_has_dev_style_header() {
	return ( 'dev' === wpex_header_style() ) ? true : false;
}

/**
 * Check if the header style is not in dev mode.
 *
 * @since 4.9.4
 */
function wpex_hasnt_dev_style_header() {
	return ( wpex_has_dev_style_header() ) ? false : true;
}

/**
 * Check if the header is set to vertical.
 *
 * @since 4.0
 */
function wpex_has_vertical_header() {
	$check = in_array( wpex_header_style(), array( 'six' ) ) ? true : false;
	return apply_filters( 'wpex_has_vertical_header', $check );
}

/**
 * Add classes to the header wrap.
 *
 * @since 1.5.3
 */
function wpex_header_classes() {
	$post_id      = wpex_get_current_post_id();
	$header_style = wpex_header_style( $post_id );

	// Setup classes array.
	$classes = array();

	// Main header style.
	$classes['header_style'] = 'header-' . sanitize_html_class( $header_style );

	// Non-Builder classes.
	if ( 'builder' !== $header_style ) {

		// Full width header.
		if ( 'full-width' === wpex_site_layout() && get_theme_mod( 'full_width_header' ) ) {
			$classes[] = 'wpex-full-width';
		}

		// Non-dev classes
		if ( 'dev' !== $header_style ) {

			// Flex header style two.
			if ( 'two' === $header_style && get_theme_mod( 'header_flex_items', false ) ) {
				$classes[] = 'wpex-header-two-flex-v';
			}

			// Dropdown style (must be added here so we can target shop/search dropdowns).
			$dropdown_style = wpex_get_header_menu_dropdown_style();
			if ( $dropdown_style && 'default' !== $dropdown_style ) {
				$classes[] = 'wpex-dropdown-style-' . sanitize_html_class( $dropdown_style );
			}

			// Dropdown shadows.
			if ( $shadow = get_theme_mod( 'menu_dropdown_dropshadow' ) ) {
				$classes[] = 'wpex-dropdowns-shadow-' . sanitize_html_class( $shadow );
			}

		}

	}

	// Sticky Header.
	if ( wpex_has_sticky_header() ) {

		// Fixed header style.
		$fixed_header_style = wpex_sticky_header_style();

		// Main fixed class.
		$classes['fixed_scroll'] = 'fixed-scroll'; // @todo rename this at some point?
		if ( wpex_has_shrink_sticky_header() ) {
			$classes['shrink-sticky-header'] = 'shrink-sticky-header';
			if ( 'shrink_animated' === $fixed_header_style ) {
				$classes['anim-shrink-header'] = 'anim-shrink-header';
			}
		}

	}

	// Header Overlay Style
	if ( wpex_has_overlay_header() ) {

		// Add overlay header class.
		$classes[] = 'overlay-header';

		// Add overlay header style class.
		$overlay_style = wpex_overlay_header_style();
		if ( $overlay_style ) {
			$classes[] = sanitize_html_class( $overlay_style ) . '-style';
		}

	}

	// Custom bg.
	if ( get_theme_mod( 'header_background' ) ) {
		$classes[] = 'custom-bg';
	}

	// Background style.
	if ( wpex_header_background_image() ) {
		$bg_style = get_theme_mod( 'header_background_image_style' );
		$bg_style = $bg_style ? $bg_style : '';
		$bg_style = apply_filters( 'wpex_header_background_image_style', $bg_style );
		if ( $bg_style ) {
			$classes[] = 'bg-' . sanitize_html_class( $bg_style );
		}
	}

	// Dynamic style class.
	$classes[] = 'dyn-styles';

	// Clearfix class.
	$classes[] = 'wpex-clr';

	// Sanitize classes.
	$classes = array_map( 'esc_attr', $classes );

	// Set keys equal to vals.
	$classes = array_combine( $classes, $classes );

	// Apply filters for child theming.
	$classes = apply_filters( 'wpex_header_classes', $classes );

	// Turn classes into space seperated string.
	$classes = implode( ' ', $classes );

	// return classes.
	return $classes;

}

/**
 * Get site header background image.
 *
 * @since 4.5.5.1
 */
function wpex_header_background_image() {
	$image = apply_filters( 'wpex_header_background_image', get_theme_mod( 'header_background_image' ) );
	$post_id = wpex_get_current_post_id();
	if ( $post_id && $meta_image = get_post_meta( $post_id, 'wpex_header_background_image', true ) ) {
		$image = $meta_image; // meta overrides filters.
	}
	return wpex_get_image_url( $image );
}

/**
 * Returns header logo image src.
 *
 * @since 4.0
 */
function wpex_header_logo_img_src() {

	if ( wpex_has_post_meta( 'wpex_overlay_header' ) && wpex_has_overlay_header() ) {
		$overlay_logo = wpex_overlay_header_logo_img( false );
		if ( $overlay_logo && is_numeric( $overlay_logo ) ) {
			return wp_get_attachment_image_src( $overlay_logo, 'full', false );
		}
	}

	$logo = wpex_header_logo_img( false );

	if ( $logo ) {
		return wp_get_attachment_image_src( $logo, 'full', false );
	}

}

/**
 * Returns header logo image.
 *
 * @since 4.0
 */
function wpex_header_logo_img( $parse_logo = true ) {
	$logo = wpex_get_translated_theme_mod( 'custom_logo' );

	/**
	 * Filters th eheader logo image url
	 *
	 * @param string $img
	 */
	$logo = apply_filters( 'wpex_header_logo_img_url', $logo );

	if ( $logo ) {
		if ( $parse_logo ) {
			return wpex_get_image_url( $logo );
		} else {
			return $logo;
		}
	}
}

/**
 * Check if the site is using a text logo.
 *
 * @since 4.3
 */
function wpex_header_has_text_logo() {
	return wpex_header_logo_img() ? false : true;
}

/**
 * Returns header logo icon.
 *
 * @since 2.0.0
 */
function wpex_header_logo_icon() {

	$html = '';

	$icon = apply_filters( 'wpex_header_logo_icon', get_theme_mod( 'logo_icon', null ) );

	if ( $icon && 'none' !== $icon ) {

		$html = '<span id="site-logo-fa-icon" class="wpex-mr-10 ticon ticon-' . esc_attr( $icon ) . '" aria-hidden="true"></span>';
	}

	/**
	 * Filters the header logo icon html
	 *
	 * @param string $html
	 */
	$html = apply_filters( 'wpex_header_logo_icon_html', $html );

	return $html;

}

/**
 * Returns header logo text.
 *
 * @since 5.0.8
 */
function wpex_header_logo_text() {
	$text = get_theme_mod( 'logo_text' );

	if ( empty( $text ) || ! is_string( $text ) ) {
		$text = get_bloginfo( 'name' );
	}

	/**
	 * Filters the header logo text.
	 *
	 * @param string $text
	 */
	$text = apply_filters( 'wpex_header_logo_text', $text );

	return $text;
}

/**
 * Returns header logo title.
 *
 * @since 2.0.0
 */
function wpex_header_logo_title() {
	return apply_filters( 'wpex_logo_title', wpex_header_logo_text() ); // @todo rename to wpex_header_logo_title
}

/**
 * Check if the header logo should scroll up on click.
 *
 * @since 4.5.3
 */
function wpex_header_logo_scroll_top() {

	/**
	 * Filter for the header logo scroll to top check.
	 *
	 * @param boolean $check
	 */
	$check = (bool) apply_filters( 'wpex_header_logo_scroll_top', false );

	if ( $post_id = wpex_get_current_post_id() ) {
		$meta = get_post_meta( $post_id, 'wpex_logo_scroll_top', true );
		if ( 'enable' === $meta ) {
			$check = true;
		} elseif ( 'disable' === $meta ) {
			$check = false;
		}
	}

	return (bool) $check;
}

/**
 * Returns header logo URL.
 *
 * @since 2.0.0
 */
function wpex_header_logo_url() {
	$url = '';
	if ( wpex_header_logo_scroll_top() ) {
		$url = '#';
	} elseif ( wpex_vc_is_inline() ) {
		$url = get_permalink();
	}
	$url = $url ? $url : home_url( '/' );
	return apply_filters( 'wpex_logo_url', $url ); // @todo rename to wpex_header_logo_url
}

/**
 * Header logo classes.
 *
 * @since 2.0.0
 */
function wpex_header_logo_classes() {

	// Define classes array.
	$classes = array(
		'site-branding',
	);

	// Default class.
	$classes[] = 'header-' . sanitize_html_class( wpex_header_style() ) . '-logo';

	// Get custom overlay logo.
	if ( wpex_has_post_meta( 'wpex_overlay_header' ) && wpex_overlay_header_logo_img() && wpex_has_overlay_header() ) {
		$classes[] = 'has-overlay-logo';
	}

	// Scroll top.
	if ( wpex_header_logo_scroll_top() ) {
		$classes[] = 'wpex-scroll-top';
	}

	// Clear floats.
	$classes[] = 'wpex-clr';

	/**
	 * Filters the header logo classes
	 *
	 * @param array $classes
	 */
	$classes = (array) apply_filters( 'wpex_header_logo_classes', $classes );

	// Sanitize classes.
	$class_escaped = array_map( 'esc_attr', $classes );

	// Return classes.
	return implode( ' ', $class_escaped );

}

/*-------------------------------------------------------------------------------*/
/* [ Logo ]
/*-------------------------------------------------------------------------------*/

/**
 * Returns header logo text class.
 *
 * @since 5.0.8
 */
function wpex_header_logo_txt_class() {
	$class = array(
		'site-logo-text',
	);

	/**
	 * Filters the header logo text class
	 *
	 * @param array $class
	 */
	$class = (array) apply_filters( 'wpex_header_logo_txt_class', $class );
	$class_escaped = array_map( 'esc_attr', $class );
	return implode( ' ', $class_escaped );
}

/**
 * Returns header logo image class.
 *
 * @since 5.0.8
 */
function wpex_header_logo_img_class() {
	$class = array(
		'logo-img',
		// Keep as abstract class for the time being.
		//	'wpex-inline',
		//	'wpex-align-middle',
		//	'wpex-w-auto',
		//	'wpex-h-auto',
		//	'wpex-max-h-100',
		//	'wpex-max-w-100',
	);

	/**
	 * Filters the header logo image class.
	 *
	 * @param array $classes
	 */
	$class = (array) apply_filters( 'wpex_header_logo_img_class', $class );
	$class_escaped = array_map( 'esc_attr', $class );
	return implode( ' ', $class_escaped );
}

/**
 * Returns header logo height.
 *
 * @since 4.0
 */
function wpex_header_logo_img_height() {

	$height = get_theme_mod( 'logo_height' );

	/**
	 * Filters the header logo image height.
	 *
	 * @param int $height
	 */
	$height = (int) apply_filters( 'logo_height', $height );

	if ( ! $height ) {
		$logo_src = wpex_header_logo_img_src();
		if ( ! empty( $logo_src[2] ) ) {
			$height = absint( $logo_src[2] );
		}
	}

	if ( $height ) {
		return absint( $height );
	}

}

/**
 * Returns header logo width.
 *
 * @since 4.0
 */
function wpex_header_logo_img_width() {

	$width = get_theme_mod( 'logo_width' );

	/**
	 * Filters the header logo image width.
	 *
	 * @param int $width
	 */
	$width = (int) apply_filters( 'logo_width', $width );

	// Calculate width from src.
	if ( ! $width ) {
		$logo_src = wpex_header_logo_img_src();
		if ( ! empty( $logo_src[1] ) ) {
			$width = absint( $logo_src[1] );
		}
	}

	if ( $width ) {
		return absint( $width );
	}

}

/**
 * Returns header logo retina img.
 *
 * @since 4.0
 */
function wpex_header_logo_img_retina() {

	if ( wpex_has_overlay_header() && wpex_overlay_header_logo_img() ) {
		$logo = wpex_overlay_header_logo_img_retina();
	} else {
		$logo = wpex_get_translated_theme_mod( 'retina_logo' );
	}

	/**
	 * Filters the header logo retina image url.
	 *
	 * @todo deprecate.
	 */
	$logo = apply_filters( 'wpex_retina_logo_url', $logo );

	/**
	 * Filters the header logo retina image url.
	 *
	 * @param string $logo url.
	 */
	$logo = apply_filters( 'wpex_header_logo_img_retina_url', $logo );

	return wpex_get_image_url( $logo );
}

/**
 * Returns header logo retina img height.
 *
 * @since 4.0
 */
function wpex_header_logo_img_retina_height() {

	$height = wpex_get_translated_theme_mod( 'logo_height' );

	if ( ! $height ) {
		$height = wpex_header_logo_img_height(); // fallback if setting is empty let's get original logo img height.
	}

	if ( wpex_has_post_meta( 'wpex_overlay_header' ) && wpex_has_overlay_header() ) {
		$overlay_logo_height = wpex_overlay_header_logo_img_retina_height();
		if ( $overlay_logo_height ) {
			$height = $overlay_logo_height;
		}
	}

	/**
	 * Filters the header logo image retina height.
	 *
	 * @param int $height
	 */
	$height = (int) apply_filters( 'wpex_retina_logo_height', $height );

	return $height;

}

/*-------------------------------------------------------------------------------*/
/* [ Overlay/Transparent Header ]
/*-------------------------------------------------------------------------------*/

/**
 * Checks if the overlay header is enabled.
 *
 * @since 4.0
 */
function wpex_has_overlay_header() {

	if ( ! wpex_has_header() ) {
		return false; //@todo is this check still needed?
	}

	// Check if enabled globally.
	$global_check = get_theme_mod( 'overlay_header', false );

	// Check if enabled for specific post types only.
	if ( $global_check ) {

		$condition = get_theme_mod( 'overlay_header_condition', null );

		if ( $condition ) {
			$conditional_logic = new TotalTheme\Conditional_Logic( $condition );
			if ( isset( $conditional_logic->result ) ) {
				$global_check = $conditional_logic->result;
			}
		}

	}

	// Default check based on global setting.
	$check = $global_check;

	// Get current post id
	$post_id = wpex_get_current_post_id();

	// Return true if enabled via the post meta
	// NOTE: The overlay header meta can still be filtered it's not hard set.
	if ( $post_id ) {
		$meta = get_post_meta( $post_id, 'wpex_overlay_header', true );
		if ( $meta ) {
			$check = wpex_validate_boolean( $meta );
		}
	}

	if ( ! $global_check && post_password_required() && ! wpex_has_page_header() ) {
		$check = false;
	}

	/**
	 * Filters the has_overlay_header check.
	 *
	 * @param bool $check Is the overlary/transparent header enabled.
	 */
	$check = (bool) apply_filters( 'wpex_has_overlay_header', $check );

	return $check;

}

/**
 * Returns overlay header style.
 *
 * @since 4.0
 */
function wpex_overlay_header_style() {

	// Define style based on theme_mod.
	$style = get_theme_mod( 'overlay_header_style' );

	// Get overlay style based on meta option if hard enabled on the post.
	if ( wpex_has_overlay_header() && wpex_has_post_meta( 'wpex_overlay_header' ) ) {
		$meta = get_post_meta( wpex_get_current_post_id(), 'wpex_overlay_header_style', true );
		if ( $meta ) {
			$style = wp_strip_all_tags( trim( $meta ) );
		}
	}

	// White is the default/fallback style.
	if ( ! $style ) {
		$style = 'white';
	}

	/**
	 * Filters the overlay header style.
	 *
	 * @param string $style
	 */
	$style = apply_filters( 'wpex_header_overlay_style', $style );

	return $style;
}

/**
 * Returns logo image for the overlay header image.
 *
 * @since 4.0
 */
function wpex_overlay_header_logo_img( $parse_logo = true ) {

	$logo = get_post_meta( wpex_get_current_post_id(), 'wpex_overlay_header_logo', true );

	// Redux fallbacks.
	if ( is_array( $logo ) ) {
		if ( ! empty( $logo['url'] ) ) {
			$logo = $logo['url'];
		} else {
			$logo = false;
		}
	}

	/**
	 * Filters the overlay header logo image url.
	 *
	 * @param string $logo
	 */
	$logo = apply_filters( 'wpex_header_overlay_logo', $logo ); // can be string or int.

	if ( $logo ) {
		if ( $parse_logo ) {
			return wpex_get_image_url( $logo );
		} else {
			return $logo;
		}
	}

}

/**
 * Returns retina logo image for the overlay header image.
 *
 * @since 4.0
 */
function wpex_overlay_header_logo_img_retina() {
	$logo = get_post_meta( wpex_get_current_post_id(), 'wpex_overlay_header_logo_retina', true );

	/**
	 * Filters the overlay header logo retina image url.
	 *
	 * @param $logo
	 */
	$logo = apply_filters( 'wpex_header_overlay_logo_retina', $logo );

	if ( $logo ) {
		return wpex_get_image_url( $logo );
	}

}

/**
 * Returns retina logo image height for the overlay header image.
 *
 * @since 4.0
 */
function wpex_overlay_header_logo_img_retina_height() {
	$meta = get_post_meta( wpex_get_current_post_id(), 'wpex_overlay_header_logo_retina_height', true );
	if ( $meta ) {
		return absint( $meta );
	}
}

/*-------------------------------------------------------------------------------*/
/* [ Sticky Header ]
/*-------------------------------------------------------------------------------*/

/**
 * Check if sticky header is enabled.
 *
 * @since 4.0
 */
function wpex_has_sticky_header() {

	// Disable in live editor.
	if ( wpex_vc_is_inline() ) {
		return;
	}

	// Disabled by default.
	$return = false;

	// Get current post id.
	$post_id = wpex_get_current_post_id();

	// Check meta first it should override any filter!
	if ( $post_id && 'disable' === get_post_meta( $post_id, 'wpex_sticky_header', true ) ) {
		return false;
	}

	// Get header style.
	$header_style = wpex_header_style( $post_id );

	// Sticky header for builder.
	if ( 'builder' === $header_style ) {
		$return = get_theme_mod( 'header_builder_sticky', false );
	}

	// Standard sticky header.
	else {

		// Return false if sticky header style is set to disabled.
		if ( 'disabled' === wpex_sticky_header_style() ) {
			$return = false;
		}

		// Otherwise check if the current header style supports sticky.
		elseif ( in_array( $header_style, wpex_get_header_styles_with_sticky_support() ) ) {
			$return = true;
		}

	}

	// Apply filters and return.
	return apply_filters( 'wpex_has_fixed_header', $return ); // @todo rename to wpex_has_sticky_header

}

/**
 * Get sticky header style.
 *
 * @since 4.0
 */
function wpex_sticky_header_style() {

	if ( 'builder' === wpex_header_style() ) {
		return 'standard'; // Header builder only supports standard.
	}

	// Get default style from customizer.
	$style = get_theme_mod( 'fixed_header_style', 'standard' );

	// If disabled in Customizer but enabled in meta set to "standard" style.
	if ( 'disabled' === $style && 'enable' === get_post_meta( wpex_get_current_post_id(), 'wpex_sticky_header', true ) ) {
		$style = 'standard';
	}

	// Fallback style.
	if ( ! $style ) {
		$style = 'standard';
	}

	// Return style.
	return apply_filters( 'wpex_sticky_header_style', $style );

}

/**
 * Returns sticky header logo img retina version.
 *
 * @since 5.1
 */
function wpex_sticky_header_logo_img_src() {

	// Get fixed header logo from the Customizer.
	$logo = get_theme_mod( 'fixed_header_logo' );

	// Set sticky logo to header logo for overlay header when custom overlay logo is set
	// This way you can have a white logo on overlay but the default on sticky.
	if ( empty( $logo )
		&& wpex_has_post_meta( 'wpex_overlay_header' ) // check if overlay header is force enabled via metadata.
		&& wpex_overlay_header_logo_img() // check for custom overlay header logo.
		&& wpex_has_overlay_header() // check if overlay header is enabled.
	) {
		$header_logo = wpex_header_logo_img();
		if ( $header_logo ) {
			$logo = $header_logo;
		}
	}

	/**
	 * Filters the sticky header logo image.
	 *
	 * @param string $logo
	 */
	$logo = apply_filters( 'wpex_fixed_header_logo', $logo );

	if ( is_numeric( $logo ) ) {
		return wp_get_attachment_image_src( $logo, 'full', false );
	}

	if ( is_string( $logo ) ) {
		return array( $logo, '', '', '' );
	}

}

/**
 * Returns sticky header logo img.
 *
 * @since 4.0
 * @todo add as data attribute to prevent the need for added checks for builder header.
 */
function wpex_sticky_header_logo_img() {

	if ( 'builder' === wpex_header_style() ) {
		return ''; // Not needed for the sticky header builder.
	}

	$logo_src = wpex_sticky_header_logo_img_src();

	if ( isset( $logo_src[0] ) ) {
		return wpex_get_image_url( $logo_src[0]  );
	}

}

/**
 * Returns sticky header logo img retina version.
 *
 * @since 4.0
 */
function wpex_sticky_header_logo_img_retina() {

	$logo = wpex_get_translated_theme_mod( 'fixed_header_logo_retina' );

	/*
	 * Set retina logo for sticky header when the header overlay is set
	 * and the sticky header logo isn't set, since the default logo is displayed for the sticky header
	 * when using the overlay header and a custom logo.
	 */
	if ( ! $logo && ! get_theme_mod( 'fixed_header_logo' ) ) {

		$logo = wpex_get_translated_theme_mod( 'retina_logo' );

		/**
		 * Filters the header logo image retina url.
		 *
		 * @param string $logo
		 */
		$logo = apply_filters( 'wpex_header_logo_img_retina_url', $logo );

	}

	/**
	 * Filters the sticky header logo retina image.
	 *
	 * @param string $logo
	 */
	$logo = apply_filters( 'wpex_fixed_header_logo_retina', $logo );

	return wpex_get_image_url( $logo );
}

/**
 * Returns sticky header logo image retina height.
 *
 * @since 4.0
 */
function wpex_sticky_header_logo_img_retina_height() {

	$height = get_theme_mod( 'fixed_header_logo_retina_height' );

	/**
	 * Filters the sticky header logo image retina height.
	 *
	 * @param int $height.
	 */
	$height = (int) apply_filters( 'wpex_fixed_header_logo_retina_height', $height );

	if ( ! $height ) {
		$logo_src = wpex_sticky_header_logo_img_src();
		if ( ! empty( $logo_src[2] ) ) {
			$height = absint( $logo_src[2] );
		}
	}

	if ( $height ) {
		return absint( $height );
	}
}

/**
 * Check if shrink sticky header is enabled.
 *
 * @since 4.0
 */
function wpex_has_shrink_sticky_header() {
	$check = false;
	if ( wpex_has_sticky_header()
		&& in_array( wpex_header_style(), wpex_get_header_styles_with_sticky_support() )
		&& in_array( wpex_sticky_header_style(), array( 'shrink', 'shrink_animated' ) ) ) {
		$check = true;
	}
	return (bool) apply_filters( 'wpex_has_shrink_sticky_header', $check );
}

/**
 * Check if shrink sticky header is enabled.
 *
 * @since 5.1.3
 */
function wpex_has_shrink_sticky_header_mobile() {
	$check = false;
	$mobile_toggle = wpex_header_menu_mobile_toggle_style();
	if ( 'icon_buttons' === $mobile_toggle || 'fixed_top' === $mobile_toggle ) {
		$check = true;
	}
	return (bool) apply_filters( 'wpex_has_shrink_sticky_header_mobile', $check );
}

/**
 * Return correct starting position for the sticky header.
 *
 * @since 4.6.5
 */
function wpex_sticky_header_start_position() {
	$position = get_theme_mod( 'fixed_header_start_position' );
	if ( is_singular() ) {
		$meta_position = get_post_meta( get_the_ID(), 'fixed_header_start_position', true );
		if ( $meta_position ) {
			$position = $meta_position;
		}
	}
	return apply_filters( 'wpex_sticky_header_start_position', $position );
}

/*-------------------------------------------------------------------------------*/
/* [ Header Aside ]
/*-------------------------------------------------------------------------------*/

/**
 * Check if the current header supports aside content.
 *
 * @since 3.0.0
 */
function wpex_header_supports_aside( $header_style = '' ) {
	$check = false;
	$header_style = $header_style ? $header_style : wpex_header_style();
	if ( in_array( $header_style, wpex_get_header_styles_with_aside_support() ) ) {
		$check = true;
	}
	return (bool) apply_filters( 'wpex_header_supports_aside', $check );
}

/**
 * Get Header Aside content.
 *
 * @since 4.0
 */
function wpex_header_aside_content() {

	// Get header aside content.
	$content = wpex_get_translated_theme_mod( 'header_aside' );

	// Check if content is a page ID and get page content.
	if ( is_numeric( $content ) ) {
		$post_id = wpex_parse_obj_id( $content, 'page' );
		$post = get_post( $post_id );
		if ( $post && ! is_wp_error( $post ) ) {
			$content = $post->post_content;
		}
	}

	// Apply filters and return content.
	return apply_filters( 'wpex_header_aside_content', $content );

}