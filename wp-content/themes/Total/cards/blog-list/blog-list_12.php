<?php
defined( 'ABSPATH' ) || exit;

$output = '';

$bk = $this->get_breakpoint();

$output .= '<div class="wpex-card-inner wpex-flex wpex-flex-col wpex-flex-grow wpex-' . $bk . '-flex-row wpex-bg-white wpex-shadow wpex-rounded-sm">';

	// Media
	$output .= $this->get_media( array(
		'class' => 'wpex-' . $bk . '-w-50 wpex-' . $bk . '-mr-25 wpex-flex-shrink-0',
	) );

	// Details
	$output .= '<div class="wpex-card-details wpex-flex-grow wpex-p-25 wpex-last-mb-0">';

		// Terms
		$output .= $this->get_terms_list( array(
			'class' => 'wpex-font-semibold wpex-leading-normal wpex-mb-15 wpex-last-mr-0',
			'term_class' => 'wpex-inline-block wpex-bg-accent wpex-text-white wpex-hover-opacity-80 wpex-no-underline wpex-mr-5 wpex-mb-5 wpex-px-10 wpex-py-5 wpex-rounded-full wpex-text-xs',
			'has_term_background_color' => true,
		) );

		// Title
		$output .= $this->get_title( array(
			'class' => 'wpex-heading wpex-text-lg wpex-font-bold wpex-mb-15',
			'link_class' => 'wpex-inherit-color-important',
		) );

		// Excerpt
		$output .= $this->get_excerpt( array(
			'class' => 'wpex-mb-30',
		) );

		// Date
		$output .= $this->get_date( array(
			'type' => 'published',
			'class' => 'wpex-text-xs wpex-uppercase wpex-font-semibold',
		) );

	$output .= '</div>';

$output .= '</div>';

return $output;