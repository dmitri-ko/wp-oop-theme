<?php
/**
 * Pretty_Dump
 *
 * @since             1.0.0
 * @package           Kodi
 * @subpackage        Kodi/inc/woocommerce
 */

namespace Kodi\Utils;

/**
 *  Pretty_Dump Class
 */
class Pretty_Dump {

	/**
	 * Dump variables with HTML markup
	 *
	 * @param mixed ...$vars the variables to dump.
	 *
	 * @return void
	 */
	public static function var_dump( ...$vars ) {
		?>
		<pre class="pretty-dump">
		<?php
		foreach ( $vars as $var ) {
			// phpcs:disable WordPress.PHP.DevelopmentFunctions
			var_dump( $var );
			// phpcs:enable
		}
		?>
		</pre>
		<?php
	}

	/**
	 * Dumps variables conditionally
	 *
	 * @param string $param conditional variable name.
	 * @param mixed  ...$vars variables to dump.
	 *
	 * @return void
	 */
	public static function var_dump_when_var_set( string $param, ...$vars ) {
		// phpcs:disable  WordPress.Security.NonceVerification.Recommended
		if ( isset( $_REQUEST[ $param ] ) ) {
			// phpcs:enable
			self::var_dump( $vars );
		}
	}
}
