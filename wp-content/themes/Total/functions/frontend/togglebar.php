<?php
/**
 * Togglebar functions.
 *
 * @package TotalTheme
 * @subpackage Functions
 * @version 5.3
 */

defined( 'ABSPATH' ) || exit;

/**
 * Get togglebar content ID.
 *
 * @since 4.0
 */
function wpex_togglebar_content_id() {
	$content_id = get_theme_mod( 'toggle_bar_page', null );

	/**
	 * Filters the toggle bar template id.
	 *
	 * @param int $content_id
	 */
	$content_id = (int) apply_filters( 'wpex_toggle_bar_content_id', $content_id );

	if ( $content_id ) {
		return wpex_parse_obj_id( $content_id );
	}
}

/**
 * Returns togglebar content.
 *
 * @since 4.0
 */
function wpex_togglebar_content() {

	$content = get_post_meta( wpex_get_current_post_id(), 'wpex_togglebar_content', true );

	if ( ! $content ) {

		$togglebar_id = wpex_togglebar_content_id();

		if ( $togglebar_id && is_numeric( $togglebar_id ) ) {
			$content = wpex_parse_vc_content( get_post_field( 'post_content', $togglebar_id ) );
		}

		if ( ! $content ) {
			$content = get_theme_mod( 'toggle_bar_content', null );
		}

	}

	/**
	 * Filters the toggle bar content.
	 *
	 * @param string $content
	 */
	return apply_filters( 'wpex_togglebar_content', $content );
}

/**
 * Check if togglebar is enabled.
 *
 * @since 4.0
 */
function wpex_has_togglebar( $post_id = '' ) {

	if ( get_theme_mod( 'toggle_bar_remember_state', false )
		&& get_theme_mod( 'toggle_bar_enable_dismiss', false )
		&& 'hidden' === wpex_togglebar_state_cookie()
	) {
		return false;
	}

	if ( ! wpex_togglebar_content() && ! wpex_elementor_location_exists( 'togglebar' ) ) {
		return false;
	}

	// Check if enabled in Customizer.
	$check = get_theme_mod( 'toggle_bar', true );

	// Get post ID.
	$post_id = $post_id ? $post_id : wpex_get_current_post_id();

	// Check meta.
	if ( $post_id ) {

		// Return true if enabled via the page settings.
		if ( 'enable' === get_post_meta( $post_id, 'wpex_disable_toggle_bar', true ) ) {
			$check = true;
		}

		// Return false if disabled via the page settings.
		if ( 'on' === get_post_meta( $post_id, 'wpex_disable_toggle_bar', true ) ) {
			$check = false;
		}

	}

	// Deprecated filter @since 5.1.3
	$check = apply_filters( 'wpex_toggle_bar_active', $check );

	// Apply filters and return bool.
	return (bool) apply_filters( 'wpex_has_togglebar', $check );

}

/**
 * Get correct togglebar style.
 *
 * @since 4.0
 */
function wpex_togglebar_style() {
	$style = ( $style = get_theme_mod( 'toggle_bar_display' ) ) ? $style : 'overlay';
	return apply_filters( 'wpex_togglebar_style', $style );
}

/**
 * Returns togglebar classes.
 *
 * @since 4.9.9.5
 */
function wpex_togglebar_class() {
	if ( $classes = wpex_togglebar_classes() ) {
		echo 'class="' . esc_attr( $classes ) . '"';
	}
}

/**
 * Returns togglebar inner classes.
 *
 * @since 5.1.3
 */
function wpex_togglebar_inner_class() {

	$classes = array(
		'wpex-flex',
		'wpex-flex-col',
		'wpex-justify-center',
	);

	if ( get_theme_mod( 'toggle_bar_fullwidth', false ) ) {
		$classes[] = 'wpex-px-30';
	} else {
		$classes[] = 'container';
	}

	$classes = (array) apply_filters( 'wpex_portfolio_loop_top_class', $classes );

	if ( $classes ) {
		echo 'class="' . esc_attr( implode( ' ', $classes ) ) . '"';
	}
}

/**
 * Returns togglebar data attributes.
 *
 * @since 5.1.3
 */
function wpex_togglebar_data_attributes() {

	$attributes = array();

	$current_state = wpex_togglebar_state();
	$attributes['data-state'] = $current_state;

	$remember_state = wp_validate_boolean( get_theme_mod( 'toggle_bar_remember_state', false ) );
	$attributes['data-remember-state'] = ( true === $remember_state ) ? 'true' : 'false';

	$attributes['data-allow-toggle'] = get_theme_mod( 'toggle_bar_enable_dismiss', false ) ? 'false' : 'true';

	$attributes = (array) apply_filters( 'wpex_togglebar_data_attributes', $attributes );

	if ( $attributes ) {

		foreach ( $attributes as $attribute_k => $attribute_v ) {
			echo ' ' . esc_attr( $attribute_k ) . '="' . esc_attr( $attribute_v ) . '"';
		}

	}

}

