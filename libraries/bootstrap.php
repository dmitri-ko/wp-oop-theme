<?php

/* Configure autoloading */
require_once get_stylesheet_directory() . '/classes/Kodi/autoloader/class-autoloader.php';

use Kodi\Autoloader\Autoloader;

$autoloader = new Autoloader();


/* Run theme class */

use Kodi\Theme\Theme;

function run_theme() {

	$theme = new Kodi\Theme\Theme();

}

run_theme();
