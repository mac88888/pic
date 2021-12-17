<?php
defined( 'ABSPATH' ) || exit;

$output = '';

$output .= '<div class="wpex-card-inner wpex-border-2 wpex-border-solid wpex-border-gray-200">';

	// Media
	$output .= $this->get_media( array(
		'image_class' => 'wpex-w-100',
	) );

	$output .= '<div class="wpex-card-details wpex-p-20">';

		// Title
		$output .= $this->get_title( array(
			'class' => 'wpex-heading wpex-text-md',
		) );

		// Excerpt
		$output .= $this->get_excerpt( array(
			'class' => 'wpex-mt-10',
		) );

	$output .= '</div>';

$output .= '</div>';

return $output;