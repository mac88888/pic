<?php
/**
 * Social share functions.
 *
 * @package TotalTheme
 * @subpackage Functions
 * @version 5.1
 */

defined( 'ABSPATH' ) || exit;

/*-------------------------------------------------------------------------------*/
/* [ Table of contents ]
/*-------------------------------------------------------------------------------*

	# General
	# Classes
	# Heading
	# Data
	# Items list (the buttons)

/*-------------------------------------------------------------------------------*/
/* [ General ]
/*-------------------------------------------------------------------------------*/

/**
 * Checks if social share is enabled.
 *
 * @since 4.0
 */
function wpex_has_social_share() {

	// Disable if password protected.
	if ( post_password_required() ) {
		return;
	}

	// Disabled by default.
	$bool = false;

	// Get current post ID.
	$post_id = wpex_get_current_post_id();

	// Check page settings to overrides theme mods and filters.
	if ( $post_id ) {

		// Meta check
		// @todo rename to something that makes more sense.
		if ( $meta = get_post_meta( $post_id, 'wpex_disable_social', true ) ) {

			// Check if disabled by meta options.
			if ( 'on' == $meta ) {
				return false;
			}

			// Return true if enabled via meta option.
			if ( 'enable' == $meta ) {
				return true;
			}

		}

		// Dynamic template check.
		if ( wpex_post_has_dynamic_template() ) {
			return true; // so that the post content module works correctly - @todo update so this isn't needed.
		}

		// Remove on woo cart/checkout pages.
		if ( ( function_exists( 'is_cart' ) && is_cart() ) || ( function_exists( 'is_checkout') && is_checkout() ) ) {
			return false;
		}

		// Check if social share is enabled for specific post types.
		if ( 'product' === get_post_type() ) {
			$bool = wp_validate_boolean( get_theme_mod( 'social_share_woo', false ) );
		} else {
			$blocks = wpex_single_blocks();
			if ( $blocks && is_array( $blocks ) ) {
				foreach ( $blocks as $block ) {
					if ( ( 'social_share' == $block || 'share' == $block ) ) {
						$bool = true;
					}
				}
			}
		}

	}

	// Apply filters and return.
	return apply_filters( 'wpex_has_social_share', $bool );

}

/**
 * Checks if there are any social sharing sites enabled.
 *
 * @since 1.0.0
 */
function wpex_has_social_share_sites() {
	return (bool) wpex_social_share_sites();
}

/**
 * Returns social sharing sites.
 *
 * @since 2.0.0
 */
function wpex_social_share_sites() {
	$sites = get_theme_mod( 'social_share_sites', array( 'twitter', 'facebook', 'linkedin', 'email' ) );
	$sites = apply_filters( 'wpex_social_share_sites', $sites );
	if ( $sites && ! is_array( $sites ) ) {
		$sites = explode( ',', $sites );
	}
	return $sites;
}

/**
 * Parses Social share arguments.
 *
 * @since 5.1
 */
function wpex_parse_social_share_args( $args = array() ) {

	$defaults = array(
		'style'         => wpex_social_share_style(),
		'position'      => wpex_social_share_position(),
		'has_labels'    => wpex_social_share_has_labels(),
		'stretch_items' => get_theme_mod( 'social_share_stretch_items', false ),
	);

	if ( 'custom' === $defaults['style'] ) {
		$defaults['link_border_radius'] = get_theme_mod( 'social_share_link_border_radius' );
	}

	if ( ! array_key_exists( 'contain', $args )
		&& 'horizontal' === $defaults['position']
		&& 'full-screen' === wpex_content_area_layout() ) {
		$defaults['contain'] = true; // contain the social share on full-screen layouts.
	}

	$args = wp_parse_args( $args, $defaults );

	if ( 'vertical' === $args['position'] ) {
		$args['has_labels'] = false; // remove labels on vertical share style.
	}

	return apply_filters( 'wpex_social_share_args', $args );

}

/**
 * Returns correct social share position.
 *
 * @since 2.0.0
 */
function wpex_social_share_position() {
	$position = get_theme_mod( 'social_share_position' );
	if ( ! $position ) {
		$position = 'horizontal';
	}
	return apply_filters( 'wpex_social_share_position', $position );
}

/**
 * Returns correct social share style.
 *
 * @since 2.0.0
 */
