<?php

namespace Kodi\Settings\Json;

/**
 * Theme settings
 */
class Theme_Settings_JSON {

	/**
	 * The top-level keys a theme.json can have.
	 *
	 * @var string[]
	 */
	const VALID_TOP_LEVEL_KEYS = array(
		'settings',
		'version',
	);
	/**
	 * The valid properties under the settings key.
	 *
	 * @var array
	 */
	const DEFAULT_SETTING = array();

	protected static $valid_settings;
	/**
	 * The valid elements that can be found under styles.
	 *
	 * @since 5.8.0
	 * @var string[]
	 */
	const DEFAULT_OPTIONS = array(
		'on' => 'on',
	);
	/**
	 * The theme instance
	 *
	 * @var Theme_Settings_JSON $instance .
	 */
	private static $instance = null;
	/**
	 * Container of data in settings.json format.
	 *
	 * @var array
	 */
	private $settings_json = null;

	/**
	 *
	 */
	public function __construct( $settings_json = array(), $valid_settings = array(), $valid_options = array() ) {
		$valid_options_names  = empty( $valid_options ) || ! is_array( $valid_options ) ? array_keys( self::DEFAULT_OPTIONS ) :
			array_keys(
				$valid_options
			);
		self::$valid_settings = empty( $valid_settings ) ? self::DEFAULT_SETTING : $valid_settings;
		$this->settings_json  = self::sanitize( $settings_json, $valid_options_names );
	}

	/**
	 * Sanitizes the input according to the schemas.
	 *
	 * @param array $input               Structure to sanitize.
	 *
	 * @return array The sanitized output.
	 */
	private static function sanitize( $input, $valid_options_names ) {
		$output = array();

		if ( ! is_array( $input ) ) {
			return $output;
		}

		$output = array_intersect_key( $input, array_flip( self::VALID_TOP_LEVEL_KEYS ) );

		// Build the schema based on valid block & element names.
		$schema                  = array();
		$schema_settings_options = array();
		foreach ( $valid_options_names as $option ) {
			$schema_settings_options[ $option ] = array();
		}

		$schema['settings']            = self::$valid_settings;
		$schema['settings']['options'] = $schema_settings_options;

		// Remove anything that's not present in the schema.
		foreach ( array( 'settings' ) as $subtree ) {
			if ( ! isset( $input[ $subtree ] ) ) {
				continue;
			}

			if ( ! is_array( $input[ $subtree ] ) ) {
				unset( $output[ $subtree ] );
				continue;
			}

			$result = self::remove_keys_not_in_schema( $input[ $subtree ], $schema[ $subtree ] );

			if ( empty( $result ) ) {
				unset( $output[ $subtree ] );
			} else {
				$output[ $subtree ] = $result;
			}
		}

		return $output;
	}

	/**
	 * Given a tree, removes the keys that are not present in the schema.
	 *
	 * It is recursive and modifies the input in-place.
	 *
	 * @param array $tree   Input to process.
	 * @param array $schema Schema to adhere to.
	 *
	 * @return array Returns the modified $tree.
	 */
	private static function remove_keys_not_in_schema( $tree, $schema ) {
		$tree = array_intersect_key( $tree, $schema );

		foreach ( $schema as $key => $data ) {
			if ( ! isset( $tree[ $key ] ) ) {
				continue;
			}

			if ( is_array( $schema[ $key ] ) && is_array( $tree[ $key ] ) ) {
				$tree[ $key ] = self::remove_keys_not_in_schema( $tree[ $key ], $schema[ $key ] );

				if ( empty( $tree[ $key ] ) ) {
					unset( $tree[ $key ] );
				}
			} elseif ( is_array( $schema[ $key ] ) && ! is_array( $tree[ $key ] ) ) {
				unset( $tree[ $key ] );
			}
		}

		return $tree;
	}

	/**
	 * Returns the raw data.
	 *
	 * @return array Raw data.
	 */
	public function get_raw_data() {
		return $this->settings_json;
	}

	/**
	 * @param $block
	 * @param $feature
	 *
	 * @return false|mixed
	 */
	public function has_support( $block, $feature ) {
		return $this->get_settings( $block, $feature )['on'] ?? false;
	}

	/**
	 * @param $block
	 * @param $feature
	 *
	 * @return array|mixed
	 */
	protected function get_settings( $block, $feature ) {
		return $this->settings_json['settings'][ $block ][ $feature ] ?? array();
	}
}
