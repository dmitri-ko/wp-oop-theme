<?php
/**
 * The theme setting resolver
 *
 * Handles theme settings file resolve.
 *
 * @since             1.0.0
 * @package           Kodi
 * @subpackage        Kodi/settings
 */

namespace Kodi\Settings\Json;

/**
 * Class that abstracts the processing of the different data sources
 * for site-level config and offers an API to work with them.
 */
class Theme_Settings_JSON_Resolver {

	/**
	 * Container for data coming from the theme.
	 *
	 * @var Theme_Settings_JSON
	 */
	private static $theme = null;

	/**
	 * Whether or not the theme supports theme.json.
	 *
	 * @var bool
	 */
	private static $theme_has_support = null;

	/**
	 * Processes a file that adheres to the theme.json schema
	 * and returns an array with its contents, or a void array if none found.
	 *
	 * @param string $file_path Path to file. Empty if no file.
	 * @return array Contents that adhere to the theme.json schema.
	 */
	private static function read_json_file( $file_path ) {
		$config = array();
		if ( $file_path ) {
			$decoded_file = wp_json_file_decode( $file_path, array( 'associative' => true ) );
			if ( is_array( $decoded_file ) ) {
				$config = $decoded_file;
			}
		}
		return $config;
	}

	/**
	 * Return core's origin config.
	 *
	 * @param array $settings valid settings.
	 * @param array $options  valid options.
	 *
	 * @return Theme_Settings_JSON Entity that holds core data.
	 */
	public static function get_theme_data( $settings = array(), $options = array() ) {
		if ( null === self::$theme ) {
			$theme_json_data = self::read_json_file( self::get_file_path_from_theme( 'theme-settings.json' ) );
			self::$theme     = new Theme_Settings_JSON( $theme_json_data, $settings, $options );
		}

		return self::$theme;
	}

	/**
	 * Whether the current theme has a theme.json file.
	 *
	 * @return bool
	 */
	public static function theme_has_support() {
		if ( ! isset( self::$theme_has_support ) ) {
			self::$theme_has_support = (
				is_readable( self::get_file_path_from_theme( 'theme.json' ) ) ||
				is_readable( self::get_file_path_from_theme( 'theme.json', true ) )
			);
		}

		return self::$theme_has_support;
	}

	/**
	 * Builds the path to the given file and checks that it is readable.
	 *
	 * If it isn't, returns an empty string, otherwise returns the whole file path.
	 *
	 * @since 5.8.0
	 * @since 5.9.0 Adapted to work with child themes, added the `$template` argument.
	 *
	 * @param string $file_name Name of the file.
	 * @param bool   $template  Optional. Use template theme directory. Default false.
	 * @return string The whole file path or empty if the file doesn't exist.
	 */
	private static function get_file_path_from_theme( $file_name, $template = false ) {
		$path      = $template ? get_template_directory() : get_stylesheet_directory();
		$candidate = $path . '/' . $file_name;

		return is_readable( $candidate ) ? $candidate : '';
	}

	/**
	 * Cleans the cached data so it can be recalculated.
	 */
	public static function clean_cached_data() {
		self::$theme             = null;
		self::$theme_has_support = null;
	}
}
