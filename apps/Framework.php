<?php
/**
 * Core file includes.
 *
 * @since    1.0.0
 * @author   KUCKLU <kuck1u@users.noreply.github.com>
 * @licenses MIT License.
 */

if ( file_exists( dirname( __DIR__ )  . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php' ) ) {
	require_once dirname( __DIR__ )  . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

	$files = [
		'Env', 'Path', 'Header'
	];

	foreach ( $files as $k => $file ) {
		if ( file_exists( __DIR__ . DIRECTORY_SEPARATOR . 'User' . DIRECTORY_SEPARATOR  . "{$file}.php" ) ) {
			require_once __DIR__ . DIRECTORY_SEPARATOR . 'User' . DIRECTORY_SEPARATOR . "{$file}.php";
		} else {
			require_once __DIR__ . DIRECTORY_SEPARATOR . 'Core' . DIRECTORY_SEPARATOR . "{$file}.php";
		}

		unset( $files[ $k ] );
	}
} else {
	error_log( print_r( "Please composer install.\n", true ), 3, dirname( __DIR__ ) . DIRECTORY_SEPARATOR . 'logs' . DIRECTORY_SEPARATOR . 'framework_error.log' );
	exit( 'Framework file load error.' );
}

/**
 * Router.
 */
if ( file_exists( USERCOREPATH . DIRECTORY_SEPARATOR . 'Router.php' ) ) {
	$router = require_once USERCOREPATH . DIRECTORY_SEPARATOR . 'Router.php';
} else {
	$router = require_once COREPATH . DIRECTORY_SEPARATOR . 'Router.php';
}

$action = $router->match( $_SERVER[ 'REQUEST_METHOD' ], $_SERVER[ 'REQUEST_URI' ] );
$CWSPF  = new $action->value[ 0 ];

call_user_func_array( [ $CWSPF, $action->value[ 1 ] ], array_values( $action->param ) );

unset( $router, $action, $CWSPF );
