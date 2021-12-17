<?php
defined( 'ABSPATH' ) || exit;

$output = '';

$output .= '<div class="wpex-card-inner wpex-flex wpex-flex-col wpex-flex-grow wpex-p-30 wpex-bg-white wpex-rounded-sm wpex-shadow-lg">';

	// Rating
	$output .= $this->get_star_rating( array(
		'class' => 'wpex-mb-15',
	) );

	// Excerpt
	$output .= $this->get_excerpt( array(
		'class'  => 'wpex-mb-20',
		'length' => '-1',
	) );

	$output .= '<div class="wpex-card-footer wpex-mt-auto wpex-flex wpex-items-center">';

		// Thumbnail
		$output .= $this->get_thumbnail( array(
			'link'        => false,
			'class'       => 'wpex-shrink-0 wpex-rounded-full wpex-mr-15',
			'image_class' => 'wpex-card-thumbnail-sm wpex-rounded-full',
		) );

		$output .= '<div class="wpex-card-footer-aside wpex-flex-grow">';

			// Author
			$output .= $this->get_element( array(
				'content' => wpex_get_testimonial_author(),
				'class'   => 'wpex-card-testimonial-author wpex-heading wpex-text-md',
			) );

			// Company
			$output .= $this->get_element( array(
				'content'     => wpex_get_testimonial_company(),
				'link'        => wpex_get_testimonial_company_url(),
				'link_target' => wpex_get_testimonial_company_link_target(),
				'class'       => 'wpex-card-testimonial-company wpex-text-gray-600 wpex-child-inherit-color',
			) );

		$output .= '</div>';

	$output .= '</div>';

$output .= '</div>';

return $output;