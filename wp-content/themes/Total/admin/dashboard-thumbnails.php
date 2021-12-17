<?php
namespace TotalTheme\Admin;

defined( 'ABSPATH' ) || exit;

/**
 * Display thumbnails in the dashboard.
 *
 * @package TotalTheme
 * @version 5.3
 */
final class Dashboard_Thumbnails {

	/**
	 * Instance.
	 *
	 * @access private
	 * @var object Class object.
	 */
	private static $instance;

	/**
	 * Create or retrieve the instance of Dashboard_Thumbnails.
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
	 * @since 5.3
	 */
	public function __construct() {
		add_action( 'admin_init', __CLASS__ . '::initialize' );
	}

	/**
	 * Start things up.
	 *
	 * @since 5.3
	 */
	public static function initialize() {

		$post_types = array(
			'post',
			'page',
			'portfolio',
			'staff',
			'testimonials',
		);

		$post_types = (array) apply_filters( 'wpex_dashboard_thumbnails_post_types', $post_types );

		if ( empty( $post_types ) ) {
			return;
		}

		foreach ( $post_types as $post_type ) {
			if ( post_type_supports( $post_type, 'thumbnail' ) ) {
				add_filter( 'manage_' . $post_type . '_posts_columns',  __CLASS__ . '::add_columns' );
			}
		}

		add_action( 'manage_posts_custom_column',  __CLASS__ . '::display_columns', 10, 2 );

		if ( in_array( 'page', $post_types ) ) {
			add_action( 'manage_pages_custom_column',  __CLASS__ . '::display_columns', 10, 2 );
		}

	}

	/**
	 * Add new admin columns.
	 *
	 * @since 5.0.6
	 */
	public static function add_columns( $columns ) {
		$columns['wpex_post_thumbs'] = esc_html__( 'Thumbnail', 'total' );
		return $columns;
	}

	/**
	 * Display custom columns.
	 *
	 * @since 5.0.6
	 */
	public static function display_columns( $column_name, $id ) {
		if ( 'wpex_post_thumbs' === $column_name ) {
			if ( has_post_thumbnail( $id ) ) {
				the_post_thumbnail(
					'thumbnail',
					array( 'style' => 'width:80px;height:80px;max-width:100%;' )
				);
			} else {
				echo '&#8212;';
			}
		}
	}

}