<?php
namespace TotalTheme\Admin;

defined( 'ABSPATH' ) || exit;

/**
 * Total Theme Panel.
 *
 * @package TotalTheme
 * @subpackage Admin
 * @version 5.3
 */
final class Theme_Panel {

	/**
	 * Class instance.
	 *
	 * @access private
	 * @var object Class object.
	 */
	private static $instance;

	/**
	 * Create or retrieve the instance of Theme_Panel.
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
	 * @since 5.0
	 */
	public function init_hooks() {
		if ( is_admin() ) {
			add_action( 'admin_menu', array( $this, 'add_menu_page' ), 0 );
			add_action( 'admin_enqueue_scripts', array( $this, 'maybe_enqueue_scripts' ) );
			add_action( 'admin_menu', array( $this, 'add_menu_subpage' ) );
			add_action( 'admin_init', array( $this, 'register_settings' ) );
		}
	}

	/**
	 * Return settings.
	 *
	 * Can't be added in construct because translations won't work.
	 *
	 * @since 3.3.3
	 * @todo deprecate wpex_addons filter.
	 * @todo Remove 'custom_id' parameter and instead add id's to all of them.
	 */
	public function get_settings() {

		$is_wpbakery_enabled = (bool) wpex_is_wpbakery_enabled();
		$is_wpbakery_theme_mode_enabled = get_theme_mod( 'visual_composer_theme_mode', true );
		$is_woocommerce_enabled = (bool) wpex_is_woocommerce_active();
		$is_elementor_enabled = (bool) WPEX_ELEMENTOR_ACTIVE;

		$settings = array(
			'demo_importer' => array(
				'label' => esc_html__( 'Demo Importer', 'total' ),
				'icon' => 'dashicons dashicons-download',
				'category' => esc_html__( 'Core', 'total' ),
				'desc' => esc_html__( 'Enables a new admin screen at Theme Panel > Demo Importer so you can import one of the Total live demos. Demos are to be used as a "starter" for a new site and are best imported on a fresh WordPress installation.', 'total' ),
			),
			'under_construction' => array(
				'label' => esc_html__( 'Under Construction', 'total' ),
				'icon' => 'dashicons dashicons-hammer',
				'category' => esc_html__( 'Core', 'total' ),
				'desc' => esc_html__( 'Enables a new admin screen at Theme Panel > Under Construction where you can select a custom page and have your entire website redirect to this page for any visitor that is not logged in. Logged in users will be able to browse the site normally.', 'total' ),
			),
			'recommend_plugins' => array(
				'label' => esc_html__( 'Bundled/Recommended Plugins', 'total' ),
				'icon' => 'dashicons dashicons-admin-plugins',
				'category' => esc_html__( 'Core', 'total' ),
				'desc' => esc_html__( 'Enables a notice that displays a list of bundled or recommended plugins for the theme. It also adds an admin screen under Appearance > Install Plugins for installing and updating bundled plugins. This feature also provides updates to the bundled plugins whenever there is a theme update which includes an updated version of a bundled plugin.', 'total' ),
			),
			'theme_meta_generator' => array(
				'label' => esc_html__( 'Theme Meta Generator Tag', 'total' ),
				'icon' => 'dashicons dashicons-code-standards',
				'category' => esc_html__( 'Core', 'total' ),
				'desc' => esc_html__( 'Enables a meta=generator tag in the site header with the current Total theme version number which is used when seeking theme support so the developer knows what version of the theme your site is currently running.', 'total' ),
			),
			'extend_visual_composer' => array(
				'label' => esc_html__( 'Custom Builder Elements', 'total' ),
				'icon' => 'dashicons dashicons-admin-customizer',
				'custom_id' => true,
				'category' => 'WPBakery Builder',
				'desc' => esc_html__( 'Enables over 60 custom builder blocks exclusive to the Total theme for WPBakery. These modules are located inside the Total Theme Core plugin. By default the modules will load regardless of the WPBakery plugin allowing you to use the blocks as standard shortcodes if you are using a different builder such as Elementor.', 'total' ),
			),
			'schema_markup' => array(
				'label' => esc_html__( 'Schema Markup', 'total' ),
				'icon' => 'dashicons dashicons-feedback',
				'category' => esc_html__( 'SEO', 'total' ),
				'desc' => esc_html__( 'Enables basic html schema markup to the theme. This includes WebPage, WPHeader, SiteNavigationElement, WPSideBar and WPFooter.', 'total' ),
			),
			'minify_js' => array(
				'label' => esc_html__( 'Minify Javascript', 'total' ),
				'icon' => 'dashicons dashicons-performance',
				'category' => esc_html__( 'Optimizations', 'total' ),
				'desc' => esc_html__( 'When enabled the live site will load a minified and compressed version of it\'s javascript files. Disable this option for troubleshooting purposes.', 'total' ),
			),
			'custom_css' => array(
				'label' => esc_html__( 'Custom CSS', 'total' ),
				'icon' => 'dashicons dashicons-admin-appearance',
				'category' => esc_html__( 'Developers', 'total' ),
				'desc' => esc_html__( 'Enables the Custom CSS admin panel for making CSS customizations via the backend. This function hooks into the core WordPress custom CSS functionality so any CSS added here will also be available in the Customizer.', 'total' ),
			),
			'custom_actions' => array(
				'label' => esc_html__( 'Custom Actions', 'total' ),
				'icon' => 'dashicons dashicons-editor-code',
				'category' => esc_html__( 'Developers', 'total' ),
				'desc' => esc_html__( 'Enables a new admin screen at Theme Panel > Custom Actions where you can insert HTML/JS to any of the theme’s core action hooks. It’s a great way to add global content anywhere on your site. PHP input is not supported for security reasons. For more advanced modifications it is adviced to use a child theme.', 'total' ),
			),
			'favicons' => array(
				'label' => esc_html__( 'Favicons', 'total' ),
				'icon' => 'dashicons dashicons-nametag',
				'category' => esc_html__( 'Core', 'total' ),
				'desc' => esc_html__( 'Enables a new admin screen at Theme Panel > Favicons where you can set custom site icons for various devices. If you rather set a global site icon that gets automatically resized you can disable this function and instead go to Appearance > Customize > Site Identity and upload your custom icon under the Site Icon option.', 'total' ),
			),
			'portfolio' => array(
				'label' => wpex_get_portfolio_name(),
				'icon' => 'dashicons dashicons-' . wpex_get_portfolio_menu_icon(),
				'category' => esc_html__( 'Post Types', 'total' ),
			),
			'staff' => array(
				'label' => wpex_get_staff_name(),
				'icon' => 'dashicons dashicons-' . wpex_get_staff_menu_icon(),
				'category' => esc_html__( 'Post Types', 'total' ),
			),
			'testimonials' => array(
				'label' => wpex_get_testimonials_name(),
				'icon' => 'dashicons dashicons-' . wpex_get_testimonials_menu_icon(),
				'category' => esc_html__( 'Post Types', 'total' ),
			),
			'post_series' => array(
				'label' => esc_html__( 'Post Series', 'total' ),
				'icon' => 'dashicons dashicons-edit',
				'category' => esc_html__( 'Core', 'total' ),
				'desc' => esc_html__( 'Enables a new taxonomy named Post Series to your standard posts which allows you to "connect" different posts together as a series. For any post in a series, the front end will display links to all other posts in the series. This is commonly used for multipart tutorials.', 'total' ),
			),
			'header_builder' => array(
				'label' => esc_html__( 'Header Builder', 'total' ),
				'icon' => 'dashicons dashicons-editor-insertmore',
				'category' => esc_html__( 'Core', 'total' ),
				'desc' => esc_html__( 'Enables a new admin screen at Theme Panel > Header Builder where you can select any template to override the default theme header. This functionality is compatible with both WPBakery and Elementor if you want to create a page builder based header.', 'total' ),
			),
			'footer_builder' => array(
				'label' => esc_html__( 'Footer Builder', 'total' ),
				'icon' => 'dashicons dashicons-editor-insertmore',
				'category' => esc_html__( 'Core', 'total' ),
				'desc' => esc_html__( 'Enables a new admin screen at Theme Panel > Footer Builder where you can select any template to override the default theme footer. This functionality is compatible with both WPBakery and Elementor if you want to create a page builder based header.', 'total' ),
			),
			'custom_admin_login'  => array(
				'label' => esc_html__( 'Custom WP Login Screen', 'total' ),
				'icon' => 'dashicons dashicons-lock',
				'category' => esc_html__( 'Core', 'total' ),
				'desc' => esc_html__( 'Enables a new admin screen at Theme Panel > Custom Login where you tweak the default WordPress login screen such as uploading a custom logo and changin the default background and form colors.', 'total' ),
			),
			'custom_404' => array(
				'label' => esc_html__( 'Custom 404 Page', 'total' ),
				'icon' => 'dashicons dashicons-dismiss',
				'category' => esc_html__( 'Core', 'total' ),
				'desc' => esc_html__( 'Enables a new admin screen at Theme Panel > Custom 404 where you can redirect your 404 pages or select a custom template for your 404 page.', 'total' ),
			),
			'customizer_panel' => array(
				'label' => esc_html__( 'Customizer Manager', 'total' ),
				'icon' => 'dashicons dashicons-admin-settings',
				'category' => esc_html__( 'Optimizations', 'total' ),
				'desc' => esc_html__( 'Enables a new admin screen at Theme Panel > Customizer Manager where you can select what theme tabs are displayed in the Customizer. For example if you do not plan on using the theme Top Bar or Toggle Bar functionaility you can hide the settings completely in the Customizer to slim things down and speed things up.', 'total' ),
			),
			'custom_wp_gallery' => array(
				'label' => esc_html__( 'Custom WordPress Gallery', 'total' ),
				'icon' => 'dashicons dashicons-images-alt2',
				'category' => esc_html__( 'Core', 'total' ),
				'desc' => esc_html__( 'Enables a custom output for the default WordPress gallery that includes lightbox functionality and custom image cropping via the settings under Theme Panel > Image Sizes > Other. If you are using Elementor it is recommended that you disable this setting to prevent conflicts because the plugin also creates a custom output for galleries.', 'total' ),
				'condition' => ! $is_elementor_enabled,
			),
			'widget_areas' => array(
				'label' => esc_html__( 'Custom Widget Areas', 'total' ),
				'icon' => 'dashicons dashicons-welcome-widgets-menus',
				'category' => esc_html__( 'Widgets', 'total' ),
				'desc' => esc_html__( 'Enables a dashboard at Appearance > Widget Areas for creating and assigning custom widget areas.', 'total' ),
			),
			'custom_widgets' => array(
				'label' => esc_html__( 'Theme Widgets', 'total' ),
				'icon' => 'dashicons dashicons-list-view',
				'category' => esc_html__( 'Widgets', 'total' ),
				'desc' => esc_html__( 'Enables over 20 custom widgets exclusive to the Total theme.', 'total' ),
			),
			'widget_block_editor' => array(
				'label' => esc_html__( 'Widget Block Editor', 'total' ),
				'icon' => 'dashicons dashicons-list-view',
				'category' => esc_html__( 'Widgets', 'total' ),
				'desc' => esc_html__( 'Enables the widget block editor in WordPress 5.8+.', 'total' ),
			),
			'term_thumbnails' => array(
				'label' => esc_html__( 'Taxonomy Thumbnails', 'total' ),
				'icon' => 'dashicons dashicons-format-image',
				'category' => esc_html__( 'Taxonomies', 'total' ),
				'desc' => esc_html__( 'Enables the ability to define a thumbnail for your categories and custom taxonomies. This thumbnail is used on the front-end as the page header background and is also used in various elements such as the Categories Grid and Categories Carousel modules.', 'total' ),
			),
			'term_colors' => array(
				'label' => esc_html__( 'Category Colors', 'total' ),
				'icon' => 'dashicons dashicons-color-picker',
				'category' => esc_html__( 'Taxonomies', 'total' ),
				'desc' => esc_html__( 'Enables the ability to set custom colors for your categories which may be used by various theme elements and card styles. This functionality is enabled for categories only but can be extended to custom taxonomies via a filter.', 'total' ),
			),
			'editor_styles' => array(
				'label' => esc_html__( 'Editor Styles', 'total' ),
				'icon' => 'dashicons dashicons-editor-paste-word',
				'category' => esc_html__( 'Editor', 'total' ),
				'desc' => esc_html__( 'Loads custom styles when using the WordPress editor so that your fonts and certain styles match the live site.', 'total' ),
			),
			'editor_formats' => array(
				'label' => esc_html__( 'Editor Formats', 'total' ),
				'icon' => 'dashicons dashicons-editor-paste-word',
				'category' => esc_html__( 'Editor', 'total' ),
				'desc' => esc_html__( 'Enables the "Formats" button with some default and custom styles that you can use when adding text via the WordPress editor.', 'total' ),
			),
			'editor_shortcodes' => array(
				'label' => esc_html__( 'Editor Shortcodes', 'total' ),
				'icon' => 'dashicons dashicons-editor-paste-word',
				'category' => esc_html__( 'Editor', 'total' ),
				'desc' => esc_html__( 'Enables a new "Shortcodes" button in the WordPress editor with some exclusive theme shortcodes including line break, icon, current year, searchform, button, divider and spacing. You can easily add new shortcodes to this dropdown via a child theme.', 'total' ),
			),
			'remove_emoji_scripts' => array(
				'label' => esc_html__( 'Remove Emoji Scripts', 'total' ),
				'icon' => 'dashicons dashicons-smiley',
				'category' => esc_html__( 'Optimizations', 'total' ),
				'desc' => esc_html__( 'By default WordPress adds scripts to the front-end of your site on all pages to render emoji icons. For most websites this is unnecessary bloat and thus is disabled by default in Total.', 'total' ),
			),
			'image_sizes' => array(
				'label' => esc_html__( 'Image Sizes', 'total' ),
				'icon' => 'dashicons dashicons-image-crop',
				'category' => esc_html__( 'Core', 'total' ),
				'desc' => esc_html__( 'Enables a new admin screen at Theme Panel > Image Sizes where you can define custom cropping for the images on your site.', 'total' ),
			),
			'page_animations' => array(
				'label' => esc_html__( 'Page Animations', 'total' ),
				'icon' => 'dashicons dashicons-welcome-view-site',
				'category' => esc_html__( 'Core', 'total' ),
				'desc' => esc_html__( 'Enables a new tab under Appearance > Customize > General Theme Options > Page Animations where you can enable a loading icon when people visit your website as well define load in and load out animations for your site.', 'total' ),
			),
			'color_palette' => array(
				'label' => esc_html__( 'Color Palette', 'total' ),
				'icon' => 'dashicons dashicons-admin-customizer',
				'category' => esc_html__( 'Core', 'total' ),
				'desc' => esc_html__( 'Enables the Color Palette admin panel for registering your site\'s color palette.', 'total' ),
			),
			'font_manager' => array(
				'label' => esc_html__( 'Font Manager', 'total' ),
				'icon' => 'dashicons dashicons-editor-spellcheck',
				'category' => esc_html__( 'Fonts', 'total' ),
				'desc' => esc_html__( 'Enables the Font Manager admin panel for registering fonts for use on the site.', 'total' ),
			),
			'typography' => array(
				'label' => esc_html__( 'Typography Options', 'total' ),
				'icon' => 'dashicons dashicons-editor-bold',
				'category' => esc_html__( 'Fonts', 'total' ),
				'desc' => esc_html__( 'Enables a new tab at Appearance > Customize > Typography where you can define custom fonts and font styles for various parts of the site including the main body, menu, sidebar, footer, callout, topbar, etc.', 'total' ),
			),
			'edit_post_link' => array(
				'label' => esc_html__( 'Page Edit Links', 'total' ),
				'icon' => 'dashicons dashicons-admin-tools',
				'category' => esc_html__( 'Core', 'total' ),
				'desc' => esc_html__( 'Enables edit links at the bottom of your posts and pages so you can quickly access the backend or front-end editor while logged into your site.', 'total' ),
			),
			'import_export' => array(
				'label' => esc_html__( 'Import/Export Panel', 'total' ),
				'icon' => 'dashicons dashicons-admin-settings',
				'category' => esc_html__( 'Core', 'total' ),
				'desc' => esc_html__( 'Enables a new admin screen at Theme Panel > Import/Export where you can quickly export or import Customizer settings from one site to the other. This is useful when switching to a child theme or if you wanted to copy a backup of your settings before making changes in the Customizer (you can copy the settings and paste them into a text file on your computer).', 'total' ),
			),
			'section_templates' => array(
				'label' =>  esc_html__( 'WPBakery Section Templates', 'total' ),
				'icon' => 'wpbakery-logo',
				'condition' => $is_wpbakery_enabled,
				'category' => 'WPBakery Builder',
				'desc' => esc_html__( 'Enables the Section Templates tab in WPBakery when inserting templates.', 'total' ),
			),
			'wpbakery_design_options' => array(
				'label' =>  esc_html__( 'WPBakery Builder Design Options', 'total' ),
				'icon' => 'wpbakery-logo',
				'condition' => ( $is_wpbakery_enabled && $is_wpbakery_theme_mode_enabled ) ? true : false,
				'category' => 'WPBakery Builder',
				'disabled' => true,
				'desc' => esc_html__( 'Enables the "Design Options" tab in the WPBakery plugin settings which is disabled by default to prevent theme conflicts and keep the site slimmer.', 'total' ),
			),
			'visual_composer_theme_mode' => array(
				'label' =>  esc_html__( 'WPBakery Builder Theme Mode', 'total' ),
				'icon' => 'wpbakery-logo',
				'custom_id' => true,
				'condition' => $is_wpbakery_enabled,
				'category' => 'WPBakery Builder',
				'desc' => esc_html__( 'Enables "Theme Mode" for the WPBakery page builder plugin. This disables the License Tab under the WPbakery admin settings and hides some unnecessary notices and about pages.', 'total' ),
			),
			'woocommerce_integration' => array(
				'label' =>  esc_html__( 'WooCommerce Integration', 'total' ),
				'icon' => 'dashicons dashicons-cart',
				'custom_id' => true,
				'condition' => $is_woocommerce_enabled,
				'category' => 'WooCommerce',
				'desc' => esc_html__( 'Enables theme modifications for the WooCommerce plugin including custom styling and modifications. Disable for testing purposes or to use WooCommerce in it\'s native/vanilla form.', 'total' ),
			),
			'thumbnail_format_icons' => array(
				'label' => esc_html__( 'Thumbnail Post Format Icons', 'total' ),
				'icon' => 'dashicons dashicons-edit',
				'category' => esc_html__( 'Core', 'total' ),
				'disabled' => true,
				'custom_id' => true,
				'desc' => esc_html__( 'Enables the display of a format icon on the front-end. For example if you publish a blog post that is set as an "Image" post format, the theme will display a little image icon over the Thumbnail (featured image) on entries and in the related posts section.', 'total' ),
			),
			'header_image' => array(
				'label' => esc_html__( 'WP Header Image', 'total' ),
				'disabled' => true,
				'icon' => 'dashicons dashicons-format-image',
				'category' => esc_html__( 'Core', 'total' ),
				'desc' => esc_html__( 'Enables the WordPress core header image function under Appearance > Customize > Header Image which simply lets you set a custom background image for your theme header.', 'total' ),
			),
			'disable_gs' => array(
				'disabled' => true,
				'label' => esc_html__( 'Remove Google Fonts', 'total' ),
				'custom_id' => true,
				'icon' => 'dashicons dashicons-editor-strikethrough',
				'category' => esc_html__( 'Fonts', 'total' ),
				'desc' => esc_html__( 'Disables all Google font options from the Customizer Typography panel and Total builder modules. This feature is primarily for users in Countries where Google hosted fonts are not allowed such as China.', 'total' ),
			),
			'remove_posttype_slugs' => array(
				'disabled' => true,
				'label' => esc_html__( 'Remove Post Type Slugs', 'total' ),
				'desc' => esc_html__( 'Removes the slug from the Portfolio, Staff and Testimonial custom post types. For example instead of a portfolio post being at site.com/portfolio-item/post-1/ it would be located at site.com/post-1/. Slugs are used in WordPress by default to prevent conflicts, enabling this setting is not recommented in most cases.', 'total' ),
				'custom_id' => true,
				'icon' => 'dashicons dashicons-art',
				'category' => esc_html__( 'Post Types', 'total' ),
			),
		);

		/**
		 * Filter the theme panel setting
		 *
		 * @param array $settings
		 * @todo rename filter to something more useful like wpex_theme_panel_settings.
		 */
		return apply_filters( 'wpex_addons', $settings );
	}

