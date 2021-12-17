<?php
/**
 * Create awesome overlays for image hovers.
 *
 * @package TotalTheme
 * @subpackage Functions
 * @version 5.3
 */

defined( 'ABSPATH' ) || exit;

/**
 * Displays the Overlay HTML.
 *
 * @since 1.0.0
 */
if ( ! function_exists( 'wpex_overlay' ) ) {

	function wpex_overlay( $position = 'inside_link', $style = '', $args = array() ) {

		// If style is set to none lets bail.
		if ( 'none' === $style ) {
			return;
		}

		// If style not defined get correct style based on theme_mods.
		elseif ( empty( $style ) ) {
			$style = wpex_overlay_style();
		}

		// If style is defined lets locate and include the overlay template.
		if ( $style && is_string( $style ) ) {

			// Add position to args.
			$args['overlay_position'] = $position;

			// Add new action for loading custom templates.
			do_action( 'wpex_pre_include_overlay_template', $style, $args );

			// Load the overlay template.
			$overlays_dir = 'partials/overlays/';
			$template = $overlays_dir . $style . '.php';
			$template = locate_template( $template, false );

			// Only load template if it exists.
			if ( $template ) {
				include( $template );
			}

		}

	}

}

/**
 * Create an array of overlay styles so they can be altered via child themes.
 *
 * @since 1.0.0
 */
function wpex_overlay_styles_array() {

	$styles = array(
		''                                => esc_html__( 'Default', 'total' ),
		'none'                            => esc_html__( 'None', 'total' ),
		'hover-button'                    => esc_html__( 'Hover Button', 'total' ),
		'magnifying-hover'                => esc_html__( 'Magnifying Glass Hover', 'total' ),
		'plus-hover'                      => esc_html__( 'Plus Icon Hover', 'total' ),
		'plus-two-hover'                  => esc_html__( 'Plus Icon #2 Hover', 'total' ),
		'plus-three-hover'                => esc_html__( 'Plus Icon #3 Hover', 'total' ),
		'view-lightbox-buttons-buttons'   => esc_html__( 'View/Lightbox Icons Hover', 'total' ),
		'view-lightbox-buttons-text'      => esc_html__( 'View/Lightbox Text Hover', 'total' ),
		'title-center'                    => esc_html__( 'Title Centered', 'total' ),
		'title-center-boxed'              => esc_html__( 'Title Centered Boxed', 'total' ),
		'title-bottom'                    => esc_html__( 'Title Bottom', 'total' ),
		'title-bottom-see-through'        => esc_html__( 'Title Bottom See Through', 'total' ),
		'title-push-up'                   => esc_html__( 'Title Push Up', 'total' ),
		'title-excerpt-hover'             => esc_html__( 'Title + Excerpt Hover', 'total' ),
		'title-category-hover'            => esc_html__( 'Title + Category Hover', 'total' ),
		'title-category-visible'          => esc_html__( 'Title + Category Visible', 'total' ),
		'title-date-hover'                => esc_html__( 'Title + Date Hover', 'total' ),
		'title-date-visible'              => esc_html__( 'Title + Date Visible', 'total' ),
		'post-author'                     => esc_html__( 'Post Author', 'total' ),
		'post-author-hover'               => esc_html__( 'Post Author Hover', 'total' ),
		'categories-title-bottom-visible' => esc_html__( 'Categories + Title Bottom Visible', 'total' ),
		'slideup-title-white'             => esc_html__( 'Slide-Up Title White', 'total' ),
		'slideup-title-black'             => esc_html__( 'Slide-Up Title Black', 'total' ),
		'category-tag'                    => esc_html__( 'Category Tag', 'total' ),
		'category-tag-two'                => esc_html__( 'Category Tag', 'total' ) .' 2',
		'thumb-swap'                      => esc_html__( 'Secondary Image Swap', 'total' ),
		'thumb-swap-title'                => esc_html__( 'Secondary Image Swap and Title', 'total' ),
		'video-icon'                      => esc_html__( 'Video Icon', 'total' ) . ' 1',
		'video-icon_2'                    => esc_html__( 'Video Icon', 'total' ) . ' 2',
		'video-icon_3'                    => esc_html__( 'Video Icon', 'total' ) . ' 3',
		'video-icon_4'                    => esc_html__( 'Video Icon', 'total' ) . ' 4',
	);

	if ( WPEX_WOOCOMMERCE_ACTIVE ) {
		$styles['title-price-hover'] = esc_html__( 'Title + Price Hover', 'total' );
	}

	/**
	 * Filters the overlay styles array.
	 *
	 * @since 1.0.0
	 * @param array $styles
	 */
	$styles = (array) apply_filters( 'wpex_overlay_styles_array', $styles );

	return $styles;
}

/**
 * Returns the overlay type depending on your theme options & post type.
 *
 * @since 1.0.0
 */
