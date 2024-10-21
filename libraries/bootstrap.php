<?php
/**
 * Bootstrap class
 *
 * @since             1.0.0
 * @package           Kodi
 * @subpackage        Kodi/inc/
 */

// Configure autoloading.
require_once get_stylesheet_directory() . '/classes/Kodi/autoloader/class-autoloader.php';

use Kodi\Autoloader\Autoloader;

$autoloader = new Autoloader();


/* Run theme class */

/**
 * Run theme
 *
 * @return void
 */
function run_theme() {

	$theme = new Kodi\Theme\Theme();
}

run_theme();
