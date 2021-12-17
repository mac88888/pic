<?php
defined( 'ABSPATH' ) || exit;

$output = '';

if ( empty( $this->args['breakpoint'] ) ) {
	$this->args['breakpoint'] = 'sm';
}

$bk = $this->get_breakpoint();

// Inner
$output .= '<div class="wpex-card-inner wpex-flex wpex-flex-col wpex-' . $bk . '-flex-row wpex-gap-30">';

	// Thumbnail
	$output .= $this->get_thumbnail( array(
		'class' => 'wpex-' . $bk . '-w-25 wpex-flex-shrink-0',
		'image_class' => 'wpex-w-100 wpex-rounded-sm'
	) );

	// Details
	$output .= '<div class="wpex-card-details wpex-flex-grow">';

		// Author wrap
		$output .= '<div class="wpex-card-author-wrap wpex-flex wpex-items-center wpex-gap-10 wpex-mb-15">';

			// Avatar
			$output .= $this->get_avatar( array(
				'size' => 30,
				'class' => 'wpex-flex-shrink-0',
				'image_class' => 'wpex-rounded-full wpex-align-middle',
			) );

			// Author
			$output .= $this->get_author( array(
				'class' => 'wpex-text-sm',
				'link_class' => 'wpex-inherit-color',
			) );

		$output .= '</div>';

		// Title
		$output .= $this->get_title( array(
			'link'  => true,
			'class' => 'wpex-heading wpex-text-xl wpex-font-bold',
		) );

		// Excerpt
		$output .= $this->get_excerpt( array(
			'class' => 'wpex-my-15',
		) );

		// Date
		$output .= $this->get_date( array(
			'format' => 'Y.m.d ',
			'class' => 'wpex-opacity-60',
		) );

	$output .= '</div>';

$output .= '</div>';

return $output;