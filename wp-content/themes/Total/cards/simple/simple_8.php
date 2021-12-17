<?php
defined( 'ABSPATH' ) || exit;

$output = '';

// Date
$output .= '<div class="wpex-card-meta wpex-flex wpex-items-center wpex-gap-15 wpex-flex-wrap wpex-mb-10">';

	$output .= $this->get_date( array(
	    'class' => 'wpex-uppercase wpex-tracking-wider',
	) );

	$output .= '<span class="wpex-block wpex-flex-grow wpex-border-b wpex-border-solid wpex-border-gray-300"></span>';

$output .= '</div>';

// Title
$output .= $this->get_title( array(
	'class' => 'wpex-heading wpex-text-xl wpex-font-medium wpex-mb-10',
) );

return $output;