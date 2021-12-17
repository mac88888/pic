<?php
/**
 * Grid frontend functions
 *
 * @package TotalTheme
 * @subpackage Functions
 * @version 5.3
 */

defined( 'ABSPATH' ) || exit;

/**
 * Returns grid class based on settings.
 *
 * @since 1.0.0
 */
 function wpex_grid_columns_class( $columns = '' ) {

   if ( ! $columns ) {
      return;
   }

   $class = '';

   // Responsive columns.
   if ( is_array( $columns ) ) {

      $responsive_columns = $columns;

      $class .= ' wpex-grid-cols-' . absint( $responsive_columns[ 'd'] );
      unset( $responsive_columns[ 'd'] );
      foreach ( $responsive_columns as $key => $val ) {
         if ( $val ) {
            $class .= ' wpex-' . sanitize_html_class( $key ) . '-grid-cols-' . sanitize_html_class( $val );
         }
      }

   }

   // Standard columns.
   else {

      $columns = absint( $columns );

      // Default colums.
      $class .= ' wpex-grid-cols-' . absint( $columns );

      /**
       * Filters whether the wpex_grid_columns_class should be auto responsive.
       *
       * @param bool $auto_responsive
       * @param string $columns
       */
      $auto_responsive = apply_filters( 'wpex_grid_columns_class_auto_responsive', true );

      if ( $auto_responsive ) {

         // Convert 4 columns to 2 columns for "auto" responsive and stick to old standards.
         if ( 4 === $columns ) {
             $class .= ' wpex-tp-grid-cols-2';
         }

         // Convert columns to 1 column for small devices.
         $class .= ' wpex-pp-grid-cols-1';

      }

   }

   /**
    * Filters the css grid columns class.
    *
    * @param string $classnames.
    */
   $class = apply_filters( 'wpex_grid_columns_class', $class, $columns );

   return trim( $class );
}

/**
 * Return
 *
 * @since 1.0.0
 */
function wpex_row_column_width_class( $columns = '4' ) {

    $class = '';

    // Responsive columns.
    if ( is_array( $columns ) && count( $columns ) > 1 ) {
		$class = 'span_1_of_' . sanitize_html_class( $columns[ 'd' ] );
		$responsive_columns = $columns;
		unset( $responsive_columns[ 'd'] );
		foreach ( $responsive_columns as $key => $val ) {
			if ( $val ) {
				$class .= ' span_1_of_' . sanitize_html_class( $val ) . '_' . sanitize_html_class( $key );
			}
		}
	}

    // Non responsive columns.
    else {
		$class = 'span_1_of_' . sanitize_html_class( $columns );
	}

    /**
     * Filter the wpex_row_column_width class.
     *
     * @param string $class.
     * @todo deprecate.
     */
    $class = apply_filters( 'wpex_grid_class', $class );

    /**
     * Filter the wpex_row_column_width class.
     *
     * @param string $class.
     */
    $class = apply_filters( 'wpex_row_column_width_class', $class, $columns );

	return $class;
}

/**
 * Returns the correct gap class.
 *
 * @since 1.0.0
 */
function wpex_gap_class( $gap = '' ) {
	if ( '0px' === $gap || '0' === $gap ) {
		$gap = 'none';
	}
	return apply_filters( 'wpex_gap_class', 'gap-' . sanitize_html_class( $gap ) );
}