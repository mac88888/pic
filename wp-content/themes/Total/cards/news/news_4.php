<?php
defined( 'ABSPATH' ) || exit;

$output = '';

if ( empty( $this->args['breakpoint'] ) ) {
	$this->args['breakpoint'] = 'sm';
}

$bk = $this->get_breakpoint();

// Inner
$output .= '<div class="wpex-card-inner wpex-flex wpex-flex-col wpex-' . $bk . '-flex-row wpex-gap-25">';

	// Thumbnail
	$output .= $this->get_thumbnail( array(
		'class' => 'wpex-' . $bk . '-w-40 wpex-flex-shrink-0',
		'image_class' => 'wpex-w-100',
	) );

	// Details
	$output .= '<div class="wpex-card-details wpex-flex wpex-flex-col wpex-flex-grow">';

		// Category
		$output .= $this->get_primary_term( array(
			'class' => 'wpex-text-xs wpex-font-bold wpex-uppercase',
			'term_class' => 'wpex-inherit-color',
			'has_term_color' => true,
		) );

		// Title
		$output .= $this->get_title( array(
			'link'  => true,
			'class' => 'wpex-heading wpex-text-2xl wpex-font-bold',
			'link_class' => 'wpex-inherit-color-important',
		) );

		// Excerpt
		$output .= $this->get_excerpt( array(
			'class' => 'wpex-my-15',
			'length' => 20,
		) );

		// Author
		$output .= $this->get_author( array(
			'class' => 'wpex-text-sm wpex-uppercase',
			'link_class' => 'wpex-inherit-color',
			'prefix' => esc_html__( 'By', 'total' ) . ' ',
		) );

	$output .= '</div>';

$output .= '</div>';

return $output;