<?php
/**
 * Logger
 *
 * @since             1.0.0
 * @package           Kodi
 * @subpackage        Kodi/inc/
 */

namespace Kodi\Utils;

/**
 *  Logger Class
 */
class Logger {
	/**
	 * Log data to file
	 *
	 * @param string $message the message.
	 * @param string $src the source pf the message.
	 * @param string $prefix the message prefix.
	 *
	 * @return void
	 */
	public static function write_to_log( string $message, string $src, string $prefix = 'Information' ) {
		if ( defined( 'WP_DEBUG' ) && WP_DEBUG === true ) {
			// phpcs:disable WordPress.PHP.DevelopmentFunctions
			error_log( $prefix . ':  ' . $src . ': ' . $message );
			// phpcs:enable
		}
	}
}
