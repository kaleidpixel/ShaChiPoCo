<?php
namespace ShaChiPoCo\Core\Controller;

use ShaChiPoCo\Core\Controller;

class Welcome extends Controller {
	public function index() {
		$this->load->view( 'welcome' );
	}
}
