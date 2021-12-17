<?php
namespace TotalTheme;

defined( 'ABSPATH' ) || exit;

/**
 * Disable Google Searvices.
 *
 * @package TotalTheme
 * @version 5.1.3
 */
final class Disable_Google_Services {

	/**
	 * Instance.
	 *
	 * @access private
	 * @var object Class object.
	 */
	private static $instance;

	/**
	 * Create or retrieve the instance of this class.
	 */
	public static function instance() {
		if ( is_null( static::$instance ) ) {
			static::$instance = new self();
		}
		return static::$instance;
	}

	/**
	 * Constructor.
	 */
	public function __construct() {

		// Remove Google Fonts from theme fonts array.
		add_filter( 'wpex_google_fonts_array', '__return_empty_array' );

		// Remove Google Fonts from WPBakery.
		add_filter( 'vc_google_fonts_render_filter', '__return_false' );
		add_filter( 'vc_google_fonts_get_fonts_filter', '__return_false' );

		// Remove scripts.
		add_action( 'wp_print_scripts', __CLASS__ . '::remove_scripts', 10 );

		// Remove inline scripts.
		add_action( 'wp_footer', __CLASS__ . '::remove_inline_scripts', 10 );

	}

	/**
	 * Remove scripts.
	 *
	 * @since 2.1.0
	 */
	public static function remove_scripts() {
		wp_dequeue_script( 'webfont' );
	}

	/**
	 * Remove footer scripts
	 *
	 * @since 2.1.0
	 */
	public static function remove_inline_scripts() {

		// Get global styles.
		global $wp_styles;

		// Loop through and remove VC fonts.
		if ( $wp_styles ) {
			foreach ( $wp_styles->registered as $handle => $data ) {
				if ( false !== strpos( $handle, 'vc_google_fonts_' ) ) {
					wp_deregister_style( $handle );
					wp_dequeue_style( $handle );
				}
			}
		}

	}

}