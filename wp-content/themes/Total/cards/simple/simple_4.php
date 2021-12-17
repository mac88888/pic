<?php
defined( 'ABSPATH' ) || exit;

$output = '';

// Primary Term
$output .= $this->get_primary_term( array(
	'class'      => 'wpex-mb-5',
	'term_class' => 'wpex-text-gray-500'
) );

// Title
$output .= $this->get_title( array(
	'class' => 'wpex-heading wpex-text-xl',
) );

return $output;