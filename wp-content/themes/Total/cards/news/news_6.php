<?php
defined( 'ABSPATH' ) || exit;

$output = '';

$has_link = $this->has_link();

$card_class = 'wpex-card-inner wpex-flex wpex-flex-col wpex-flex-grow wpex-bordered wpex-p-25 wpex-rounded-md wpex-shadow-sm wpex-transition-all wpex-duration-300 wpex-hover-shadow-xl';

// Inner
if ( $has_link ) {
	$output .= $this->get_link_open( array(
		'class' => $card_class . ' wpex-no-underline wpex-inherit-color',
	) );
} else {
	$output .= '<div class="' . esc_attr( $card_class ) . '">';
}

	// Title
	$output .= $this->get_title( array(
		'link'  => false,
		'class' => 'wpex-heading wpex-text-lg wpex-font-bold',
	) );

	// Excerpt
	$output .= $this->get_excerpt( array(
		'class' => 'wpex-my-20',
		'length' => 20,
		'link'  => false,
	) );

	// Meta
	$output .= '<div class="wpex-card-meta wpex-flex wpex-items-center wpex-flex-wrap wpex-gap-10 wpex-mt-auto">';

		// Avatar
		$output .= $this->get_avatar( array(
			'size' => 30,
			'class' => 'wpex-flex-shrink-0',
			'image_class' => 'wpex-rounded-full wpex-align-middle',
			'link' => false,
		) );

		// Author
		$output .= $this->get_author( array(
			'class' => 'wpex-font-semibold',
			'link' => false,
		) );

		// Date
		$output .= $this->get_date( array(
			'type' => 'time_ago',
			'class' => 'wpex-opacity-70',
			'link' => false,
		) );

	$output .= '</div>';

$output .= $has_link ? '</a>' : '</div>';

return $output;