function wpex_social_share_style() {
	$style = get_theme_mod( 'social_share_style' );
	if ( function_exists( 'is_product' ) && is_product() ) {
		$style = get_theme_mod( 'woo_product_social_share_style', $style );
	}
	$style = $style ? $style : 'flat'; // style can't be empty
	return apply_filters( 'wpex_social_share_style', $style );
}

/**
 * Check if social share labels should display.
 *
 * @since 4.9.8
 */
function wpex_social_share_has_labels() {
	$bool = get_theme_mod( 'social_share_label', true );
	if ( function_exists( 'is_product' ) && is_product() ) {
		$bool = get_theme_mod( 'woo_social_share_label', true );
	}
	return apply_filters( 'wpex_social_share_has_labels', $bool );
}

/**
 * Checks if we are using custom social share.
 *
 * @since 5.0
 */
function wpex_has_custom_social_share() {
	return (bool) wpex_custom_social_share();
}

/**
 * Checks if we are using custom social share.
 *
 * @since 5.0
 */
function wpex_custom_social_share() {
	return apply_filters( 'wpex_custom_social_share', get_theme_mod( 'social_share_shortcode' ) );
}

/*-------------------------------------------------------------------------------*/
/* [ Classes ]
/*-------------------------------------------------------------------------------*/

/**
 * Social share class.
 *
 * @since 5.0
 */
function wpex_social_share_class( $args = array() ) {

	$classes = array();

	if ( empty( $args ) && wpex_has_custom_social_share() ) {

		$classes = array(
			'wpex-custom-social-share',
			'wpex-mb-40',
			'wpex-clr',
		);

		if ( 'full-screen' === wpex_content_area_layout() ) {
			$classes[] = 'container';
		}

	} else {

		$args = wpex_parse_social_share_args( $args );

		$classes = array(
			'class' => 'wpex-social-share',
		);

		if ( ! empty( $args['style'] ) ) {
			$classes[] = 'style-' . sanitize_html_class( $args['style'] );
		}

		if ( ! empty( $args['position'] ) ) {
			$classes[] = 'position-' . sanitize_html_class( $args['position'] );
		}

		if ( empty( $args['has_labels'] ) ) {
			$classes[] = 'disable-labels';
		}

		if ( ! empty( $args['stretch_items'] ) && 'horizontal' == $args['position'] ) {
			$classes[] = 'wpex-social-share--stretched';
		}

		if ( ! empty( $args['contain'] ) ) {
			$classes[] = 'container';
		}

	}

	$classes = (array) apply_filters( 'wpex_social_share_class', $classes );

	if ( $classes ) {
		echo 'class="' . esc_attr( implode( ' ', $classes ) ) . '"';
	}

}

/*-------------------------------------------------------------------------------*/
/* [ Heading ]
/*-------------------------------------------------------------------------------*/

/**
 * Checks if the social sharing style supports a custom heading.
 *
 * @since 5.0
 */
function wpex_has_social_share_heading() {
	$check = ( 'horizontal' === wpex_social_share_position() ) ? true : false;
	return (bool) apply_filters( 'wpex_has_social_share_heading', $check );
}

/**
 * Returns the social share heading.
 *
 * @since 2.0.0
 */
function wpex_social_share_heading() {

	if ( ! wpex_has_social_share_heading() ) {
		return;
	}

	$heading = wpex_get_translated_theme_mod( 'social_share_heading', esc_html__( 'Share This', 'total' ) );

	if ( function_exists( 'is_product' ) && is_product() ) {
		$heading = wpex_get_translated_theme_mod( 'woo_product_social_share_heading', $heading );
	}

	$heading = apply_filters( 'wpex_social_share_heading', $heading );

	if ( $heading ) {

		$heading_args = array(
			'tag'           => 'h4',
			'content'		=> $heading,
			'classes'		=> array( 'social-share-title' ),
			'apply_filters'	=> 'social_share',
		);

		if ( function_exists( 'is_product' ) && is_product() ) {
			$heading_args[ 'style' ] = 'plain';
		}

		wpex_heading( $heading_args );

	}

}

/*-------------------------------------------------------------------------------*/
/* [ Data ]
/*-------------------------------------------------------------------------------*/

/**
 * Output social share data.
 *
 * @since 5.0
 */
