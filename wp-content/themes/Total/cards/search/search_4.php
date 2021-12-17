<?php
defined( 'ABSPATH' ) || exit;

$output = '';

$output .= '<div class="wpex-card-inner wpex-flex wpex-justify-between wpex-items-start wpex-gap-20">';

	// Details.
	$output .= '<div class="wpex-card-details wpex-flex-grow">';

		// Title.
		$output .= $this->get_title( array(
			'class' => 'wpex-heading wpex-text-lg wpex-m-0',
		) );

		// Crumbs.
		$post_type = get_post_type( $this->post_id );
		if ( ! $post_type || 'post' === $post_type ) {
			$post_type = esc_html__( 'Articles', 'total' );
		}

		$output .= $this->get_element( array(
			'class' => 'wpex-card-trail wpex-opacity-60 wpex-text-sm',
			'content' => esc_html( 'Home', 'total' ) . ' ' . wpex_get_theme_icon_html( 'angle-right' ) . ' ' . ucfirst( esc_html( $post_type ) ),
		) );

		// Excerpt.y-5
		$output .= $this->get_excerpt( array(
			'class' => 'wpex-mt-5'
		) );

	$output .= '</div>';

	// Thumbnail
	$thumbnail_args = array(
		'class' => 'wpex-flex-shrink-0 wpex-w-20 wpex-p-3 wpex-border wpex-border-solid wpex-border-gray-200',
		'css' => 'padding:2px;',
		'image_class' => 'wpex-w-100',
	);
	if ( empty( $this->args['media_width'] ) ) {
		$thumbnail_args['css'] .= 'max-width:63px;';
	}
	$output .= $this->get_thumbnail( $thumbnail_args );

$output .= '</div>';

return $output;