<?php
/**
 * Autoloader with namespaces support
 *
 * Handles methods to make routine theme functions.
 *
 * @since             1.0.0
 * @package           Kodi
 * @subpackage        Kodi/theme
 */

namespace Kodi\Autoloader;

/**
 * Autoloader class
 */
class Autoloader {

	/**
	 * Valid class file prefixes
	 */
	const CLASS_PREFIXES = array(
		'class',
		'abstract',
		'interface',
	);

	/**
	 * Namespaces root directory
	 *
	 * @var string
	 */
	protected $root;

	/**
	 * Valid namespaces
	 *
	 * @var string[]
	 */
	protected $namespaces;

	/**
	 * Default constructor
	 *
	 * @param string[] $namespaces list of valid namespace names.
	 * @param string   $root namespaces root directory.
	 */
	public function __construct( array $namespaces = array(), string $root = '/classes' ) {
		$this->root       = untrailingslashit( $root );
		$this->namespaces = array_merge( array( 'Kodi' ), $namespaces );
		$this->register_autoloader();

	}


	/**
	 * Register autoloader for PHP
	 *
	 * @return void
	 */
	private function register_autoloader() {

		spl_autoload_register( array( $this, 'load_class' ) );

	}


	/**
	 * Load class file
	 *
	 * @param string $class_name the class name.
	 *
	 * @return bool
	 */
	private function load_class( string $class_name ): bool {

		if ( ! $this->is_in_namespaces( $class_name ) ) {
			return false;
		}
		// Split the class name into an array to read the namespace and class.
		$file_parts = explode( '\\', $class_name );

		// Do a reverse loop through $file_parts to build the path to the file.
		$namespace = '';
		for ( $i = count( $file_parts ) - 1; $i >= 0; $i -- ) {

			// Read the current component of the file part.
			$current = strtolower( $file_parts[ $i ] );
			$current = str_ireplace( '_', '-', $current );

			// If we're at the first entry, then we're at the filename.
			if ( count( $file_parts ) - 1 === $i ) {
				$file_name = "$current.php";
			} else {
				$namespace = '/' . $current . $namespace;
			}
		}

		// Now build a path to the file using mapping to the file location.
		$found     = false;
		$namespace = $this->root . $namespace;
		$filepath  = trailingslashit(
			untrailingslashit( get_stylesheet_directory() ) . $namespace
		);
		foreach ( self::CLASS_PREFIXES as $prefix ) {
			$full_path = $filepath . $prefix . '-' . $file_name;
			if ( file_exists( $full_path ) ) {
				include_once $full_path;
				$found = true;
				break;
			}
		}
		if ( ! $found ) {
			wp_die(
				esc_html( 'The file attempting to be loaded at ' . $filepath . ' does not exist.' )
			);
		}
		return true;
	}

	/**
	 * Check if the classname in valid namespace
	 *
	 * @param string $class_name the class name.
	 *
	 * @return bool
	 */
	protected function is_in_namespaces( string $class_name ): bool {
		foreach ( $this->namespaces as $namespace ) {
			if ( false !== strpos( $class_name, $namespace ) ) {
				return true;
			}
		}
		return false;
	}
}
