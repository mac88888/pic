<?php
defined( 'ABSPATH' ) || exit;

$output = '';

// Title
$output .= $this->get_title( array(
	'class' => 'wpex-heading wpex-text-2xl wpex-mb-10',
) );

// Date
$output .= $this->get_date( array(
    'class' => 'wpex-text-gray-700 wpex-text-sm wpex-uppercase wpex-tracking-wider',
) );

return $output;