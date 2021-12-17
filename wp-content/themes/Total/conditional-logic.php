<?php
namespace TotalTheme;

defined( 'ABSPATH' ) || exit;

/**
 * Conditional Logic.
 *
 * @package TotalTheme
 * @version 5.3
 */
final class Conditional_Logic {

	/**
	 * Result.
	 *
	 * @var bool $result The result of the conditional check.
	 */
	public $result = false;

	/**
	 * Constructor.
	 *
	 * @since 5.3
	 */
	public function __construct( $conditions ) {

		if ( is_string( $conditions ) ) {
			$conditions = str_replace( ' ', '', $conditions );
			parse_str( $conditions, $conditions );
		}

		if ( ! is_array( $conditions ) ) {
			return;
		}

		foreach ( $conditions as $condition => $check ) {

			if ( $this->result ) {
				return; // once the logic has returnd true there is no need to check anymore.
			}

			if ( is_callable( $condition ) ) {
				if ( $check ) {
					$this->result = (bool) call_user_func( $condition, $this->string_to_array( $check ) );
				} else {
					$this->result = (bool) call_user_func( $condition );
				}
			}

		}

	}

	/**
	 * Converts strings to arrays.
	 *
	 * @since 5.3
	 */
	private function string_to_array( $input ) {
		if ( is_string( $input ) ) {
			$input = explode( ',', $input );
		}
		return $input;
	}

}