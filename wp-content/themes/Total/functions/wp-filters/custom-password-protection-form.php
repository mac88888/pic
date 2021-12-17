<?php
/**
 * Alters the default WordPress password protected form so it's easier to style
 *
 * @package TotalTheme
 * @version 5.0
 */

defined( 'ABSPATH' ) || exit;

function wpex_custom_password_protected_form() {
	ob_start();
	include( locate_template( 'partials/password-protection-form.php' ) );
	return ob_get_clean();
}
add_filter( 'the_password_form', 'wpex_custom_password_protected_form' );