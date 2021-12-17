<?php
namespace TotalTheme;

defined( 'ABSPATH' ) || exit;

final class Initialize {

	/**
	 * Class instance.
	 *
	 * @access private
	 * @var object Class object.
	 */
	private static $instance;

	/**
	 * Create or retrieve the class instance.
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

		// Include functions and classes.
		$this->global_functionality();
		$this->admin_functionality();
		$this->public_functionality();

		// Run hooks on init.
		$this->init_hooks();

	}

	/**
	 * Global functionality.
	 */
	public function global_functionality() {

		/*** Always include ***/

		// Core.
		require_once WPEX_INC_DIR . 'updates/after-update.php';
		require_once WPEX_INC_DIR . 'functions/deprecated.php';
		require_once WPEX_INC_DIR . 'functions/theme-mods.php';
		require_once WPEX_INC_DIR . 'functions/core-functions.php';
		require_once WPEX_INC_DIR . 'functions/conditionals.php';
		require_once WPEX_INC_DIR . 'functions/svgs.php';
		require_once WPEX_INC_DIR . 'functions/css-utility.php';
		require_once WPEX_INC_DIR . 'functions/parsers.php';
		require_once WPEX_INC_DIR . 'functions/sanitization-functions.php';
		require_once WPEX_INC_DIR . 'functions/arrays.php';
		require_once WPEX_INC_DIR . 'functions/translations.php';
		require_once WPEX_INC_DIR . 'functions/template-parts.php';
		require_once WPEX_INC_DIR . 'functions/wp-fallbacks.php';
		require_once WPEX_INC_DIR . 'functions/post-types-branding.php';
		require_once WPEX_INC_DIR . 'functions/recommended-plugins.php';
		require_once WPEX_INC_DIR . 'functions/fonts.php';
		require_once WPEX_INC_DIR . 'functions/theme-icons.php';
		require_once WPEX_INC_DIR . 'functions/post-thumbnails.php';
		require_once WPEX_INC_DIR . 'functions/overlays.php';
		require_once WPEX_INC_DIR . 'functions/shape-dividers.php';
		require_once WPEX_INC_DIR . 'functions/blocks-single.php';
		require_once WPEX_INC_DIR . 'functions/blocks-entry.php';
		require_once WPEX_INC_DIR . 'functions/blocks-meta.php';
		require_once WPEX_INC_DIR . 'functions/aria-labels.php';
		require_once WPEX_INC_DIR . 'cards/card-functions.php';

		// Actions and filters.
		require_once WPEX_INC_DIR . 'functions/wp-actions/after-switch-theme.php';
		require_once WPEX_INC_DIR . 'functions/wp-actions/register-widget-areas.php';
		require_once WPEX_INC_DIR . 'functions/wp-filters/disable-wp-update-check.php';
		require_once WPEX_INC_DIR . 'functions/wp-filters/honor-ssl-for-attachements.php';
		require_once WPEX_INC_DIR . 'functions/wp-filters/allowed-kses-protocols.php';

		// Theme builder.
		require_once WPEX_INC_DIR . 'theme-builder/functions/theme-builder-functions.php'; // most load here.
		require_once WPEX_INC_DIR . 'theme-builder/theme-builder.php';

		/** Maybe include */

		// Header builder.
		if ( get_theme_mod( 'header_builder_enable', true ) ) {
			require_once WPEX_INC_DIR . 'theme-builder/header-builder.php';
		}

		// Footer builder.
		if ( get_theme_mod( 'footer_builder_enable', true ) ) {
			require_once WPEX_INC_DIR . 'theme-builder/footer-builder.php';
		}

		// Custom 404 Page.
		if ( get_theme_mod( 'custom_404_enable', true ) ) {
			require_once WPEX_INC_DIR . 'theme-builder/custom-404.php';
		}

		// Page animations.
		if ( get_theme_mod( 'page_animations_enable', true ) ) {
			Page_Animations::instance();
		}

		// WP Header Image support.
		if ( get_theme_mod( 'header_image_enable', false ) ) {
			WP_Custom_Header::instance();
		}

		// Custom accent colors.
		if ( get_theme_mod( 'accent_colors_enable', true ) ) {
			Accent_Colors::instance();
		}

		// Custom border colors.
		if ( get_theme_mod( 'border_colors_enable', true ) ) {
			Border_Colors::instance();
		}

		// Custom output for WP gallery.
		if ( wpex_custom_wp_gallery_supported() ) {
			Post_Gallery::instance();
		}

		// Under Construction.
		if ( get_theme_mod( 'under_construction_enable', true ) ) {
			Under_Construction::instance();
		}

		// Helper class for disabling Google services.
		if ( wpex_disable_google_services() ) {
			Disable_Google_Services::instance();
		}

		// Widget Block editor.
		Widgets\Block_Editor::instance();

		// Custom Favicons panel and output.
		if ( get_theme_mod( 'favicons_enable', true ) ) {
			Favicons::instance();
		}

		// Custom login page.
		if ( get_theme_mod( 'custom_admin_login_enable', true ) ) {
			Custom_Login::instance();
		}

		// Custom actions panel.
		if ( get_theme_mod( 'custom_actions_enable', true ) ) {
			Custom_Actions::instance();
		}

		// Helper class for removing cpt slugs.
		if ( get_theme_mod( 'remove_posttype_slugs', false ) ) {
			Remove_Cpt_Slugs::instance();
		}

		/** 3rd Party Integrations **/
		Integrations::instance();

		/* These Classes must Load last */

		// Image sizes panel and registration.
		if ( get_theme_mod( 'image_sizes_enable', true ) ) {
			Image_Sizes::instance();
		}

		// Customizer utility class and theme settings array.
		require_once WPEX_INC_DIR . 'customizer/class-wpex-customizer.php';

		// Typography Customizer panel, settings and front-end output.
		if ( get_theme_mod( 'typography_enable', true ) ) {
			Typography::instance();
		}

	}

