<?php
defined( 'ABSPATH' ) || exit;

$output = '';

$output .= '<div class="wpex-card-inner wpex-bg-white wpex-rounded-md wpex-border wpex-border-gray-200 wpex-border-solid">';

	// Details
	$output .= '<div class="wpex-card-details wpex-px-25 wpex-pt-25">';

		// Title
		$output .= $this->get_title( array(
			'class' => 'wpex-heading wpex-text-lg wpex-font-normal wpex-mb-15 wpex-text-accent',
			'link_class' => 'wpex-hover-underline',
		) );

		// Excerpt
		$output .= $this->get_excerpt( array(
			'class' => 'wpex-my-15'
		) );

	$output .= '</div>';

	// Permalink
	$output .= $this->get_element( array(
		'link' => get_permalink(),
		'content' => get_permalink(),
		'class' => 'wpex-card-permalink wpex-py-15 wpex-px-25 wpex-border-t wpex-border-gray-200 wpex-border-solid',
		'css' => 'color:#006627',
		'link_class' => 'wpex-inherit-color-important'
	) );

$output .= '</div>';

return $output;