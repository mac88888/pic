<?php
defined( 'ABSPATH' ) || exit;

$output = '';

// "Tag"
$tagged = get_post_type( $this->post_id );

if ( 'post' === $tagged ) {
	$first_term = wpex_get_first_term_name();
	if ( $first_term ) {
		$tagged = $first_term;
	}
}

$output .= $this->get_element( array(
	'class' => 'wpex-card-tags wpex-text-xs wpex-opacity-60 wpex-uppercase wpex-tracking-wider wpex-font-medium',
	'content' => '<span>' . esc_html( $tagged ) . '</span>',
) );

// Title
$output .= $this->get_title( array(
	'class' => 'wpex-heading wpex-text-lg wpex-m-0',
) );

// Excerpt
$output .= $this->get_excerpt( array(
	'class' => 'wpex-mt-5'
) );

return $output;