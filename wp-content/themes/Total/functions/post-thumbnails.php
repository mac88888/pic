<?php
/**
 * Helper functions for returning/generating post thumbnails.
 *
 * @package TotalTheme
 * @subpackage Functions
 * @version 5.3
 */

defined( 'ABSPATH' ) || exit;

/**
 * Returns thumbnail sizes.
 *
 * @since 2.0.0
 */
function wpex_get_thumbnail_sizes( $size = '' ) {

	global $_wp_additional_image_sizes;

	$sizes = array(
		'full'  => array(
			'width'  => 9999,
			'height' => 9999,
			'crop'   => false,
		),
	);

	$get_intermediate_image_sizes = get_intermediate_image_sizes();

	// Create the full array with sizes and crop info
	foreach( $get_intermediate_image_sizes as $_size ) {

		if ( in_array( $_size, array( 'thumbnail', 'medium', 'large' ) ) ) {

			$sizes[$_size]['width']  = get_option( $_size . '_size_w' );
			$sizes[$_size]['height'] = get_option( $_size . '_size_h' );
			$sizes[$_size]['crop']   = (bool) get_option( $_size . '_crop' );

		} elseif ( isset( $_wp_additional_image_sizes[ $_size ] ) ) {

			$sizes[$_size] = array(
				'width'  => $_wp_additional_image_sizes[ $_size ]['width'],
				'height' => $_wp_additional_image_sizes[ $_size ]['height'],
				'crop'   => $_wp_additional_image_sizes[ $_size ]['crop'],
			);

		}

	}

	// Get only 1 size if found.
	if ( $size ) {
		if ( isset( $sizes[$size] ) ) {
			return $sizes[$size];
		} else {
			return false;
		}
	}

	// Return sizes.
	return $sizes;
}

/**
 * Generates a retina image.
 *
 * @since 2.0.0
 */
function wpex_generate_retina_image( $attachment, $width, $height, $crop, $size = '' ) {
	return wpex_image_resize( array(
		'attachment' => $attachment,
		'width'      => $width,
		'height'     => $height,
		'crop'       => $crop,
		'return'     => 'url',
		'retina'     => true,
		'size'       => $size, // Used to update metadata accordingly
	) );
}

/**
 * Echo post thumbnail url.
 *
 * @since 2.0.0
 */
function wpex_post_thumbnail_url( $args = array() ) {
	echo wpex_get_post_thumbnail_url( $args );
}

/**
 * Return post thumbnail url.
 *
 * @since 2.0.0
 */
function wpex_get_post_thumbnail_url( $args = array() ) {
	$args['return'] = 'url';
	return wpex_get_post_thumbnail( $args );
}

/**
 * Return post thumbnail src.
 *
 * @since 4.0
 */
function wpex_get_post_thumbnail_src( $args = array() ) {
	$args['return'] = 'src';
	return wpex_get_post_thumbnail( $args );
}

/**
 * Outputs the img HTMl thubmails used in the Total VC modules.
 *
 * @since 2.0.0
 */
function wpex_post_thumbnail( $args = array() ) {
	echo wpex_get_post_thumbnail( $args );
}

/**
 * Returns HTMl for post thumbnails.
 *
 * @since 2.0.0
 * @todo Create a class for this to better organize things.
 */