/**
 * Returns togglebar state.
 *
 * @since 5.0.6
 * @return (string) hidden or visible
 */
function wpex_togglebar_state() {

	if ( get_theme_mod( 'toggle_bar_remember_state', false ) ) {
		return wpex_togglebar_state_cookie();
	}

	$state = apply_filters( 'wpex_togglebar_state', get_theme_mod( 'toggle_bar_default_state', 'hidden' ) );
	switch ( $state ) {
		case 'open':
			$state = 'visible';
			break;
		case 'closed':
			$state = 'hidden';
			break;
	}
	return $state;
}

/**
 * Returns togglebar state.
 *
 * @since 5.0.6
 * @return (string) hidden or visible
 */
function wpex_togglebar_state_cookie() {
	if ( ! empty( $_COOKIE['total_togglebar_state'] ) ) {
		if ( 'hidden' === $_COOKIE['total_togglebar_state'] ) {
			return 'hidden';
		}
		if ( 'visible' === $_COOKIE['total_togglebar_state'] ) {
			return 'visible';
		}
	}
}

/**
 * Returns togglebar visibility.
 *
 * @since 5.0.6
 */
function wpex_togglebar_visibility() {
	return apply_filters( 'wpex_togglebar_visibility', get_theme_mod( 'toggle_bar_visibility', 'always-visible' ) );
}

/**
 * Returns togglebar classes.
 *
 * @since 1.0
 */
function wpex_togglebar_classes() {

	$classes     = array();
	$style       = wpex_togglebar_style();
	$visibility  = wpex_togglebar_visibility();
	$is_builder  = wpex_elementor_location_exists( 'togglebar' );
	$animation   = get_theme_mod( 'toggle_bar_animation', 'fade' );
	$padding_y   = get_theme_mod( 'toggle_bar_padding_y' );
	$dismissable = get_theme_mod( 'toggle_bar_enable_dismiss', false );

	if ( $padding_y && '0px' !== $padding_y ) {
		$padding_y = absint( $padding_y );
	}

	/*** Add theme classes ***/

	$classes[] = 'toggle-bar-' . sanitize_html_class( $style );

		if ( 'overlay' === $style && $animation ) {
			$classes[] = 'toggle-bar-' . sanitize_html_class( $animation );
		}

		if ( 'visible' === wpex_togglebar_state() ) {
			$classes[] = 'active-bar';
		} elseif( ! get_theme_mod( 'toggle_bar_remember_state', false ) ) {
			$classes[] = 'close-on-doc-click';
		}

		if ( $visibility && 'always-visible' !== $visibility ) {
			$classes[] = sanitize_html_class( $visibility );
		}

	/*** Add utility classes ***/

		// Default.
		$classes[] = 'wpex-invisible';
		$classes[] = 'wpex-opacity-0';
		$classes[] = 'wpex-bg-white';
		$classes[] = 'wpex-w-100';

		// Style specific classes.
		switch ( $style ) {
			case 'overlay':
				$classes[] = 'wpex-fixed';
				$classes[] = '-wpex-z-1';
				$classes[] = 'wpex-top-0';
				$classes[] = 'wpex-inset-x-0';
				$classes[] = 'wpex-max-h-100';
				$classes[] = 'wpex-overflow-auto';
				$classes[] = 'wpex-shadow';
				if ( ! $padding_y ) {
					$padding_y = '40';
				}
				break;
			case 'inline':
				if ( $dismissable ) {
					$classes[] = 'wpex-relative';
				}
				$classes[] = 'wpex-hidden';
				$classes[] = 'wpex-border-b';
				$classes[] = 'wpex-border-solid';
				$classes[] = 'wpex-border-main';
				if ( ! $padding_y ) {
					$padding_y = '20';
				}
				break;
		}

		// Add vertical padding.
		if ( ! $is_builder && $padding_y && '0px' !== $padding_y ) {
			$classes[] = 'wpex-py-' . sanitize_html_class( $padding_y );
		}

		// Add animation classes.
		if ( 'overlay' === $style && $animation ) {
			$classes[] = 'wpex-transition-all';
			$classes[] = 'wpex-duration-300';
			if ( 'fade-slide' === $animation ) {
				$classes[] = '-wpex-translate-y-50';
			}
		}

		// Add clearfix.
		$classes[] = 'wpex-clr';

	/*** Sanitize & Apply Filters ***/

		// Sanitize.
		$classes = array_map( 'esc_attr', $classes );

		// Apply filters for child theming.
		$classes = apply_filters( 'wpex_togglebar_classes', $classes );

		// Turn classes into space seperated string.
		$classes = is_array( $classes ) ? implode( ' ', $classes ) : $classes;

	/*** Return classes ***/
	return $classes;

}