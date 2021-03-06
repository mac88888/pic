<?php
/**
 * Remove WP emoji scripts
 *
 * @package TotalTheme
 * @version 5.3
 */

defined( 'ABSPATH' ) || exit;

remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
remove_action( 'wp_print_styles', 'print_emoji_styles' );
remove_action( 'admin_print_styles', 'print_emoji_styles' );
remove_action( 'the_content', 'convert_smilies', 20 );