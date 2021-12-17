<?php
namespace TotalTheme\Admin;

defined( 'ABSPATH' ) || exit;

/**
 * Register and Enqueue Admin scripts.
 *
 * @package TotalTheme
 * @version 5.2
 */
final class Scripts {

	/**
	 * Instance.
	 *
	 * @access private
	 * @var object Class object.
	 */
	private static $instance;

	/**
	 * Create or retrieve the instance of Scripts.
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
	 *
	 * @since 5.0.6
	 */
	public function init_hooks() {

		if ( is_admin() ) {
			add_action( 'admin_enqueue_scripts', array( $this, 'register_scripts' ), 5 );
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_ticons' ) );
		}

	}

	/**
	 * Register admin scripts.
	 *
	 * @since 5.1
	 */
	public function register_scripts() {

		// Theme Icons.
		wp_register_style(
			'ticons',
			wpex_asset_url( 'lib/ticons/css/ticons.min.css' ),
			array(),
			WPEX_THEME_VERSION
		);

		// Chosen select.
		wp_register_style(
			'wpex-chosen',
			wpex_asset_url( 'lib/chosen/chosen.min.css' ),
			false,
			'1.4.1'
		);

		wp_register_script(
			'wpex-chosen',
			wpex_asset_url( 'lib/chosen/chosen.jquery.min.js' ),
			array( 'jquery' ),
			'1.4.1'
		);

		// Chosen Icons.
		wp_register_script(
			'wpex-chosen-icon',
			wpex_asset_url( 'js/dynamic/admin/wpex-chosen-icon.min.js' ),
			array( 'jquery', 'wpex-chosen' ),
			WPEX_THEME_VERSION
		);

		// Theme Panel.
		wp_register_style(
			'wpex-admin-pages',
			wpex_asset_url( 'css/wpex-theme-panel.css' ),
			array(),
			WPEX_THEME_VERSION
		);

		wp_register_script(
			'wpex-admin-pages',
			wpex_asset_url( 'js/dynamic/admin/wpex-theme-panel.min.js' ),
			array( 'jquery' ),
			WPEX_THEME_VERSION,
			true
		);

	}

	/**
	 * Enqueue theme icons.
	 *
	 * @since 5.1
	 */
	public function enqueue_ticons( $hook ) {

		// Array of places to load font awesome.
		$hooks = array(
			'edit.php',
			'post.php',
			'post-new.php',
			'widgets.php',
		);

		// Only needed on these admin screens.
		if ( ! in_array( $hook, $hooks ) ) {
			return;
		}

		// Load font awesome script for VC icons and other.
		wp_enqueue_style( 'ticons' );

	}

}