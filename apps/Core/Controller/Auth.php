<?php
namespace ShaChiPoCo\Core\Controller;

realpath( $_SERVER[ 'SCRIPT_FILENAME' ] ) !== realpath( __FILE__ ) OR exit( 'No direct script access allowed' );

use ShaChiPoCo\Core\Controller;
use \ShaChiPoCo\Core\Library\Auth as LibAuth;

class Auth extends Controller {
	/**
	 * Auth instance.
	 *
	 * @var LibAuth
	 */
	public $auth;

	/**
	 * Class constructor.
	 *
	 * @return    void
	 */
	public function __construct() {
		parent::__construct();

		$this->auth = $this->load->library( 'Auth' );
		$this->load->helper( [ 'Form' ] );
	}

	public function login() {
		if ( $this->auth->is_logged_in() === true ) {
			header( 'Location: /' );
			exit;
		}

		$vers = set_csrf_token();
		$vers = merge_alert_message( $vers );

		$this->load->view( 'login', $vers );
	}

	public function login_process() {
		$this->auth->login_process();
	}

	public function logout() {
		$this->auth->logout();
	}

	public function forgot_password() {
		http_response_code( 404 );
		$this->load->view( '404' );
	}

	public function register() {
		http_response_code( 404 );
		$this->load->view( '404' );
	}
}
