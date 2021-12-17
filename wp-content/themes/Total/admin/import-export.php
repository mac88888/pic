<?php
namespace TotalTheme\Admin;

defined( 'ABSPATH' ) || exit;

/**
 * Creates the admin panel for the customizer.
 *
 * @package Total WordPress theme
 * @subpackage Admin
 * @version 5.3
 */
final class Import_Export {

	/**
	 * Instance.
	 *
	 * @access private
	 * @var object Class object.
	 */
	private static $instance;

	/**
	 * Create or retrieve the instance of Import_Export.
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

		if ( ! is_admin() ) {
			return;
		}

		add_action( 'admin_menu', array( $this, 'add_admin_submenu_page' ), 9999 );
		add_action( 'admin_init', array( $this, 'register_settings' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'maybe_enqueue_scripts' ) );
		add_action( 'admin_notices', array( $this, 'notices' ) );

	}

	/**
	 * Add sub menu page
	 *
	 * @since 1.6.0
	 */
	public function add_admin_submenu_page() {
		add_submenu_page(
			WPEX_THEME_PANEL_SLUG,
			esc_attr__( 'Import/Export', 'total' ),
			esc_attr__( 'Import/Export', 'total' ),
			'manage_options',
			WPEX_THEME_PANEL_SLUG . '-import-export',
			array( $this, 'create_admin_page' )
		);
	}

	/**
	 * Register a setting and its sanitization callback.
	 *
	 * @since 1.6.0
	 */
	public function register_settings() {
		register_setting(
			'wpex_customizer_options',
			'wpex_customizer_options',
			array(
				'sanitize_callback' => array( $this, 'save_options' ),
				'default' => null,
			)
		);
	}

	/**
	 * Register scripts.
	 *
	 * @since 5.1.3
	 */
	public function maybe_enqueue_scripts( $hook_suffix ) {

		if ( 'theme-panel_page_wpex-panel-import-export' !== $hook_suffix ) {
			return;
		}

		wp_enqueue_script(
			'wpex-import-export',
			wpex_asset_url( 'js/dynamic/admin/wpex-import-export.min.js' ),
			array( 'jquery' ),
			WPEX_THEME_VERSION,
			true
		);

		wp_localize_script( 'wpex-import-export', 'wpex_import_export_l10n', array(
			'confirmReset'  => esc_html__( 'Confirm Reset', 'total' ),
			'importOptions' => esc_html__( 'Import Options', 'total' ),
		) );

	}

	/**
	 * Displays all messages registered to 'wpex-customizer-notices'.
	 *
	 * @since 1.6.0
	 */
	public function notices() {
		settings_errors( 'wpex-customizer-notices' );
	}

	/**
	 * Save options.
	 *
	 * @since 1.6.0
	 */
	public function save_options( $options ) {

		// Import the imported options.
		if ( $options ) {

			// Delete options if import set to -1.
			if ( isset( $options['reset'] ) && '-1' == $options['reset'] ) {

				// Get menu locations.
				$locations 	= get_theme_mod( 'nav_menu_locations' );
				$save_menus	= array();

				if ( $locations ) {

					foreach( $locations as $key => $val ) {

						$save_menus[$key] = $val;
					}

				}

				// Get sidebars.
				$widget_areas = get_theme_mod( 'widget_areas' );

				// Remove all mods.
				remove_theme_mods();

				// WP fix. Logo doesn't get removed with remove_theme_mods();
				set_theme_mod( 'custom_logo', '' );
				remove_theme_mod( 'custom_logo' );

				// Re-add the menus.
				set_theme_mod( 'nav_menu_locations', array_map( 'absint', $save_menus ) );
				set_theme_mod( 'widget_areas', $widget_areas );

				// Error messages.
				$error_msg	= esc_attr__( 'All settings have been reset.', 'total' );
				$error_type	= 'updated';

			}
			// Set theme mods based on json data.
			elseif( ! empty( $options['import'] ) ) {

				// Decode input data.
				$theme_mods = json_decode( $options['import'], true );

				// Validate json file then set new theme options.
				if ( function_exists( 'json_last_error' ) && defined( 'JSON_ERROR_NONE' ) ) {

					if ( JSON_ERROR_NONE === json_last_error() ) {

						// Loop through mods and add them.
						foreach ( $theme_mods as $theme_mod => $value ) {
							set_theme_mod( $theme_mod, $value );
						}

						// Success message.
						$error_msg  = esc_attr__( 'Settings imported successfully.', 'total' );
						$error_type = 'updated';

					}

					// Display invalid json data error.
					else {

						$error_msg  = esc_attr__( 'Invalid Import Data.', 'total' );
						$error_type = 'error';

					}

				} else {

					$error_msg  = esc_attr__( 'The version of PHP on your server is very outdated and can not support a proper import. Please make sure your server has been updated to the WordPress "supported" version of PHP.', 'total' );
					$error_type = 'error';

				}

			}

			// No json data entered.
			else {
				$error_msg = esc_attr__( 'No import data found.', 'total' );
				$error_type = 'error';
			}

			// Display message.
			add_settings_error(
				'wpex-customizer-notices',
				esc_attr( 'settings_updated' ),
				$error_msg,
				$error_type
			);

		}

	}

