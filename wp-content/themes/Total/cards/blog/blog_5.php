<?php
defined( 'ABSPATH' ) || exit;

$output = '';

// Media
$output .= $this->get_media( array(
	'class' => 'wpex-mb-15',
) );

// Title
$output .= $this->get_title( array(
	'class' => 'wpex-heading wpex-text-lg',
) );

return $output;