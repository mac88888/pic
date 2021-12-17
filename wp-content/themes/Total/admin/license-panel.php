<?php
namespace TotalTheme\Admin;

defined( 'ABSPATH' ) || exit;

/**
 * Theme License Activation and De-activation.
 *
 * @package Total WordPress theme
 * @subpackage Admin
 * @version 5.3
 */
final class License_Panel {

	/**
	 * Instance.
	 *
	 * @access private
	 * @var object Class object.
	 */
	private static $instance;

	/**
	 * Create or retrieve the instance of License_Panel.
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

		if ( false === get_transient( 'wpex_verify_active_license' ) ) {
			add_action( 'admin_init', array( $this, 'verify_license' ) );
		}

		add_action( 'admin_menu', array( $this, 'add_admin_submenu_page' ), 1 );
		add_action( 'admin_enqueue_scripts', array( $this, 'maybe_enqueue_scripts' ) );
		add_action( 'wp_ajax_wpex_theme_license_form', array( $this, 'license_form_ajax' ) );

		if ( ! wpex_get_theme_license() && ! get_option( 'total_dismiss_license_notice', false ) ) {
			add_action( 'admin_notices', array( $this, 'admin_notice' ) );
		}

	}

	/**
	 * Verify license every.
	 *
	 * @since 4.5.4
	 */
	public function verify_license() {
		wpex_verify_active_license();
		set_transient( 'wpex_verify_active_license', 1, WEEK_IN_SECONDS );
	}

	/**
	 * Return sanitized current site URL.
	 *
	 * @since 4.5
	 */
	public function get_site_url() {
		return rawurlencode( trim( site_url() ) );
	}

	/**
	 * Add sub menu page.
	 *
	 * @since 4.5
	 */
	public function add_admin_submenu_page() {
		add_submenu_page(
			WPEX_THEME_PANEL_SLUG,
			esc_html__( 'Theme License', 'total' ),
			esc_html__( 'Theme License', 'total' ),
			'administrator', // admin only!
			WPEX_THEME_PANEL_SLUG . '-theme-license',
			array( $this, 'theme_license_page' )
		);
	}

	/**
	 * Maybe enqueue scripts.
	 *
	 * @since 5.1.2
	 */
	public function maybe_enqueue_scripts( $hook ) {

		if ( 'theme-panel_page_wpex-panel-theme-license' !== $hook ) {
			return;
		}

		$this->enqueue_scripts();

	}

	/**
	 * Enqueue scripts.
	 *
	 * @since 5.1.2
	 */
	public function enqueue_scripts() {

		wp_enqueue_style(
			'wpex-license-activation',
			wpex_asset_url( 'css/wpex-license-activation.css' ),
			array(),
			WPEX_THEME_VERSION
		);

		wp_enqueue_script(
			'wpex-license-activation',
			wpex_asset_url( 'js/dynamic/admin/wpex-license-activation.min.js' ),
			array( 'jquery' ),
			WPEX_THEME_VERSION,
			true
		);

	}

