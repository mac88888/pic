<?php
namespace TotalTheme;

defined( 'ABSPATH' ) || exit;

/**
 * Adds custom CSS to alter all main theme border colors.
 *
 * @package TotalTheme
 * @version 5.3
 *
 * @todo go through all classes and remove any no longer needed.
 */
final class Border_Colors {

	/**
	 * Class instance.
	 *
	 * @access private
	 * @var object Class object.
	 */
	private static $instance;

	/**
	 * Create or retrieve the instance of Border_Colors.
	 */
	public static function instance() {
		if ( is_null( static::$instance ) ) {
			static::$instance = new self();
		}
		return static::$instance;
	}

	/**
	 * Hook into actions and filters.
	 */
	public function __construct() {

		if ( is_customize_preview() ) {
			add_action( 'wp_head', __CLASS__ . '::customizer_css', 99 );
			add_action( 'customize_preview_init', __CLASS__ . '::customize_preview_init' );
		} else {
			add_filter( 'wpex_head_css', __CLASS__ . '::live_css', 1 );
		}

	}

	/**
	 * Array of elements.
	 *
	 * @todo move vcex elements out and only add if Total Theme Core and vcex elements are enabled.
	 */
	public static function elements() {
		return apply_filters( 'wpex_border_color_elements', array(

			// Utility classes
			'.wpex-border-main',
			'.wpex-bordered',
			'.wpex-bordered-list li',
			'.wpex-bordered-list li:first-child',
			'.wpex-divider',

			// General
			'.theme-heading.border-side span.text:after',
			'.theme-heading.border-w-color',
			'#comments .comment-body',
			'.theme-heading.border-bottom',

			// Pagination
			'ul .page-numbers a,
			 a.page-numbers,
			 span.page-numbers',

			// Widgets
			'.modern-menu-widget',
			'.modern-menu-widget li',
			'.modern-menu-widget li ul',

			'#sidebar .widget_nav_menu a',
			'#sidebar .widget_nav_menu ul > li:first-child > a',
			'.widget_nav_menu_accordion .widget_nav_menu a',
			'.widget_nav_menu_accordion .widget_nav_menu ul > li:first-child > a',

			// Modules
			'.vcex-blog-entry-details',
			'.theme-button.minimal-border',
			'.vcex-login-form',
			'.vcex-recent-news-entry',
			'.vcex-toggle-group--bottom-borders .vcex-toggle',

		) );
	}

	/**
	 * Generates the CSS output.
	 */
	public static function generate() {

		// Get array to loop through
		$elements = self::elements();

		// Return if array is empty
		if ( empty( $elements ) ) {
			return;
		}

		// Get border color
		$color = get_theme_mod( 'main_border_color', '#eee' );

		// Check for theme mod and make sure it's not the same as the theme's default color
		if ( $color && ! in_array( $color, array( '#eee', '#eeeeee' ), true ) ) {

			// Define css var
			$css = '';

			// Borders
			$elements = implode( ',', $elements );
			$css .= $elements . '{border-color:' . $color . ';}';

			// Return CSS
			if ( $css ) {
				return '/*BORDER COLOR*/' . $css;
			}

		}

	}

	/**
	 * Live site output.
	 */
	public static function live_css( $output ) {
		if ( $css = self::generate() ) {
			$output .= $css;
		}
		return $output;
	}

	/**
	 * Customizer Output.
	 */
	public static function customizer_css() {
		echo '<style id="wpex-borders-css">' . self::generate() . '</style>';
	}

	/**
	 * Customizer Live JS.
	 */
	public static function customize_preview_init() {

		$elements = self::elements();

		if ( empty( $elements ) ) {
			return;
		}

		wp_enqueue_script( 'wpex-customizer-border-colors',
			wpex_asset_url( 'js/dynamic/customizer/wpex-border-colors.min.js' ),
			array( 'customize-preview' ),
			WPEX_THEME_VERSION,
			true
		);

		wp_localize_script(
			'wpex-customizer-border-colors',
			'wpexBorderColorElements',
			$elements
		);

	}

}