	/**
	 * Registers a new menu page.
	 *
	 * @since 1.6.0
	 */
	public function add_menu_page() {
	  add_menu_page(
			esc_html__( 'Theme Panel', 'total' ),
			'Theme Panel', // menu title - can't be translated because it' used for the $hook prefix
			'manage_options',
			WPEX_THEME_PANEL_SLUG,
			'',
			'dashicons-admin-generic',
			null
		);
	}

	/**
	 * Enqueue scripts.
	 *
	 * @since 5.1.2
	 */
	public function maybe_enqueue_scripts( $hook ) {

		if ( false === strpos( $hook, WPEX_THEME_PANEL_SLUG ) || false !== strpos( $hook, WPEX_THEME_PANEL_SLUG . '-' ) ) {
			return;
		}

		$this->enqueue_scripts();

	}

	/**
	 * Enqueue scripts.
	 *
	 * @since 5.1.2
	 */
	public function enqueue_scripts() {
		wp_enqueue_style( 'wpex-admin-pages' );
		wp_enqueue_script( 'wpex-admin-pages' );
	}

	/**
	 * Registers a new submenu page.
	 *
	 * @since 1.6.0
	 */
	public function add_menu_subpage() {
		add_submenu_page(
			'wpex-general',
			esc_html__( 'General', 'total' ),
			esc_html__( 'General', 'total' ),
			'manage_options',
			WPEX_THEME_PANEL_SLUG,
			array( $this, 'create_admin_page' )
		);
	}

