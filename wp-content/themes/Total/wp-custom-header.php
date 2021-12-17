<?php
namespace TotalTheme;

defined( 'ABSPATH' ) || exit;

/**
 * Adds support for the Custom Header image and adds it to the header.
 *
 * @package TotalTheme
 * @version 5.1.3
 */
final class WP_Custom_Header {

	/**
	 * Instance.
	 *
	 * @access private
	 * @var object Class object.
	 */
	private static $instance;

	/**
	 * Create or retrieve the instance of WP_Custom_Header.
	 */
	public static function instance() {
		if ( is_null( static::$instance ) ) {
			static::$instance = new self();
			static::$instance->init_hooks();
		}

		return static::$instance;
	}

	/**
	 * Hook into actions and filters.
	 */
	public function init_hooks() {
		add_filter( 'after_setup_theme', array( $this, 'add_support' ) );
		add_filter( 'wpex_head_css', array( $this, 'custom_header_css' ), 99 );
	}

	/**
	 * Retrieves cached CSS or generates the responsive CSS
	 *
	 * @since 1.6.0
	 */
	public function add_support() {
		add_theme_support( 'custom-header', apply_filters( 'wpex_custom_header_args', array(
			'default-image'          => '',
			'width'                  => 0,
			'height'                 => 0,
			'flex-width'             => true,
			'flex-height'            => true,
			'admin-head-callback'    => 'wpex_admin_header_style',
			'admin-preview-callback' => 'wpex_admin_header_image',
		) ) );
	}

	/**
	 * Displays header image as a background for the header
	 *
	 * @since 1.6.0
	 */
	public function custom_header_css( $output ) {
		if ( $header_image = get_header_image() ) {
			$output .= '#site-header,.is-sticky #site-header{background-image:url(' . esc_url( $header_image ) . ');background-size: cover;}';
		}
		return $output;
	}

}