<?php
defined( 'ABSPATH' ) || exit;

$output = '';

$output .= '<div class="wpex-card-inner wpex-flex wpex-flex-col wpex-flex-grow">';

	// Media
	$output .= $this->get_media( array(
		'class'       => 'wpex-mb-25 wpex-rounded',
		'image_class' => 'wpex-rounded',
	) );

	$output .= '<div class="wpex-card-details wpex-mb-25 wpex-last-mb-0">';

		// Terms
		$output .= $this->get_terms_list( array(
			'class' => 'wpex-mb-10 wpex-text-sm wpex-font-semibold',
			'separator' => ' &middot; ',
			'has_term_color' => true,
		) );

		// Title
		$output .= $this->get_title( array(
			'class' => 'wpex-heading wpex-text-xl wpex-mb-15',
			'link_class' => 'wpex-inherit-color-important',
		) );

		// Excerpt
		$output .= $this->get_excerpt( array(
			'class' => 'wpex-mb-25',
			'length' => 30,
		) );

	$output .= '</div>';

	$output .= '<div class="wpex-card-footer wpex-flex wpex-items-center">';

		// Avatar
		$output .= $this->get_avatar( array(
			'size'        => 40,
			'class'       => 'wpex-flex-shrink-0 wpex-mr-15',
			'image_class' => 'wpex-rounded-full wpex-align-middle',
		) );

		$output .= '<div class="wpex-card-meta wpex-flex-grow wpex-leading-snug wpex-text-sm">';

			// Author
			$output .= $this->get_author( array(
				'class' => 'wpex-text-gray-900 wpex-font-bold wpex-capitalize',
				'link'  => false,
			) );

			// Date
			$output .= $this->get_date( array(
				'type' => 'published',
			) );

		$output .= '</div>';

	$output .= '</div>';

$output .= '</div>';

return $output;