	/**
	 * Settings page output.
	 *
	 * @since 4.5
	 */
	public function theme_license_page() {

		$this->enqueue_scripts(); // backup.

		if ( isset( $_GET['troubleshoot'] ) ) {
			$this->troubleshoot();
			return;
		}

		$license = wpex_get_theme_license();

		$license = wpex_verify_active_license( $license ) ? $license : null;

		$is_dev = get_option( 'active_theme_license_dev' );

		$license_cleared = ! empty( $_GET[ 'license-cleared' ] ) ? true : false; ?>

		<div class="wrap wpex-license-activation">

			<h1><?php esc_html_e( 'Theme License', 'total' ); ?></h1>

			<?php if ( $license || $license_cleared ) {

				$notice_type = 'updated';

				if ( $is_dev || $license_cleared ) {
					$notice_type = 'notice-warning';
				}

				?>

				<div class="wpex-license-activation__notice notice <?php echo esc_attr( $notice_type ); ?>">
					<?php if ( $license_cleared ) { ?>
						<p><?php echo wp_kses_post( __( 'The current URL did not match the URL of the registered license. Your license has been removed from this site but remains active on the original URL. You can now enter a new license for this site.', 'total' ) ); ?>
					<?php } elseif ( $is_dev ) { ?>
						<p><?php esc_html_e( 'Your site is currently active as a development environment.', 'total' ); ?></p>
					<?php } else { ?>
						<p><?php esc_html_e( 'Congratulations. Your theme license is active.', 'total' ); ?></p>
					<?php } ?>
				</div>

			<?php } else { ?>

				<div class="wpex-license-activation__notice notice"></div>

			<?php } ?>

			<div class="wpex-license-activation__card">

				<h2 class="wpex-license-activation__heading"><?php esc_html_e( 'Verify your License', 'total' ); ?></h2>

				<div class="wpex-license-activation__card-inner">

					<p class="wpex-license-activation__info"><?php echo wp_kses_post( __( 'Enter your purchase code below and click the activate button or hit enter. You can learn how to find your purchase code <a target="_blank" rel="noopener noreferrer" href="https://wpexplorer-themes.com/total/docs/how-to-find-your-total-theme-license/">here</a>.', 'total' ) ); ?></p>

					<form method="post" class="wpex-license-activation__form">

						<?php if ( $license ) { ?>

							<input type="text" class="wpex-license-activation__input" name="license" placeholder="<?php echo esc_attr( $license ); ?>" value="<?php echo esc_attr( $license ); ?>" readonly="readonly" autocomplete="off" onclick="select()">

						<?php } else { ?>

							<input type="text" class="wpex-license-activation__input" name="license" placeholder="<?php esc_html_e( 'Enter your purchase code here.', 'total' ); ?>" autocomplete="off">

						<?php } ?>

						<?php if ( ! $license ) { ?>
							<p class="wpex-license-activation__dev">
								<input type="checkbox" name="devlicense" class="wpex-license-activation__input-dev" id="wpex_dev_license"><label for="wpex_dev_license"><?php echo wp_kses_post( __( 'Check this box if this is your development environment (not the final or live website)', 'total' ) ); ?></label>
							</p>
						<?php } ?>

						<?php wp_nonce_field( 'wpex_theme_license_form_nonce', 'wpex_theme_license_form_nonce' ); ?>

						<div class="wpex-license-activation__submit">

							<?php
							$submit_classes = 'wpex-license-activation__button primary button-hero ';
							$submit_classes .= $license ? 'deactivate' : 'activate';
							$activate_txt   = esc_html__( 'Activate your license', 'total' );
							$deactivate_txt = esc_html__( 'Deactivate your license', 'total' );
							submit_button(
								$license ? $deactivate_txt : $activate_txt,
								$submit_classes,
								'submit',
								false,
								array(
									'data-activate'   => $activate_txt,
									'data-deactivate' => $deactivate_txt,
								)
							); ?>

							<div class="wpex-license-activation__spinner"><?php wpex_svg( 'wp-spinner' ); ?></div>

						</div>

					</form>

					<p class="wpex-license-activation__description"><?php echo wp_kses_post( __( 'A purchase code (license) is only valid for <strong>One WordPress Installation</strong> (single or multisite). Are you already using this theme on another installation? Purchase <a target="_blank" rel="noopener noreferrer" href="https://themeforest.net/item/total-responsive-multipurpose-wordpress-theme/6339019?ref=WPExplorer&license=regular&open_purchase_for_item_id=6339019">new license here</a> to get your new purchase code. If you are running a multisite network you only need to activate your license on the main site.', 'total' ) ); ?></p>

				</div>

			</div>

			<div class="wpex-license-activation__troubleshoot">
				<a href="https://wpexplorer-themes.com/support/" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Manage Licenses', 'total' ); ?></a> | <a href="<?php echo esc_url( admin_url( 'admin.php?page=wpex-panel-theme-license&troubleshoot=1' ) ); ?>"><?php esc_html_e( 'Troubleshoot', 'total' ); ?></a>
			</div>

		</div>

	<?php }

