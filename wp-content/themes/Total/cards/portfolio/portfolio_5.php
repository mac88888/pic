<?php
defined( 'ABSPATH' ) || exit;

$output = '';

// Media
$output .= $this->get_media( array(
	'class' => 'wpex-mb-20',
) );

// Title
$output .= $this->get_title( array(
	'class' => 'wpex-heading wpex-text-md',
) );

// Excerpt
$output .= $this->get_excerpt( array(
	'class' => 'wpex-mt-10',
) );

return $output;