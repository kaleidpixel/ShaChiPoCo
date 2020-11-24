<?php
namespace ShaChiPoCo\Core\Library;

realpath( $_SERVER[ 'SCRIPT_FILENAME' ] ) !== realpath( __FILE__ ) OR exit( 'No direct script access allowed' );

use ShaChiPoCo\Core\Loader;
use ShaChiPoCo\Core\Database;
use ShaChiPoCo\Core\Model\Auth as M_Auth;

class Auth {
	/**
	 * Core Loader.
	 *
	 * @var    Loader
	 */
	public $load;

	/**
	 * Database instance.
	 *
	 * @var    Database
	 */
	public $db;

	/**
	 * Model Auth instance.
	 *
	 * @var    M_Auth
	 */
	public $m_auth;

	/**
	 * Reference to the singleton.
	 *
	 * @var    object
	 */
	private static $instance;

	/**
	 * Class constructor.
	 *
	 * @return    void
	 */
	public function __construct() {
		self::$instance =& $this;
		$this->load     = new Loader();
		$this->db       = new Database();
		$this->m_auth   = $this->load->model( 'auth' );

		$this->load->helper( [ 'Form', 'Token', 'Cookie' ] );
	}

	public function login() {
	}

	public function login_process() {
		if ( validate_csrf_token() === false ) {
			create_alert_message( 'トークンが一致しません', 'error' );
			header( 'Location: /login/' );
			exit();
		}

		$error    = false;
		$_post    = form_post();
		$login    = htmlspecialchars_decode( isset( $_post[ 'user' ] ) ? $_post[ 'user' ] : '' );
		$password = htmlspecialchars_decode( isset( $_post[ 'password' ] ) ? $_post[ 'password' ] : '' );

		if ( empty( $login ) ) {
			$error = true;
			create_alert_message( 'アカウント名を入力してください', 'error' );
		}

		if ( empty( $password ) ) {
			$error = true;
			create_alert_message( 'パスワードを入力してください', 'error' );
		}

		if ( $error === true ) {
			header( 'Location: /login/' );
			exit();
		}

		$user = $this->m_auth->get_login_user( $login );

		if (
			! empty( $user )
			&& isset( $user[ 'password' ] )
			&& ! empty( $user[ 'password' ] )
			&& $this->password_verify( $password, $user[ 'password' ] ) === true
		) {
			session_regenerate_id( true );

			$_SESSION[ 'auth' ]         = generate_session_token();
			$_SESSION[ 'login' ]        = $user[ 'login' ];
			$_SESSION[ 'display_name' ] = $user[ 'display_name' ];

			session_write_close();
			header( 'Location: /dashboard/' );
			exit();
		} else {
			create_alert_message( 'アカウント名、またはパスワードが間違っています', 'error' );
			header( 'Location: /login/' );
			exit();
		}
	}

	public function logout() {
		unset( $_SESSION[ 'auth' ] );

		if ( ini_get( 'session.use_cookies' ) ) {
			$params = session_get_cookie_params();

			setcookie_org( session_name(), '', time() - 42000, $params[ 'path' ] );
		}

		session_destroy();
		header( 'Location: /login/' );
		exit();
	}

	/**
	 * @return void
	 */
	public function logged_in() {
		if ( $this->is_logged_in() !== true ) {
			header( 'Location: /logout/' );
			exit;
		}
	}

	/**
	 * @return bool
	 */
	public function is_logged_in() {
		$logged_in = false;

		if ( isset( $_SESSION[ 'auth' ] ) ) {
			$logged_in = validate_session_token( $_SESSION[ 'auth' ] );
		}

		return $logged_in;
	}

	/**
	 * パスワードをハッシュ＆ソルト.
	 *
	 * @param string $password
	 *
	 * @return false|string|null
	 */
	public static function password_hash( $password = '' ) {
		return password_hash( $password, PASSWORD_BCRYPT );
	}

	/**
	 * 入力されたパスワードがハッシュ値と一致するか確認する.
	 *
	 * @param string $password
	 * @param string $hash
	 *
	 * @return bool
	 */
	public static function password_verify( $password = '', $hash = '' ) {
		return password_verify( $password, $hash );
	}
}
