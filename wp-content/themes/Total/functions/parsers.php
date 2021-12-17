<?php
/**
 * Parser functions.
 *
 * @package TotalTheme
 * @subpackage Functions
 * @version 5.3
 */

defined( 'ABSPATH' ) || exit;

/**
 * Parses color to return correct value.
 *
 * @since 5.0
 */
function wpex_parse_color( $color = '' ) {

	$color = trim( $color );

	if ( 'inherit' === $color || 'currentColor' === $color ) {
		return $color;
	}

	$palette = wpex_get_color_palette();

	if ( ! empty( $palette )
		&& is_array( $palette )
		&& array_key_exists( $color, $palette )
		&& ! empty( $palette[$color]['color'] )
	) {
		$color = $palette[$color]['color'];
	}

    return $color;

}

/**
 * Cleans up an array, comma- or space-separated list of scalar values.
 *
 * @since 5.0
 */
function wpex_parse_list( $list ) {

	if ( function_exists( 'wp_parse_list' ) ) {
		return wp_parse_list( $list ); // added in WP 5.1
	}

    if ( ! is_array( $list ) ) {
        return preg_split( '/[\s,]+/', $list, -1, PREG_SPLIT_NO_EMPTY );
    }

    return $list;
}

/**
 * Parse CSS.
 */
function wpex_parse_css( $value = '', $property = '', $selector = '', $unit = '', $important = false ) {

	if ( ! $value || ! $selector || ! $property || ! $selector ) {
		return;
	}

	$safe_selector = wp_strip_all_tags( $selector );
	$safe_property = wp_strip_all_tags( $property );
	$safe_value    = wp_strip_all_tags( $value );

	if ( ! empty( $unit ) ) {
		$safe_value .= strtolower( $unit );
	}

	if ( $important ) {
		$safe_value .= '!important';
	}

	return $safe_selector . '{' . $safe_property . ':' . $safe_value . ';}';

}

/**
 * Takes an array of attributes and outputs them for HTML.
 *
 * @since 3.4.0
 */
function wpex_parse_html( $tag = '', $attrs = array(), $content = '' ) {

	$attrs       = wpex_parse_attrs( $attrs );
	$tag_escaped = tag_escape( $tag );

	$output = '<' . $tag_escaped . ' ' . $attrs . '>';

	if ( $content ) {
		$output .= $content; // should be sanitized already.
	}

	$output .= '</' . $tag_escaped . '>';

	return $output;
}

/**
 * Parses an html data attribute.
 *
 * @since 3.4.0
 * @todo create helper class so the code can be organized better.
 */
function wpex_parse_attrs( $attrs = null ) {

	if ( empty( $attrs ) || ! is_array( $attrs ) ) {
		return $attrs; // return $attrs incase it's a string already.
	}

	// Add noopener noreferrer automatically to nofollow links if rel attr isn't set.
	if ( isset( $attrs['href'] )
		&& isset( $attrs['target'] )
		&& in_array( $attrs['target'], array( '_blank', 'blank' ) )
	) {
		$rel = apply_filters( 'wpex_targeted_link_rel', 'noopener noreferrer', $attrs['href'] );
		if ( ! empty( $rel ) ) {
			if ( ! empty( $attrs['rel'] ) ) {
				$attrs['rel'] .= ' ' . $rel;
			} else {
				$attrs['rel'] = $rel;
			}
		}
	}

	// Define output var.
	$output = '';

	// Loop through attributes.
	foreach ( $attrs as $key => $val ) {

		// Attributes used for other things, we can skip these.
		if ( 'content' === $key ) {
			continue;
		}

		// If the attribute is an array convert to string.
		if ( is_array( $val ) ) {
			$val = array_map( 'trim', $val );
			$val = implode( ' ', $val );
		}

		// Sanitize specific attributes.
		switch ( $key ) {
			case 'href':
			case 'src':
				$val = esc_url( $val );
				break;
			case 'id':
				$val = trim( str_replace( '#', '', $val ) );
				$val = str_replace( ' ', '', $val );
				break;
			case 'target':
				if ( ! in_array( $val, array( '_blank', 'blank', '_self', '_parent', '_top' ) ) ) {
					$val = '';
				} elseif ( 'blank' === $val ) {
					$val = '_blank';
				}
				break;
		}

		// Add attribute to output if value exists or is a string equal to 0.
		if ( $val || '0' === $val ) {

			switch ( $key ) {

				// Attributes that don't have values.
				case 'download':

					$safe_attr = preg_replace( '/[^a-z0-9_\-]/', '', $val );
					$output .= ' ' . trim( $safe_attr ); // Used for example on total button download attribute.

					break;

				// Attributes with values.
				default:

					$needle = ( 'data' === $key ) ? 'data-' : esc_attr( $key ) . '=';

					// Tag is already included in the value.
					if ( strpos( $val, $needle ) !== false ) {
						$output .= ' ' . trim( wp_strip_all_tags( $val ) );
					}

					// Tag not included in the value.
					else {
						$safe_attr = preg_replace( '/[^a-z0-9_\-]/', '', $key );
						$output .= ' ' . trim( $safe_attr ) . '="' . esc_attr( trim( $val ) ) . '"';
					}

					break;
			}

		}

		// Items with empty vals.
		elseif( 'data-wpex-hover' !== $key ) {

			// Empty alts are allowed.
			if ( 'alt' === $key ) {
				$output .= ' alt=""';
			}

			// Empty data attributes.
			elseif ( strpos( $key, 'data-' ) !== false ) {
				$safe_attr = preg_replace( '/[^a-z0-9_\-]/', '', $key );
				$output .= ' ' . trim( $safe_attr );
			}

		}

	} // end loop.

	// Return output.
	return trim( $output );

}

