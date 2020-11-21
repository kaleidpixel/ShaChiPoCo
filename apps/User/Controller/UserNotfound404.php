<?php
namespace ShaChiPoCo\User\Controller;

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
