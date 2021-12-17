<?php
defined( 'ABSPATH' ) || exit;

$output = '';

$bk = $this->get_breakpoint();

$output .= '<div class="wpex-card-inner wpex-bg-white wpex-flex wpex-flex-col wpex-flex-grow wpex-' . $bk . '-flex-row wpex-' . $bk . '-items-center wpex-rounded wpex-shadow-lg wpex-overflow-hidden">';

	// Media
	$output .= $this->get_media( array(
		'class' => 'wpex-' . $bk . '-w-50 wpex-flex-shrink-0',
	) );

	// Details.
	$output .= '<div class="wpex-card-details wpex-flex-grow wpex-p-30 wpex-last-mb-0">';

		// Title
		$output .= $this->get_title( array(
			'class' => 'wpex-heading wpex-text-lg wpex-mb-5',
			'link_class' => 'wpex-inherit-color-important',
		) );

		// Terms
		$output .= $this->get_terms_list( array(
			'class' => 'wpex-mb-15 wpex-text-xs wpex-font-semibold wpex-uppercase',
			'separator' => ' &middot; ',
			'has_term_color' => true,
		) );

		// Excerpt
		$output .= $this->get_excerpt( array(
			'class' => '',
		) );

	$output .= '</div>';

$output .= '</div>';

return $output;