	/**
	 * Register a setting and its sanitization callback.
	 *
	 * @since 1.6.0
	 */
	public function register_settings() {
		register_setting(
			'wpex_theme_panel',
			'wpex_theme_panel',
			array(
				'sanitize_callback' => array( $this, 'save_options' ),
				'default' => null,
			)
		);
	}

	/**
	 * Main Sanitization callback.
	 *
	 * @since 1.6.0
	 */
	public function save_options( $options ) {

		if ( ! is_array( $options ) || empty( $options ) || ( false === $options ) ) {
			return;
		}

		// Get addons array.
		$panel_settings = $this->get_settings();

		// Add theme parts to checkboxes.
		foreach ( $panel_settings as $key => $val ) {

			// Get correct ID.
			$id = isset( $val['custom_id'] ) ? $key : $key . '_enable';

			// No need to save items that are enabled by default unless they have been disabled.
			$default = isset ( $val['disabled'] ) ? false : true;

			// If default is true.
			if ( $default ) {
				if ( ! isset( $options[$id] ) ) {
					set_theme_mod( $id, 0 ); // Disable option that is enabled by default
				} else {
					remove_theme_mod( $id ); // Make sure its not in the theme_mods since it's already enabled
				}
			}

			// If default is false.
			elseif ( ! $default ) {
				if ( isset( $options[$id] ) ) {
					set_theme_mod( $id, 1 ); // Enable option that is disabled by default
				} else {
					remove_theme_mod( $id ); // Remove theme mod because it's disabled by default
				}
			}

		} // end addon saves.

		// Save Branding.
		$value = isset( $options['theme_branding'] ) ? $options['theme_branding'] : '';
		if ( empty( $value ) ) {
			set_theme_mod( 'theme_branding', 'disabled' );
		} else {
			set_theme_mod( 'theme_branding', wp_strip_all_tags( $value ) );
		}

		// Save Google tracking.
		$value = isset( $options['google_property_id'] ) ? $options['google_property_id'] : '';
		if ( ! empty( $value ) ) {
			set_theme_mod( 'google_property_id', wp_strip_all_tags( $value ) );
		} else {
			remove_theme_mod( 'google_property_id' );
		}

		// Save reCAPTCHA keys (these are stored in the options table).
		$recaptcha_keys = array();

		if ( ! empty( $options[ 'recaptcha_site_key'] ) ) {
			$recaptcha_keys['site_key'] = wp_strip_all_tags( $options[ 'recaptcha_site_key'] );
		}

		if ( ! empty( $options[ 'recaptcha_secret_key'] ) ) {
			$recaptcha_keys['secret_key'] = wp_strip_all_tags( $options[ 'recaptcha_secret_key'] );
		}

		if ( $recaptcha_keys ) {
			update_option( 'wpex_recaptcha_keys', $recaptcha_keys, false );
		} else {
			delete_option( 'wpex_recaptcha_keys' );
		}

	}

