<?php
defined( 'ABSPATH' ) || exit;

$has_link = ( $this->has_link() && ! $this->has_thumbnail_overlay() );
$output = '';

if ( $has_link ) {
	$output .= $this->get_link_open( array(
		'class' => 'wpex-card-inner wpex-text-center wpex-text-gray-600 wpex-no-underline wpex-last-mb-0',
	) );
} else {
	$output .= '<div class="wpex-card-inner wpex-text-center wpex-last-mb-0">';
}

	// Media
	$output .= $this->get_media( array(
		'class' => 'wpex-mb-15',
		'link'  => $has_link ? false : true,
	) );

	// Title
	$title_class = 'wpex-heading wpex-text-lg wpex-font-normal';
	if ( $has_link ) {
		$title_class .= ' wpex-hover-text-accent';
	}
	$output .= $this->get_title( array(
		'class' => $title_class,
		'link'  => $has_link ? false : true,
	) );

if ( $has_link ) {
	$output .= '</a>';
} else {
	$output .= '</div>';
}

return $output;