function wpex_get_post_thumbnail( $args = array() ) {

	$defaults = array(
		'post'           => null,
		'attachment'     => '',
		'size'           => '',
		'width'          => '',
		'height'         => '',
		'crop'           => '',
		'return'         => 'html',
		'style'          => '',
		'alt'            => '',
		'class'          => '',
		'before'         => '',
		'after'          => '',
		'attributes'     => array(),
		'retina'         => wpex_is_retina_enabled(),
		//'retina_data'    => 'rjs', // @deprecated 5.3
		'add_image_dims' => true,
		'schema_markup'  => false,
		'placeholder'    => false,
		'lazy'           => true,
		'apply_filters'  => '',
		'filter_arg1'    => '',
	);

	// Parse args.
	$args = wp_parse_args( $args, $defaults );

	// Apply filters = Must run here !!
	if ( $args['apply_filters'] ) {
		$args = apply_filters( $args['apply_filters'], $args, $args['filter_arg1'] );
	}

	// If attachment is empty get attachment from current post.
	if ( empty( $args['attachment'] ) ) {
		$args['attachment'] = get_post_thumbnail_id( $args['post'] );
	}

	/**
	 * Custom post thumbnail output that runs before fetching the thumbnail.
	 *
	 * @param null output.
	 * @param array $args
	 */
	$custom_output = apply_filters( 'wpex_get_post_thumbnail_custom_output', null, $args );

	if ( $custom_output ) {
		return $custom_output;
	}

	// Extract args.
	extract( $args );

	// Check if return has been set to null via filter.
	if ( null === $return ) {
		return;
	}

	// Return placeholder image.
	if ( $placeholder || 'placeholder' === $attachment ) {
		return ( $placeholder = wpex_placeholder_img_src() ) ? '<img src="' . esc_url( $placeholder ) . '">' : '';
	}

	// Is this a custom image crop?
	$is_custom = false;

	// Width and height should be numerical.
	$width = absint( $width );
	$height = absint( $height );

	// If size is not defined it's either going to be custom or full.
	if ( ! $size ) {
		if ( $width || $height ) {
			$size = 'wpex_custom';
			$is_custom = true;
		} else {
			$size = 'full';
		}
	}

	// Set size to null if set to custom as we won't need it later.
	if ( 'wpex-custom' === $size || 'wpex_custom' === $size ) {
		$size = null;
		$is_custom = true;
	}

	// Get image dimensions for defined image size.
	if ( $size && $size !== 'full' ) {
		$dims = wpex_get_thumbnail_sizes( $size );
		if ( $dims && is_array( $dims ) ) {
			if ( array_key_exists( 'width', $dims ) ) {
				$width = $dims['width'];
			}
			if ( array_key_exists( 'height', $dims ) ) {
				$height = $dims['height'];
			}
			if ( array_key_exists( 'crop', $dims ) ) {
				$crop = $dims['crop'];
			}
		}
		// If image size is empty or greater then or equal to 9999 set size to full.
		// This allows the WordPress srcset function to work properly on full images,
		// as it bypasses the theme's resizing.
		if ( ! $width && ! $height || ( $width >= 9999 && $height >= 9999 ) ) {
			$size = 'full';
		}
	}

	// Set size to full if size isn't defined and the width/height are massive values;
	if ( ! $size && ( ( $width >= 9999 && $height >= 9999 ) || ( ! $width && ! $height ) ) ) {
		$size = 'full';
	}

	// Disable lazy loading if data-no-lazy attribute is defined.
	if ( ! empty( $attributes['data-no-lazy'] ) ) {
		$lazy = false;
	}

	// Extra attributes for html return.
	if ( 'html' === $return ) {

		// Define attributes for html output.
		$attr = $attributes;

		// Add native browser lazy loading support for theme featured images.
		if ( $lazy ) {

			$has_lazy_loading = get_theme_mod( 'post_thumbnail_lazy_loading', true );

			/**
			 * Filters whether the post thumbnails should have lazy loading.
			 *
			 * @param boolean $has_lazy_loading
			 */
			$has_lazy_loading = apply_filters( 'wpex_has_post_thumbnail_lazy_loading', $has_lazy_loading );

			if ( $has_lazy_loading ) {
				$attr['loading'] = 'lazy';
			}

		}

		// Add skip-lazy class for use with 3rd party plugins like Jetpack.
		else {
			if ( is_array( $class ) ) {
				$class[] = 'skip-lazy';
			} else {
				$class = $class ? $class . ' skip-lazy' : 'skip-lazy';
			}
		}

		// Add custom class if defined.
		if ( $class ) {
			if ( is_array( $class ) ) {
				$class = array_map( 'esc_attr', $class );
				$class = implode( ' ', $class ); // important for wp_get_attachment_image
			}
			$attr['class'] = $class;
		}

		// Add style.
		if ( $style ) {
			$attr['style'] = $style;
		}

		// Add schema markup.
		if ( $schema_markup ) {
			$attr['itemprop'] = 'image';
		}

		// Add alt.
		if ( $alt ) {
			$attr['alt'] = $alt;
		}

	}

	/**
	 * On demand resizing.
	 * Custom Total output (needs to run even when image_resizing is disabled for custom image cropping in WPBakery and widgets).
	 */
	if ( 'full' !== $size && ( get_theme_mod( 'image_resizing', true ) || $is_custom ) ) {

		// Crop standard image.
		$image = wpex_image_resize( array(
			'attachment' => $attachment,
			'size'       => $size,
			'width'      => $width,
			'height'     => $height,
			'crop'       => $crop,
		) );

		// Image couldn't be generated for some reason or another.
		if ( ! $image ) {
			return;
		}

		// Return image URL.
		if ( 'url' === $return ) {
			return $image['url'];
		}

		// Return src.
		if ( 'src' === $return ) {
			return array(
				$image['url'],
				$image['width'],
				$image['height'],
				$image['is_intermediate'],
			);
		}

		// Return image HTMl (default return)
		if ( 'html' === $return ) {

			// Get image srcset.
			if ( $size ) {
				$srcset = wp_get_attachment_image_srcset( $attachment, $size );
			} elseif ( ! empty( $image['width'] ) && ! empty( $image['height'] ) ) {
				$srcset = wp_get_attachment_image_srcset( $attachment, array( $image['width'], $image['height'] ) );
			}

			// Add src tag.
			$attr['src'] = esc_url( $image['url'] );

			// Check for custom alt if no alt is defined manually.
			if ( ! $alt ) {
				$alt = trim( strip_tags( get_post_meta( $attachment, '_wp_attachment_image_alt', true ) ) );
			}

			// Add alt attribute (add empty if none is found).
			$attr['alt'] = $alt ? ucwords( $alt ) : '';

			// Generate retina version.
			if ( $retina ) {

				/**
				 * Filters the retina image url before grabbing it.
				 *
				 * @param int $attachment
				 * @param string $size
				 */
				$retina_img = apply_filters( 'wpex_get_post_thumbnail_retina', null, $attachment, $size );

				if ( ! $retina_img ) {
					$retina_img = wpex_generate_retina_image( $attachment, $width, $height, $crop, $size );
				}

				// Add retina attributes.
				if ( $retina_img ) {
					//$attr['data-' . $retina_data] = $retina_img; // @deprecated 5.3
					if ( ! empty( $srcset ) ) {
						$srcset .= ', ' . $retina_img . ' 2x';
					} else {
						$srcset = $retina_img . ' 2x';
					}
					// By default retina images will display at the original image size,
					// by setting this filter to false retina images will render at their full size.
					if ( ! apply_filters( 'wpex_retina_resize', true ) ) {
						$attr['data-no-resize'] = '';
						$add_image_dims = false;
					}
				}

			}

			// Define srcset attribute.
			if ( ! empty( $srcset ) ) {
				$attr['srcset'] = trim( esc_attr( $srcset ) );
			}

			// Add width and height if not empty (we don't want to add 0 values)
			// Also only add the dims if we haven't specified them previously via the attributes param.
			if ( true === $add_image_dims ) {
				if ( ! empty( $image['width'] ) && empty( $attr['width'] ) ) {
					$attr['width'] = intval( $image['width'] );
				}
				if ( ! empty( $image['height'] ) && empty( $attr['height'] ) ) {
					$attr['height'] = intval( $image['height'] );
				}
			}

			/**
			 * Filters the wpex_get_post_thumbnail_image_attributes.
			 *
			 * @param array $attributes
			 * @param int $attachment
			 * @param array $args
			 */
			$attr = apply_filters( 'wpex_get_post_thumbnail_image_attributes', $attr, $attachment, $args );

			/**
			 * Filters the wpex_post_thumbnail_html output.
			 *
			 * @param string $thumbnail_html
			 * @todo update to use wp_get_attachment_image
			 */
			//$img_html = wp_get_attachment_image( $attachment, $size, false, $attr );
			$img_html = apply_filters( 'wpex_post_thumbnail_html', '<img ' . wpex_parse_attrs( $attr ) . '>' );

			if ( $img_html ) {
				return $before . $img_html . $after;
			}

		}

	}

	// Return image from add_image_size.
	// If on-the-fly is disabled for defined sizes or image size is set to "full".
	else {

		// Return image URL.
		if ( 'url' === $return ) {
			return wp_get_attachment_image_url( $attachment, $size, false );
		}

		// Return src.
		elseif ( 'src' === $return ) {
			return wp_get_attachment_image_src( $attachment, $size, false );
		}

		// Return image HTML.
		// Should this use get_the_post_thumbnail instead?.
		elseif ( 'html' === $return ) {

			if ( ! $lazy && function_exists( 'wp_lazy_loading_enabled' ) ) {
				$attr['loading'] = false;
			}

			// Parses the style attribute to prevent issues where the style tag may already be there.
			if ( ! empty( $attr['style'] ) && is_string( $attr['style'] ) && 0 === strpos( trim( $attr['style'] ), 'style=' ) ) {
				$parsed_style = trim( $attr['style'] );
				$parsed_style = str_replace( 'style="', '', $parsed_style );
				$parsed_style = substr( $parsed_style, 0, -1 );
				$attr['style'] = $parsed_style;
			}

			$image = wp_get_attachment_image( $attachment, $size, false, $attr );

			/**
			 * Filters the wpex_post_thumbnail_html output.
			 *
			 * @param string $thumbnail_html
			 * @todo update to use wp_get_attachment_image
			 */
			$img_html = apply_filters( 'wpex_post_thumbnail_html', $image );

			if ( $img_html ) {
				return $before . $img_html . $after;
			}

		}

	}

}

