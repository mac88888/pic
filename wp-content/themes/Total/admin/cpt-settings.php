<?php
namespace TotalTheme\Admin;

defined( 'ABSPATH' ) || exit;

/**
 * Adds a Post Type Editor Panel for defined Post Types.
 *
 * @package TotalTheme
 * @version 5.3
 */
class Cpt_Settings {
	private $types;
	private $post_type;

	/**
	 * Instance.
	 *
	 * @access private
	 * @var object Class object.
	 */
	private static $instance;

	/**
	 * Create or retrieve the instance of Cpt_Settings.
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
	 */
	public function init_hooks() {
		$types = array( 'portfolio', 'staff', 'testimonials' );
		$this->types = apply_filters( 'wpex_post_type_editor_types', $types );

		if ( empty( $this->types ) ) {
			return;
		}

		$this->post_type = ! empty( $_GET['post_type'] ) ? wp_strip_all_tags( $_GET['post_type'] ) : '';

		add_action( 'admin_menu', array( $this, 'add_submenu_pages' ), 40 );
		add_action( 'admin_init', array( $this, 'register_page_options' ), 40 );
		add_action( 'admin_notices', array( $this, 'setting_notice' ), 40 );
	}

	/**
	 * Enqueue scripts for the Post Type Editor Panel.
	 */
	public function enqueue_scripts() {

		wp_enqueue_style( 'wpex-chosen' );
		wp_enqueue_script( 'wpex-chosen' );
		wp_enqueue_script( 'wpex-chosen-icon' );

		wp_enqueue_style( 'wpex-admin-pages' );
		wp_enqueue_script( 'wpex-admin-pages' );

	}

	/**
	 * Return array of settings.
	 */
	public function get_settings( $type ) {
		return array(
			'page' => array(
				'label' => esc_html__( 'Main Page', 'total' ),
				'type'  => 'wp_dropdown_pages',
				'description' => esc_html__( 'Used for theme breadcrumbs when the auto archive is disabled.', 'total' ),
			),
			'admin_icon' => array(
				'label' => esc_html__( 'Admin Icon', 'total' ),
				'type'  => 'dashicon',
				'default' => array(
					'staff'        => 'businessman',
					'portfolio'    => 'portfolio',
					'testimonials' => 'testimonial',
				),
			),
			'has_archive' => array(
				'label' => esc_html__( 'Enable Auto Archive?', 'total' ),
				'type'  => 'checkbox',
				'description' => esc_html__( 'Disabled by default so you can create your archive page using a page builder.', 'total' ),
			),
			'archive_orderby' => array(
				'label' => esc_html__( 'Archive Orderby', 'total' ),
				'type'  => 'select',
				'choices' => array(
					'' => esc_html__( 'Default', 'total' ),
					'date' => esc_html__( 'Date', 'total' ),
					'title' => esc_html__( 'Title', 'total' ),
					'name' => esc_html__( 'Name (post slug)', 'total' ),
					'modified' => esc_html__( 'Modified', 'total' ),
					'author' => esc_html__( 'Author', 'total' ),
					'parent' => esc_html__( 'Parent', 'total' ),
					'ID' => esc_html__( 'ID', 'total' ),
					'comment_count' => esc_html__( 'Comment Count', 'total' ),
				),

			),
			'archive_order' => array(
				'label' => esc_html__( 'Archive Order', 'total' ),
				'type'  => 'select',
				'choices' => array(
					'' => esc_html__( 'Default', 'total' ),
					'DESC' => esc_html__( 'Descending', 'total' ),
					'ASC' => esc_html__( 'Ascending', 'total' ),
				),

			),
			'has_single' => array(
				'label' => esc_html__( 'Enable Single Post?', 'total' ),
				'type'  => 'checkbox',
				'default' => true,
			),
			'show_in_rest' => array(
				'label' => esc_html__( 'Show in Rest?', 'total' ),
				'type'  => 'checkbox',
				'default' => false,
				'description' => esc_html__( 'Enables support for the Gutenberg Editor.', 'total' ),
			),
			'custom_sidebar' => array(
				'label' => esc_html__( 'Enable Custom Sidebar?', 'total' ),
				'type'  => 'checkbox',
				'default' => true,
			),
			'search' => array(
				'label' => esc_html__( 'Include in Search Results?', 'total' ),
				'type'  => 'checkbox',
				'default' => true,
			),
			'labels' => array(
				'label' => esc_html__( 'Post Type: Name', 'total' ),
				'type'  => 'text',
			),
			'singular_name' => array(
				'label' => esc_html__( 'Post Type: Singular Name', 'total' ),
				'type'  => 'text',
			),
			'slug' => array(
				'label' => esc_html__( 'Post Type: Slug', 'total' ),
				'type'  => 'text',
			),
			'categories' => array(
				'label' => esc_html__( 'Enable Categories?', 'total' ),
				'type'  => 'checkbox',
				'default' => true,
			),
			'cat_labels' => array(
				'label' => esc_html__( 'Categories: Label', 'total' ),
				'type'  => 'text',
			),
			'cat_slug' => array(
				'label' => esc_html__( 'Categories: Slug', 'total' ),
				'type'  => 'text',
			),
			'tags' => array(
				'label' => esc_html__( 'Enable Tags?', 'total' ),
				'type'  => 'checkbox',
				'default' => true,
				'exclusive' => array( 'portfolio', 'staff' ),
			),
			'tag_labels' => array(
				'label' => esc_html__( 'Tag: Label', 'total' ),
				'type'  => 'text',
				'conditional' => 'has_tags',
				'exclusive' => array( 'portfolio', 'staff' ),
			),
			'tag_slug' => array(
				'label' => esc_html__( 'Tag: Slug', 'total' ),
				'type'  => 'text',
				'conditional' => 'has_tags',
				'exclusive' => array( 'portfolio', 'staff' )
			),
		);
	}

