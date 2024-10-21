<?php
/**
 * The theme configurator interface
 *
 * Handles theme configuration.
 *
 * @since             1.0.0
 * @package           Kodi
 * @subpackage        Kodi/inc/settings
 */

namespace Kodi\Settings\Configurator;

use Kodi\Theme\Theme;

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
