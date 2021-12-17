<?php
/**
 * Toggle Bar Customizer Settings.
 *
 * @package TotalTheme
 * @subpackage Customizer
 * @version 5.1.3
 */

defined( 'ABSPATH' ) || exit;

// General
$this->sections['wpex_togglebar'] = array(
	'title' => esc_html__( 'General', 'total' ),
	'settings' => array(
		array(
			'id' => 'toggle_bar',
			'default' => true,
			'control' => array(
				'label' => esc_html__( 'Enable Toggle Bar?', 'total' ),
				'type' => 'checkbox',
			),
		),
		array(
			'id' => 'toggle_bar_fullwidth',
			'default' => false,
			'control' => array(
				'label' => esc_html__( 'Full Width?', 'total' ),
				'type' => 'checkbox',
			),
		),
		array(
			'id' => 'toggle_bar_page',
			'default' => '',
			'control' => array(
				'label' => esc_html__( 'Template', 'total' ),
				'type' => 'wpex-dropdown-pages',
				'include_templates' => true,
			),
		),
		array(
			'id' => 'toggle_bar_content',
			'control' => array(
				'label' => esc_html__( 'Custom Content', 'total' ),
				'type' => 'textarea',
			),
			'control_display' => array(
				'check' => 'toggle_bar_page',
				'value' => 'false', // same as empty.
			),
		),
		array(
			'id' => 'toggle_bar_visibility',
			'control' => array(
				'label' => esc_html__( 'Visibility', 'total' ),
				'type' => 'wpex-visibility-select',
			),
		),
		array(
			'id' => 'toggle_bar_default_state',
			'default' => 'hidden',
			'control' => array(
				'label' => esc_html__( 'Default State', 'total' ),
				'type' => 'select',
				'choices' => array(
					'hidden' => esc_html__( 'Closed', 'total' ),
					'visible' => esc_html__( 'Open', 'total' ),
				),
			),
		),
		array(
			'id' => 'toggle_bar_display',
			'default' => 'overlay',
			'control' => array(
				'label' => esc_html__( 'Display', 'total' ),
				'type' => 'select',
				'choices' => array(
					'overlay' => esc_html__( 'Overlay (opens over site content)', 'total' ),
					'inline' => esc_html__( 'Inline (opens above site content)', 'total' ),
				),
			),
		),
		array(
			'id' => 'toggle_bar_animation',
			'default' => 'fade',
			'control' => array(
				'label' => esc_html__( 'Open/Close Animation', 'total' ),
				'type' => 'select',
				'choices' => array(
					'fade' => esc_html__( 'Fade', 'total' ),
					'fade-slide' => esc_html__( 'Fade & Slide Down', 'total' ),
				),
			),
			'control_display' => array(
				'check' => 'toggle_bar_display',
				'value' => 'overlay',
			),
		),
		array(
			'id' => 'toggle_bar_remember_state',
			'default' => false,
			'control' => array(
				'label' => esc_html__( 'Remember state?', 'total' ),
				'type' => 'checkbox',
				'description' => esc_html__( 'If enabled the theme will store a cookie whenever the state changes so the next time the user visits the site in the same browser it will display in that state.', 'total' ),
			),
		),
		array(
			'id' => 'toggle_bar_enable_dismiss',
			'default' => false,
			'control' => array(
				'label' => esc_html__( 'Disable Toggle?', 'total' ),
				'type' => 'checkbox',
				'description' => esc_html__( 'When checked, the theme will display a close button only (x) instead of allowing users to open and close the Toggle Bar.', 'total' ),
			),
			'control_display' => array(
				'check' => 'toggle_bar_default_state',
				'value' => 'visible',
			),
		),
		// Button
		array(
			'id' => 'toggle_bar_button_heading',
			'control' => array(
				'type' => 'wpex-heading',
				'label' => esc_html__( 'Toggle Button', 'total' ),
			),
			'control_display' => array(
				'check' => 'toggle_bar_enable_dismiss',
				'value' => 'false',
			),
		),
		array(
			'id' => 'toggle_bar_button_icon',
			'default' => 'plus',
			'control' => array(
				'label' => esc_html__( 'Button Icon', 'total' ),
				'type' => 'wpex-fa-icon-select',
			),
			'control_display' => array(
				'check' => 'toggle_bar_enable_dismiss',
				'value' => 'false',
			),
		),
		array(
			'id' => 'toggle_bar_button_icon_active',
			'default' => 'minus',
			'control' => array(
				'label' => esc_html__( 'Button Icon: Active', 'total' ),
				'type' => 'wpex-fa-icon-select',
			),
			'control_display' => array(
				'check' => 'toggle_bar_enable_dismiss',
				'value' => 'false',
			),
		),
		array(
			'id' => 'toggle_bar_btn_bg',
			'transport' => 'postMessage',
			'control' => array(
				'type' => 'color',
				'label' => esc_html__( 'Button Background', 'total' ),
			),
			'inline_css' => array(
				'target' => '.toggle-bar-btn',
				'alter' => array( 'border-top-color', 'border-right-color' ),
			),
			'control_display' => array(
				'check' => 'toggle_bar_enable_dismiss',
				'value' => 'false',
			),
		),
		array(
			'id' => 'toggle_bar_btn_color',
			'transport' => 'postMessage',
			'control' => array(
				'type' => 'color',
				'label' => esc_html__( 'Button Color', 'total' ),
			),
			'inline_css' => array(
				'target' => '.toggle-bar-btn span',
				'alter' => 'color',
			),
			'control_display' => array(
				'check' => 'toggle_bar_enable_dismiss',
				'value' => 'false',
			),
		),
		array(
			'id' => 'toggle_bar_btn_hover_bg',
			'transport' => 'postMessage',
			'control' => array(
				'type' => 'color',
				'label' => esc_html__( 'Button Hover Background', 'total' ),
			),
			'inline_css' => array(
				'target' => '.toggle-bar-btn:hover',
				'alter' => array( 'border-top-color', 'border-right-color' ),
			),
			'control_display' => array(
				'check' => 'toggle_bar_enable_dismiss',
				'value' => 'false',
			),
		),
		array(
			'id' => 'toggle_bar_btn_hover_color',
			'transport' => 'postMessage',
			'control' => array(
				'type' => 'color',
				'label' => esc_html__( 'Button Hover Color', 'total' ),
			),
			'inline_css' => array(
				'target' => '.toggle-bar-btn:hover span',
				'alter' => 'color',
			),
			'control_display' => array(
				'check' => 'toggle_bar_enable_dismiss',
				'value' => 'false',
			),
		),

		// Design
		array(
			'id' => 'toggle_bar_design_heading',
			'control' => array(
				'type' => 'wpex-heading',
				'label' => esc_html__( 'Design', 'total' ),
			),
		),
		array(
			'id' => 'toggle_bar_padding_y',
			'control' => array(
				'label' => esc_html__( 'Vertical Padding', 'total' ),
				'type'  => 'select',
				'choices' => wpex_utl_margins(),
			),
		),
		array(
			'id' => 'toggle_bar_min_height',
			'transport' => 'postMessage',
			'control' => array(
				'label' => esc_html__( 'Minimum Height', 'total' ),
				'type'  => 'text',
			),
			'inline_css' => array(
				'target' => '#toggle-bar',
				'alter' => 'min-height',
			),
		),
		array(
			'id' => 'toggle_bar_bg',
			'transport' => 'postMessage',
			'control' => array(
				'type' => 'color',
				'label' => esc_html__( 'Content Background', 'total' ),
			),
			'inline_css' => array(
				'target' => '#toggle-bar-wrap',
				'alter' => 'background',
			),
		),
		array(
			'id' => 'toggle_bar_color',
			'transport' => 'postMessage',
			'control' => array(
				'type' => 'color',
				'label' => esc_html__( 'Content Text Color', 'total' ),
			),
			'inline_css' => array(
				'target' => array(
					'#toggle-bar-wrap',
					'#toggle-bar-wrap strong',
				),
				'alter' => 'color',
			),
		),
		array(
			'id' => 'toggle_bar_link_color',
			'transport' => 'postMessage',
			'control' => array(
				'type' => 'color',
				'label' => esc_html__( 'Content Link Color', 'total' ),
				'description' => esc_html__( 'Will only target links without classnames to prevent issues with utility classes, shortcodes and other elements.', 'total' ),
			),
			'inline_css' => array(
				'target' => array(
					'#toggle-bar-wrap a:not([class])',
				),
				'alter' => 'color',
			),
		),
		array(
			'id' => 'toggle_bar_border',
			'transport' => 'postMessage',
			'control' => array(
				'type' => 'color',
				'label' => esc_html__( 'Content Border Color', 'total' ),
				'description' => esc_html__( 'Used for the "Inline" display style bottom border.', 'total' ),
			),
			'inline_css' => array(
				'target' => '#toggle-bar-wrap',
				'alter' => 'border-color',
				'important' => true,
			),
		),
	)
);