	/**
	 * Admin functionality.
	 */
	public function admin_functionality() {

		// Main Theme Panel.
		Admin\Theme_Panel::instance();

		// Provide auto updates for the theme.
		if ( get_theme_mod( 'auto_updates', true ) ) {
			Updates\Theme_Updater::instance();
		}

		// Plugin update notifications.
		if ( apply_filters( 'wpex_has_bundled_plugin_update_notices', true ) ) {
			Updates\Plugin_Updater::instance();
		}

		// Post type editor class.
		if ( get_theme_mod( 'post_type_admin_settings', true ) ) {
			Admin\Cpt_Settings::instance();
		}

		// Import/Export panel.
		if ( get_theme_mod( 'import_export_enable', true ) ) {
			Admin\Import_Export::instance();
		}

		// Theme license panel.
		if ( apply_filters( 'wpex_show_license_panel', true ) ) {
			Admin\License_Panel::instance();
		}

		// Mce format styles.
		Editor\Mce_Formats::instance();

		// Accessibility Panel.
		if ( apply_filters( 'wpex_accessibility_panel', true ) ) {
			Accessibility\Admin_Panel::instance();
		}

		// Custom styles for the WP editor (classic & gutenberg).
		if ( get_theme_mod( 'editor_styles_enable', true ) ) {
			Editor\Editor_Styles::instance();
		}

		// Display Thumbnails in the WP admin posts list view.
		Admin\Dashboard_Thumbnails::instance();

		// Register & Enqueue scripts for use in the admin.
		Admin\Scripts::instance();

	}

