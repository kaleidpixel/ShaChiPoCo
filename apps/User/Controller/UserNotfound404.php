<?php
namespace ShaChiPoCo\User\Controller;

realpath( $_SERVER[ 'SCRIPT_FILENAME' ] ) !== realpath( __FILE__ ) OR exit( 'No direct script access allowed' );

use ShaChiPoCo\Core\Controller;

class UserNotfound404 extends Controller {
	/**
	 * Class constructor.
	 *
	 * @return	void
	 */
	public function __construct() {
		parent::__construct();
	}

	public function index() {
		http_response_code( 404 );

		$this->load->view( '404' );
	}
}
