<?php
/**
 * Define and get aria-labels
 *
 * @package TotalTheme
 * @subpackage Functions
 * @version 5.3
 */

defined( 'ABSPATH' ) || exit;

/**
 * Return default aria-labels.
 *
 * @since 5.0
 */
function wpex_aria_label_defaults() {
	return array(
		'site_navigation'    => esc_attr_x( 'Main menu', 'aria-label', 'total' ),
		'footer_callout'     => '',
		'footer_bottom_menu' => esc_attr_x( 'Footer menu', 'aria-label', 'total' ),
		'mobile_menu_toggle' => esc_attr_x( 'Toggle mobile menu', 'aria-label', 'total' ),
		'mobile_menu_close'  => esc_attr_x( 'Close mobile menu', 'aria-label', 'total' ),
		'mobile_menu'        => esc_attr_x( 'Mobile menu', 'aria-label', 'total' ),
		'search'             => esc_attr_x( 'Search', 'aria-label', 'total' ),
		'breadcrumbs'        => esc_attr_x( 'You are here:', 'aria-label', 'total' ),
		'shop_cart'          => esc_attr_x( 'Your cart', 'aria-label', 'total' ),

		// @deprecated in 5.3
		'mobile_menu_search' => esc_attr_x( 'Search', 'aria-label', 'total' ),
		'search_submit'      => esc_attr_x( 'Submit search', 'aria-label', 'total' ),
	);
}

/**
 * Get aria label based on location.
 *
 * @since 5.0
 */
function wpex_get_aria_label( $location = null, $apply_filters = true ) {

	if ( ! $location || ! get_theme_mod( 'aria_labels_enable', true ) ) {
		return;
	}

	$defaults = wpex_aria_label_defaults();

	$labels = wp_parse_args( (array) get_theme_mod( 'aria_labels' ), $defaults );

	$label = isset( $labels[ $location ] ) ? $labels[ $location ] : '';

	if ( true === $apply_filters ) {
		$label = apply_filters( 'wpex_aria_label', $label, $location );
	}

	if ( $label ) {
		return wp_strip_all_tags( $label );
	}

}

/**
 * Output aria-label HTML.
 *
 * @since 5.0
 */
function wpex_aria_label( $location ) {
	$label = wpex_get_aria_label( $location );
	if ( ! empty( $label ) ) {
		echo ' aria-label="' . esc_attr( trim( $label ) ) .'"';
	}
}