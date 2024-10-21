<?php
/**
 * WordPress OOP Theme
 *
 * @since             2.0.0
 * @package           Kodi
 * @subpackage        Kodi/theme
 */

namespace Kodi\Theme;

use Kodi\Utils\Version_Checker;

/**
 * WordPress OOP Theme Class
 */
class Theme {
	/**
	 * WordPress Hooks Loader
	 *
	 * @var Loader Loader
	 */
	protected $loader;

	/**
	 * The theme slug
	 *
	 * @var string
	 * @since 1.0.2
	 */
	protected string $theme_slug = 'Kodi';

	/**
	 * Default Constructor
	 *
	 * @param string $min_php_version minimum PHP version to run the theme.
	 */
	public function __construct( string $min_php_version = '7.1' ) {
		$min_php_version = Version_Checker::is_greater( $min_php_version, '7.1' ) ? $min_php_version : '7.1';
		if ( ! Version_Checker::check_version( $min_php_version ) ) {
			wp_die(
				esc_html__(
					'Minimum version of PHP to use the class is ',
					'Kodi'
				) . esc_html( $min_php_version ),
				esc_html__( 'Wrong PHP version', 'Kodi' )
			);
		}
		$this->loader = new Loader();
		$this->add_style( $this->theme_slug . '-styles', get_stylesheet_uri(), array(), false, 'all', 20 )
			->add_style( $this->theme_slug . '-theme', get_stylesheet_directory_uri() . '/css/theme.css', array(), false, 'all', 20 );
	}

	/**
	 * Add theme support
	 *
	 * @param string $feature the feature.
	 * @param mixed  $options the feature options.
	 *
	 * @return $this
	 */
	public function add_support( string $feature, $options = null ): Theme {
		$this->action_after_setup(
			function () use ( $feature, $options ) {
				if ( $options ) {
					add_theme_support( $feature, $options );
				} else {
					add_theme_support( $feature );
				}
			}
		);

		return $this;
	}

	/**
	 * Add action after theme  setup
	 *
	 * @param callable $func the funtion to run.
	 *
	 * @return void
	 */
	private function action_after_setup( callable $func ) {
		add_action(
			'after_setup_theme',
			function () use ( $func ) {
				$func();
			}
		);
	}

	/**
	 * Add image size
	 *
	 * @param string $name   the image size name.
	 * @param int    $width  the image width.
	 * @param int    $height the image height.
	 * @param bool   $crop   if the image will be cropped.
	 *
	 * @return $this
	 */
	public function add_image_size( string $name, int $width = 0, int $height = 0, bool $crop = false ): Theme {
		$this->action_after_setup(
			function () use ( $name, $width, $height, $crop ) {
				add_image_size( $name, $width, $height, $crop );
			}
		);

		return $this;
	}

	/**
	 * Add script to the theme
	 *
	 * @param string      $handle    the script handle.
	 * @param string      $src       the script source URL.
	 * @param array       $deps      the script dependencies.
	 * @param bool|string $ver       the script version.
	 * @param bool        $in_footer if script will be added to site footer.
	 * @param bool        $cond      the condition when script will be added.
	 * @param bool        $ajax      if AJAX support for the script will be added.
	 *
	 * @return $this
	 */
	public function add_script(
		string $handle,
		string $src = '',
		array $deps = array(),
		$ver = false,
		bool $in_footer = false,
		bool $cond = true,
		bool $ajax = false
	): Theme {
		if ( $cond ) {
			$this->action_enqueue_scripts(
				function () use ( $handle, $src, $deps, $ver, $in_footer, $ajax ) {
					wp_enqueue_script( $handle, $src, $deps, $ver, $in_footer );
					if ( $ajax ) {
						wp_add_inline_script(
							$handle,
							'const ajax_info = ' . wp_json_encode(
								array(
									'ajaxurl' => admin_url( 'admin-ajax.php' ),
									'nonce'   => wp_create_nonce( 'ver_nonce' ),
								)
							),
							'before'
						);
					}
				}
			);
		}

		return $this;
	}

	/**
	 * Enqueue script
	 *
	 * @param callable $func the function to be executed.
	 * @param int      $priority the priority.
	 *
	 * @return void
	 */
	private function action_enqueue_scripts( callable $func, int $priority = 10 ) {
		add_action(
			'wp_enqueue_scripts',
			function () use ( $func ) {
				$func();
			},
			$priority
		);
	}

	/**
	 * Add style to the theme
	 *
	 * @param string      $handle   the style handle.
	 * @param string      $src      the style source URL.
	 * @param array       $deps     the style dependencies.
	 * @param bool|string $ver      the style version.
	 * @param string      $media    the media selector.
	 * @param int         $priority the priority.
	 *
	 * @return $this
	 */
	public function add_style(
		string $handle,
		string $src = '',
		array $deps = array(),
		$ver = false,
		string $media = 'all',
		$priority = 10
	): Theme {
		$this->action_enqueue_scripts(
			function () use ( $handle, $src, $deps, $ver, $media ) {
				wp_enqueue_style( $handle, $src, $deps, $ver, $media );
			},
			$priority
		);

		return $this;
	}

	/**
	 * Add shortcode to the theme
	 *
	 * @param string   $tag      the shortcode tag.
	 * @param callable $callback the shortcode callback.
	 *
	 * @return $this
	 */
	public function add_shortcode( string $tag, callable $callback ): Theme {
		add_shortcode( $tag, $callback );

		return $this;
	}