	/**
	 * Return theme panel tabs navigation.
	 *
	 * @since 4.5
	 */
	public function panel_tabs() { ?>

		<h2 class="nav-tab-wrapper">

			<a href="<?php echo esc_url( admin_url( 'admin.php?page=wpex-panel' ) ); ?>" class="nav-tab nav-tab-active"><span class="dashicons dashicons-admin-settings" aria-hidden="true"></span><?php esc_html_e( 'Features', 'total' ); ?></a>

			<?php if ( apply_filters( 'wpex_show_license_panel', true ) ) {
				$license = wpex_get_theme_license();
				$icon = $license ? 'yes-alt' : 'warning'; ?>
				<a href="<?php echo esc_url( admin_url( 'admin.php?page=wpex-panel-theme-license' ) ); ?>" class="nav-tab wpex-theme-license"><span class="dashicons dashicons-<?php echo esc_attr( $icon ); ?>" aria-hidden="true"></span><?php esc_html_e( 'License', 'total' ); ?></a>
			<?php } ?>

			<?php if ( get_theme_mod( 'demo_importer_enable', true ) && class_exists( 'TotalThemeCore\Plugin' ) ) { ?>
				<a href="<?php echo esc_url( admin_url( 'admin.php?page=wpex-panel-demo-importer' ) ); ?>" class="nav-tab"><span class="dashicons dashicons-download" aria-hidden="true"></span><?php esc_html_e( 'Demo Import', 'total' ); ?></a>
			<?php } ?>

			<a href="<?php echo esc_url( admin_url( 'customize.php' ) ); ?>" class="nav-tab"><span class="dashicons dashicons-admin-appearance" aria-hidden="true"></span><?php esc_html_e( 'Customize', 'total' ); ?></a>

		</h2>

	<?php }

