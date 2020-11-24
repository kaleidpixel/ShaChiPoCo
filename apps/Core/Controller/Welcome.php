<?php
namespace ShaChiPoCo\Core\Controller;

realpath( $_SERVER[ 'SCRIPT_FILENAME' ] ) !== realpath( __FILE__ ) OR exit( 'No direct script access allowed' );

use ShaChiPoCo\Core\Controller;

class Welcome extends Controller {
	public function index() {
		$this->load->view( 'welcome' );
	}
}
