<?php
defined( 'ABSPATH' ) || exit;

$output = '';

// Date
$output .= $this->get_date( array(
	'class' => 'wpex-mb-5 wpex-text-gray-600',
) );

// Title
$output .= $this->get_title( array(
	'class' => 'wpex-heading wpex-text-lg wpex-mb-15',
) );

// Excerpt
$output .= $this->get_excerpt();

return $output;