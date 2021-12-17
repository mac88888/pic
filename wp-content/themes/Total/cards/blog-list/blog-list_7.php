<?php
defined( 'ABSPATH' ) || exit;

$output = '';

$bk = $this->get_breakpoint();

$output .= '<div class="wpex-card-inner wpex-flex wpex-flex-col wpex-flex-grow wpex-' . $bk . '-flex-row wpex-bg-white wpex-border wpex-border-solid wpex-border-main">';

	// Media
	$output .= $this->get_media( array(
		'class' => 'wpex-' . $bk . '-w-50 wpex-flex-shrink-0',
	) );

	$output .= '<div class="wpex-card-details wpex-flex-grow wpex-p-25 wpex-last-mb-0">';

		// Primary term
		$output .= $this->get_primary_term( array(
			'class' => 'wpex-font-semibold wpex-leading-normal wpex-mb-15',
			'term_class' => 'wpex-inline-block wpex-bg-accent wpex-text-white wpex-hover-bg-accent_alt wpex-no-underline wpex-px-10 wpex-py-5 wpex-text-xs',
			'has_term_background_color' => true,
		) );

		// Title
		$output .= $this->get_title( array(
			'class' => 'wpex-heading wpex-text-xl wpex-font-bold wpex-my-15',
			'link_class' => 'wpex-inherit-color-important',
		) );

		// Excerpt
		$output .= $this->get_excerpt( array(
			'class' => 'wpex-text-gray-600 wpex-my-15',
		) );

		// Date
		$output .= $this->get_date( array(
			'type' => 'modified',
			'class' => 'wpex-mt-20',
			'icon' => 'ticon ticon-clock-o wpex-mr-5',
		) );

	$output .= '</div>';

$output .= '</div>';

return $output;