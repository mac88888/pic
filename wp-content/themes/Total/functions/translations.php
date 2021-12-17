<?php
/**
 * Translation functions.
 *
 * @package TotalTheme
 * @subpackage Functions
 * @version 5.1.3
 */

defined( 'ABSPATH' ) || exit;

/**
 * Returns correct ID for any object.
 * Used to fix issues with translation plugins such as WPML & Polylang.
 *
 * @since 3.1.1
 */
function wpex_parse_obj_id( $id = '', $type = 'page', $key = '' ) {

	if ( ! $id ) {
		return;
	}

	// WPML Check.
	if ( WPEX_WPML_ACTIVE ) {

		// If you want to set type to term and key to category for example.
		$type = ( 'term' === $type && $key ) ? $key : $type;

		// Make sure to grab the correct type.
		// Fixes issues when using templatera for example for the topbar, header, footer, etc.
		if ( 'page' === $type ) {
			$type = get_post_type( $id );
		}

		// Get correct ID.
		$id = apply_filters( 'wpml_object_id', $id, $type, true );

	}

	// Polylang check.
	elseif ( function_exists( 'pll_get_post' ) ) {
		$type = taxonomy_exists( $type ) ? 'term' : $type; // Fixes issue where type may be set to 'category' instead of term.
		if ( 'page' === $type || 'post' === $type ) {
			$id = pll_get_post( $id );
		} elseif ( 'term' === $type && function_exists( 'pll_get_term' ) ) {
			$id = pll_get_term( $id );
		}
	}

	// Return ID.
	return $id;

}

/**
 * Retrives a theme mod value and translates it
 * Note :   Translated strings do not have any defaults in the Customizer
 *          Because they all have localized fallbacks.
 *
 * @since 3.3.0
 */
function wpex_get_translated_theme_mod( $id, $default = '' ) {
	return wpex_translate_theme_mod( $id, get_theme_mod( $id, $default ) );
}

/**
 * Provides translation support for plugins such as WPML for theme_mods
 *
 * @since 1.6.3
 */
function wpex_translate_theme_mod( $id = '', $val = '' ) {

	if ( $val && $id ) {

		// WPML.
		if ( function_exists( 'icl_t' ) ) {
			$val = icl_t( 'Theme Settings', $id, $val );
		}

		// Polylang.
		elseif ( function_exists( 'pll__' ) ) {
			$val = pll__( $val );
		}

		return $val;

	}

}

/**
 * Register theme mods for translations.
 *
 * @since 2.1.0
 */
function wpex_register_theme_mod_strings() {

	$strings = array(
		'custom_logo'                    => false,
		'retina_logo'                    => false,
		'fixed_header_logo'              => false,
		'logo_height'                    => false,
		'error_page_title'               => '404: Page Not Found',
		'error_page_text'                => false,
		'top_bar_content'                => '[topbar_item icon="phone" text="1-800-987-654" link="tel:1-800-987-654"][topbar_item icon="envelope" text="admin@totalwptheme.com" link="mailto:admin@totalwptheme.com"][topbar_item type="login" icon="user" icon_logged_in="sign-out" text="User Login" text_logged_in="Log Out" logout_text="Logout"]',
		'top_bar_social_alt'             => false,
		'header_aside'                   => false,
		'breadcrumbs_home_title'         => false,
		'blog_entry_readmore_text'       => 'Read More',
		'social_share_heading'           => 'Share This',
		'portfolio_related_title'        => 'Related Projects',
		'staff_related_title'            => 'Related Staff',
		'blog_related_title'             => 'Related Posts',
		'callout_text'                   => 'I am the footer call-to-action block, here you can add some relevant/important information about your company or product. I can be disabled in the Customizer.',
		'callout_link'                   => '#',
		'callout_link_txt'               => 'Get In Touch',
		'footer_copyright_text'          => 'Copyright <a href="#">Your Business LLC.</a> [current_year] - All Rights Reserved',
		'blog_single_header_custom_text' => 'Blog',
		'mobile_menu_toggle_text'        => 'Menu',
		'page_animation_loading'         => 'Loading&hellip;',
	);

	if ( WPEX_WOOCOMMERCE_ACTIVE ) {
		$strings['woo_shop_single_title']     = 'Store';
		$strings['woo_menu_icon_custom_link'] = '';
		$strings['woo_sale_flash_text']       = '';
	}

	return apply_filters( 'wpex_register_theme_mod_strings', $strings );
}

/**
 * Prevent issues with WPGlobus trying to translate certain theme settings.
 *
 * @since 2.1.0
 */
function wpex_modify_wpglobus_customize_disabled_setting_mask( $disabled_setting_mask ) {

	// Design settings.
	$disabled_setting_mask[] = '_bg';
	$disabled_setting_mask[] = '_background';
	$disabled_setting_mask[] = '_border';
	$disabled_setting_mask[] = '_padding';

	// Social settings.
	$social_options = wpex_topbar_social_options();
	if ( ! empty( $social_options ) && is_array( $social_options ) ) {
		foreach ( $social_options as $key => $value ) {
			$disabled_setting_mask[] = $key;
		}
	}

	// Return disabled mask array.
	return $disabled_setting_mask;

}
add_filter( 'wpglobus_customize_disabled_setting_mask', 'wpex_modify_wpglobus_customize_disabled_setting_mask' );