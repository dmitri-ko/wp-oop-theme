<?php
/**
 * Timer
 *
 * @since             1.0.0
 * @package           Kodi
 * @subpackage        Kodi/inc/woocommerce
 */

namespace Kodi\Utils;

/**
 *  Timer Class
 */
class Timer {

	/**
	 * The timer start time
	 *
	 * @var int
	 */
	private $time_start;
	/**
	 * The timer start time microseconds
	 *
	 * @var int
	 */
	private $microseconds_start;
	/**
	 * The timer stop time
	 *
	 * @var int
	 */
	private $time_stop;
	/**
	 * The timer stop time microseconds
	 *
	 * @var int
	 */
	private $microseconds_stop;

	/**
	 * Default constructor
	 */
	public function __construct() {
		$this->start();
	}

	/**
	 * Starts the times
	 *
	 * @return void
	 */
	public function start(): void {
		$this->fill_times( $this->microseconds_start, $this->time_start );
		$this->time_stop         = null;
		$this->microseconds_stop = null;
	}

	/**
	 * Stops the timer
	 *
	 * @return void
	 */
	public function stop(): void {
		$this->fill_times( $this->microseconds_stop, $this->time_stop );
	}

	/**
	 * Get measured time
	 *
	 * @return float
	 */
	public function get_time(): float {
		$time_end         = $this->time_stop;
		$microseconds_end = $this->microseconds_stop;
		if ( ! $time_end ) {
			$this->fill_times( $microseconds_end, $time_end );
		}

		$seconds      = $time_end - $this->time_start;
		$microseconds = $microseconds_end - $this->microseconds_start;

		return round( ( $seconds + $microseconds ), 6 );
	}

	/**
	 * Fill time and microseconds from current time
	 *
	 * @param int $microseconds the microseconds to fill.
	 * @param int $time the time to fill.
	 *
	 * @return void
	 */
	protected function fill_times( &$microseconds, &$time ): void {
		$times        = explode( ' ', microtime() );
		$microseconds = $times[0];
		$time         = $times[1];
	}
}
