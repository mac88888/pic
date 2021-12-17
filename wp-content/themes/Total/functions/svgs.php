<?php
defined( 'ABSPATH' ) || exit;

/**
 * Display theme svg.
 *
 * @since 5.1.2
 *
 * @param string $svg The SVG we are going to display.
 * @param int $size The size to be used for the svg width and height.
 * @return html
 */
function wpex_svg( $svg = '', $size = 20 ) {
	echo wpex_get_svg( $svg, $size );
}

/**
 * Get theme svgs.
 *
 * @since 5.1.2
 *
 * @param string $svg The SVG we are going to display.
 * @param int $size The size to be used for the svg width and height.
 * @return html
 */
function wpex_get_svg( $svg = '', $size = 20 ) {

	if ( ! $svg ) {
		return;
	}

	switch ( $svg ) {
		case 'wp-spinner':
		case 'spinner':
			$svg = '<svg viewBox="0 0 36 36" xmlns="http://www.w3.org/2000/svg"><circle cx="18" cy="18" r="18" fill="#a2a2a2" fill-opacity=".5"/><circle cx="18" cy="8" r="4" fill="#fff"><animateTransform attributeName="transform" dur="1100ms" from="0 18 18" repeatCount="indefinite" to="360 18 18" type="rotate"/></circle></svg>';
			break;
		case 'total':
		case 'total-logo':
			$svg = '<svg viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg"><g clip-rule="evenodd" fill="currentColor" fill-rule="evenodd"><path d="m68.3 21.5 33.7-19.5 42.5 24.5 42.4 24.5v39z"/><path d="m17.2 120.7v-20.7-49l60.3 34.9z"/><path d="m186.9 149-42.4 24.5-42.5 24.5-42.4-24.5-15.8-9.2 84.8-49z"/></g></svg>';
			break;
		case 'close':
			$svg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M0 0h24v24H0z" fill="none"/><path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/></svg>';
			break;
		case 'wpbakery-logo':
		case 'wpbakery':
			$svg = '<svg viewBox="0.0004968540742993355 -0.00035214610397815704 65.50897979736328 49.80835723876953"  xmlns="http://www.w3.org/2000/svg" fill="currentColor"><path d="M51.345 9.041c-4.484-.359-6.371.747-8.509 1.039 2.169 1.135 5.125 2.099 8.708 1.89-3.3 1.296-8.657.853-12.355-1.406-.963-.589-1.975-1.519-2.733-2.262C33.459 5.583 31.247.401 21.683.018 9.687-.457.465 8.347.016 19.645s8.91 20.843 20.907 21.318c.158.008.316.006.472.006 3.137-.184 7.27-1.436 10.383-3.355-1.635 2.32-7.746 4.775-10.927 5.553.319 2.527 1.671 3.702 2.78 4.497 2.459 1.76 5.378-.73 12.11-.606 3.746.069 7.61 1.001 10.734 2.75l3.306-11.54c8.402.13 15.4-6.063 15.716-14.018.321-8.088-5.586-14.527-14.152-15.209h0z" fill-rule="evenodd"></path></svg>';
			break;
		default:
			break;
	}

	if ( $svg ) {

		$size_escaped = absint( $size );

		if ( $size_escaped ) {
			$svg = str_replace( '<svg', '<svg height="' . $size_escaped . '" width="' . $size_escaped . '"', $svg );
		}

	}

	return apply_filters( 'wpex_svg', $svg, $svg, $size );

}