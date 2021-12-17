<?php
defined( 'ABSPATH' ) || exit;

$output = '';

$output .= '<div class="wpex-card-inner wpex-flex wpex-flex-col wpex-flex-grow wpex-bg-gray-100 wpex-text-gray-700 wpex-p-30">';

		// Thumbnail
		$output .= $this->get_thumbnail( array(
			'class' => 'wpex-text-center',
			'image_class' => 'wpex-rounded-full',
			'link' => false,
		) );

		// Excerpt.
		$output .= $this->get_excerpt( array(
			'length' => '-1',
			'class' => 'wpex-my-20',
		) );

		// Author
		$output .= $this->get_element( array(
			'content' => wpex_get_testimonial_author(),
			'class'   => 'wpex-card-testimonial-author wpex-text-black wpex-font-semibold wpex-text-md wpex-mt-auto',
		) );

		// Company
		$output .= $this->get_element( array(
			'content' => wpex_get_testimonial_company(),
			'class' => 'wpex-card-testimonial-company wpex-text-sm wpex-opacity-70',
		) );

$output .= '</div>';

return $output;