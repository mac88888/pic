<?php
/**
 * Run functions after theme switch.
 *
 * @package TotalTheme
 * @version 5.1
 */

defined( 'ABSPATH' ) || exit;

function wpex_after_switch_theme() {

	// Flush rewrite rules.
	flush_rewrite_rules();

	// Delete tgma plugin activation script user meta data to make sure notices display correctly.
	delete_metadata( 'user', null, 'tgmpa_dismissed_notice_wpex_theme', null, true );

}
add_action( 'after_switch_theme', 'wpex_after_switch_theme' );