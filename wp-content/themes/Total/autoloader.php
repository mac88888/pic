<?php
namespace TotalTheme;

defined( 'ABSPATH' ) || exit;

final class Autoloader {

	/**
	 * Register our autoloader.
	 */
	public static function run() {
		spl_autoload_register( array( __CLASS__, 'autoload' ) );
	}

	/**
	 * Function registered as an autoloader which loads class files.
	 */
	private static function autoload( $class ) {

		// Check to make sure the class is part of our namespace.
		if ( 0 !== strpos( $class, __NAMESPACE__ ) || 0 === strpos( $class, 'TotalThemeCore' ) ) {
			return;
		}

		// Get the absolute path to a class file.
		$path = self::get_class_path( $class );

		// Include class file if it's readable.
		if ( $path && is_readable( $path ) ) {
			require $path;
		}

	}

	/**
	 * Get the absolute path to a class file.
	 */
	private static function get_class_path( $class ) {

		// Remove namespace.
		$class = str_replace( __NAMESPACE__ . '\\', '', $class );

		// Lowercase.
		$class = strtolower( $class );

		// Convert underscores to dashes.
		$class = str_replace( '_', '-', $class );

		// Fix classnames with incorrect naming convention.
		$class = self::parse_class_filename( $class );

		// Return early if parsing returns null.
		if ( ! $class ) {
			return;
		}

		// Convert backslash to correct directory separator.
		$class = str_replace( '\\', DIRECTORY_SEPARATOR, $class ) . '.php';

		// Return final class path.
		return trailingslashit( WPEX_THEME_DIR )  . 'inc/' . $class;

	}

	/**
	 * Parses the class filename to fix classnames with incorrect naming convention.
	 */
	private static function parse_class_filename( $class ) {
		return $class;
	}

}
Autoloader::run();