<?php
defined( 'ABSPATH' ) || exit;

$output = '';

$output .= '<div class="wpex-card-inner wpex-flex wpex-flex-col wpex-flex-grow wpex-border wpex-border-gray-200 wpex-border-solid wpex-p-30 wpex-text-center">';

	// Icon
	$output .= $this->get_icon( array(
		'size'  => 'sm',
		'class' => 'wpex-text-accent wpex-mb-20',
	) );

	// Title
	$output .= $this->get_title( array(
		'class' => 'wpex-heading wpex-text-lg wpex-mb-15',
	) );

	// Excerpt
	$output .= $this->get_excerpt( array(
		'class' => 'wpex-mb-15',
	) );

	// More Link
	$output .= $this->get_more_link( array(
		'class'  => 'wpex-font-semibold wpex-mt-auto',
		'text'   => esc_html__( 'Learn more', 'total' ),
		'suffix' => ' &rarr;',
	) );

$output .= '</div>';

return $output;