<?php
defined( 'ABSPATH' ) || exit;

$output = '';

// Media
$output .= $this->get_media( array(
	'class' => 'wpex-mb-15',
) );

// Title
$output .= $this->get_title( array(
	'class' => 'wpex-heading wpex-text-xl wpex-font-medium',
) );

// Date
$output .= $this->get_date( array(
	'type' => 'published',
	'class' => 'wpex-mt-10 wpex-text-2xs wpex-uppercase wpex-font-medium wpex-text-gray-600 wpex-tracking-widest',
) );

return $output;