	/**
	 * Get default value.
	 */
	public function get_default( $setting_args ) {
		if ( ! empty( $setting_args['default'] ) ) {
			if ( is_array( $setting_args['default'] ) && isset( $setting_args['default'][$this->post_type] ) ) {
				return $setting_args['default'][$this->post_type];
			}
			return $setting_args['default'];
		}
	}

	/**
	 * Add sub menu page for the Staff Editor.
	 */
	public function add_submenu_pages() {

		foreach ( $this->types as $type ) {

			$post_type_obj = get_post_type_object( $type );

			if ( ! is_object( $post_type_obj ) ) {
				continue;
			}

			$submenu_page = add_submenu_page(
				'edit.php?post_type=' . $type,
				$post_type_obj->labels->name . ' ' . esc_html__( 'Settings', 'total' ),
				esc_html__( 'Settings', 'total' ),
				'manage_options',
				'wpex-' . $type . '-editor',
				array( $this, 'create_admin_page' )
			);

			add_action( 'load-' . $submenu_page, array( $this, 'flush_rewrite_rules' ) );

		}

	}

	/**
	 * Flush re-write rules.
	 */
	public function flush_rewrite_rules() {
		if ( in_array( $this->post_type, $this->types ) ) {
			flush_rewrite_rules();
		}
	}

	/**
	 * Function that will register the staff editor admin page.
	 */
	public function register_page_options() {
		foreach ( $this->types as $type ) {
			register_setting(
				'wpex_' . $type . '_editor_options',
				'wpex_' . $type . '_editor',
				array(
					'sanitize_callback' => array( $this, 'save_options' ),
					'default' => null,
				)
			);
		}
	}

	/**
	 * Displays saved message after settings are successfully saved.
	 */
	public function setting_notice() {
		foreach ( $this->types as $type ) {
			settings_errors( 'wpex_' . $type . '_editor_options' );
		}
	}

	/**
	 * Save settings.
	 */
	public function save_options( $options ) {

		if ( empty( $options ) || empty( $options[ 'post_type'] ) ) {
			return;
		}

		$post_type = $options[ 'post_type'];

		if ( ! in_array( $post_type, $this->types ) ) {
			return;
		}

		$settings = $this->get_settings( $post_type );

		foreach ( $settings as $setting_name => $setting_args ) {

			if ( isset( $setting_args['exclusive'] ) && ! in_array( $post_type, $setting_args['exclusive'] ) ) {
				continue;
			}

			if ( 'has_single' === $setting_name && 'testimonials' !== $post_type ) {
				continue;
			}

			$mod_name     = $post_type . '_' . $setting_name;
			$setting_type = $setting_args['type'];
			$default      = $this->get_default( $setting_args );
			$value        = isset( $options[$mod_name] ) ? $options[$mod_name] : '';

			switch ( $setting_type ) {
				case 'checkbox':
					if ( $default ) {
						if ( $value ) {
							remove_theme_mod( $mod_name );
						} else {
							set_theme_mod( $mod_name, false );
						}
					} else {
						if ( $value ) {
							set_theme_mod( $mod_name, true );
						} else {
							remove_theme_mod( $mod_name );
						}
					}
					break;
				case 'select':
					if ( ! empty( $value )
						&& isset( $setting_args['choices'] )
						&& array_key_exists( $value, $setting_args['choices'] )
					) {
						set_theme_mod( $mod_name, $value );
					} else {
						remove_theme_mod( $mod_name );
					}
					break;
				default:
					if ( $value ) {
						set_theme_mod( $mod_name, $value );
					} else {
						remove_theme_mod( $mod_name );
					}
					break;
			}

		}

		// Show notice after saving.
		add_settings_error(
			'wpex_' . $post_type . '_editor_options',
			'settings_updated',
			esc_html__( 'Settings saved and rewrite rules flushed.', 'total' ),
			'updated'
		);

	}

