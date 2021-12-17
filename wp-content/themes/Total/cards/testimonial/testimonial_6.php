<?php
defined( 'ABSPATH' ) || exit;

$output = '';

$output .= '<div class="wpex-card-inner wpex-flex-col wpex-flex-grow wpex-bg-white wpex-border wpex-border wpex-border-gray-200 wpex-border-solid wpex-rounded wpex-mt-50 wpex-px-25 wpex-pb-25 wpex-text-center">';

	// Thumbnail.
	$output .= $this->get_thumbnail( array(
		'link' => false,
		'class' => '',
		'image_class' => 'wpex-bg-white wpex-rounded-full wpex-p-5 wpex-border wpex-border wpex-border-gray-200 wpex-border-solid',
	) );

	// Excerpt.
	$output .= $this->get_excerpt( array(
		'length' => '-1',
		'class'  => 'wpex-italic wpex-mt-15',
	) );

	// Author.
	$author = wpex_get_testimonial_author();
	if ( $author ) {
		$company = wpex_get_testimonial_company();
		if ( $company ) {
			$author = $author . ' - '. $company;
		}
		$output .= $this->get_element( array(
			'content'  => $author,
			'class'    => 'wpex-card-testimonial-author wpex-mt-10 wpex-heading wpex-text-sm wpex-text-accent wpex-font-bold',
		) );
	}

	// Rating.
	$output .= $this->get_star_rating( array(
		'class' => 'wpex-mt-5 wpex-text-md',
	) );

$output .= '</div>';

return $output;