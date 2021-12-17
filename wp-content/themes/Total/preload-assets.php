<?php
namespace TotalTheme;

defined( 'ABSPATH' ) || exit;

/**
 * Class used to insert links in the head for preloading assets.
 *
 * @package TotalTheme
 * @version 5.3
 */
class Preload_Assets {

	/**
	 * Instance.
	 *
	 * @access private
	 * @var object Class object.
	 */
	private static $instance;

	/**
	 * Create or retrieve the instance of Preload_Assets.
	 */
	public static function instance() {
		if ( is_null( static::$instance ) ) {
			static::$instance = new self();
		}
		return static::$instance;
	}

	/**
	 * Constructor.
	 *
	 * @since 5.0
	 */
	public function __construct() {
		add_action( 'wp_head', __CLASS__ . '::add_links' );
	}

	/**
	 * Add links to wp_head
	 *
	 * @since 5.0
	 */
	public static function add_links() {

		if ( defined( 'IFRAME_REQUEST' ) && IFRAME_REQUEST ) {
			return; // prevents preloading in iFrames where it's not needed (like widgets block editor).
		}

		$output = '';

		$links = self::get_links();

		if ( $links ) {

			foreach ( $links as $link => $atts ) {

				if ( isset( $atts['condition'] ) && false === $atts['condition'] ) {
					continue;
				}

				$output .= '<link rel="preload" href="' . esc_url( $atts['href'] ) . '"';

					if ( isset( $atts['type'] ) ) {
						$output .= ' type="' . esc_attr( $atts['type'] ) . '"';
					}

					if ( isset( $atts['as'] ) ) {
						$output .= ' as="' . esc_attr( $atts['as'] ) . '"';
					}

					if ( isset( $atts['media'] ) ) {
						$output .= ' media="' . esc_attr( $atts['media'] ) . '"';
					}

					if ( isset( $atts['crossorigin'] ) ) {
						$output .= ' crossorigin';
					}

				$output .= '>';

			}

		}

		$output_escaped = $output;

		echo $output_escaped; // @codingStandardsIgnoreLine

	}

	/**
	 * Return array of links.
	 *
	 * @since 5.0
	 */
	public static function get_links() {

		$links = array();

		// Theme Icon woff2 file.
		$links[] = array(
			'href'        => wpex_asset_url( 'lib/ticons/fonts/ticons.woff2' ),
			'type'        => 'font/woff2',
			'as'          => 'font',
			'crossorigin' => true,
			'condition'   => wp_style_is( 'ticons' ),
		);

		// Return links.
		return (array) apply_filters( 'wpex_preload_links', $links );

	}

}