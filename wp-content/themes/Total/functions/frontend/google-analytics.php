<?php
/**
 * Tracking functions.
 *
 * @package TotalTheme
 * @subpackage Functions
 * @version 5.1.2
 */

defined( 'ABSPATH' ) || exit;

/**
 * Returns Google Analytics tracking code.
 *
 * @since 5.0
 */
function wpex_google_analytics_tag() {

	$property_id = apply_filters( 'wpex_google_property_id', get_theme_mod( 'google_property_id' ) );

	if ( empty( $property_id ) ) {
		return;
	}

	// Old Ga format.
	if ( 0 === strpos( $property_id, 'UA-' ) ) {

		$validate_id = (bool) preg_match( '/^ua-\d{4,9}-\d{1,4}$/i', strval( $property_id ) );

		if ( $validate_id ) {

			echo "<!-- Google Analytics -->";
			echo "<script>";
				echo "window.ga=window.ga||function(){(ga.q=ga.q||[]).push(arguments)};ga.l=+new Date;";
				echo "ga('create', '" . wp_strip_all_tags( $property_id ) . "', 'auto');";
				echo "ga('send', 'pageview');";
				echo "ga('set', 'anonymizeIp', true);";
			echo "</script>";
			echo "<script async src='https://www.google-analytics.com/analytics.js'></script>";
			echo "<!-- End Google Analytics -->";

		}

		return;

	}

	// Newer ga4 format.
	if ( 0 === strpos( $property_id, 'G-' ) ) {

		echo "<!-- Global site tag (gtag.js) - Google Analytics -->";
		echo "<script async src=\"https://www.googletagmanager.com/gtag/js?id=" . wp_strip_all_tags( $property_id ) . "\"></script>";
		echo "<script>";
			echo "window.dataLayer = window.dataLayer || [];";
			echo "function gtag(){dataLayer.push(arguments);}";
			echo "gtag('js', new Date());";

			echo "gtag('config', '" . wp_strip_all_tags( $property_id ) . "', { 'anonymize_ip': true });";
		echo "</script>";

		return;

	}

}