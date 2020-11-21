<?php
namespace ShaChiPoCo\Core\Controller;

use ShaChiPoCo\Core\Controller;

class Notfound404 extends Controller {
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
		$this->load->view( 'welcome' );
	}
}
