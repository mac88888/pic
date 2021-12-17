<?php
/**
 * Scroll To Top Options.
 *
 * @package TotalTheme
 * @subpackage Customizer
 * @version 5.3
 */

defined( 'ABSPATH' ) || exit;

$this->sections['wpex_scroll_top'] = array(
	'title' => esc_html__( 'Scroll To Top', 'total' ),
	'panel' => 'wpex_general',
	'settings' => array(
		array(
			'id' => 'scroll_top',
			'default' => true,
			'transport' => 'refresh',
			'control' => array(
				'label' => esc_html__( 'Enable Scroll Up Button?', 'total' ),
				'type' => 'checkbox',
			),
		),
		array(
			'id' => 'scroll_top_style',
			'default' => '',
			'transport' => 'refresh',
			'control' => array(
				'label' => esc_html__( 'Style', 'total' ),
				'type' => 'select',
				'choices' => array(
					'' => esc_html__( 'Default', 'total' ),
					'black' => esc_html__( 'Black', 'total' ),
					'accent' => esc_html__( 'Accent', 'total' ),
					'icon' => esc_html__( 'Icon Only', 'total' ),
				),
			),
		),
		array(
			'id' => 'scroll_top_breakpoint',
			'transport' => 'refresh',
			'control' => array(
				'label' => esc_html__( 'Breakpoint', 'total' ),
				'type' => 'select',
				'choices' => wpex_utl_breakpoints(),
				'description' => esc_html__( 'Select the breakpoint at which point the scroll to button becomes visible. By default it is visible on all devices.', 'total' ),
			),
		),
		array(
			'id' => 'scroll_top_arrow',
			'default' => 'chevron-up',
			'transport' => 'refresh',
			'control' => array(
				'label' => esc_html__( 'Arrow', 'total' ),
				'type' => 'select',
				'choices' => array(
					'chevron-up' => esc_html__( 'Chevron', 'total' ),
					'caret-up' => esc_html__( 'Caret', 'total' ),
					'angle-up' => esc_html__( 'Angle', 'total' ),
					'angle-double-up' => esc_html__( 'Double Angle', 'total' ),
					'long-arrow-up' => esc_html__( 'Long Arrow', 'total' ),
					'arrow-circle-o-up' => esc_html__( 'Circle', 'total' ),
					'arrow-up' => esc_html__( 'Arrow', 'total' ),
					'caret-square-o-up' => esc_html__( 'Caret Square', 'total' ),
					'level-up' => esc_html__( 'Level', 'total' ),
					'sort-up' => esc_html__( 'Sort', 'total' ),
					'toggle-up' => esc_html__( 'Toggle', 'total' ),
				),
			),
		),
		array(
			'id' => 'scroll_top_shadow',
			'transport' => 'refresh',
			'control' => array(
				'label' => esc_html__( 'Shadow', 'total' ),
				'type' => 'select',
				'choices' => wpex_utl_shadows(),
			),
		),
		array(
			'id' => 'local_scroll_reveal_offset',
			'control' => array(
				'label' => esc_html__( 'Reveal Offset', 'total' ),
				'type' => 'text',
				'input_attrs' => array(
					'placeholder' => '100',
				),
				'description' => esc_html__( 'Offset in pixels at which point the button becomes visible when scrolling down.', 'total' ),
			),
		),
		array(
			'id' => 'scroll_top_speed',
			'transport' => 'refresh',
			'control' => array(
				'label' => esc_html__( 'Local Scroll Speed in Milliseconds', 'total' ),
				'type' => 'text',
				'input_attrs' => array(
					'placeholder' => '1000',
				),
			),
			'control_display' => array(
				'check' => 'scroll_to_easing',
				'value' => true,
			),
		),
		array(
			'id' => 'scroll_top_size',
			'transport' => 'postMessage',
			'control' => array(
				'type' => 'text',
				'label' => esc_html__( 'Button Size', 'total' ),
				'input_attrs' => array(
					'placeholder' => '35px',
				),
			),
			'inline_css' => array(
				'target' => '#site-scroll-top',
				'sanitize' => 'px',
				'alter' => array(
					'width',
					'height',
					'line-height',
				),
			),
		),
		array(
			'id' => 'scroll_top_icon_size',
			'transport' => 'postMessage',
			'control' => array(
				'type' => 'text',
				'label' => esc_html__( 'Icon Size', 'total' ),
				'input_attrs' => array(
					'placeholder' => '16px',
				),
			),
			'inline_css' => array(
				'target' => '#site-scroll-top',
				'alter' => 'font-size',
			),
		),
		array(
			'id' => 'scroll_top_border_radius',
			'transport' => 'postMessage',
			'control' => array(
				'type' => 'text',
				'label' => esc_html__( 'Border Radius', 'total' ),
				'input_attrs' => array(
					'placeholder' => '9999px',
				),
			),
			'inline_css' => array(
				'target' => '#site-scroll-top',
				'alter' => 'border-radius',
			),
		),
		array(
			'id' => 'scroll_top_right_position',
			'transport' => 'postMessage',
			'control' => array(
				'type' => 'text',
				'label' => esc_html__( 'Side Position', 'total' ),
				'input_attrs' => array(
					'placeholder' => '25px',
				),
			),
			'inline_css' => array(
				'target' => '#site-scroll-top',
				'alter' => is_rtl() ? 'margin-left' : 'margin-right',
			),
		),
		array(
			'id' => 'scroll_top_bottom_position',
			'transport' => 'postMessage',
			'control' => array(
				'type' => 'text',
				'label' => esc_html__( 'Bottom Position', 'total' ),
				'input_attrs' => array(
					'placeholder' => '25px',
				),
			),
			'inline_css' => array(
				'target' => '#site-scroll-top',
				'alter' => 'margin-bottom',
			),
		),
		array(
			'id' => 'scroll_top_color',
			'transport' => 'postMessage',
			'control' => array(
				'type' => 'color',
				'label' => esc_html__( 'Color', 'total' ),
			),
			'inline_css' => array(
				'target' => '#site-scroll-top',
				'alter' => 'color',
			),
		),
		array(
			'id' => 'scroll_top_color_hover',
			'transport' => 'postMessage',
			'control' => array(
				'type' => 'color',
				'label' => esc_html__( 'Color: Hover', 'total' ),
			),
			'inline_css' => array(
				'target' => '#site-scroll-top:hover',
				'alter' => 'color',
			),
		),
		array(
			'id' => 'scroll_top_bg',
			'transport' => 'postMessage',
			'control' => array(
				'type' => 'color',
				'label' => esc_html__( 'Background', 'total' ),
			),
			'inline_css' => array(
				'target' => '#site-scroll-top',
				'alter' => 'background-color',
			),
		),
		array(
			'id' => 'scroll_top_bg_hover',
			'transport' => 'postMessage',
			'control' => array(
				'type' => 'color',
				'label' => esc_html__( 'Background: Hover', 'total' ),
			),
			'inline_css' => array(
				'target' => '#site-scroll-top:hover',
				'alter' => 'background-color',
			),
		),
	),
);