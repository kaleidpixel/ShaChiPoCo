<?php
/**
 * Utility helper.
 *
 * @since    1.0.0
 * @author   KUCKLU <kuck1u@users.noreply.github.com>
 * @licenses MIT License.
 * @package  ShaChiPoCo
 */

realpath( $_SERVER[ 'SCRIPT_FILENAME' ] ) !== realpath( __FILE__ ) OR exit( 'No direct script access allowed' );

/**
 * 与えられた値をただ返す
 *
 * @param mixed $value
 *
 * @return mixed
 */
function with( $v ) {
	return $v;
}

$GLOBALS[ 'with' ] = 'with';
