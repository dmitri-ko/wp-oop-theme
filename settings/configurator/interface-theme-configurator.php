<?php
/**
 * The theme configurator interface
 *
 * Handles theme configuration.
 *
 * @since             1.0.0
 * @package           Dmitriko
 * @subpackage        Dmitriko/inc/settings
 */

namespace Dmitriko\Settings\Configurator;

use Dmitriko\Theme\Theme;

/**
 * Theme configurator interface
 */
interface Theme_Configurator {
	/**
	 * Configure the theme
	 *
	 * @param  Theme $theme the theme.
	 *
	 * @return void
	 */
	public static function configure( $theme );
}
