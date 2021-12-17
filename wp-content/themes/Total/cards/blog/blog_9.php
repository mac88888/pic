<?php
defined( 'ABSPATH' ) || exit;

$output = '';

$output .= '<div class="wpex-card-inner wpex-flex wpex-flex-col wpex-flex-grow wpex-bg-white wpex-border-2 wpex-border-solid wpex-border-gray-200">';

	// Media
	$output .= $this->get_media();

	// Details
	$output .= '<div class="wpex-card-details wpex-m-25 wpex-last-mb-0">';

		// Title
		$output .= $this->get_title( array(
			'link'  => true,
			'class' => 'wpex-heading wpex-text-lg wpex-font-bold wpex-mb-15',
		) );

		// Excerpt
		$output .= $this->get_excerpt( array(
			'class' => 'wpex-mb-15',
		) );

	$output .= '</div>';

	// Footer
	$output .= '<div class="wpex-card-footer wpex-mt-auto wpex-mb-25 wpex-mx-25">';

		// More Link
		$output .= $this->get_more_link( array(
			'class'  => 'wpex-font-semibold',
			'text'   => esc_html__( 'Continue reading', 'total' ),
			'suffix' => ' &rarr;',
		) );

	$output .= '</div>';

$output .= '</div>';

return $output;