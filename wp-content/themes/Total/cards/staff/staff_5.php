<?php
defined( 'ABSPATH' ) || exit;

$output = '';

// Inner
$output .= '<div class="wpex-card-inner wpex-flex wpex-flex-col wpex-flex-grow wpex-bg-white wpex-p-40 wpex-text-center wpex-rounded wpex-shadow">';

	// Thumbnail
	$output .= $this->get_thumbnail( array(
		'class' => 'wpex-mb-20 wpex-rounded-full',
		'image_class' => 'wpex-rounded-full'
	) );

	// Title
	$output .= $this->get_title( array(
		'class' => 'wpex-heading wpex-text-lg',
	) );

	// Email
	if ( ! empty( $this->post_id ) ) {
		$email = sanitize_email( get_post_meta( $this->post_id, 'wpex_staff_email', true ) );
		if ( $email ) {
			$output .= $this->get_element( array(
				'content' => $email,
				'link'    => 'mailto:' . $email,
				'class'   => 'wpex-card-staff-member-email wpex-text-gray-500 wpex-mt-5',
			) );
		}
	}

$output .= '</div>';

return $output;