function wpex_social_share_data( $post_id = 0, $sites = array() ) {

	$data = wpex_get_social_share_data( $post_id, $sites );

	if ( ! empty( $data ) && is_array( $data ) ) {

		$html = '';

		foreach ( $data as $k => $v ) {
			$html .=' data-' . esc_attr( $k ) .'="' . esc_attr( $v ) . '"';
		}

		echo trim( $html );

	}

}

/**
 * Return social share data.
 *
 * @since 4.5.5.1
 */
function wpex_get_social_share_data( $post_id = 0, $sites = array() ) {

	$post_id = $post_id ? $post_id : wpex_get_current_post_id();
	$sites   = $sites ? $sites : wpex_social_share_sites();
	$url     = apply_filters( 'wpex_social_share_url', wpex_get_current_url() );

	$data = array();

	// Singular data
	if ( $post_id ) {

		$title = wpex_get_esc_title();

		if ( in_array( 'pinterest', $sites ) || in_array( 'linkedin', $sites ) ) {

			$summary = wpex_get_excerpt( apply_filters( 'wpex_social_share_excerpt_args', array(
				'post_id' => $post_id,
				'length'  => 30,
				'echo'    => false,
				'more'    => '',
			) ) );

		}

	}

	// Most likely an archive.
	else {
		$title   = get_the_archive_title();
		$summary = get_the_archive_description();
	}

	// Share source.
	$source = apply_filters( 'wpex_social_share_data_source', home_url( '/' ) );
	$data['source'] = rawurlencode( esc_url( $source ) );

	// Share url.
	$url = apply_filters( 'wpex_social_share_data_url', $url );
	$data['url'] = rawurlencode( esc_url( $url ) );

	// Share title.
	$title = apply_filters( 'wpex_social_share_data_title', $title );
	$data['title'] = html_entity_decode( wp_strip_all_tags( $title ) );

	// Thumbnail.
	if ( is_singular() && has_post_thumbnail() ) {
		$image = apply_filters( 'wpex_social_share_data_image', wp_get_attachment_url( get_post_thumbnail_id( $post_id ) ) );
		$data['image'] = rawurlencode( esc_url( $image ) );
	}

	// Add twitter handle.
	if ( $handle = get_theme_mod( 'social_share_twitter_handle' ) ) {
		$data['twitter-handle'] = esc_attr( $handle );
	}

	// Share summary.
	if ( ! empty( $summary ) ) {
		$summary = apply_filters( 'wpex_social_share_data_summary', wp_strip_all_tags( strip_shortcodes( $summary ) ) );
		$data['summary'] = rawurlencode( html_entity_decode( $summary ) );
	}

	// Get WordPress SEO meta share values.
	if ( class_exists( 'WPSEO_Meta' ) && method_exists( 'WPSEO_Meta', 'get_value' ) ) {
		$twitter_title = WPSEO_Meta::get_value( 'twitter-title', $post_id );
		if ( ! empty( $twitter_title ) ) {
			if ( class_exists( 'WPSEO_Replace_Vars' ) ) {
				$replace_vars = new WPSEO_Replace_Vars();
				$twitter_title = $replace_vars->replace( $twitter_title, get_post() );
			}
			$data['twitter-title'] = rawurlencode( html_entity_decode( wp_strip_all_tags( $twitter_title ) ) );
		}
		$twitter_desc =  WPSEO_Meta::get_value( 'twitter-description', $post_id );
		if ( ! empty( $twitter_desc ) ) {
			if ( class_exists( 'WPSEO_Replace_Vars' ) ) {
				$replace_vars = new WPSEO_Replace_Vars();
				$twitter_desc = $replace_vars->replace( $twitter_desc, get_post() );
			}
			if ( $twitter_title ) {
				$data['twitter-title'] = rawurlencode( html_entity_decode( wp_strip_all_tags( $twitter_title . ': ' . $twitter_desc ) ) );
			} else {
				$data['twitter-title'] = rawurlencode( html_entity_decode( wp_strip_all_tags( $data['title'] . ': ' . $twitter_desc ) ) );
			}
		}
	}

	// Email data.
	if ( in_array( 'email', $sites ) ) {
		$data['email-subject'] = apply_filters( 'wpex_social_share_data_email_subject', esc_html__( 'I wanted you to see this link', 'total' ) );
		$body = esc_html__( 'I wanted you to see this link', 'total' ) . ' '. rawurlencode( esc_url( $url ) );
		$data['email-body'] = apply_filters( 'wpex_social_share_data_email_body', $body );
	}

	// Specs.
	$data['specs'] = 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600';

	// Apply filters and return social share data.
	return apply_filters( 'wpex_get_social_share_data', $data );

}

