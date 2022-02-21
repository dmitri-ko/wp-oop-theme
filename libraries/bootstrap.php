<?php

/* Configure autoloading */
require_once get_stylesheet_directory() . '/classes/dmitriko/autoloader/class-autoloader.php';

use Dmitriko\Autoloader\Autoloader;

$autoloader = new Autoloader();


/* Run theme class */

use Dmitriko\Theme\Theme;

function run_theme() {

	$theme = new Dmitriko\Theme\Theme();

}

run_theme();