	/**
	 * Settings page output.
	 *
	 * @since 1.6.0
	 */
	public function create_admin_page() {

		delete_option( 'wpex_theme_panel' ); // remove possible bloat since we are saving options in theme_mods now.

		$this->enqueue_scripts(); // backup.

		?>

		<div class="wpex-theme-panel wpex-main-panel wpex-clr">

			<?php if ( get_option( 'active_theme_license_dev' ) ) { ?>
				<p></p>
				<div class="wpex-notice wpex-warning">
					<p><?php esc_html_e( 'Your site is currently active as a development environment.', 'total' ); ?></p>
				</div>
			<?php } ?>

			<div class="wrap wpex-theme-panel__wrap">

				<div class="wpex-theme-panel__about">

					<div class="wpex-theme-panel__about-text">

						<h1><?php esc_attr_e( 'Theme Options Panel', 'total' ); ?></h1>

						<p><?php echo sprintf( esc_html__( 'Here you can enable or disable various core features of the theme in order to keep the site optimized for your needs. Visit the %sCustomizer%s to access all theme settings.', 'total' ), '<a href="' . esc_url( admin_url( '/customize.php' ) ) . '">', '</a>' ); ?></p>

					</div><!-- .wpex-theme-panel__about-text -->

					<?php if ( apply_filters( 'wpex_panel_badge', true ) ) { ?>

						<a href="https://wpexplorer-themes.com/total/changelog/" target="_blank" rel="noopener noreferrer" title="<?php esc_html_e( 'Theme Changelog', 'total' ); ?>" class="wpex-theme-panel__badge">
								<div class="wpex-theme-panel__badge-svg"><?php echo wpex_get_svg( 'total-logo', 70 ); ?></div>
								<div class="wpex-theme-panel__badge-version"><?php echo esc_html__( 'Version', 'total' ) .' <span class="wpex-version">' . WPEX_THEME_VERSION . '</span>'; ?></div>
						</a>

					<?php } ?>

				</div><!-- .wpex-theme-panel__about -->

				<?php $this->panel_tabs(); ?>

				<div id="wpex-theme-panel-content">

					<ul class="wpex-theme-panel__sublinks wpex-theme-panel__sort">
						<li><strong><?php esc_html_e( 'Setting status:', 'total' ); ?></strong> &#124; </li>
						<li><a href="#" data-filter="status-all" class="wpex-theme-panel__sort-item"><?php esc_html_e( 'All', 'total' ); ?></a> &#124; </li>
						<li><a href="#" data-filter="enabled" class="wpex-theme-panel__sort-item"><?php esc_html_e( 'Enabled', 'total' ); ?></a> &#124; </li>
						<li><a href="#" data-filter="disabled" class="wpex-theme-panel__sort-item"><?php esc_html_e( 'Disabled', 'total' ); ?></a></li>
					</ul>

					<div class="wpex-theme-panel__savenag">
						<p><?php echo wp_kses_post( __( 'Don\'t forget to <a href="javascript:void(0)">save your changes</a>', 'total' ) ); ?></p>
					</div>

					<form method="post" action="options.php" class="wpex-theme-panel__form">
						<?php settings_fields( 'wpex_theme_panel' ); ?>
						<div class="wpex-theme-panel__form-grid">
							<div class="wpex-theme-panel__form-main"><?php $this->get_features(); ?></div>
							<div class="wpex-theme-panel__form-sidebar"><?php $this->get_widgets(); ?></div>
						</div>
						<?php submit_button(); ?>
					</form>

					</div>

			</div>

		</div>

	<?php
	}

