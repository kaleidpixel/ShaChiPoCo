<?php
/**
 * Model.
 *
 * @since    1.0.0
 * @author   KUCKLU <kuck1u@users.noreply.github.com>
 * @licenses MIT License.
 * @package  ShaChiPoCo
 */

namespace ShaChiPoCo\Core;

realpath( $_SERVER[ 'SCRIPT_FILENAME' ] ) !== realpath( __FILE__ ) OR exit( 'No direct script access allowed' );

class Model extends Controller {
	/**
	 * Class constructor.
	 *
	 * @return    void
	 */
	public function __construct() {
		parent::__construct();
	}
}