	/**
	 * Troubleshoot.
	 *
	 * @since 5.0
	 */
	public function troubleshoot() {

		echo '<div class="wrap">';

			echo '<h1>License API Troubleshooting</h1>';

			if ( ! function_exists( 'wp_remote_retrieve_response_code' ) ) {
				echo 'Looks like the wp_remote_retrieve_response_code function doesnt exist, make sure you update WordPress';
				return;
			}

			$remote_response = wp_remote_get( 'https://wpexplorer-themes.com/activate-license/?troubleshoot=1' );

			$response_code = intval( wp_remote_retrieve_response_code( $remote_response ) );

			echo '<div class="wpex-license-activation__response wpex-license-activation__response--' . sanitize_html_class( $response_code ) . '">';

				switch ( $response_code ) {
					case 200:
						echo '<div><strong>' . json_decode( wp_remote_retrieve_body( $remote_response ) ) . '</strong></div>';
						break;
					case 301:
						echo '<div><strong>301 Error</strong>: Firewall blocking access.</div>';
						break;
					case 403:
						echo '<div><strong>Forbidden</strong>: Your server has been blocked by our firewall for security reasons.</div>';
						break;
					case 404:
						echo '<div><strong>404 Error</strong>: Please contact the theme developer for assistance.</div>';
						break;
					default:
						if ( isset( $remote_response->errors ) && is_array( $remote_response->errors ) ) {
							foreach ( $remote_response->errors as $k => $v ) {
								if ( empty( $v[0] ) ) {
									continue;
								}
								echo '<div><strong>' . $k . '</strong>: ' . $v[0] . '</div>';
							}

						}
						break;
				}

			echo '</div>';

		echo '</div>';

	}

	/**
	 * Activate License.
	 *
	 * @since 4.5
	 */
	public function activate_license( $license, $dev, $response ) {
		$args = array(
			'market'     => 'envato',
			'product_id' => '6339019',
			'license'    => $license,
			'url'        => $this->get_site_url(),
		);
		if ( $dev ) {
			$args['dev'] = '1';
		}
		$remote_url = add_query_arg( $args, 'https://wpexplorer-themes.com/activate-license/' );
		$remote_response = wp_remote_get( $remote_url );
		if ( is_wp_error( $remote_response ) ) {
			$response['message'] = $response->get_error_message();
		} else {
			$remote_response_code = wp_remote_retrieve_response_code( $remote_response );
			$response['response_code'] = $remote_response_code;
			if ( 200 == $remote_response_code ) {
				$result = json_decode( wp_remote_retrieve_body( $remote_response ) );
				$status = $result->status;
				if ( 'active' === $status ) {
					$response['success'] = true;
					$response['message'] = esc_html__( 'Congratulations. Your theme license is active.', 'total' );
					$response['messageClass'] = 'updated';
					update_option( 'active_theme_license', $license );
					if ( $dev ) {
						update_option( 'active_theme_license_dev', true );
					}
				} else {
					switch ( $status ) {
						case 'api_error':
							$response['message'] = esc_html__( 'The license code is not properly formated or couldn\'t be validated by the Envato API.', 'total' );
							break;
						case 'wrong_product':
							$response['message'] = esc_html__( 'This license code is for a different product.', 'total' );
							break;
						case 'invalid':
							$response['message'] = esc_html__( 'This license code is not valid.', 'total' );
							break;
						case 'duplicate':
							$response['message'] = esc_html__( 'This license is already in use. Click the "manage licenses" link below to log in with your Envato ID and manage your licenses.', 'total' );
							break;
						default:
							if ( ! empty( $result->error ) ) {
								$response['message'] = $result['error'];
							}
							break;
					}
					$response['messageClass'] = 'notice-error';
				}
			} else {
				$response['message'] = esc_html( 'Can not connect to the verification server at this time. Please make sure outgoing connections are enabled on your server and try again. If it still does not work please wait a few minutes and try again.', 'total' );
			}
		}
		return $response;
	}

