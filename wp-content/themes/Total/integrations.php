<?php
namespace TotalTheme;

defined( 'ABSPATH' ) || exit;

/**
 * 3rd Party Integrations.
 *
 * @package TotalTheme
 * @version 5.3
 */
final class Integrations {

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
	 * Construct.
	 */
	public function __construct() {

		// WPBakery integration.
		if ( WPEX_VC_ACTIVE && wpex_has_vc_mods() ) {
			Integration\WPBakery::instance();
		}

		// Templatera integration.
		if ( WPEX_TEMPLATERA_ACTIVE ) {
			Integration\Templatera::instance();
		}

		// WooCommerce integration.
		if ( WPEX_WOOCOMMERCE_ACTIVE ) {
			if ( wpex_has_woo_mods() && wpex_woo_version_supported() ) {
				Integration\WooCommerce\WooCommerce_Advanced::instance();
			} else {
				Integration\WooCommerce\WooCommerce_Vanilla::instance();
			}
		}

		// Elementor integration.
		if ( WPEX_ELEMENTOR_ACTIVE && apply_filters( 'wpex_elementor_support', true ) ) {
			Integration\Elementor::instance();
		}

		// Post Types Unlimited integration.
		if ( WPEX_PTU_ACTIVE ) {
			Integration\Post_Types_Unlimited::instance();
		}

		// Yoast SEO integration.
		if ( defined( 'WPSEO_VERSION' ) && apply_filters( 'wpex_yoastseo_support', true ) ) {
			Integration\Yoast_SEO::instance();
		}

		// The Events Calendar integration.
		if ( class_exists( 'Tribe__Events__Main' ) && apply_filters( 'wpex_tribe_events_support', true ) ) {
			Integration\Tribe_Events::instance();
		}

		// One-Click Demo importer integration.
		if ( class_exists( 'OCDI\OneClickDemoImport' ) && apply_filters( 'wpex_ocdi_support', true ) ) {
			Integration\One_Click_Demo_Import::instance();
		}

		// W3 Total cache integration.
		if ( defined( 'W3TC' ) && apply_filters( 'wpex_w3_total_cache_support', true ) ) {
			Integration\W3_Total_cache::instance();
		}

		// Real Media library integration.
		if ( defined( 'RML_VERSION' ) && apply_filters( 'wpex_realmedialibrary_support', true ) ) {
			new Integration\Real_Media_Library();
		}

		// WPML integration.
		if ( WPEX_WPML_ACTIVE && apply_filters( 'wpex_wpml_support', true ) ) {
			Integration\WPML::instance();
		}

		// Polylang integration.
		if ( class_exists( 'Polylang' ) && apply_filters( 'wpex_polylang_support', true ) ) {
			Integration\Polylang::instance();
		}

		// bbPress integration.
		if ( WPEX_BBPRESS_ACTIVE && apply_filters( 'wpex_bbpress_support', true ) ) {
			Integration\bbPress::instance();
		}

		// BuddyPress integration.
		if ( function_exists( 'buddypress' ) && apply_filters( 'wpex_buddypress_support', true ) ) {
			Integration\BuddyPress::instance();
		}

		// Contact form 7 integration.
		if ( defined( 'WPCF7_VERSION' ) && apply_filters( 'wpex_contactform7_support', true ) ) {
			Integration\Contact_Form_7::instance();
		}

		// Gravity form integration.
		if ( class_exists( 'RGForms' ) && apply_filters( 'wpex_gravityforms_support', true ) ) {
			Integration\Gravity_Forms::instance();
		}

		// JetPack integration.
		if ( class_exists( 'Jetpack' ) && apply_filters( 'wpex_jetpack_support', true ) ) {
			Integration\Jetpack::instance();
		}

		// Learndash integration.
		if ( defined( 'LEARNDASH_VERSION' ) && apply_filters( 'wpex_learndash_support', true ) ) {
			Integration\Learn_Dash::instance();
		}

		// Sensei plugin integration.
		if ( function_exists( 'Sensei' ) && apply_filters( 'wpex_sensei_support', true ) ) {
			Integration\Sensei::instance();
		}

		// Custom Post Type UI integration.
		if ( function_exists( 'cptui_init' ) && apply_filters( 'wpex_cptui_support', true ) ) {
			Integration\Custom_Post_Type_UI::instance();
		}

		// Massive Addons for WPBakery integration.
		if ( defined( 'MPC_MASSIVE_VERSION' ) && apply_filters( 'wpex_massive_addons_support', true ) ) {
			Integration\Massive_Addons_For_WPBakery::instance();
		}

		// TablePress integration.
		if ( class_exists( 'TablePress' ) && apply_filters( 'wpex_tablepress_support', true ) ) {
			Integration\TablePress::instance();
		}

		// Slider Revolution integration.
		if ( class_exists( 'RevSlider' ) && apply_filters( 'wpex_revslider_support', true ) ) {
			Integration\Revslider::instance();
		}

	}

}