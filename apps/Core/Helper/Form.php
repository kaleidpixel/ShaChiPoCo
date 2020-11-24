<?php
/**
 * Form helper.
 *
 * @since    1.0.0
 * @author   KUCKLU <kuck1u@users.noreply.github.com>
 * @licenses MIT License.
 * @package  ShaChiPoCo
 */

realpath( $_SERVER[ 'SCRIPT_FILENAME' ] ) !== realpath( __FILE__ ) OR exit( 'No direct script access allowed' );

require_once COREPATH . DIRECTORY_SEPARATOR . 'Helper'. DIRECTORY_SEPARATOR . 'String.php';
require_once COREPATH . DIRECTORY_SEPARATOR . 'Helper'. DIRECTORY_SEPARATOR . 'Token.php';

/**
 * $_POST を取得する.
 *
 * @param string $specialchars
 * @param string $mode
 *
 * @return array
 */
function form_post( $specialchars = '', $mode = '_POST' ) {
	switch ( $mode ) {
		case '_GET' :
			$data = $_GET;
			break;
		default:
			$data = $_POST;
			break;
	}

	switch ( $specialchars ) {
		case 'raw' :
			$result = $data;
			break;
		default:
			$result = array_map( 'mb_trim', $data );
			$result = array_map( 'h', $result );
		break;
	}

	return $result;
}

/**
 * $_GET を取得する.
 *
 * @param string $specialchars
 *
 * @return array
 */
function form_get( $specialchars = '' ) {
	return form_post( $specialchars, '_GET' );
}

/**
 * CSRF 用のトークンと From パーツを生成する.
 *
 * @return array
 */
function set_csrf_token() {
	$token = generate_unique_token();

	$_SESSION[ 'csrf_token' ] = $token;

	return [
		'csrf_token' => $token,
		'csrf_form'  => '<input type="hidden" name="csrf_token" value="' . $token . '">',
	];
}

/**
 * 送られてきた CSRF 用トークンを検証する.
 *
 * @return bool
 */
function validate_csrf_token() {
	$result = false;

	if (
		isset( $_POST[ 'csrf_token' ] )
		&& ! empty( $_POST[ 'csrf_token' ] )
		&& isset( $_SESSION[ 'csrf_token' ] )
		&& ! empty( $_SESSION[ 'csrf_token' ] )
	) {
		$result = $_POST[ 'csrf_token' ] === $_SESSION[ 'csrf_token' ];
	}

	destroy_csrf_token();

	return $result;
}

/**
 * セッションに保存されている CSRF 用トークンを破棄する.
 *
 * @return void
 */
function destroy_csrf_token() {
	unset( $_SESSION[ 'csrf_token' ] );
}

/**
 * Compares the first two arguments and if identical marks as $type
 *
 * @param mixed  $checked One of the values to compare
 * @param mixed  $current (true) The other value to compare if not just true
 * @param string $format  The format of string|array we are doing
 * @param bool   $echo    Whether to echo or just return the string
 * @param string $type    The type of checked|selected|disabled|readonly we are doing
 *
 * @return string html attribute or empty string
 * @internal
 * @access private
 */
function __checked_helper( $checked, $current, $format, $echo, $type ) {
	if ( $format === 'array' && is_array( $checked ) ) {
		$result = ( in_array( $current, array_keys( $checked ), true ) ) ? " $type='$type'" : '';
	} else {
		$checked = (string) $checked;
		$current = (string) $current;
		$result  = ( $checked === $current ) ? " $type='$type'" : '';
	}

	if ( $echo ) {
		echo $result;
	}

	return $result;
}

/**
 * Outputs the html checked attribute.
 *
 * @param mixed  $checked One of the values to compare
 * @param mixed  $current (true) The other value to compare if not just true
 * @param string $format  The format of string|array we are doing
 * @param bool   $echo    Whether to echo or just return the string
 *
 * @return string html attribute or empty string
 */
function checked( $checked, $current = true, $format = 'string', $echo = true ) {
	return __checked_helper( $checked, $current, $format, $echo, 'checked' );
}

/**
 * Outputs the html selected attribute.
 *
 * @param mixed  $checked One of the values to compare
 * @param mixed  $current (true) The other value to compare if not just true
 * @param string $format  The format of string|array we are doing
 * @param bool   $echo    Whether to echo or just return the string
 *
 * @return string html attribute or empty string
 */
function selected( $checked, $current = true, $format = 'string', $echo = true ) {
	return __checked_helper( $checked, $current, $format, $echo, 'selected' );
}

/**
 * アラートメッセージをセッションにセットする
 *
 * @param string $body
 * @param string $type
 */
function create_alert_message( $body = '', $type = 'success' ) {
	$_SESSION[ 'alerts' ][] = [
		'body' => h( $body )
	];
	$_SESSION[ 'alert_type' ] = $type;

	session_write_close();
}

/**
 * セッションにセットされたアラートメッセージを View で使用できるようにセット
 *
 * @param array $vers
 *
 * @return array
 */
function merge_alert_message( array $vers ) {
	if ( isset( $_SESSION[ 'alerts' ] ) && isset( $_SESSION[ 'alerts' ][ 0 ][ 'body' ] ) ) {
		$vers[ 'alerts' ]     = $_SESSION[ 'alerts' ];
		$vers[ 'alert_type' ] = $_SESSION[ 'alert_type' ];

		unset( $_SESSION[ 'alerts' ], $_SESSION[ 'alert_type' ] );
	}

	return $vers;
}

/**
 * Form の送信データをセッションに保存
 *
 * @param array $_post
 */
function keep_send_post( array $_post ) {
	unset( $_post[ 'csrf_token' ] );

	$_SESSION[ '_post' ] = $_post;
}

/**
 * セッションに保存されている Form の送信データを破棄する
 */
function destroy_send_post( ) {
	unset( $_SESSION[ '_post' ] );
}
