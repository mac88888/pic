<?php
defined( 'ABSPATH' ) || exit;

$output = '';

$output .= '<div class="wpex-card-inner wpex-flex wpex-flex-col wpex-flex-grow">';

	// Number
	$output .= $this->get_number( array(
		'class' => 'wpex-text-accent wpex-font-bold wpex-leading-none wpex-mb-15',
		'css' => 'font-size:6em;',
	) );

	// Title
	$output .= $this->get_title( array(
		'class' => 'wpex-heading wpex-text-xl',
	) );

	// Divider.
	$output .= $this->get_empty_element( array(
		'html_tag' => 'span',
		'class'    => 'wpex-card-divider wpex-inline-block wpex-bg-accent wpex-my-10',
		'css'      => 'width:30px;height:2px;',
	) );

	// Excerpt
	$output .= $this->get_excerpt( array(
		'class' => 'wpex-mb-15 wpex-text-gray-600',
	) );

	// More Link
	$output .= $this->get_more_link( array(
		'class' => 'wpex-font-semibold',
		'text' => esc_html__( 'Learn more', 'total' ),
		'suffix' => ' &rarr;',
	) );

$output .= '</div>';

return $output;