<?php
/**
 * Remove navigation menu id's
 *
 * @package TotalTheme
 * @version 5.0
 */

defined( 'ABSPATH' ) || exit;

add_filter( 'nav_menu_item_id', '__return_false' );