	/**
	 * Admin features.
	 *
	 * @since 5.1.2
	 */
	public function get_features() {

		$panel_settings = $this->get_settings();

		?>

		<div class="wpex-theme-panel__settings">

			<?php
			// Loop through theme features.
			foreach ( $panel_settings as $key => $val ) :

				// Display setting?
				$display = true;

				if ( isset( $val['condition'] ) ) {
					$condition = $val['condition'];
					if ( is_bool( $condition ) ) {
						$display = $condition;
					} elseif ( function_exists( $condition ) ) {
						$display = wp_validate_boolean( call_user_func( $condition ) );
					}
				}

				// Sanitize vars.
				$default = isset ( $val['disabled'] ) ? false : true;
				$label = isset ( $val['label'] ) ? $val['label'] : '';
				$icon = isset ( $val['icon'] ) ? $val['icon'] : '';

				// Set id.
				if ( isset( $val['custom_id'] ) ) {
					$key = $key;
				} else {
					$key = $key . '_enable';
				}

				// Get theme option.
				$theme_mod = get_theme_mod( $key, $default );

				// Is enabled?
				$enabled = ! empty( $theme_mod ) ? true : false;

				// Get category and sanitize.
				$category = isset( $val['category'] ) ? $val['category'] : ' other';
				$category = strtolower( str_replace( ' ', '_', $category ) );

				// Sanitize category.
				$category = strtolower( str_replace( ' ', '_', $category ) );

				// Classes.
				$classes = 'wpex-theme-panel__setting';
				if ( false === $display ) {
					$classes .= ' wpex-theme-panel__setting--hidden';
				}

				$status = $enabled ? 'enabled' : 'disabled';
				?>

				<div id="<?php echo esc_attr( $key ); ?>" class="<?php echo esc_attr( $classes ); ?>" data-status="<?php echo esc_attr( $status ); ?>" data-category="<?php echo esc_attr( $category ); ?>">

					<div class="wpex-theme-panel__setting-heading">
						<div>
							<input type="checkbox" id="wpex_theme_panel[<?php echo esc_attr( $key ); ?>]" name="wpex_theme_panel[<?php echo esc_attr( $key ); ?>]" value="<?php echo esc_attr( $theme_mod ); ?>" <?php checked( $theme_mod, true ); ?> class="wpex-checkbox">

							<label for="wpex_theme_panel[<?php echo esc_attr( $key ); ?>]"><?php

								if ( $icon ) {
									$this->render_setting_icon( $icon );
									echo '<span>' . esc_html( $label ) . '</span>';
								} else {
									echo esc_html( $label );
								}
							?></label>
						</div>
						<?php if ( isset( $val['desc'] ) ) { ?>
							<button type="button" aria-expanded="false" class="wpex-theme-panel__setting-toggle" aria-label="<?php esc_html_e( 'Show setting description', 'total' ); ?>" role="button"><span aria-hidden="true"><svg width="24" height="24" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" role="img" aria-hidden="true" focusable="false"><path d="M17.5 11.6L12 16l-5.5-4.4.9-1.2L12 14l4.5-3.6 1 1.2z"></path></svg></span></button>
						<?php } ?>
					</div>

					<?php if ( isset( $val['desc'] ) ) { ?>
						<div class="wpex-theme-panel__setting-info"><?php
							echo wp_kses_post( $val['desc'] );
						?></div>
					<?php } ?>

				</div>

			<?php endforeach; ?>

		</div>

	<?php }