	/**
	 * Public/Frontend functionality.
	 */
	public function public_functionality() {

		// Core functions.
		require_once WPEX_INC_DIR . 'functions/frontend/google-analytics.php';
		require_once WPEX_INC_DIR . 'functions/frontend/aria-landmark.php';
		require_once WPEX_INC_DIR . 'functions/frontend/layouts.php';
		require_once WPEX_INC_DIR . 'functions/frontend/breadcrumbs.php';
		require_once WPEX_INC_DIR . 'functions/frontend/wpex-the-content.php';
		require_once WPEX_INC_DIR . 'functions/frontend/js-localize-data.php';
		require_once WPEX_INC_DIR . 'functions/frontend/head-meta-tags.php';
		require_once WPEX_INC_DIR . 'functions/frontend/schema-markup.php';
		require_once WPEX_INC_DIR . 'functions/frontend/social-share.php';
		require_once WPEX_INC_DIR . 'functions/frontend/videos.php';
		require_once WPEX_INC_DIR . 'functions/frontend/audio.php';
		require_once WPEX_INC_DIR . 'functions/frontend/author.php';
		require_once WPEX_INC_DIR . 'functions/frontend/post-media.php';
		require_once WPEX_INC_DIR . 'functions/frontend/excerpts.php';
		require_once WPEX_INC_DIR . 'functions/frontend/togglebar.php';
		require_once WPEX_INC_DIR . 'functions/frontend/topbar.php';
		require_once WPEX_INC_DIR . 'functions/frontend/header.php';
		require_once WPEX_INC_DIR . 'functions/frontend/header-menu.php';
		require_once WPEX_INC_DIR . 'functions/frontend/title.php';
		require_once WPEX_INC_DIR . 'functions/frontend/post-slider.php';
		require_once WPEX_INC_DIR . 'functions/frontend/post-gallery.php';
		require_once WPEX_INC_DIR . 'functions/frontend/page-header.php';
		require_once WPEX_INC_DIR . 'functions/frontend/sidebar.php';
		require_once WPEX_INC_DIR . 'functions/frontend/footer-callout.php';
		require_once WPEX_INC_DIR . 'functions/frontend/footer.php';
		require_once WPEX_INC_DIR . 'functions/frontend/footer-bottom.php';
		require_once WPEX_INC_DIR . 'functions/frontend/pagination.php';
		require_once WPEX_INC_DIR . 'functions/frontend/grids.php';
		require_once WPEX_INC_DIR . 'functions/frontend/page.php';
		require_once WPEX_INC_DIR . 'functions/frontend/archives.php';
		require_once WPEX_INC_DIR . 'functions/frontend/loop.php';
		require_once WPEX_INC_DIR . 'functions/frontend/blog.php';
		require_once WPEX_INC_DIR . 'functions/frontend/portfolio.php';
		require_once WPEX_INC_DIR . 'functions/frontend/staff.php';
		require_once WPEX_INC_DIR . 'functions/frontend/testimonials.php';
		require_once WPEX_INC_DIR . 'functions/frontend/cpt.php';
		require_once WPEX_INC_DIR . 'functions/frontend/search.php';
		require_once WPEX_INC_DIR . 'functions/frontend/star-rating.php';
		require_once WPEX_INC_DIR . 'functions/frontend/user-social-links.php';
		require_once WPEX_INC_DIR . 'functions/frontend/post-format-icons.php';
		require_once WPEX_INC_DIR . 'functions/frontend/local-scroll.php';

		// Actions and filters.
		require_once WPEX_INC_DIR . 'functions/wp-actions/redirections.php';
		require_once WPEX_INC_DIR . 'functions/wp-actions/pre-get-posts.php';
		require_once WPEX_INC_DIR . 'functions/wp-actions/wp-enqueue-scripts.php';

		require_once WPEX_INC_DIR . 'functions/wp-filters/body-class.php';
		require_once WPEX_INC_DIR . 'functions/wp-filters/post-class.php';
		require_once WPEX_INC_DIR . 'functions/wp-filters/oembed.php';
		require_once WPEX_INC_DIR . 'functions/wp-filters/singular-pagination-fix.php';
		require_once WPEX_INC_DIR . 'functions/wp-filters/move-comment-form-fields.php';
		require_once WPEX_INC_DIR . 'functions/wp-filters/schema-author-posts-link.php';
		require_once WPEX_INC_DIR . 'functions/wp-filters/comments-link-scrollto-fix.php';
		require_once WPEX_INC_DIR . 'functions/wp-filters/next-previous-posts-exclude.php';
		require_once WPEX_INC_DIR . 'functions/wp-filters/custom-password-protection-form.php';
		require_once WPEX_INC_DIR . 'functions/wp-filters/widget-tag-cloud-args.php';
		require_once WPEX_INC_DIR . 'functions/wp-filters/widget-custom-classes.php';

		// Remove menu ID's for accessibility if enabled.
		if ( get_theme_mod( 'remove_menu_ids', false ) && apply_filters( 'wpex_accessibility_panel', true ) ) {
			require_once WPEX_INC_DIR . 'functions/wp-filters/accessibility-remove-menu-ids.php';
		}

		// Remove site emoji scripts.
		if ( get_theme_mod( 'remove_emoji_scripts_enable', true ) ) {
			require_once WPEX_INC_DIR . 'functions/wp-actions/remove-emoji-scripts.php';
		}

		// Add a span around the WordPress category widgets for easier styling.
		if ( apply_filters( 'wpex_widget_counter_span', true ) ) {
			require_once WPEX_INC_DIR . 'functions/wp-filters/widget-add-span-to-count.php';
		}

		// Enable post thumbnail format icons.
		if ( get_theme_mod( 'thumbnail_format_icons', false ) ) {
			Thumbnail_Format_Icons::instance();
		}

		// Load global fonts.
		Fonts\Global_Fonts::instance();

		// Custom site backgrounds.
		Site_Backgrounds::instance();

		// Theme breadcrumbs.
		require_once WPEX_INC_DIR . 'lib/wpex-breadcrumbs.php';

		// Outputs inline CSS for theme settings.
		Inline_CSS::instance();

		// Preload assets.
		Preload_Assets::instance();

		/*** Maybe include ***/

			// Advanced styles for various Customizer options.
			if ( apply_filters( 'wpex_generate_advanced_styles', true ) ) {
				Advanced_Styles::instance();
			}

	}

	/**
	 * Hook into actions and filters.
	 */
	public function init_hooks() {
		After_Setup_Theme::instance();
		add_action( 'after_setup_theme', __CLASS__ . '::hooks_actions', 10 );
		add_filter( 'woocommerce_create_pages', __CLASS__ . '::disable_woocommerce_create_pages' );
	}

	/**
	 * Defines all theme hooks and runs all needed actions for theme hooks.
	 */
	public static function hooks_actions() {

		// Register theme hooks (needed in backend for actions panel).
		require_once WPEX_INC_DIR . 'functions/hooks/hooks.php';

		// Add default theme actions.
		require_once WPEX_INC_DIR . 'functions/hooks/add-actions.php';

		// Remove actions and helper functions to remove all theme actions.
		require_once WPEX_INC_DIR . 'functions/hooks/remove-actions.php';

		// Functions used to return correct partial/template-parts.
		require_once WPEX_INC_DIR . 'functions/hooks/partials.php';

	}

	/**
	 * Prevent Woocommerce from installing pages on installation.
	 */
	public static function disable_woocommerce_create_pages( $pages ) {

		if ( defined( 'WC_INSTALLING' ) && true === WC_INSTALLING ) {
			return array();
		}

		return $pages;

	}

}