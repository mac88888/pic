<?php
defined( 'ABSPATH' ) || exit;

$output = '';

$output .= '<div class="wpex-card-inner wpex-flex wpex-flex-col wpex-flex-grow wpex-p-30 wpex-bg-white wpex-shadow-xl wpex-last-mb-0">';

	// Number
	$output .= $this->get_number( array(
		'class' => 'wpex-text-gray-900 wpex-text-6xl wpex-font-light',
		'prepend_zero' => true,
	) );

	// Title
	$output .= $this->get_title( array(
		'class' => 'wpex-heading wpex-text-lg wpex-mb-10',
	) );

	// Excerpt
	$output .= $this->get_excerpt( array(
		'class' => 'wpex-mb-15 wpex-text-gray-600',
	) );

	// More Link
	$output .= $this->get_more_link( array(
		'class'  => 'wpex-mt-auto wpex-font-semibold',
		'text'   => esc_html__( 'Learn more', 'total' ),
		'suffix' => ' &rarr;',
	) );

$output .= '</div>';

return $output;