	/**
	 * Admin widgets.
	 *
	 * @since 5.1.2
	 */
	public function get_widgets() {

		$this->branding_widget();
		$this->sort_widget();
		$this->tracking_widget();
		$this->recaptcha_widget();

		if ( current_user_can( 'switch_themes' ) ) {
			$this->system_status_widget();
		}

	}

	/**
	 * Branding widget
	 *
	 * @since 5.1.2
	 */
	public function branding_widget() { ?>

		<div class="wpex-theme-panel__widget">
			<div class="wpex-theme-panel__widget-heading"><p><?php esc_html_e( 'Branding', 'total' ); ?></p></div>
			<div class="wpex-theme-panel__widget-inner">
				<p class="wpex-theme-panel__widget-desc"><?php esc_html_e( 'Used in widgets and builder blocks.', 'total' ); ?></p>
				<?php
				$value = get_theme_mod( 'theme_branding', 'Total' );
				$branding_disabled = ( 'disabled' === $value );
				$placeholder = $branding_disabled ? esc_attr__( 'Disabled', 'total' ) : 'Total';
				$value = ( $branding_disabled || empty( $value ) ) ? '' : wp_strip_all_tags( $value );
				?>
				<label class="screen-reader-text" for="wpex_theme_panel[theme_branding]"><?php esc_html_e( 'Branding', 'total' ); ?></label>
				<input id="wpex_theme_panel[theme_branding]" type="text" name="wpex_theme_panel[theme_branding]" value="<?php echo esc_attr( $value ); ?>" placeholder="<?php echo esc_attr( $placeholder ); ?>">
			</div>
		</div>

	<?php }

	/**
	 * Tracking widget
	 *
	 * @since 5.1.2
	 */
	public function tracking_widget() { ?>

		<div class="wpex-theme-panel__widget">
			<div class="wpex-theme-panel__widget-heading"><p><?php esc_html_e( 'Google Analytics ID', 'total' ); ?></p></div>
			<div class="wpex-theme-panel__widget-inner">
				<p class="wpex-theme-panel__widget-desc"><?php esc_html_e( 'Enter your Google analytics property ID so the theme can add the tracking code for you.', 'total' ); ?></p>
				<label class="screen-reader-text" for="wpex_theme_panel[google_property_id]"><?php esc_html_e( 'Google Analytics ID', 'total' ); ?></label>
				<input id="wpex_theme_panel[google_property_id]" type="text" name="wpex_theme_panel[google_property_id]" value="<?php echo esc_attr( get_theme_mod( 'google_property_id' ) ); ?>" placeholder="UA-XXXXX-Y or G-XXXXXXX" autocomplete="off">
			</div>
		</div>

	<?php }

	/**
	 * reCAPTCHA widget
	 *
	 * @since 5.1.2
	 */
	public function recaptcha_widget() {

		$site_key = wpex_get_recaptcha_keys( 'site' );
		$secret_key = wpex_get_recaptcha_keys( 'secret' );
		?>

		<div class="wpex-theme-panel__widget">
			<div class="wpex-theme-panel__widget-heading"><p><?php esc_html_e( 'reCAPTCHA Keys', 'total' ); ?></p></div>
			<div class="wpex-theme-panel__widget-inner">
				<p class="wpex-theme-panel__widget-desc"><?php echo sprintf( esc_html__( 'Enter your Google %sreCAPTCHA keys%s for use with theme elements such as the Contact Form.', 'total' ), '<a href="https://g.co/recaptcha/v3" target="_blank" rel="nofollow noopener noreferrer">', '</a>' ); ?></p>
				<p><label class="screen-reader-text" for="wpex_theme_panel[recaptcha_site_key]">Google reCAPTCHA <?php echo esc_attr__( 'Site Key', 'total' ); ?></label><input id="wpex_theme_panel[recaptcha_site_key]" type="text" name="wpex_theme_panel[recaptcha_site_key]" value="<?php echo esc_attr( $site_key ); ?>" placeholder="<?php echo esc_attr__( 'Site Key', 'total' ); ?>" autocomplete="off"></p>
				<p><label class="screen-reader-text" for="wpex_theme_panel[recaptcha_secret_key]">Google reCAPTCHA <?php echo esc_attr__( 'Secret Key', 'total' ); ?></label><input id="wpex_theme_panel[recaptcha_secret_key]" type="password" name="wpex_theme_panel[recaptcha_secret_key]" value="<?php echo esc_attr( $secret_key ); ?>" placeholder="<?php echo esc_attr__( 'Secret Key', 'total' ); ?>" autocomplete="off"></p>
			</div>
		</div>

	<?php }

