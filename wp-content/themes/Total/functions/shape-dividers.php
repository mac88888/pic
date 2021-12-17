<?php
/**
 * Shape Dividers.
 *
 * @package TotalTheme
 * @subpackage Functions
 * @version 5.1.2
 */

defined( 'ABSPATH' ) || exit;

/**
 * Shape Divider Styles.
 *
 * @since 4.9.3
 */
function wpex_get_shape_divider_types() {
	return apply_filters( 'wpex_get_section_shape_divider_types', array(
		''                      => esc_html__( 'None', 'total' ),
		'tilt'                  => esc_html__( 'Tilt', 'total' ),
		'triangle'              => esc_html__( 'Triangle', 'total' ),
		'triangle_asymmetrical' => esc_html__( 'Triangle Asymmetrical', 'total' ),
		'arrow'                 => esc_html__( 'Arrow', 'total' ),
		'curve'                 => esc_html__( 'Curve', 'total' ),
		'waves'                 => esc_html__( 'Waves', 'total' ),
		'clouds'                => esc_html__( 'Clouds', 'total' ),
	) );
}

/**
 * Insert top shape divider.
 *
 * @since 4.9.3
 * @deprecated 5.3
 */
function vcex_insert_top_shape_divider( $content, $atts ) {
	if ( ! empty( $atts['wpex_shape_divider_top'] ) ) {
		$content .= wpex_get_shape_divider( 'top', $atts['wpex_shape_divider_top'], wpex_get_shape_divider_settings( 'top', $atts ) );
	}
	return $content;
}

/**
 * Insert bottom shape divider.
 *
 * @since 4.9.3
 * @deprecated 5.3
 */
function vcex_insert_bottom_shape_divider( $content, $atts ) {
	if ( ! empty( $atts['wpex_shape_divider_bottom'] ) ) {
		$content .= wpex_get_shape_divider( 'bottom', $atts['wpex_shape_divider_bottom'], wpex_get_shape_divider_settings( 'bottom', $atts ) );
	}
	return $content;
}

/**
 * Print row section divider.
 */
function wpex_get_shape_divider_settings( $position = 'top', $atts = array() ) {

	$settings = array(
		'color'      => '',
		'width'      => '',
		'height'     => '',
		'flip'       => false,
		'invert'     => false,
		'visibility' => '',
	);

	foreach( $settings as $k => $v ) {
		$atts_setting_k = 'wpex_shape_divider_' . $position . '_' . $k;
		if ( isset( $atts[ $atts_setting_k ] ) ) {
			$settings[ $k ] = $atts[ $atts_setting_k ];
		}
	}

	return apply_filters( 'wpex_get_shape_divider_settings', $settings, $atts );
}

/**
 * Print row section divider.
 */
function wpex_get_shape_divider( $position = 'top', $type = 'triangle', $settings = array() ) {

	$classes = array(
		'wpex-shape-divider',
		'wpex-shape-divider-' . esc_attr( $type ),
		'wpex-shape-divider-' . esc_attr( $position ),
	);

	$rotate = false;
	$flip   = isset( $settings['flip'] ) && 'true' == $settings['flip'] ? true : false;
	$invert = isset( $settings['invert'] ) && 'true' == $settings['invert'] ? true : false;

	if ( $flip ) {
		$classes[] = 'wpex-shape-divider-flip';
	}

	if ( wpex_shape_divider_rotate( $position, $type, $invert ) ) {
		$classes[] = 'wpex-shape-divider-rotate';
	}

	if ( ! empty( $settings['visibility'] ) ) {
		$classes[] = esc_attr( $settings['visibility'] );
	}

	$classes = array_unique( $classes );

	return '<div class="' . esc_attr( implode( ' ', $classes ) ) .'">' . wpex_get_shape_dividers_svg( $type, $settings ) . '</div>';

}

/**
 * Check if shape needs rotating.
 */
function wpex_shape_divider_rotate( $position, $type, $invert ) {

	if ( 'top' === $position ) {
		if ( $invert && in_array( $type, array( 'triangle', 'triangle_asymmetrical', 'arrow', 'clouds', 'curve', 'waves' ) ) ) {
			return true;
		}
	}

	if ( 'bottom' === $position ) {
		if ( ! $invert && in_array( $type, array( 'tilt', 'triangle', 'triangle_asymmetrical', 'arrow', 'clouds', 'curve', 'waves' ) ) ) {
			return true;
		}
	}

}

/**
 * Array of shape dividers.
 */
function wpex_get_shape_dividers_svg( $type = '', $settings = array() ) {

	$svg = '';

	// SVG filename.
	$svg_filename = $type;

	if ( isset( $settings[ 'invert' ] )
		&& 'true' == $settings[ 'invert' ]
		&& ( 'tilt' !== $type )
	) {
		$svg_filename = $svg_filename . '-invert';
	}

	// Include template.
	$shape_template = locate_template( 'assets/shape-dividers/' . $svg_filename . '.svg', false );
	if ( $shape_template ) {
		$svg = file_get_contents( $shape_template );
	}

	// Bail if we can't locate the svg.
	if ( ! $svg ) {
		return;
	}

	// Get inline Styles
	$svg_styles = array();
	$svg_styles_html = '';

	if ( ! empty( $settings['height'] ) ) {
		$svg_styles['height'] = absint( $settings['height'] ) . 'px';
	}

	if ( ! empty( $settings['width'] ) ) {
		$svg_styles['width'] = 'calc(' . absint( $settings['width'] ) . '% + 1.3px)';
	}

	if ( $svg_styles ) {

		$svg_styles_html = ' style="';

			$svg_styles = array_map( 'esc_attr', $svg_styles );

			foreach ( $svg_styles as $name => $value ) {
				$svg_styles_html .= ' ' . $name . ':' . $value . ';';
			}

		$svg_styles_html .= '"';

	}

	// Set inline path attributes
	$path_attrs = array();
	$path_attrs_html = '';
	$fill_color = '#fff';

	if ( ! empty( $settings['color'] ) ) {
		$custom_fill_color = wpex_parse_color( $settings['color'] );
		if ( $custom_fill_color ) {
			$fill_color = $custom_fill_color;
		}
	}

	$path_attrs['fill'] = $fill_color;

	if ( $path_attrs ) {
		foreach ( $path_attrs as $name => $value ) {
			$path_attrs_html .= ' ' . $name . '="' . esc_attr( $value ) . '"';
		}
	}

	// Add svg attributes.
	if ( $svg_styles_html ) {
		$svg = str_replace(
			'<svg class="wpex-shape-divider-svg"',
			'<svg class="wpex-shape-divider-svg"' . $svg_styles_html,
			$svg
		);
	}

	// Add path attributes.
	if ( $path_attrs_html ) {
		$svg = str_replace(
			'<path class="wpex-shape-divider-path"',
			'<path class="wpex-shape-divider-path"' . $path_attrs_html,
			$svg
		);
	}

	/**
	 * Filters the shape divider output.
	 *
	 * @param html $svg The divider svg output.
	 * @param string $type SVG type.
	 * @param array $settings Shortcode settings.
	 * @param html $svg_styles_html The inline styles added to the svg element.
	 * @param html $path_attrs_html The inline attributes added to the path element.
	 */
	$shape_divider = apply_filters( 'wpex_get_shape_dividers_svg', $svg, $type, $settings, $svg_styles_html, $path_attrs_html );

	return $shape_divider;
}