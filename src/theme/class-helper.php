<?php
/**
 * WorpdPress Theme Helper
 *
 * Handles methods to make routine theme functions.
 *
 * @since             1.0.0
 * @package           Kodi
 * @subpackage        Kodi/theme
 */

namespace Kodi\Theme;

/**
 * Theme Helper class
 */
class Helper {

	/**
	 * Removes WP action with priority check
	 *
	 * @param string                      $hook_name the hook name to remove.
	 * @param array|callable|false|string $callback  the callback to remove.
	 */
	public static function safe_remove_action( $hook_name, $callback = false ) {
		$priority = has_action( $hook_name, $callback );
		if ( $priority ) {
			remove_action( $hook_name, $callback, $priority );
		}
	}
}
