<?php
defined( 'ABSPATH' ) || exit;

$output = '';

// Rating.
$output .= $this->get_star_rating( array(
	'class' => 'wpex-text-md wpex-text-accent',
) );

// Title.
$output .= $this->get_title( array(
	'link' => false,
	'class'  => 'wpex-heading wpex-text-md wpex-font-semibold wpex-my-5',
) );

// Excerpt.
$output .= $this->get_excerpt( array(
	'length' => '-1',
	'class'  => 'wpex-italic',
) );

return $output;