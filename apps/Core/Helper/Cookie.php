<?php
/**
 * クッキーをセットする
 *
 * @param string $name
 * @param string $value
 * @param int    $expire
 * @param string $path
 */
function setcookie_org( $name = '', $value = '', $expire = 86400, $path = '/' ) {
	$name   = h( $name );
	$value  = h( $value );
	$secure = $_SERVER[ 'HTTPS' ] === 'on';

	setcookie( "{$name}", "{$value}", $expire, "{$path}", '', $secure, true );
}
