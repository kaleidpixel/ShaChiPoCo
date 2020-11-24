<?php
/**
 * 各固定ディレクトリ・ファイルのパス.
 *
 * @since    1.0.0
 * @author   KUCKLU <kuck1u@users.noreply.github.com>
 * @licenses MIT License.
 */

realpath( $_SERVER[ 'SCRIPT_FILENAME' ] ) !== realpath( __FILE__ ) OR exit( 'No direct script access allowed' );

define( 'APPPATH', BASEPATH . DIRECTORY_SEPARATOR . 'apps' );
define( 'COREPATH', APPPATH . DIRECTORY_SEPARATOR . 'Core' );
define( 'USERCOREPATH', APPPATH . DIRECTORY_SEPARATOR . 'User' );
define( 'VENDORPATH', BASEPATH . DIRECTORY_SEPARATOR . 'vendor' );
define( 'PUBLICPATH', BASEPATH . DIRECTORY_SEPARATOR . 'public' );
define( 'SRCPATH', BASEPATH . DIRECTORY_SEPARATOR . 'src' );
define( 'CACHEPATH', BASEPATH . DIRECTORY_SEPARATOR . 'cache' );
