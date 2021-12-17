<?php
/**
 * Template for displaying the post author bio.
 *
 * @package TotalTheme
 * @subpackage Templates
 * @version 5.3
 */

defined( 'ABSPATH' ) || exit;

$data = (array) wpex_get_author_box_data();

if ( empty( $data ) ) {
	return;
}

extract( $data );

?>

<section class="author-bio wpex-boxed wpex-flex wpex-gap-20 wpex-flex-col wpex-sm-flex-row wpex-mb-40 wpex-text-center wpex-sm-text-left">

	<?php if ( ! empty( $avatar ) ) { ?>

		<div class="author-bio-avatar wpex-flex-shrink-0">

			<?php if ( ! empty( $posts_url ) ) { ?>

				<a href="<?php echo esc_url( $posts_url ); ?>" title="<?php esc_attr_e( 'Visit Author Page', 'total' ); ?>"><?php echo wp_kses_post( $avatar ); ?></a>

			<?php } else { ?>

				<?php echo wp_kses_post( $avatar ); ?>

			<?php } ?>

		</div>

	<?php } ?>

	<div class="author-bio-content wpex-flex-grow wpex-last-mb-0">

		<?php if ( ! empty( $author_name ) ) { ?>

			<h4 class="author-bio-title wpex-m-0 wpex-mb-10 wpex-text-lg">

				<?php if ( ! empty( $posts_url ) ) { ?>

					<a href="<?php echo esc_url( $posts_url ); ?>" title="<?php esc_attr_e( 'Visit Author Page', 'total' ); ?>"><?php echo strip_tags( $author_name ); ?></a>

				<?php } else { ?>

					<?php echo strip_tags( $author_name ); ?>

				<?php } ?>

			</h4>

		<?php } ?>

		<?php if ( ! empty( $description ) ) { ?>

			<div class="author-bio-description wpex-mb-15 wpex-last-mb-0"><?php
				echo wpautop( do_shortcode( wp_kses_post( $description ) ) );
			?></div>

		<?php } ?>

		<?php wpex_author_box_social_links( $post_author ); ?>

	</div>

</section>