<?php
namespace TotalTheme;

defined( 'ABSPATH' ) || exit;

/**
 * Sanitize inputted data.
 *
 * @package TotalTheme
 * @version 5.3
 */
final class Sanitize_Data {

	/**
	 * Main class function parses input to return sanitized output.
	 *
	 * @since 2.0.0
	 */
	public function parse_data( $input, $type ) {
		$type = str_replace( '-', '_', $type );
		if ( method_exists( $this, $type ) ) {
			return $this->$type( $input );
		} else {
			return $input;
		}
	}

	/**
	 * URL.
	 *
	 * @since 4.8
	 */
	public function url( $input ) {
		return esc_url( $input );
	}

	/**
	 * Text.
	 *
	 * @since 4.8
	 */
	public function text( $input ) {
		return sanitize_text_field( $input );
	}

	/**
	 * Text Field.
	 *
	 * @since 4.8
	 */
	public function text_field( $input ) {
		return sanitize_text_field( $input );
	}

	/**
	 * Textarea.
	 *
	 * @since 4.8
	 */
	public function textarea( $input ) {
		return wp_kses_post( $input );
	}

	/**
	 * Boolean.
	 *
	 * @since 2.0.0
	 */
	public function boolean( $input ) {
		return wpex_validate_boolean( $input );
	}

	/**
	 * Pixels.
	 *
	 * @since 2.0.0
	 */
	public function px( $input ) {
		if ( 'none' === $input ) {
			return '0';
		} else {
			return floatval( $input ) . 'px'; // Not sure why we used floatval but lets leave it incase
		}
	}

	/**
	 * Font Size.
	 *
	 * @since 2.0.0
	 */
	public function font_size( $input ) {
		return wpex_sanitize_font_size( $input );
	}

	/**
	 * Font Weight.
	 *
	 * @since 2.0.0
	 */
	public function font_weight( $input ) {
		switch ( $input ) {
			case 'normal':
				return '400';
				break;
			case 'semibold':
				return '600';
				break;
			case 'bold':
				return '700';
				break;
			case 'bolder':
				return '900';
				break;
			default:
				return wp_strip_all_tags( $input );
				break;
		}
	}

	/**
	 * Hex Color.
	 *
	 * @since 2.0.0
	 */
	public function hex_color( $input ) {
		if ( ! $input ) {
			return null;
		} elseif ( 'none' === $input ) {
			return 'transparent';
		} elseif ( preg_match('|^#([A-Fa-f0-9]{3}){1,2}$|', $input ) ) {
			return $input;
		} else {
			return null;
		}
	}

	/**
	 * Border Radius.
	 *
	 * @since 2.0.0
	 */
	public function border_radius( $input ) {
		if ( 'none' === $input ) {
			return '0';
		} elseif ( strpos( $input, 'px' ) ) {
			return $input;
		} elseif ( strpos( $input, '%' ) ) {
			if ( '50%' == $input ) {
				return $input;
			} else {
				return str_replace( '%', 'px', $input );
			}
		} else {
			return intval( $input ) .'px';
		}
	}

	/**
	 * Pixel or Percent.
	 *
	 * @since 2.0.0
	 */
	public function px_pct( $input ) {
		if ( 'none' === $input || '0px' === $input ) {
			return '0';
		} elseif ( strpos( $input, '%' ) ) {
			return wp_strip_all_tags( $input );
		} elseif ( $input = floatval( $input ) ) {
			return wp_strip_all_tags( $input ) .'px';
		}
	}

	/**
	 * Opacity.
	 *
	 * @since 2.0.0
	 */
	public function opacity( $input ) {
		if ( ! is_numeric( $input ) || $input > 1 ) {
			return;
		} else {
			return wp_strip_all_tags( $input );
		}
	}

	/**
	 * HTML.
	 *
	 * @since 3.3.0
	 */
	public function html( $input ) {
		return wp_kses_post( $input );
	}

	/**
	 * Image.
	 *
	 * @since 2.0.0
	 * @todo remove, image markup should be trusted the attributes should be sanitized before hand.
	 */
	public function img( $input ) {
		return wp_kses( $input, array(
			'img' => array(
				'src'      => array(),
				'alt'      => array(),
				'srcset'   => array(),
				'id'       => array(),
				'class'    => array(),
				'height'   => array(),
				'width'    => array(),
				'data'     => array(),
				'data-rjs' => array(),
			),
		) );
	}

	/**
	 * Image from setting.
	 *
	 * @since 3.5.0
	 */
	public function image_src_from_mod( $input ) {
		return wpex_get_image_url( $input );
	}

	/**
	 * Background Style.
	 *
	 * @since 3.5.0
	 */
	public function background_style_css( $input ) {
		switch ( $input ) {
			case 'stretched':
				return '-webkit-background-size: cover;
					-moz-background-size: cover;
					-o-background-size: cover;
					background-size: cover;
					background-position: center center;
					background-attachment: fixed;
					background-repeat: no-repeat;';
				break;
			case 'cover':
				return 'background-position: center center;
					-webkit-background-size: cover;
					-moz-background-size: cover;
					-o-background-size: cover;
					background-size: cover;';
				break;
			case 'repeat':
				return 'background-repeat:repeat;';
				break;
			case 'repeat-y':
				return 'background-position: center center;background-repeat:repeat-y;';
				break;
			case 'fixed':
				return 'background-repeat: no-repeat; background-position: center center; background-attachment: fixed;';
				break;
			case 'fixed-top':
				return 'background-repeat: no-repeat; background-position: center top; background-attachment: fixed;';
				break;
			case 'fixed-bottom':
				return 'background-repeat: no-repeat; background-position: center bottom; background-attachment: fixed;';
				break;
			default:
				return 'background-repeat:' . esc_attr( $input ) . ';';
				break;
		}

	}

	/**
	 * Embed URL.
	 *
	 * @since 2.0.0
	 */
	public function embed_url( $url ) {
		return wpex_get_video_embed_url( $url );
	}

	/**
	 * Google Map Embed.
	 *
	 * @since 4.8
	 */
	public function google_map( $input ) {
		return wp_kses( $input, array(
			'iframe' => array(
				'src'             => array(),
				'height'          => array(),
				'width'           => array(),
				'frameborder'     => array(),
				'style'           => array(),
				'allowfullscreen' => array(),
			),
		) );
	}

	/**
	 * iFrame.
	 *
	 * @since 5.0
	 */
	public function iframe( $input ) {
		return wp_kses( $input, array(
			'iframe' => array(
				'align' => array(),
				'width' => array(),
				'height' => array(),
				'frameborder' => array(),
				'name' => array(),
				'src' => array(),
				'id' => array(),
				'class' => array(),
				'style' => array(),
				'scrolling' => array(),
				'marginwidth' => array(),
				'marginheight' => array(),
				'allow' => array(),
			),
		) );
	}

	/**
	 * SVG.
	 *
	 * @since 5.3
	 */
	public function svg( $input ) {
		return wp_kses( $input, array(
			'svg' => array(
				'class' => true,
				'aria-hidden' => true,
				'aria-labelledby' => true,
				'role' => true,
				'xmlns' => true,
				'width' => true,
				'height' => true,
				'viewbox' => true, // <= Must be lower case!
				'style' => true,
				'preserveAspectRatio' => true, // WP bug prevents this from working :(
			),
			'g' => array( 'fill' => true ),
			'title' => array( 'title' => true ),
			'path' => array(
				'd' => true,
				'fill' => true,
				'class' => true,
			),
		) );
	}

}