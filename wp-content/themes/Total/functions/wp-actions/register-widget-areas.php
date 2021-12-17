<?php
/**
 * Functions that run on widgets init.
 *
 * @package TotalTheme
 * @version 5.3
 */

defined( 'ABSPATH' ) || exit;

/**
 * Register sidebar widget area. Wrapper for register_sidebar().
 *
 * @since 5.3
 */
function wpex_register_sidebar( $args = array() ) {

	$tag_escaped = ( $tag = get_theme_mod( 'sidebar_headings' ) ) ? tag_escape( $tag ) : 'div';

	/**
	 * Filters the sidebar widget class.
	 *
	 * @param string $class
	 */
	$widget_class = apply_filters( 'wpex_sidebar_widget_class', 'sidebar-box widget %2$s wpex-mb-30 wpex-clr' );

	$default_args = array(
		'before_widget' => '<div id="%1$s" class="' . esc_attr( $widget_class ) . '">',
		'after_widget'  => '</div>',
		'before_title'  => '<' . $tag_escaped . ' class="widget-title wpex-heading wpex-text-md wpex-mb-20">',
		'after_title'   => '</' . $tag_escaped . '>',
	);

	$args = wp_parse_args( $args, $default_args );

	if ( empty( $args['id'] ) ) {
		return;
	}

	register_sidebar( $args );

}

/**
 * Register sidebar widget areas
 *
 * @since 4.0
 */
function wpex_register_sidebar_widget_areas() {

	// Define sidebars array.
	$sidebars = array(
		'sidebar' => esc_html__( 'Main Sidebar', 'total' ),
	);

	// Pages Sidebar.
	if ( get_theme_mod( 'pages_custom_sidebar', true ) ) {
		$sidebars['pages_sidebar'] = esc_html__( 'Pages Sidebar', 'total' );
	}

	// Blog Sidebar.
	if ( get_theme_mod( 'blog_custom_sidebar', false ) ) {
		$sidebars['blog_sidebar'] = esc_html__( 'Blog Sidebar', 'total' );
	}

	// Search Results Sidebar.
	if ( get_theme_mod( 'search_custom_sidebar', true ) ) {
		$sidebars['search_sidebar'] = esc_html__( 'Search Results Sidebar', 'total' );
	}

	// WooCommerce.
	if ( WPEX_WOOCOMMERCE_ACTIVE && get_theme_mod( 'woo_custom_sidebar', true ) ) {
		$sidebars['woo_sidebar'] = esc_html__( 'WooCommerce Sidebar', 'total' );
	}

	/**
	 * Filters the array of sidebars to be registered.
	 *
	 * @param array $sidebars. Sidebars array with the format: widget_id => widget_name
	 */
	$sidebars = apply_filters( 'wpex_register_sidebars_array', $sidebars );

	// If there are no sidebars then return.
	if ( ! $sidebars ) {
		return;
	}

	// Loop through sidebars and register them.
	foreach ( $sidebars as $k => $v ) {

		if ( is_array( $v ) ) {
			$args = $args;
		} else {
			$args = array(
				'id' => $k,
				'name' => $v,
			);
		}

		wpex_register_sidebar( $args );

	}

}
add_action( 'widgets_init', 'wpex_register_sidebar_widget_areas' );

/**
 * Register footer widget area. Wrapper for register_sidebar().
 *
 * @since 5.3
 */
function wpex_register_footer_widget_area( $args = array() ) {

	$tag_escaped = ( $tag = get_theme_mod( 'footer_headings' ) ) ? tag_escape( $tag ) : 'div';

	/**
	 * Filters the footer widget class.
	 *
	 * @param string $class
	 */
	$widget_class = apply_filters( 'wpex_footer_widget_class', 'footer-widget widget wpex-pb-40 wpex-clr %2$s' );

	$default_args = array(
		'before_widget' => '<div id="%1$s" class="' . esc_attr( $widget_class ) . '">',
		'after_widget'  => '</div>',
		'before_title'  => '<' . $tag_escaped . ' class="widget-title wpex-heading wpex-text-md wpex-mb-20">',
		'after_title'   => '</' . $tag_escaped . '>',
	);

	$args = wp_parse_args( $args, $default_args );

	if ( empty( $args['id'] ) ) {
		return;
	}

	register_sidebar( $args );

}

/**
 * Register footer widget areas.
 *
 * @since 4.0
 */
function wpex_register_footer_widget_areas() {

	if ( wpex_has_custom_footer() ) {
		$maybe_register = get_theme_mod( 'footer_builder_footer_widgets', false );
	} else {
		$maybe_register = get_theme_mod( 'footer_widgets', true );
	}

	/**
	 * Filters whether the footer has widgets or not.
	 *
	 * @param bool $maybe_register
	 * @todo rename filter to "wpex_maybe_register_footer_widget_areas".
	 */
	$maybe_register = apply_filters( 'wpex_register_footer_sidebars', $maybe_register );

	if ( ! $maybe_register ) {
		return;
	}

	// Footer widget columns.
	$footer_columns = (int) get_theme_mod( 'footer_widgets_columns', 4 );

	// Check if we are in the customizer.
	$customizing = is_customize_preview();

	// Footer 1.
	wpex_register_footer_widget_area( array(
		'name' => esc_html__( 'Footer Column 1', 'total' ),
		'id'   => 'footer_one',
	) );

	// Footer 2.
	if ( $footer_columns > 1 || $customizing ) {
		wpex_register_footer_widget_area( array(
			'name' => esc_html__( 'Footer Column 2', 'total' ),
			'id' => 'footer_two',
		) );
	}

	// Footer 3.
	if ( $footer_columns > 2 || $customizing ) {
		wpex_register_footer_widget_area( array(
			'name' => esc_html__( 'Footer Column 3', 'total' ),
			'id' => 'footer_three',
		) );
	}

	// Footer 4.
	if ( $footer_columns > 3 || $customizing ) {
		wpex_register_footer_widget_area( array(
			'name' => esc_html__( 'Footer Column 4', 'total' ),
			'id' => 'footer_four',
		) );
	}

	// Footer 5.
	if ( $footer_columns > 4 || $customizing ) {
		wpex_register_footer_widget_area( array(
			'name' => esc_html__( 'Footer Column 5', 'total' ),
			'id' => 'footer_five',
		) );
	}

	// Footer 6.
	if ( $footer_columns > 5 || $customizing ) {
		wpex_register_footer_widget_area( array(
			'name' => esc_html__( 'Footer Column 6', 'total' ),
			'id' => 'footer_six',
		) );
	}

}
add_action( 'widgets_init', 'wpex_register_footer_widget_areas', 40 );