/**
 * Returns secondary thumbnail.
 *
 * @since 4.5.5
 * @todo rename to wpex_get_secondary_thumbnail_id and deprecate.
 */
function wpex_get_secondary_thumbnail( $post_id = '' ) {

	$thumbnail_id = '';

	if ( ! $post_id ) {
		$post_id = get_the_ID();
	}

	$meta = get_post_meta( $post_id, 'wpex_secondary_thumbnail', true );

	// Check meta field first.
	if ( ! empty( $meta ) ) {
		$thumbnail_id = $meta;
	}

	// Check post gallery if meta field isn't set.
	else {

		$gallery_ids = wpex_get_gallery_ids( $post_id );

		if ( $gallery_ids && is_array( $gallery_ids ) ) {

			if ( isset( $gallery_ids[0] ) && $gallery_ids[0] != get_post_thumbnail_id() ) {
				$thumbnail_id = $gallery_ids[0];
			} elseif ( ! empty( $gallery_ids[1] ) && is_numeric( $gallery_ids[1] ) ) {
				$thumbnail_id = $gallery_ids[1];
			}

		}

	}

	/**
	 * Filters the secondary post thumbnail id.
	 *
	 * @param int $thumbnail_id
	 * @param int $post_id
	 */
	$thumbnail_id = (int) apply_filters( 'wpex_secondary_post_thumbnail_id', $thumbnail_id, $post_id );

	return $thumbnail_id;

}