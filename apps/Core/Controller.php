<?php
/**
 * Controller.
 *
 * @since    1.0.0
 * @author   KUCKLU <kuck1u@users.noreply.github.com>
 * @licenses MIT License.
 * @package  ShaChiPoCo
 */

namespace ShaChiPoCo\Core;

Class Controller {
	/**
	 * Reference to the Core singleton.
	 *
	 * @var	object
	 */
	private static $instance;

	/**
	 * Core Loader.
	 *
	 * @var	Loader
	 */
	public $load;

	/**
	 * PDO instance.
	 *
	 * @var	Database
	 */
	public $db;

	/**
	 * Class constructor.
	 *
	 * @return	void
	 */
	public function __construct() {
		self::$instance =& $this;
		$this->load = new Loader;
		$this->db   = new Database;
	}

	/**
	 * Get the Core singleton.
	 *
	 * @static
	 * @return	object
	 */
	public static function get_instance() {
		return self::$instance;
	}
}