if ( ! function_exists( 'wpex_overlay_style' ) ) {

	function wpex_overlay_style( $post_type = '' ) {

		$post_type = $post_type ? $post_type : get_post_type();

		$style = get_theme_mod( $post_type . '_entry_overlay_style' );

		if ( 'related' === wpex_get_loop_instance() ) {
			$style = wpex_get_mod( $post_type . '_related_entry_overlay_style', $style, true );
		}

		/**
		 * Filters the current overlay style.
		 *
		 * @param string $style
		 */
		$style = apply_filters( 'wpex_overlay_style', $style );

		return $style;

	}

}

/**
 * Returns overlay speed.
 *
 * @since 4.9.9.5
 */
function wpex_overlay_speed( $style, $speed = '' ) {

	if ( empty( $speed ) ) {
		$speed = get_theme_mod( 'overlay_speed', '300' );
	}

	/**
	 * Filters the overlay animation speed.
	 *
	 * @param int $speed The desired speed in ms.
	 * @param string $style The overlay style being targeted.
	 */
	$speed = apply_filters( 'wpex_overlay_speed', $speed, $style );

	return sanitize_html_class( $speed ); // sanitize as class since it's added to a classname.

}

/**
 * Returns overlay background.
 *
 * @since 4.9.9.5
 */
function wpex_overlay_bg( $style, $bg = '' ) {

	if ( empty( $bg ) ) {
		$bg = get_theme_mod( 'overlay_bg', 'black' );
	}

	/**
	 * Filters the overlay background.
	 *
	 * @param $bg string
	 * @param $style string The overlay style being targeted.
	 */
	$bg = apply_filters( 'wpex_overlay_bg', $bg, $style );

	return sanitize_html_class( $bg ); // sanitize as class since it's added to a classname.
}

/**
 * Returns overlay opacity.
 *
 * @since 4.9.9.5
 */
function wpex_overlay_opacity( $style, $opacity = '' ) {

	if ( empty( $opacity ) ) {
		$opacity = get_theme_mod( 'overlay_opacity', '60' );
	}

	/**
	 * Filters the overlay opacity.
	 *
	 * @param int $opacity The decired opacity.
	 * @param string $style The overlay style being targeted.
	 */
	$opacity = apply_filters( 'wpex_overlay_opacity', $opacity, $style );

	return sanitize_html_class( $opacity ); // sanitize as class since it's added to a classname.
}

/**
 * Returns the correct overlay Classname.
 *
 * @since 1.0.0
 */
if ( ! function_exists( 'wpex_overlay_classes' ) ) {

	function wpex_overlay_classes( $style = '' ) {

		$style = $style ? $style : wpex_overlay_style();

		if ( empty( $style ) || 'none' === $style || ! is_string( $style ) ) {
			return;
		}

		// Core classnames.
		$classes = array(
			'overlay-parent',
			'overlay-parent-' . sanitize_html_class( $style ),
		);

		// Mobile support is false by default (only added to certain styles).
		$mobile_support = false;

		// Overlays with hover.
		if ( in_array( $style, apply_filters( 'wpex_overlays_with_hover', array(
			'hover-button',
			'magnifying-hover',
			//'plus-hover',
			//'plus-two-hover',
			//'plus-three-hover',
			'view-lightbox-buttons-buttons',
			'view-lightbox-buttons-text',
			'title-push-up',
			'title-excerpt-hover',
			'title-category-hover',
			'title-date-hover',
			'slideup-title-white',
			'slideup-title-black',
			'thumb-swap',
			'thumb-swap-title',
		), $style ) ) ) {
			$mobile_support = true;
			$classes[] = 'overlay-h';
		}

		// Hide overflow on certain items to prevent issues with border radius.
		$hide_overflow = false;

		if ( in_array( $style, array(
			'hover-button',
			'magnifying-hover',
			'plus-hover',
			'plus-two-hover',
			'plus-three-hover',
			'view-lightbox-buttons-buttons',
			'view-lightbox-buttons-text',
			'title-center',
			'title-excerpt-hover',
			'title-category-hover',
			'title-category-visible',
			'title-price-hover',
			'title-date-hover',
			'title-date-visible',
			'slideup-title-white',
			'slideup-title-black',
			'thumb-swap',
			'thumb-swap-title',
			'video-icon_2',
			'video-icon_3',
			'video-icon_4',
		) ) ) {
			$hide_overflow = true;
		}

		/**
		 * Filters if an element should hide overflow or not.
		 *
		 * @param bool $hide_overflow
		 */
		$hide_overflow = apply_filters( 'wpex_has_overlay_overflow_hidden', $hide_overflow );

		if ( $hide_overflow ) {
			$classes[] = 'wpex-overflow-hidden';
		}

		/**
		 * Filters if mobile support is enabled for overlays.
		 *
		 * @param bool $mobile_support.
		 */
		$mobile_support = apply_filters( 'wpex_overlay_mobile_support', $mobile_support, $style );

		if ( $mobile_support ) {
			$classes[] = 'overlay-ms';
		}

		// Return classes.
		return implode( ' ', $classes );

	}

}