<?php
namespace TotalTheme;

defined( 'ABSPATH' ) || exit;

/**
 * Theme setup.
 *
 * @package TotalTheme
 * @version 5.3
 */
final class After_Setup_Theme {

	/**
	 * Instance.
	 *
	 * @access private
	 * @var object Class object.
	 */
	private static $instance;

	/**
	 * Create or retrieve the instance of our class.
	 */
	public static function instance() {
		if ( is_null( static::$instance ) ) {
			static::$instance = new self();
		}
		return static::$instance;
	}

	/**
	 * Get things started.
	 */
	public function __construct() {
		add_action( 'after_setup_theme', __CLASS__ . '::after_setup_theme', 10 );
	}

	/**
	 * Adds basic theme support functions and registers the nav menus.
	 */
	public static function after_setup_theme() {

		// Load text domain.
		load_theme_textdomain( 'total', WPEX_THEME_DIR . '/languages' );

		// Set the global content_width var.
		self::set_content_width_global_var();

		// Register theme support.
		self::add_theme_support();

		// Register menu areas.
		self::register_nav_menus();

		// Enable excerpts for pages.
		add_post_type_support( 'page', 'excerpt' );

	}

	/**
	 * Content width.
	 *
	 * @todo update to check customizer site width setting and to change based on the current page layout.
	 */
	public static function set_content_width_global_var() {
		global $content_width;
		if ( ! isset( $content_width ) ) {
			$content_width = 980;
		}
	}

	/**
	 * Register theme support.
	 */
	public static function add_theme_support() {

		add_theme_support( 'automatic-feed-links' );
		add_theme_support( 'post-thumbnails' );
		add_theme_support( 'title-tag' );
		add_theme_support( 'customize-selective-refresh-widgets' );
		add_theme_support( 'align-wide' );
		add_theme_support( 'responsive-embeds' );

		add_theme_support( 'post-formats', array(
			'video',
			'gallery',
			'audio',
			'quote',
			'link'
		) );

		add_theme_support( 'html5', array(
			'comment-list',
			'comment-form',
			'search-form',
			'gallery',
			'caption',
			'style',
			'script',
		) );

		// Add custom theme support for gutenberg editor.
		if ( ! class_exists( 'Classic_Editor' )
			&& ! ( WPEX_VC_ACTIVE && get_option( 'wpb_js_gutenberg_disable' ) )
		) {
			add_theme_support( 'gutenberg-editor' );
		}

		// Enable Custom Logo if the header customizer section isn't enabled.
		if ( ! wpex_has_customizer_panel( 'header' ) ) {
			add_theme_support( 'custom-logo' );
		}

	}

	/**
	 * Register menus.
	 */
	public static function register_nav_menus() {
		register_nav_menus( array(
			'topbar_menu'     => esc_html__( 'Top Bar', 'total' ),
			'main_menu'       => esc_html__( 'Main/Header', 'total' ),
			'mobile_menu_alt' => esc_html__( 'Mobile Menu Alternative', 'total' ),
			'mobile_menu'     => esc_html__( 'Mobile Icons', 'total' ),
			'footer_menu'     => esc_html__( 'Footer', 'total' ),
		) );
	}

}