/**
 * Returns link target attribute.
 *
 * @since 4.9
 * @todo deprecate and use parse_attrs instead?
 */
function wpex_parse_link_target( $target = true, $add_rel = true ) {
	$output = '';
	if ( 'blank' === $target || '_blank' === $target ) {
		$output = ' target="_blank"';
		if ( $add_rel ) {
			$output .= ' rel="noopener noreferrer"';
		}
	}
	return $output;
}

/**
 * Parses text_align class.
 */
function wpex_parse_text_align_class( $align = '' ) {
	if ( 'left' === $align || 'center' === $align || 'right' === $align ) {
		return 'wpex-text-' . sanitize_html_class( $align );
	}
}

/**
 * Parses padding class.
 */
function wpex_parse_padding_class( $padding = '' ) {
	if ( $padding ) {
		return 'wpex-p-' . trim( absint( $padding ) );
	}
}

/**
 * Parses border_radius class.
 */
function wpex_parse_border_radius_class( $border_radius = '' ) {
	if ( $border_radius ) {
		return 'wpex-' . sanitize_html_class( $border_radius );
	}
}

/**
 * Parses border_width class.
 *
 * @todo deprecate - this isn't used anywhere, we can instead use vcex_ function instead.
 */
function wpex_parse_border_width_class( $border_width = '', $sides = 'all' ) {
	if ( ! $border_width ) {
		return;
	}

	$border_width = absint( $border_width );

	$prefix = 'border';

	switch ( $sides ) {
		case 'top':
			$prefix = 'border-t';
			break;
		case 'left':
			$prefix = 'border-l';
			break;
		case 'right':
			$prefix = 'border-r';
			break;
	}

	if ( 1 === $border_width ) {
		return 'wpex-border';
	}

	return 'wpex-' . sanitize_html_class( $prefix . '-' . $border_width );

}

/**
 * Parses border_radius class.
 *
 * @todo deprecate - this isn't used anywhere, we can instead use vcex_ function instead.
 */
function wpex_parse_border_style_class( $border_style = '' ) {
	$allowed = array( 'dashed', 'solid', 'double', 'dotted' );
	if ( $border_style && in_array( $border_style, $allowed ) ) {
		return 'wpex-border-' . sanitize_html_class( $border_style );
	}
}

/**
 * Parses margin class.
 *
 * @todo deprecate - this isn't used anywhere, we can instead use vcex_ function instead.
 */
function wpex_parse_margin_class( $margin = '', $prefix = '' ) {
	$margin_choices = wpex_utl_margins();
	if ( $margin && array_key_exists( $margin, $margin_choices ) ) {
		$margin = absint( $margin );
	}
	return sanitize_html_class( $prefix . $margin );
}

/**
 * Parses a direction for RTL compatibility.
 */
function wpex_parse_direction( $direction = '' ) {
	if ( ! $direction ) {
		return '';
	}
	if ( is_rtl() ) {
		switch ( $direction ) {
			case 'left' :
				$direction = 'right';
			break;
			case 'right' :
				$direction = 'left';
			break;
		}
	}
	return $direction;
}