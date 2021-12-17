<?php
defined( 'ABSPATH' ) || exit;

$output = '';

// Inner
$output .= '<div class="wpex-card-inner wpex-flex wpex-flex-grow wpex-gap-20 wpex-border wpex-border-solid wpex-border-gray-300 wpex-rounded-sm wpex-p-20">';

	// Details
	$output .= '<div class="wpex-card-details wpex-flex wpex-flex-col wpex-flex-grow">';

		// Title
		$output .= $this->get_title( array(
			'link'  => true,
			'class' => 'wpex-heading wpex-text-xl wpex-font-bold',
		) );

		// Meta
		$output .= '<div class="wpex-card-meta wpex-text-sm wpex-flex wpex-flex-wrap wpex-gap-5 wpex-mt-5 wpex-opacity-60">';

			// Category
			$output .= $this->get_primary_term( array(
				'term_class' => 'wpex-inherit-color',
			) );

			// Date
			$output .= $this->get_date( array(
				'type' => 'time_ago',
				'before' => '<span>&bull;</span>',
			) );

			// Read Time
			$output .= $this->get_estimated_read_time( array(
				'before' => '<span>&bull;</span>',
			) );

		$output .= '</div>';

		// Read more
		$output .= $this->get_more_link( array(
			'class' => 'wpex-mt-auto wpex-pt-15',
			'text' => esc_html__( 'Read Article', 'total' ) . ' &rarr;',
		) );

	$output .= '</div>';

	// Thumbnail
	$media_args = array(
		'class' => 'wpex-w-20 wpex-flex-shrink-0', // default width needed to work with custom width Post Cards element setting.
		'image_class' => 'wpex-rounded-sm',
	);
	if ( empty( $this->args['media_width'] ) ) {
		$media_args['css'] = 'max-width:100px;';
	}
	$output .= $this->get_thumbnail( $media_args );

$output .= '</div>';

return $output;