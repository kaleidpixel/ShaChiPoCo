<?php
/**
 * ENV ファイル・ログファイルの制御.
 *
 * @since    1.0.0
 * @author   KUCKLU <kuck1u@users.noreply.github.com>
 * @licenses MIT License.
 */

realpath( $_SERVER[ 'SCRIPT_FILENAME' ] ) !== realpath( __FILE__ ) OR exit( 'No direct script access allowed' );

use \Dotenv\Exception\ValidationException;

/**
 * エラーログの制御.
 */
define( 'BASEPATH', dirname( dirname( __DIR__ ) ) );
define( 'LOGPATH', BASEPATH . DIRECTORY_SEPARATOR . 'logs' );
define( 'ERROR_LOG_FILE', LOGPATH . DIRECTORY_SEPARATOR . 'php_error.log' );
ini_set( 'error_log', ERROR_LOG_FILE );
ini_set( 'xdebug.var_display_max_children', -1 );
ini_set( 'xdebug.var_display_max_data', -1 );
ini_set( 'xdebug.var_display_max_depth', -1 );

error_reporting( E_ALL | E_STRICT );

if ( ! is_dir( LOGPATH ) ) {
	mkdir( LOGPATH, 755, true );
}

/**
 * ENV ファイルの制御.
 *
 * @see https://packagist.org/packages/vlucas/phpdotenv
 */
try {
	$dotenv = \Dotenv\Dotenv::createImmutable( BASEPATH );
	$dotenv->load();
	$dotenv->required( [
		'APP_ENV',
		'DB_CONNECTION',
		'DB_HOST',
		'DB_PORT',
		'DB_DATABASE',
		'DB_USERNAME',
		'DB_PASSWORD',
		'APP_CHARSET',
		'APP_CHARSET_STR',
		'APP_LANGUAGE',
		'APP_TIMEZONE',
	] )->notEmpty();
} catch ( ValidationException $e ) {
	error_log( print_r( $e, true ), 3, dirname( __DIR__ ) . DIRECTORY_SEPARATOR . 'logs' . DIRECTORY_SEPARATOR . 'framework_error.log' );
	exit( 'Framework setting error.' );
}

/**
 * エラーログの出力
 */
if ( getenv( 'APP_ENV' ) === 'development' ) {
	ini_set( 'display_errors', 1 );
	ini_set( 'log_errors', 0 );

	$whoops = new \Whoops\Run;
	$whoops->pushHandler( new \Whoops\Handler\PrettyPageHandler );
	$whoops->register();
} else {
	ini_set( 'display_errors', 0 );
	ini_set( 'log_errors', 1 );
}
