<?php
/**
 * Customizer HR Control.
 *
 * @package TotalTheme
 * @subpackage Customizer
 * @version 5.3
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'WPEX_Customizer_Hr_Control' ) ) {

	class WPEX_Customizer_Hr_Control extends WP_Customize_Control {

		/**
		 * The control type.
		 *
		 * @access public
		 * @var string
		 */
		public $type = 'hr';

		/**
		 * The control template.
		 *
		 * @since 3.6.0
		 */
		public function content_template() { ?>

			<hr>

		<?php }

	}

}