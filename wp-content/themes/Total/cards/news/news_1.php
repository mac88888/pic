<?php
defined( 'ABSPATH' ) || exit;

$output = '';

if ( empty( $this->args['breakpoint'] ) ) {
	$this->args['breakpoint'] = 'sm';
}

$bk = $this->get_breakpoint();

// Inner
$output .= '<div class="wpex-card-inner wpex-flex wpex-flex-col wpex-' . $bk . '-flex-row wpex-gap-20">';

	// Thumbnail
	$output .= $this->get_thumbnail( array(
		'class' => 'wpex-' . $bk . '-w-30 wpex-flex-shrink-0',
		'image_class' => 'wpex-w-100',
	) );

	// Details
	$output .= '<div class="wpex-card-details wpex-flex-grow">';

		// Title
		$output .= $this->get_title( array(
			'link'  => true,
			'class' => 'wpex-heading wpex-text-lg',
		) );

		// Meta
		$output .= '<div class="wpex-card-meta wpex-flex wpex-flex-wrap wpex-gap-5 wpex-mb-15 wpex-child-inherit-color wpex-opacity-60 wpex-text-sm">';

			// Date
			$output .= $this->get_date( array(
				'format' => 'F j, Y g:ia',
			) );

			// Author
			$output .= $this->get_author( array(
				'prefix' => esc_html__( 'by', 'total' ) . ' ',
			) );

		$output .= '</div>';

		// Excerpt
		$output .= $this->get_excerpt( array(
			'class' => 'wpex-my-15',
			'length' => 40,
		) );

		// Footer
		$output .= '<div class="wpex-card-footer wpex-text-sm">';

			// Read more
			$output .= $this->get_more_link( array(
				'html_tag' => 'span',
				'text' => esc_html__( 'Read Full Article', 'total' ),
			) );

			// Comment count
			$output .= $this->get_comment_count( array(
				'html_tag' => 'span',
				'before' => ' &bull; '
			) );

		$output .= '</div>';

	$output .= '</div>';

$output .= '</div>';

return $output;