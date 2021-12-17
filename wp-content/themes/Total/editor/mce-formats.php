<?php
namespace TotalTheme\Editor;

defined( 'ABSPATH' ) || exit;

/**
 * Customizations for the WP tinymce editor.
 *
 * @package TotalTheme
 * @version 5.2
 */
final class Mce_Formats {

	/**
	 * Instance.
	 *
	 * @access private
	 * @var object Class object.
	 */
	private static $instance;

	/**
	 * Create or retrieve the instance of Mce_Formats.
	 */
	public static function instance() {
		if ( is_null( static::$instance ) ) {
			static::$instance = new self();
			static::$instance->init_hooks();
		}

		return static::$instance;
	}

	/**
	 * Hook into actions and filters.
	 *
	 * @since 5.0
	 */
	public function init_hooks() {

		add_filter( 'mce_buttons_2', array( $this, 'enable_fontsizeselect_button' ) );
		add_filter( 'tiny_mce_before_init', array( $this, 'custom_fontsize_formats' ) );

		if ( get_theme_mod( 'editor_formats_enable', true ) ) {
			add_filter( 'mce_buttons', array( $this, 'enable_styleselect_button' ) );
			add_filter( 'tiny_mce_before_init', array( $this, 'add_formats' ) );
		}

	}

	/**
	 * Enable the font size button in the editor.
	 *
	 * @since 5.0
	 */
	public function enable_fontsizeselect_button( $buttons ) {
		array_push( $buttons, 'fontsizeselect' );
		return $buttons;
	}

	/**
	 * Custom font size options for the editor.
	 *
	 * @since 5.0
	 */
	public function custom_fontsize_formats( $settings ) {
		$settings['fontsize_formats'] = '10px 13px 14px 16px 18px 21px 24px 28px 32px 36px';
		return $settings;
	}

	/**
	 * Enable the Formats button in the editor.
	 *
	 * @since 5.0
	 */
	public function enable_styleselect_button( $buttons ) {
		array_push( $buttons, 'styleselect' );
		return $buttons;
	}