	/**
	 * Settings page output.
	 *
	 * @since 1.6.0
	 */
	public function create_admin_page() {

		?>

		<div class="wpex-theme-import-export wrap">

		<h1><?php esc_html_e( 'Import, Export or Reset Theme Settings', 'total' ); ?></h1>

		<div class="notice notice-warning"><p><?php esc_html_e( 'This will export/import/delete ALL theme_mods that means if other plugins are adding settings in the Customizer it will export/import/delete those as well.', 'total' ); ?></p></div>

		<?php
		// Default options.
		$options = array(
			'import' => '',
			'reset'  => '',
		); ?>

		<form method="post" action="options.php">

			<?php
			// Output nonce, action, and option_page fields for a settings page.
			$options = get_option( 'wpex_customizer_options', $options );
			settings_fields( 'wpex_customizer_options' );

			?>

			<table class="form-table">

				<tr valign="top">

					<th scope="row"><?php esc_html_e( 'Export Settings', 'total' ); ?></th>

					<td>
						<?php
						// Get an array of all the theme mods.
						if ( $theme_mods = get_theme_mods() ) {
							$mods = array();
							foreach ( $theme_mods as $theme_mod => $value ) {
								$mods[$theme_mod] = maybe_unserialize( $value );
							}
							$json = json_encode( $mods );
							$disabled = '';
						} else {
							$json     = esc_attr__( 'No Settings Found', 'total' );
							$disabled = ' disabled';
						}
						echo '<textarea class="wpex-theme-import-export__settings" rows="10" cols="50" readonly style="width:100%;">' . $json . '</textarea>'; ?>
						<p class="submit">
							<a href="#" class="wpex-theme-import-export__highlight button-primary<?php echo esc_attr( $disabled ); ?>"><?php esc_html_e( 'Highlight Options', 'total' ); ?></a>
						</p>
					</td>
				</tr>

				<tr valign="top">
					<th scope="row"><?php esc_html_e( 'Import Settings', 'total' ); ?></th>
					<td>
						<textarea name="wpex_customizer_options[import]" rows="10" cols="50" style="width:100%;"></textarea>
						<input class="wpex-theme-import-export__reset" name="wpex_customizer_options[reset]" type="hidden" value=""></input>
						<p class="submit">
							<input type="submit" class="wpex-theme-import-export__submit button-primary" value="<?php esc_attr_e( 'Import Options', 'total' ) ?>">
							<a href="#" class="wpex-theme-import-export__delete button-secondary"><?php esc_html_e( 'Reset Options', 'total' ); ?></a>
							<a href="#" class="wpex-theme-import-export__delete-cancel button-secondary" style="display:none;"><?php esc_html_e( 'Cancel Reset', 'total' ); ?></a>
						</p>
						<div class="wpex-theme-import-export__warning error inline" style="display:none;">
							<p style="margin:.5em 0;"><?php esc_attr_e( 'Always make sure you have a backup of your settings before resetting, just incase! Your menu locations and widget areas will not reset and will remain intact. All customizer and addon settings will reset.', 'total' ); ?></p>
						</div>
					</td>
				</tr>
			</table>
		</form>

		</div>

	<?php }

}