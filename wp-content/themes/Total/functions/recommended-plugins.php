<?php
/**
 * Recommend/bundled plugins.
 *
 * @package TotalTheme
 * @subpackage Functions
 * @version 5.3
 */

defined( 'ABSPATH' ) || exit;

/**
 * Returns array of recommended plugins.
 *
 * @since 3.3.3
 */
function wpex_recommended_plugins() {
	return apply_filters( 'wpex_recommended_plugins', array(
		'total-theme-core'       => array(
			'name'               => 'Total Theme Core',
			'slug'               => 'total-theme-core',
			'version'            => WPEX_THEME_CORE_PLUGIN_SUPPORTED_VERSION,
			'source'             => 'https://totalwptheme.s3.us-east-1.amazonaws.com/plugins/total-theme-core/version-1-3-1/total-theme-core.zip',
			'required'           => true,
			'force_activation'   => false,
		),
		'js_composer'          => array(
			'name'             => 'WPBakery Page Builder',
			'slug'             => 'js_composer',
			'version'          => WPEX_VC_SUPPORTED_VERSION,
			'source'           => 'https://totalwptheme.s3.us-east-1.amazonaws.com/plugins/wpbakery/version-6-7-0/js_composer.zip',
			'required'         => false,
			'force_activation' => false,
		),
		'templatera'           => array(
			'name'             => 'Templatera',
			'slug'             => 'templatera',
			'source'           => 'https://totalwptheme.s3.us-east-1.amazonaws.com/plugins/templatera/version-2-0-4/templatera.zip',
			'version'          => '2.0.4',
			'required'         => false,
			'force_activation' => false,
		),
		'revslider'            => array(
			'name'             => 'Slider Revolution',
			'slug'             => 'revslider',
			'version'          => '6.5.9',
			'source'           => 'https://totalwptheme.s3.us-east-1.amazonaws.com/plugins/revslider/version-6-5-9/revslider.zip',
			'required'         => false,
			'force_activation' => false,
		),
	) );
}

/**
 * Register recommended plugins with the tgmpa script.
 *
 * @since 5.0
 */
if ( is_admin() && get_theme_mod( 'recommend_plugins_enable', true ) ) {

	if ( ! class_exists( 'TGM_Plugin_Activation' ) ) {
		require_once WPEX_INC_DIR . 'lib/tgmpa/class-tgm-plugin-activation.php';
	}

	function wpex_tgmpa_register() {

		$plugins = wpex_recommended_plugins();

		$dismissable = true;

		if ( WPEX_VC_ACTIVE ) {
			if ( wpex_vc_theme_mode_check() ) {
				$dismissable = wpex_vc_is_supported() ? true : false;
			} else {
				unset( $plugins['js_composer'] );
			}
		}

		tgmpa( $plugins, array(
			'id'           => 'wpex_theme',
			'domain'       => 'total',
			'menu'         => 'install-required-plugins',
			'has_notices'  => true,
			'is_automatic' => true, // auto activation on installation/updating.
			'dismissable'  => $dismissable,
		) );

	}

	add_action( 'tgmpa_register', 'wpex_tgmpa_register' );

}