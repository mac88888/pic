<?php
/**
 * Redirect single posts if redirect custom field is being used.
 *
 * @package TotalTheme
 * @version 5.1.3
 */

defined( 'ABSPATH' ) || exit;

function wpex_post_redirect() {

	if ( wpex_vc_is_inline() ) {
		return; // never redirect while editing a page
	}

	$redirect = '';

	// Redirect singular posts.
	if ( is_singular() ) {
		if ( 'link' === get_post_format() && ! apply_filters( 'wpex_redirect_link_format_posts', false ) ) {
			$redirect = '';
		} else {
			$redirect = wpex_get_custom_permalink();
		}
	}

	// Terms.
	elseif ( is_tax() || is_category() || is_tag() ) {
		$redirect = get_term_meta( get_queried_object_id(), 'wpex_redirect', true );
	}

	// No redirection.
	if ( ! $redirect ) {
		return;
	}

	// Redirect code.
	$redirect_status = apply_filters( 'wpex_redirect_status_code', 301, $redirect );

	// If redirect url is a number get the permalink and perform a safe redirect.
	if ( is_numeric( $redirect ) ) {
		$redirect = get_permalink( $redirect );
		if ( $redirect ) {
			wp_safe_redirect( esc_url( $redirect ), $redirect_status );
			exit;
		}
		return;
	}

	// Redirect.
	if ( $redirect ) {
		wp_redirect( esc_url( $redirect ), $redirect_status );
		exit;
	}

}

add_action( 'template_redirect', 'wpex_post_redirect' );