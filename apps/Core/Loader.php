<?php
/**
 * Loader.
 *
 * @since    1.0.0
 * @author   KUCKLU <kuck1u@users.noreply.github.com>
 * @licenses MIT License.
 * @package  ShaChiPoCo
 */

namespace ShaChiPoCo\Core;

realpath( $_SERVER[ 'SCRIPT_FILENAME' ] ) !== realpath( __FILE__ ) OR exit( 'No direct script access allowed' );

use \Twig\Loader\FilesystemLoader as TwigFilesystemLoader;
use \Twig\Environment as TwigEnvironment;
use \Twig\Extension\EscaperExtension as TwigEscaperExtension;
use \Twig\Extension\OptimizerExtension as TwigOptimizerExtension;
use \Twig\TwigFunction as TwigFunction;

class Loader {
	public function __construct() {
	}

	/**
	 * テンプレートをレンダリングします.
	 *
	 * @param string $view
	 * @param array  $vars
	 * @param bool   $auto_reload
	 *
	 * @static
	 * @throws \Twig\Error\LoaderError
	 * @throws \Twig\Error\RuntimeError
	 * @throws \Twig\Error\SyntaxError
	 */
	public function view( $view = '', $vars = [], $auto_reload = false ) {
		global $with;

		$this->helper( [ 'Url', 'Utility' ] );

		$view        = filter_var( $view, FILTER_SANITIZE_FULL_SPECIAL_CHARS );
		$view        = preg_replace( '#[^a-zA-Z0-9_\-/]+#', '', $view );
		$cache       = getenv( 'APP_ENV' ) === 'development';
		$loader      = new TwigFilesystemLoader( SRCPATH . DIRECTORY_SEPARATOR . 'twig' );
		$auto_reload = $cache === true || $auto_reload === true ? true : false;
		$twig        = new TwigEnvironment( $loader, [
			'debug'       => $cache,
			'auto_reload' => $auto_reload,
			'cache'       => CACHEPATH . DIRECTORY_SEPARATOR . 'twig',
		] );

		$twig->addExtension( new TwigEscaperExtension() );
		$twig->addExtension( new TwigOptimizerExtension() );
		$twig->addFunction( new TwigFunction( 'url', 'url' ) );
		$twig->addFunction( new TwigFunction( 'current_url', 'current_url' ) );
		$twig->addFunction( new TwigFunction( 'assets_url', 'assets_url' ) );
		$twig->addFunction( new TwigFunction( 'dump', 'var_dump' ) );

		echo $twig->render(  "{$with( strtolower( $view ) )}.twig", $vars );
	}

	/**
	 * @param string $name
	 * @param array  $vars
	 *
	 * @return mixed
	 */
	public function library( $name = '', $vars = [], $category = '' ) {
		static $_classes = [];

		$namespace = '';
		$name   = ucfirst( $name );
		$name   = preg_replace( '#[^a-zA-Z0-9_]+#', '', $name );

		if ( in_array( $category, [ 'model' ], true ) === false ) {
			$category = 'library';
		}

		if ( isset( $_classes[ $category ][ $name ] ) ) {
			return $_classes[ $category ][ $name ];
		}

		foreach ( [ USERCOREPATH, COREPATH ] as $k => $path ) {
			$file = $path . DIRECTORY_SEPARATOR . $category . DIRECTORY_SEPARATOR . $name . '.php';

			if ( file_exists( $file ) ) {
				$namespace = 'ShaChiPoCo\\' . str_replace( APPPATH . DIRECTORY_SEPARATOR, '', $path ) . '\\' . $category;

				require_once $file;
				break;
			}
		}

		if ( empty( $namespace ) ) {
			http_response_code( 500 );
			exit( 'No ' . $name . ' file.' );
		}

		$class                          = $namespace . '\\' . $name;
		$_classes[ $category ][ $name ] = empty( $vars ) ? new $class : new $class( $vars );

		return $_classes[ $category ][ $name ];
	}

	/**
	 * @param string|array $name
	 *
	 * @return void
	 */
	public function helper( $helper ) {
		$helper = (array) $helper;
		$count  = count( $helper );

		for ( $i = 0; $i < $count; $i++ ) {
			$helper[ $i ] = ucfirst( $helper[ $i ] );
			$helper[ $i ] = preg_replace( '#[^a-zA-Z0-9_]+#', '', $helper[ $i ] );

			foreach ( [ USERCOREPATH, COREPATH ] as $k => $path ) {
				$file = $path . DIRECTORY_SEPARATOR . 'Helper' . DIRECTORY_SEPARATOR . $helper[ $i ] . '.php';

				if ( file_exists( $file ) ) {
					require_once $file;
					break;
				}
			}
		}
	}

	/**
	 * @param string $name
	 * @param array  $vars
	 *
	 * @return mixed
	 */
	public function model( $name = '', $vars = [] ) {
		return $this->library( $name, $vars, 'model' );
	}
}