	/**
	 * Sort widget
	 *
	 * @since 5.1.2
	 */
	public function sort_widget() { ?>

		<div class="wpex-theme-panel__widget">
			<div class="wpex-theme-panel__widget-heading"><p><?php esc_html_e( 'Sort Features', 'total' ); ?></p></div>

			<div class="wpex-theme-panel__widget-inner">

				<?php
				// Get panel settings.
				$panel_settings = $this->get_settings();

				// Categories.
				$categories = wp_list_pluck( $panel_settings, 'category' );
				$categories = array_unique( $categories );
				asort( $categories ); ?>

				<ul class="wpex-theme-panel__sort wpex-theme-panel__sort--cats">
					<li><a href="#" data-filter="all" class="wpex-theme-panel__sort-item wpex-theme-panel__sort-item--active"><?php esc_html_e( 'All', 'total' ); ?></a></li>
					<?php
					// Loop through cats.
					foreach ( $categories as $key => $category ) :

						// Check condition.
						$display = true;

						if ( isset( $panel_settings[$key]['condition'] ) ) {
							$condition = $panel_settings[$key]['condition'];
							if ( is_bool( $condition ) ) {
								$display = $condition;
							} elseif ( function_exists( $condition ) ) {
								$display = wp_validate_boolean( call_user_func( $condition ) );
							}
						}

						// Show cat.
						if ( $display ) {
							$sanitize_category = strtolower( str_replace( ' ', '_', $category ) ); ?>
							<li><a href="#" data-filter="<?php echo esc_attr( $sanitize_category ); ?>" class="wpex-theme-panel__sort-item"><?php echo wp_strip_all_tags( $category ); ?></a></li>
						<?php } ?>

					<?php endforeach; ?>
				</ul>

			</div>

		</div>

	<?php }

	/**
	 * System Status widget
	 *
	 * @since 5.1.2
	 */
	public function system_status_widget() { ?>

		<div class="wpex-theme-panel__widget">

			<div class="wpex-theme-panel__widget-heading"><p><?php esc_html_e( 'System Status', 'total' ); ?></p></div>

			<div class="wpex-theme-panel__widget-inner wpex-system-status">

				<?php
				$mem_limit = ini_get( 'memory_limit' );
				$mem_limit_bytes = wp_convert_hr_to_bytes( $mem_limit );
				$enough = $mem_limit_bytes < 268435456 ? false : true;
				$val_class = $enough ? 'wpex-good' : 'wpex-bad'; ?>
				<div class="wpex-system-status__item">
					<?php esc_html_e( 'Memory Limit', 'total' ); ?>
					<span class="wpex-system-status__val <?php echo esc_html( $val_class ); ?>"><?php echo esc_html( $mem_limit ); ?></span>
					<span class="wpex-system-status__rec"><?php esc_html_e( 'Recommended: 256M', 'total' ); ?></span>
				</div>

				<?php
				$max_execute = ini_get( 'max_execution_time' );
				$enough = $max_execute < 300 ? false : true;
				$val_class = $enough ? 'wpex-good' : 'wpex-bad'; ?>
				<div class="wpex-system-status__item">
					<?php esc_html_e( 'Max Execution Time', 'total' ); ?>
					<span class="wpex-system-status__val <?php echo esc_html( $val_class ); ?>"><?php echo esc_html( $max_execute ); ?></span>
					<br>
					<span class="wpex-system-status__rec"><?php esc_html_e( 'Recommended: 300', 'total' ); ?></span>
				</div>

				<?php
				$post_max_size = ini_get( 'post_max_size' );
				$post_max_size_byte = wp_convert_hr_to_bytes( $post_max_size );
				$enough = $post_max_size_byte < 33554432 ? false : true;
				$val_class = $enough ? 'wpex-good' : 'wpex-bad'; ?>
				<div class="wpex-system-status__item">
					<?php esc_html_e( 'Max Post Size', 'total' ); ?>
					<span class="wpex-system-status__val <?php echo esc_html( $val_class ); ?>"><?php echo esc_html( $post_max_size ); ?></span>
					<br>
					<span class="wpex-system-status__rec"><?php esc_html_e( 'Recommended: 32M', 'total' ); ?></span>
				</div>

				<?php
				$input_vars = ini_get( 'max_input_vars' );
				$enough = $input_vars < 1000 ? false : true;
				$val_class = $enough ? 'wpex-good' : 'wpex-bad'; ?>
				<div class="wpex-system-status__item wpex-system-status__item--last">
					<?php esc_html_e( 'Max Input Vars', 'total' ); ?>
					<span class="wpex-system-status__val <?php echo esc_html( $val_class ); ?>"><?php echo esc_html( $input_vars ); ?></span>
					<br>
					<span class="wpex-system-status__rec"><?php esc_html_e( 'Recommended: 1000', 'total' ); ?></span>
				</div>

				<div class="wpex-theme-panel__widget-footer"><a href="https://wordpress.org/about/requirements/" target="_blank"><?php esc_html_e( 'WordPress requirements', 'total' ); ?></a></div>

			</div>

		</div>

	<?php }

	/**
	 * Renders setting icon for display.
	 *
	 * @since 5.1.2
	 */
	public function render_setting_icon( $icon ) {
		if ( 0 === strpos( $icon, 'dashicons' ) ) {
			echo '<i class="' . esc_attr( $icon ) . '" aria-hidden="true"></i>';
		} else {
			$svg = wpex_get_svg( $icon, 20 );
			if ( $svg ) {
				echo wpex_sanitize_data( $svg, 'svg' );
			}
		}
	}

}