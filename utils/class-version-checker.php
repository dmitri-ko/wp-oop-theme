<?php
/**
 * Version Checker
 *
 * @since             1.0.0
 * @package           Dmitriko
 * @subpackage        Dmitriko/inc/
 */

namespace Dmitriko\Utils;

/**
 *  Version Checker Class
 */
class Version_Checker {

	/**
	 * Check current PHP version.
	 *
	 * @param string $min minimum PHP version to check.
	 *
	 * @return bool
	 */
	public static function check_version( string $min = '7.1' ): bool {
		return version_compare( PHP_VERSION, $min, '>=' );
	}

	/**
	 * Check if checked version is greater than base one
	 *
	 * @param string $checked_version the version to check.
	 * @param string $base_version the base version.
	 *
	 * @return bool|int
	 */
	public static function is_greater( string $checked_version, string $base_version ) {
		return version_compare( $checked_version, $base_version, '>' );
	}
}
