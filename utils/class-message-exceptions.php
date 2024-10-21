<?php
/**
 * Messages to exceptions utils
 *
 * @since             1.0.0
 * @package           Kodi
 * @subpackage        Kodi/inc/woocommerce
 */

namespace Kodi\Utils;

use ErrorException;

/**
 *  Convert PHP messages to Exceptions Class
 */
class Message_Exceptions {

	/**
	 * Set error handler
	 *
	 * @return void
	 */
	public static function set_message_to_exceptions() {
		if ( defined( 'WP_DEBUG' ) && WP_DEBUG === true ) {
			// phpcs:disable WordPress.PHP.DevelopmentFunctions
			set_error_handler( array( self::class, 'exceptions_error_handler' ) );
			// phpcs:enable
		}
	}

	/**
	 * Convert WP messages into exceptions
	 *
	 * @param int    $severity the message severity.
	 * @param string $message the message.
	 * @param string $filename the filename of the message.
	 * @param int    $lineno the line number.
	 *
	 * @return mixed
	 * @throws ErrorException Exception thrown instead of message.
	 */
	public static function exceptions_error_handler( $severity, $message, $filename, $lineno ) {
		throw new ErrorException( esc_html( $message ), 0, esc_html( $severity ), esc_html( $filename ), esc_html( $lineno ) );
	}
}
