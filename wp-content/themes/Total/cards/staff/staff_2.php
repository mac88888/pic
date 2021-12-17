<?php
defined( 'ABSPATH' ) || exit;

$output = '';

$output .= '<div class="wpex-card-inner wpex-flex wpex-flex-col wpex-flex-grow wpex-p-15 wpex-bg-white wpex-shadow-xs">';

	// Thumbnail
	$output .= $this->get_thumbnail( array(
		'class' => 'wpex-mb-15',
	) );

	// Title
	$output .= $this->get_title( array(
		'class' => 'wpex-heading wpex-text-md',
	) );

	// Position
	$output .= $this->get_element( array(
		'content' => wpex_get_staff_member_position(),
		'class'   => 'wpex-card-staff-member-position wpex-text-gray-500',
	) );

$output .= '</div>';

return $output;