<?php
/**
 * Token helper.
 *
 * @since    1.0.0
 * @author   KUCKLU <kuck1u@users.noreply.github.com>
 * @licenses MIT License.
 * @package  ShaChiPoCo
 */

realpath( $_SERVER[ 'SCRIPT_FILENAME' ] ) !== realpath( __FILE__ ) OR exit( 'No direct script access allowed' );

/**
 * ユニークなトークンを生成する.
 *
 * @return string
 */
function generate_unique_token( $length = 1 ) {
	$length = filter_var( $length, FILTER_VALIDATE_INT );

	if ( empty( $length ) || $length > 32 ) {
		$length = 32;
	}

	try {
		return uniqid( bin2hex( random_bytes( $length ) ) );
	} catch ( \Exception $e ) {
		return uniqid( bin2hex( openssl_random_pseudo_bytes( $length ) ) );
	}
}

/**
 * セッション ID を基にトークンを生成する.
 *
 * @return string
 */
function generate_session_token() {
	return hash( 'sha256', session_id() );
}

/**
 * @param string $token
 *
 * @return bool
 */
function validate_session_token( $token = '') {
	return $token === generate_session_token();
}
