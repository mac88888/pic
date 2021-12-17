<?php
defined( 'ABSPATH' ) || exit;

/**
 * Customizer Templates Select Control.
 *
 * @package TotalTheme
 * @subpackage Customizer
 * @version 5.1.3
 */
if ( ! class_exists( 'WPEX_Customizer_Dropdown_Templates' ) ) {

	class WPEX_Customizer_Dropdown_Templates extends WP_Customize_Control {

		/**
		 * The control type.
		 */
		public $type = 'wpex-dropdown-templates';

		/**
		 * Render the content
		 */
		public function render_content() {

			$this->choices = wpex_choices_dynamic_templates();

			$input_id                 = '_customize-input-' . $this->id;
			$description_id           = '_customize-description-' . $this->id;
			$describedby_attr_escaped = ( ! empty( $this->description ) ) ? ' aria-describedby="' . esc_attr( $description_id ) . '" ' : '';

			$admin_color = get_user_option( 'admin_color' );
			$admin_color = $admin_color ? ' wpex-customizer-chosen-select--' . $admin_color : '';

			?>

			<?php if ( ! empty( $this->label ) ) : ?>
				<label for="<?php echo esc_attr( $input_id ); ?>" class="customize-control-title"><?php echo esc_html( $this->label ); ?></label>
			<?php endif; ?>

			<?php if ( ! empty( $this->description ) ) : ?>
				<span id="<?php echo esc_attr( $description_id ); ?>" class="description customize-control-description"><?php echo wp_kses_post( $this->description ); ?></span>
			<?php endif; ?>

			<div class="wpex-customizer-chosen-select<?php echo esc_attr( $admin_color ); ?>">

				<select id="<?php echo esc_attr( $input_id ); ?>" <?php echo wp_strip_all_tags( $describedby_attr_escaped ); ?> <?php $this->link(); ?>>

					<?php foreach ( $this->choices as $value => $label ) {

						if ( empty( $value ) && 0 === $this->value() ) {
							$value = '0';
						}

						echo '<option value="' . esc_attr( $value ) . '"' . selected( $this->value(), $value, false ) . '>' . esc_html( $label ) . '</option>';

					} ?>

				</select>

			</div>

		<?php }

	}

}