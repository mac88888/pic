<?php
defined( 'ABSPATH' ) || exit;

$output = '';

if ( empty( $this->args['breakpoint'] ) ) {
	$this->args['breakpoint'] = 'sm';
}

$bk = $this->get_breakpoint();

// Inner
$output .= '<div class="wpex-card-inner wpex-flex wpex-flex-col wpex-' . $bk . '-flex-row wpex-gap-20">';

	// Thumbnail
	$output .= $this->get_thumbnail( array(
		'class' => 'wpex-' . $bk . '-w-20 wpex-flex-shrink-0',
		'image_class' => 'wpex-w-100',
	) );

	// Details
	$output .= '<div class="wpex-card-details wpex-flex wpex-flex-col wpex-flex-grow">';

		// Category
		$output .= $this->get_primary_term( array(
			'class' => 'wpex-text-xs wpex-font-bold wpex-uppercase wpex-mb-5',
			'has_term_color' => true,
		) );

		// Title
		$output .= $this->get_title( array(
			'link'  => true,
			'class' => 'wpex-heading wpex-text-lg wpex-font-bold',
			'link_class' => 'wpex-inherit-color-important',
		) );

	$output .= '</div>';

$output .= '</div>';

return $output;