	/**
	 * Deactivate License.
	 *
	 * @since 4.5
	 */
	public function deactivate_license( $license, $dev, $response ) {
		$args = array(
			'market'  => 'envato',
			'license' => $license,
			'url'     => $this->get_site_url(),
		);
		if ( $dev ) {
			$args['dev'] = '1';
		}
		$remote_url = add_query_arg( $args, 'https://wpexplorer-themes.com/deactivate-license/' );
		$remote_response = wp_remote_get( $remote_url );
		if ( is_wp_error( $remote_response ) ) {
			$response['message'] = $response->get_error_message();
		} else {
			$remote_response_code = wp_remote_retrieve_response_code( $remote_response );
			$response['response_code'] = $remote_response_code;
			if ( 200 == $remote_response_code ) {
				$result = json_decode( wp_remote_retrieve_body( $remote_response ) );
				if ( 'success' === $result->status ) {
					delete_option( 'active_theme_license' );
					delete_option( 'active_theme_license_dev' );
					$response['message'] = esc_html__( 'The license has been deactivated successfully.', 'total' );
					$response['messageClass'] = 'notice-warning';
					$response['success'] = true;
				} elseif ( $result->message ) {
					$response['message'] = $result->message;
				} else {
					$response['message'] = $result;
				}
				if ( isset( $result->clearLicense ) ) {
					delete_option( 'active_theme_license' );
					delete_option( 'active_theme_license_dev' );
					$response['success']      = true;
					$response['clearLicense'] = true;
					$response['message']      =  '';
				}
			} else {
				$response['message'] = esc_html( 'Can not connect to the verification server at this time, please try again in a few minutes.', 'total' );
			}
		}
		return $response;
	}

	/**
	 * License form ajax.
	 *
	 * @since 4.5
	 */
	public function license_form_ajax() {

		if ( empty( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'wpex_theme_license_form_nonce' ) ) {
			wp_die();
		}

		$response = array(
			'message'       => '',
			'messageClass'  => 'notice-error',
			'success'       => false,
			'response_code' => '',
		);
		$license = isset( $_POST['license'] ) ? trim( wp_strip_all_tags( $_POST['license'] ) ) : '';
		$process = isset( $_POST['process'] ) ? wp_strip_all_tags( $_POST['process'] ) : '';

		if ( 'deactivate' === $process ) {
			$response = $this->deactivate_license( $license, get_option( 'active_theme_license_dev', false ), $response );
			wp_send_json( $response );
		}

		elseif ( 'activate' === $process ) {

			$dev = ( isset( $_POST['devlicense'] ) && 'checked' === $_POST['devlicense'] ) ? true : false;

			if ( empty( $license ) ) {
				$response['message']      = esc_html__( 'Please enter a license.', 'total' );
				$response['messageClass'] = 'notice-warning';
			} else {
				$response = $this->activate_license( $license, $dev, $response );
			}

			wp_send_json( $response );

		}

		wp_die();

	}

	/**
	 * Admin Notice.
	 *
	 * @since 4.9.6
	 */
	public function admin_notice() {

		if ( isset( $_GET['total-dismiss'] )
			&& 'license-nag' === $_GET['total-dismiss']
			&& isset( $_GET[ 'total_dismiss_license_nag_nonce' ] )
			&& wp_verify_nonce( $_GET['total_dismiss_license_nag_nonce'], 'total_dismiss_license_nag' )
		) {
			update_option( 'total_dismiss_license_notice', true );
			return;
		}

		$screen = get_current_screen();

	    if ( in_array( $screen->id, array( 'dashboard', 'themes', 'plugins' ) ) ) { ?>

			<div class="notice notice-warning is-dismissible">
				<p><strong><?php esc_html_e( 'Activate Theme License', 'total' ); ?></strong>: <?php echo esc_html_e( 'Don\'t forget to activate your theme license to receive updates and support.', 'total' ); ?></p>
				<p><strong><a href="<?php echo esc_url( admin_url( 'admin.php?page=wpex-panel-theme-license' ) ); ?>"><?php esc_html_e( 'Activate your license', 'total' ); ?></a> | <a href="<?php echo esc_url( wp_nonce_url( add_query_arg( 'total-dismiss', 'license-nag' ), 'total_dismiss_license_nag', 'total_dismiss_license_nag_nonce'  ) ); ?>"><?php esc_html_e( 'Dismiss notice', 'total' ); ?></a></strong></p>
			</div>

		<?php }
	}

}