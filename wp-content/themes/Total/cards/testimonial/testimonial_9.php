<?php
defined( 'ABSPATH' ) || exit;

$output = '';

$bk = $this->get_breakpoint();

$output .= '<div class="wpex-card-inner wpex-flex wpex-flex-col wpex-flex-grow wpex-' . $bk . '-flex-row wpex-gap-50">';

	$output .= '<div class="wpex-card-details wpex-flex wpex-flex-col wpex-' . $bk . '-justify-between wpex-' . $bk . '-w-50 wpex-gap-15">';

		$output .= '<div class="wpex-card-meta">';

			// Author.
			$output .= $this->get_element( array(
				'content' => wpex_get_testimonial_author(),
				'class' => 'wpex-card-testimonial-author wpex-font-semibold',
			) );

		$output .= '</div>';

		// Excerpt.
		$output .= $this->get_excerpt( array(
			'length' => '-1',
			'class' => 'wpex-text-black wpex-font-semibold wpex-text-2xl',
		) );

		// Company
		$output .= $this->get_element( array(
			'content' => wpex_get_testimonial_company(),
			'class' => 'wpex-card-testimonial-company wpex-text-sm wpex-opacity-70',
		) );

	$output .= '</div>';

	// Thumbnail
	$output .= $this->get_thumbnail( array(
		'class' => 'wpex-' . $bk . '-w-50',
		'link' => false,
	) );

$output .= '</div>';

return $output;