<?php
/**
 * WorpdPress Actions and Filters Loader
 *
 * @since             1.0.0
 * @package           Dmitriko
 * @subpackage        Dmitriko/theme
 */

namespace Dmitriko\Theme;

/**
 * Loader Class
 */
class Loader {

	/**
	 * Theme actions
	 *
	 * @var array
	 */
	protected $actions;

	/**
	 * Theme filters
	 *
	 * @var array
	 */
	protected $filters;


	/**
	 *  Default constructor
	 */
	public function __construct() {

		$this->actions = array();
		$this->filters = array();

	}


	/**
	 * Add WordPress action
	 *
	 * @param string          $hook the hook name.
	 * @param string|callable $callback the callback function name.
	 * @param int             $priority the hook priority.
	 * @param array           $accepted_args accepted hook arguments.
	 *
	 * @return void
	 */
	public function add_action( $hook, $callback, $priority = 10, $accepted_args = 1 ) {

		$this->actions = $this->add( $this->actions, $hook, $callback, $priority, $accepted_args );

	}

	/**
	 * Add WordPress action
	 *
	 * @param string          $hook the hook name.
	 * @param string|callable $callback the callback function.
	 *
	 * @return void
	 */
	public function remove_action( $hook, $callback ) {

		$this->actions = $this->remove( $this->actions, $hook, $callback );

	}


	/**
	 * Add WordPress filter
	 *
	 * @param string          $hook the hook name.
	 * @param string|callable $callback the callback function name.
	 * @param int             $priority the hook priority.
	 * @param array           $accepted_args accepted hook arguments.
	 *
	 * @return void
	 */
	public function add_filter( $hook, $callback, $priority = 10, $accepted_args = 1 ) {

		$this->filters = $this->add( $this->filters, $hook, $callback, $priority, $accepted_args );

	}


	/**
	 * Helper method to add hook to hooks array
	 *
	 * @param array           $hooks the hooks array.
	 * @param string          $hook the hook name.
	 * @param string|callable $callback the callback function name.
	 * @param int             $priority the hook priority.
	 * @param array           $accepted_args accepted hook arguments.
	 *
	 * @return mixed
	 */
	private function add( $hooks, $hook, $callback, $priority, $accepted_args ) {

		$hooks[] = array(
			'hook'          => $hook,
			'callback'      => $callback,
			'priority'      => $priority,
			'accepted_args' => $accepted_args,
		);

		return $hooks;

	}


	/**
	 * Helper method to add hook to hooks array to safely remove it
	 *
	 * @param array           $hooks the hooks array.
	 * @param string          $hook the hook name.
	 * @param string|callable $callback the callback function name.
	 *
	 * @return mixed
	 */
	private function remove( $hooks, $hook, $callback ) {

		$hooks[] = array(
			'hook'        => $hook,
			'callback'    => $callback,
			'safe_remove' => true,
		);

		return $hooks;

	}
	/**
	 * Run loader
	 *
	 * @return void
	 */
	public function run() {

		foreach ( $this->filters as $hook ) {

			add_filter( $hook['hook'], $hook['callback'], $hook['priority'], $hook['accepted_args'] );

		}

		foreach ( $this->actions as $hook ) {
			if ( empty( $hook['safe_remove'] ) || ! $hook['safe_remove'] ) {
				add_action( $hook['hook'], $hook['callback'], $hook['priority'], $hook['accepted_args'] );
			} else {
				Helper::safe_remove_action( $hook['hook'], $hook['callback'] );
			}
		}

	}

}