	/**
	 * Adds custom styles to the formats dropdown by altering the $settings.
	 *
	 * @since 5.0
	 */
	public function add_formats( $settings ) {

		// General.
		$total = apply_filters( 'wpex_tiny_mce_formats_items', array(
			array(
				'title'    => esc_html__( 'Theme Button', 'total' ),
				'selector' => 'a',
				'classes'  => 'theme-button',
			),
			array(
				'title'   => esc_html__( 'Highlight', 'total' ),
				'inline'  => 'span',
				'classes' => 'text-highlight',
			),
			array(
				'title'   => esc_html__( 'Thin Font', 'total' ),
				'inline'  => 'span',
				'classes' => 'thin-font'
			),
			array(
				'title'   => esc_html__( 'White Text', 'total' ),
				'inline'  => 'span',
				'classes' => 'white-text'
			),
			array(
				'title'    => esc_html__( 'Check List', 'total' ),
				'selector' => 'ul',
				'classes'  => 'check-list'
			),
		) );

		// Font Sizes.
		$font_sizes = apply_filters( 'wpex_tiny_mce_formats_font_sizes', array(
			array(
				'title' => '7xl',
				'inline' => 'span',
				'classes'  => 'wpex-text-7xl',
			),
			array(
				'title' => '6xl',
				'inline' => 'span',
				'classes'  => 'wpex-text-6xl',
			),
			array(
				'title' => '5xl',
				'inline' => 'span',
				'classes'  => 'wpex-text-5xl',
			),
			array(
				'title' => '4xl',
				'inline' => 'span',
				'classes'  => 'wpex-text-4xl',
			),
			array(
				'title' => '3xl',
				'inline' => 'span',
				'classes'  => 'wpex-text-3xl',
			),
			array(
				'title' => '2xl',
				'inline' => 'span',
				'classes'  => 'wpex-text-2xl',
			),
			array(
				'title' => 'xl',
				'inline' => 'span',
				'classes'  => 'wpex-text-xl',
			),
			array(
				'title' => 'lg',
				'inline' => 'span',
				'classes'  => 'wpex-text-lg',
			),
			array(
				'title' => 'md',
				'inline' => 'span',
				'classes'  => 'wpex-text-md',
			),
			array(
				'title' => 'sm',
				'inline' => 'span',
				'classes'  => 'wpex-text-sm',
			),
		) );

		// Alerts.
		$alerts = apply_filters( 'wpex_tiny_mce_formats_alerts', array(
			array(
				'title'   => esc_html__( 'Info', 'total' ),
				'block'   => 'div',
				'classes' => 'wpex-alert wpex-alert-info',
			),
			array(
				'title'   => esc_html__( 'Success', 'total' ),
				'block'   => 'div',
				'classes' => 'wpex-alert wpex-alert-success',
			),
			array(
				'title'   => esc_html__( 'Warning', 'total' ),
				'block'   => 'div',
				'classes' => 'wpex-alert wpex-alert-warning',
			),
			array(
				'title'   => esc_html__( 'Error', 'total' ),
				'block'   => 'div',
				'classes' => 'wpex-alert wpex-alert-error',
			),
		) );

		// Dropcaps.
		$dropcaps = apply_filters( 'wpex_tiny_mce_formats_dropcaps', array(
			array(
				'title'   => esc_html__( 'Dropcap', 'total' ),
				'inline'  => 'span',
				'classes' => 'dropcap',
			),
			array(
				'title'   => esc_html__( 'Boxed Dropcap', 'total' ),
				'inline'  => 'span',
				'classes' => 'dropcap boxed',
			),
		) );

		// Color buttons.
		$color_buttons = apply_filters( 'wpex_tiny_mce_formats_color_buttons', array(
			array(
				'title'     => esc_html__( 'Blue', 'total' ),
				'selector'  => 'a',
				'classes'   => 'color-button blue',
			),
			array(
				'title'     => esc_html__( 'Black', 'total' ),
				'selector'  => 'a',
				'classes'   => 'color-button black',
			),
			array(
				'title'     => esc_html__( 'Red', 'total' ),
				'selector'  => 'a',
				'classes'   => 'color-button red',
			),
			array(
				'title'     => esc_html__( 'Orange', 'total' ),
				'selector'  => 'a',
				'classes'   => 'color-button orange',
			),
			array(
				'title'     => esc_html__( 'Green', 'total' ),
				'selector'  => 'a',
				'classes'   => 'color-button green',
			),
			array(
				'title'     => esc_html__( 'Gold', 'total' ),
				'selector'  => 'a',
				'classes'   => 'color-button gold',
			),
			array(
				'title'     => esc_html__( 'Teal', 'total' ),
				'selector'  => 'a',
				'classes'   => 'color-button teal',
			),
			array(
				'title'     => esc_html__( 'Purple', 'total' ),
				'selector'  => 'a',
				'classes'   => 'color-button purple',
			),
			array(
				'title'     => esc_html__( 'Pink', 'total' ),
				'selector'  => 'a',
				'classes'   => 'color-button pink',
			),
			array(
				'title'     => esc_html__( 'Brown', 'total' ),
				'selector'  => 'a',
				'classes'   => 'color-button brown',
			),
			array(
				'title'     => esc_html__( 'Rosy', 'total' ),
				'selector'  => 'a',
				'classes'   => 'color-button rosy',
			),
			array(
				'title'     => esc_html__( 'White', 'total' ),
				'selector'  => 'a',
				'classes'   => 'color-button white',
			),
		) );

		$formats = array();

		if ( $total ) {

			$formats[] = array(
				'title' => esc_html__( 'Theme Styles', 'total' ),
				'items' => ( object ) $total,
			);

		}

		if ( $font_sizes ) {

			$formats[] = array(
				'title' => esc_html__( 'Font Sizes', 'total' ),
				'items' => ( object ) $font_sizes,
			);

		}

		if ( $alerts ) {

			$formats[] = array(
				'title' => esc_html__( 'Alerts', 'total' ),
				'items' => ( object ) $alerts,
			);

		}

		if ( $dropcaps ) {

			$formats[] = array(
				'title' => esc_html__( 'Dropcaps', 'total' ),
				'items' => ( object ) $dropcaps,
			);

		}

		if ( $color_buttons ) {

			$formats[] = array(
				'title' => esc_html__( 'Color Buttons', 'total' ),
				'items' => ( object ) $color_buttons,
			);

		}

		if ( $formats ) {

			// Merge Formats.
			$settings['style_formats_merge'] = true;

			// Add new formats.
			$settings['style_formats'] = json_encode( $formats );

		}

		// Return New Settings.
		return $settings;

	}

}