/*-------------------------------------------------------------------------------*/
/* [ Items List ]
/*-------------------------------------------------------------------------------*/

/**
 * Output social share list.
 *
 * @since 5.0
 */
function wpex_social_share_list( $args = array(), $sites = array() ) {

	$sites = $sites ? $sites : wpex_social_share_sites();
	$items = wpex_social_share_items();

	if ( empty( $sites ) || empty( $items ) ) {
		return;
	}

	// Good place to load the social share scripts
	wp_enqueue_script( 'wpex-social-share' );

	$args = wpex_parse_social_share_args( $args );

	$has_labels = false;

	if ( array_key_exists( 'has_labels', $args ) && wpex_validate_boolean( $args['has_labels' ] ) ) {
		$has_labels = true;
	}

	$item_class = 'wpex-social-share__item';

	$sq_links = ( ! $has_labels || ( array_key_exists( 'position', $args ) && 'vertical' == $args['position'] ) );

	if ( 'custom' === $args['style'] ) {

		if ( ! empty( $args['link_border_radius'] ) ) {
			$link_border_radius_class = wpex_parse_border_radius_class( $args['link_border_radius'] );
		}

		if ( isset( $link_border_radius_class ) && 'vertical' == $args['position'] ) {
			$item_class .= ' wpex-ml-5 wpex-mb-5';
		}

	}

	?>

	<ul class="wpex-social-share__list">

		<?php
		// Loop through sites and save new array with filters for output
		foreach ( $sites as $site ) :

			if ( isset( $items[ $site ] ) ) :

				$item = isset( $items[ $site ] ) ? $items[ $site ] : '';

				if ( ! $item ) {
					continue;
				}

				$li_class = isset( $item[ 'li_class' ] ) ? ' ' . $item[ 'li_class' ] : '';

				$link_class = 'wpex-social-share__link';

				if ( $sq_links ) {
					$link_class .= ' wpex-social-share__link--sq';
				}

				if ( isset( $link_border_radius_class ) ) {
					$link_class .= ' ' . $link_border_radius_class;
				}

				?>

				<li class="<?php echo esc_attr( $item_class ); ?><?php echo esc_attr( $li_class ); ?>">

					<?php if ( isset( $item[ 'href' ] ) ) { ?>

						<a href="<?php echo esc_attr( $item[ 'href' ] ); ?>" role="button" class="<?php echo esc_attr( $link_class ); ?>">

					<?php } else { ?>

						<a href="#" role="button" class="<?php echo esc_attr( $link_class ); ?>">

					<?php } ?>

						<?php
						/**
						 * Display social share item icon.
						 */

						// Check for icon param first which overrides default icon class.
						if ( ! empty( $item[ 'icon' ] ) ) {
							$allowed_icon_html = array(
								'img' => array(
									'id' => array(),
									'class' => array(),
									'src' => array(),
									'title' => array(),
									'alt' => array(),
									'width' => array(),
									'height' => array()
								),
								'span' => array(
									'id' => array(),
									'class' => array(),
									'aria-hidden' => array(),
								)
							);
							echo wp_kses( $item[ 'icon' ], $allowed_icon_html );
						}

						// If an icon param doesn't exist return default theme font icon.
						elseif ( ! empty( $item[ 'icon_class' ] ) ) {
							echo '<span class="' .  esc_attr( $item[ 'icon_class' ] ) . '" aria-hidden="true"></span>';
						} ?>

						<?php
						// Display labels.
						if ( $has_labels ) { ?>
							<span class="wpex-social-share__label wpex-label"><?php echo esc_html( $item[ 'label' ] ); ?></span>
						<?php }
						// Screen reader text if labels are disabled.
						else {

							$text = ! empty( $item['reader_text'] ) ? $item['reader_text'] : $site;

							?>
							<span class="screen-reader-text"><?php echo esc_html( $text ); ?></span>
						<?php } ?>

					</a>

				</li>

			<?php

			endif;

		 endforeach; ?>

	</ul>

	<?php
}