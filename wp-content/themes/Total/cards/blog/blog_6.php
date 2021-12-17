<?php
defined( 'ABSPATH' ) || exit;

$output = '';

$output .= '<div class="wpex-card-inner wpex-flex wpex-flex-col wpex-flex-grow wpex-bg-white wpex-border wpex-border-solid wpex-border-main">';

	// Media
	$output .= $this->get_media();

	// Details
	$output .= '<div class="wpex-card-details wpex-m-25 wpex-last-mb-0">';

		// Terms
		$output .= $this->get_terms_list( array(
			'class'      => 'wpex-mb-15 wpex-font-bold wpex-text-xs wpex-uppercase wpex-tracking-wide',
			'term_class' => 'wpex-inline-block',
			'separator'  => '<span class="wpex-card-terms-list-sep wpex-inline-block wpex-mx-5">&#8725;</span>',
			'has_term_color' => true,
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

	$output .= '</div>';

	// Footer
	$output .= '<div class="wpex-card-footer wpex-mt-auto wpex-mx-25 wpex-mb-25">';

		// Date
		$date_prefix = esc_html__( 'Published', 'total' );
		if ( function_exists( 'tribe_get_start_date' ) && 'tribe_events' === $this->get_post_type() ) {
			$date_prefix = '';
		}
		$output .= $this->get_date( array(
			'type'   => 'time_ago',
			'prefix' =>  $date_prefix . ' ',
			'icon'   => 'ticon ticon-clock-o wpex-mr-5',
		) );

	$output .= '</div>';

$output .= '</div>';

return $output;