	/**
	 * Output for the actual Staff Editor admin page.
	 */
	public function create_admin_page() {

		if ( ! in_array( $this->post_type, $this->types ) ) {
			wp_die();
		}

		$post_type_obj = get_post_type_object( $this->post_type );

		$this->enqueue_scripts();

		?>

		<div class="wrap">

			<h2><?php echo ucfirst( esc_html( $post_type_obj->labels->name ) ); ?> <?php esc_html_e( 'Settings', 'total' ); ?></h2>

			<form method="post" action="options.php">

				<table class="form-table">

					<?php

					settings_fields( 'wpex_' . $this->post_type . '_editor_options' );

					$settings = $this->get_settings( $this->post_type );

					foreach ( $settings as $field_id => $field ) :

						if ( isset( $field['exclusive'] ) && ! in_array( $this->post_type, $field['exclusive'] ) ) {
							continue;
						}

						if ( 'has_single' === $field_id && 'testimonials' !== $this->post_type  ) {
							continue;
						}

						$method = 'field_' . $field['type'];

						if ( method_exists( $this, $method ) ) {

							$mod_name         = $this->post_type . '_' . $field_id;
							$field['default'] = $this->get_default( $field );
							$field['id']      = 'wpex_' . $this->post_type . '_editor[' . $mod_name . ']';
							$mod_v            = get_theme_mod( $mod_name, $field['default'] );

							if ( 'checkbox' === $field['type'] ) {
								$field['value'] = ( $mod_v && 'off' !== $mod_v ) ? true : false;
							} else {
								$field['value'] = $mod_v;
							}

							?>

							<tr valign="top">

								<th scope="row"><label for="<?php echo esc_attr( $field['id'] ); ?>"><?php echo esc_html( $field['label'] ); ?></label></th>

								<td>
									<?php $this->$method( $field ); ?>
									<?php if ( ! empty( $field['description'] ) ) { ?>

										<?php if ( 'wp_dropdown_pages' === $field['type'] || 'select' === $field['type'] ) { ?>
											<p><span class="description"><?php
												echo esc_html( $field['description'] );
											?></span></p>
										<?php } else { ?>
											<span class="description"><?php
												echo esc_html( $field['description'] );
											?></span>
										<?php } ?>

									<?php } ?>
								</td>

							</tr>

						<?php } ?>

					<?php endforeach; ?>

				</table>

				<input type="hidden" name="wpex_<?php echo esc_attr( $this->post_type ); ?>_editor[post_type]" value="<?php echo esc_attr( $this->post_type ); ?>">

				<?php submit_button(); ?>

			</form>

		</div>

	<?php }

	/**
	 * Return wp_dropdown_pages field.
	 */
	private function field_wp_dropdown_pages( $field ) {
		wp_dropdown_pages( array(
			'echo'             => true,
			'selected'         => $field['value'],
			'name'             => $field['id'],
			'id'               => $field['id'],
			'class'            => 'wpex-chosen',
			'show_option_none' => esc_html__( 'None', 'total' ),
		) );
	}

	/**
	 * Return select field.
	 */
	private function field_select( $field ) {

		if ( empty( $field['choices'] ) ) {
			return;
		}

		?>

		<select id="<?php echo esc_attr( $field['id'] ); ?>" name="<?php echo esc_attr( $field['id'] ); ?>"><?php
			foreach( $field['choices'] as $ck => $cv ) { ?>
				<option value="<?php echo esc_attr( $ck ); ?>" <?php selected( $field['value'], $ck, true ) ?>><?php
					echo esc_html( $cv );
				?></option>
			<?php }
		?></select>

	<?php }

	/**
	 * Return text field.
	 */
	private function field_text( $field ) {

		$attributes = array(
			'type'  => 'text',
			'id'    => $field['id'],
			'name'  => $field['id'],
			'value' => $field['value'],
		);

		if ( isset( $field['size'] ) ) {
			$attributes['size'] = absint( $field['size'] );
		}

		echo '<input';

	        foreach ( $attributes as $name => $value ) {
	            echo ' ' . $name . '="' . esc_attr( $value ) . '"';
	        }

	    echo '>';

	}

	/**
	 * Return checkbox field.
	 */
	private function field_checkbox( $field ) {

		$attributes = array(
			'type'  => 'checkbox',
			'id'    => $field['id'],
			'name'  => $field['id'],
		);

		if ( isset( $field['class'] ) ) {
			$attributes['class'] = $field['class'];
		}

		echo '<input';

			foreach ( $attributes as $name => $value ) {
	            echo ' ' . $name . '="' . esc_attr( $value ) . '"';
	        }

			checked( $field['value'], true, true );

		echo '>';

	}

	/**
	 * Return dashicon field.
	 *
	 * @since 4.8
	 *
	 * @access private
	 * @return string
	 */
	private function field_dashicon( $field ) {

		$dashicons = wpex_get_dashicons_array();

		?>

		<select name="<?php echo esc_attr( $field['id'] ); ?>"  id="<?php echo esc_attr( $field['id'] ); ?>" class="wpex-chosen-icon-select">

			<?php foreach ( $dashicons as $k => $v ) {

				$class = ( $field['value'] === $k ) ? 'button-primary' : 'button';

				?>

				<option value="<?php echo esc_attr( $k ) ; ?>" data-icon="dashicons dashicons-<?php echo sanitize_html_class( $k ); ?>" <?php selected( $k, $field['value'], true ); ?>><?php echo esc_html( $k ); ?></option>

			<?php } ?>

		</select>

	<?php

	}

}