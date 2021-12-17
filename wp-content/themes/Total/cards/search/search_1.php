<?php
defined( 'ABSPATH' ) || exit;

$output = '';

$has_link = $this->has_link();

if ( $has_link ) {
	$output .= $this->get_link_open( array(
		'class' => 'wpex-card-header wpex-no-underline',
	) );
} else {
	$output .= '<div class="wpex-card-header">';
}

	// Permalink
	$output .= $this->get_element( array(
		'content' => get_permalink(),
		'class' => 'wpex-card-permalink',
		'css' => 'color:#006627;',
	) );

	// Title.
	$title_class = 'wpex-font-normal wpex-text-lg wpex-m-0';
	if ( $has_link ) {
		$title_class .= ' wpex-inherit-color wpex-hover-underline';
	}
	$output .= $this->get_title( array(
		'class' => $title_class,
		'link' => false,
	) );

if ( $has_link ) {
	$output .= '</a>';
} else {
	$output .= '</div>';
}

// Excerpt.
$output .= $this->get_excerpt( array(
	'class' => 'wpex-mt-5'
) );

return $output;