<?php
/**
 * URL Helper.
 *
 * @since    1.0.0
 * @author   KUCKLU <kuck1u@users.noreply.github.com>
 * @licenses MIT License.
 * @package  ShaChiPoCo
 */

realpath( $_SERVER[ 'SCRIPT_FILENAME' ] ) !== realpath( __FILE__ ) OR exit( 'No direct script access allowed' );

use LayerShifter\TLDExtract\Extract;

/**
 * URL として正しいかを検証
 *
 * @param string $url
 *
 * @return bool
 */
function is_valid_url( $url ) {
	return false !== filter_var( $url, FILTER_VALIDATE_URL ) && preg_match( '@^https?+://@i', $url );
}

/**
 * Delete leading slash.
 *
 * @param string $str
 *
 * @return string
 */
function delete_leading_slash( $str = '' ) {
	if ( ! empty( $str) ) {
		$str = ltrim( $str, '/\\' );
	}

	return $str;
}

/**
 * Add leading slash.
 *
 * @param string $str
 *
 * @return string
 */
function leading_slash( $str = '' ) {
	$str = delete_leading_slash( $str );

	return "/{$str}";
}

/**
 * Delete trailing slash.
 *
 * @param string $str
 *
 * @return string
 */
function delete_trailing_slash( $str = '' ) {
	if ( ! empty( $str) ) {
		$str = rtrim( $str, '/\\' );
	}

	return $str;
}

/**
 * Add trailing slash.
 *
 * @param string $str
 *
 * @return string
 */
function trailing_slash( $str = '' ) {
	$str = delete_trailing_slash( $str );

	return "{$str}/";
}

/**
 * ウェブサイトの URL を返す
 *
 * @param string $path
 * @param bool   $last_slash
 *
 * @return string
 */
function url( $path = '', $last_slash = true ) {
	static $root = '';

	$result = $root;
	$path   = delete_leading_slash( $path );

	if ( empty( $root ) ) {
		$extract  = new Extract();
		$fqdn     = $extract->parse( filter_var( $_SERVER[ 'SERVER_NAME' ], FILTER_SANITIZE_URL ) );
		$protocol = 'http';

		if ( isset( $_SERVER[ 'HTTPS' ] ) && $_SERVER[ 'HTTPS' ] === 'on' ) {
			$protocol = 'https';
		}

		$root   = $protocol . '://' . $fqdn->getFullHost();
		$result = $root;
	}

	if ( ! empty( $path ) ) {
		$result = "{$result}/{$path}";
	}

	if ( $last_slash === true ) {
		$result = trailing_slash( $result );
	}

	return $result;
}

/**
 * 現在の URL を返す
 *
 * @param string $path
 * @param bool   $last_slash
 *
 * @return string
 */
function current_url( $path = '', $last_slash = true ) {
	$protocol = 'http';
	$path     = delete_leading_slash( $path );

	if ( isset( $_SERVER[ 'HTTPS' ] ) && $_SERVER[ 'HTTPS' ] === 'on' ) {
		$protocol = 'https';
	}

	$result = $protocol . '://' . $_SERVER[ 'HTTP_HOST' ] . delete_trailing_slash( $_SERVER[ 'REQUEST_URI' ] );

	if ( ! empty( $path ) ) {
		$result = "{$result}/{$path}";
	}

	if ( $last_slash === true ) {
		$result = trailing_slash( $result );
	}

	return $result;
}

/**
 * assets の URL を返す
 *
 * @param string $path
 *
 * @return string
 */
function assets_url( $path = '' ) {
	$path   = delete_leading_slash( $path );
	$result = $path;

	if ( ! empty( $path ) ) {
		$result = url( "assets/{$path}", false );
	}

	return $result;
}