	/**
	 * Add admin script to the theme
	 *
	 * @param string      $handle    the script handle.
	 * @param string      $src       the script source URL.
	 * @param array       $deps      the script dependencies.
	 * @param bool|string $ver       the script version.
	 * @param bool        $in_footer if script will be added to site footer.
	 *
	 * @return $this
	 */
	public function add_admin_script(
		string $handle,
		string $src = '',
		array $deps = array(),
		$ver = false,
		bool $in_footer = false
	): Theme {
		$this->action_admin_enqueue_scripts(
			function () use ( $handle, $src, $deps, $ver, $in_footer ) {
				wp_enqueue_script( $handle, $src, $deps, $ver, $in_footer );
			}
		);

		return $this;
	}

	/**
	 * Enqueue admin script
	 *
	 * @param callable $func the function to be executed.
	 *
	 * @return void
	 */
	private function action_admin_enqueue_scripts( callable $func ) {
		add_action(
			'admin_enqueue_scripts',
			function () use ( $func ) {
				$func();
			}
		);
	}

	/**
	 * Add admin style to the theme
	 *
	 * @param string      $handle the style handle.
	 * @param string      $src    the style source URL.
	 * @param array       $deps   the style dependencies.
	 * @param bool|string $ver    the style version.
	 * @param string      $media  the media selector.
	 *
	 * @return $this
	 */
	public function add_admin_style(
		string $handle,
		string $src = '',
		array $deps = array(),
		$ver = false,
		string $media = 'all'
	): Theme {
		$this->action_admin_enqueue_scripts(
			function () use ( $handle, $src, $deps, $ver, $media ) {
				wp_enqueue_style( $handle, $src, $deps, $ver, $media );
			}
		);

		return $this;
	}

	/**
	 * Add editor style to the theme
	 *
	 * @param string      $handle the style handle.
	 * @param string      $src    the style source URL.
	 * @param array       $deps   the style dependencies.
	 * @param bool|string $ver    the style version.
	 * @param string      $media  the media selector.
	 *
	 * @return $this
	 */
	public function add_editor_style(
		string $handle,
		string $src = '',
		array $deps = array(),
		$ver = false,
		string $media = 'all'
	): Theme {
		add_action(
			'enqueue_block_editor_assets',
			function () use ( $handle, $src, $deps, $ver, $media ) {
				wp_enqueue_style( $handle, $src, $deps, $ver, $media );
			}
		);

		return $this;
	}

	/**
	 * Add comment script.
	 *
	 * @return $this
	 */
	public function add_comment_script(): Theme {
		$this->action_enqueue_scripts(
			function () {
				if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
					wp_enqueue_script( 'comment-reply' );
				}
			}
		);

		return $this;
	}

	/**
	 * Remove support
	 *
	 * @param string $feature the feature name.
	 *
	 * @return $this
	 */
	public function remove_support( string $feature ): Theme {
		$this->action_after_setup(
			function () use ( $feature ) {
				remove_theme_support( $feature );
			}
		);

		return $this;
	}

	/**
	 * Load text domain.
	 *
	 * @param string       $domain the language domain.
	 * @param false|string $path   the path to domain files.
	 *
	 * @return $this
	 */
	public function load_text_domain( string $domain, $path = false ): Theme {
		$this->action_after_setup(
			function () use ( $domain, $path ) {
				load_theme_textdomain( $domain, $path );
			}
		);

		return $this;
	}

	/**
	 * Remove image size
	 *
	 * @param string $name image size name.
	 *
	 * @return $this
	 */
	public function remove_image_size( string $name ): Theme {
		$this->action_after_setup(
			function () use ( $name ) {
				remove_image_size( $name );
			}
		);

		return $this;
	}

	/**
	 * Remove style
	 *
	 * @param string $handle the style handle.
	 *
	 * @return $this
	 */
	public function remove_style( string $handle ): Theme {
		$this->action_enqueue_scripts(
			function () use ( $handle ) {
				wp_dequeue_style( $handle );
				wp_deregister_style( $handle );
			}
		);

		return $this;
	}

	/**
	 * Remove script
	 *
	 * @param string $handle the script handle.
	 *
	 * @return $this
	 */
	public function remove_script( string $handle ): Theme {
		$this->action_enqueue_scripts(
			function () use ( $handle ) {
				wp_dequeue_script( $handle );
				wp_deregister_script( $handle );
			}
		);

		return $this;
	}

	/**
	 * Add navigation menus
	 *
	 * @param array $locations the menu locations.
	 *
	 * @return $this
	 */
	public function add_nav_menus( array $locations = array() ): Theme {
		$this->action_after_setup(
			function () use ( $locations ) {
				register_nav_menus( $locations );
			}
		);

		return $this;
	}

	/**
	 * Add navigation menu
	 *
	 * @param string $location    the menu location.
	 * @param string $description the menu description.
	 *
	 * @return $this
	 */
	public function add_nav_menu( string $location, string $description ): Theme {
		$this->action_after_setup(
			function () use ( $location, $description ) {
				register_nav_menu( $location, $description );
			}
		);

		return $this;
	}

	/**
	 * Remove navigation menu
	 *
	 * @param string $location the menu location.
	 *
	 * @return $this
	 */
	public function remove_nav_menu( string $location ): Theme {
		$this->action_after_setup(
			function () use ( $location ) {
				unregister_nav_menu( $location );
			}
		);

		return $this;
	}

	/**
	 * Get hooks loader
	 *
	 * @return Loader Loader the hooks loader.
	 */
	public function get_loader(): Loader {
		return $this->loader;
	}

	/**
	 * Initialize theme
	 *
	 * @return void
	 */
	protected function init() {
		$this->loader->run();
	}
}
