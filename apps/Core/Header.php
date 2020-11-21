<?php
/**
 * HTTP header.
 *
 * @since    1.0.0
 * @author   KUCKLU <kuck1u@users.noreply.github.com>
 * @licenses MIT License.
 * @package  ShaChiPoCo
 */

// プロジェクト全体の内部エンコーディング
define( 'APP_CHARSET', getenv( 'APP_CHARSET' ) );
ini_set( 'default_charset', APP_CHARSET );
mb_internal_encoding( APP_CHARSET );

// APP_CHARSET からハイフンを削除
define( 'APP_CHARSET_DB', str_replace( '-', '', mb_strtolower( APP_CHARSET ) ) );

// プロジェクト全体の言語
define( 'APP_LANGUAGE', getenv( 'APP_LANGUAGE' ) );

// タイムゾーン
date_default_timezone_set( getenv( 'APP_TIMEZONE' ) );

// ロボット制御用のヘッダーを出力
if ( getenv( 'APP_ENV' ) === 'development' ) {
	header( 'X-Robots-Tag: noindex, nofollow, noarchive, nocache, noodp, nosnippet' );
}

// HTTPS 接続時のヘッダーを出力
if ( isset( $_SERVER[ 'HTTP_X_FORWARDED_PROTO' ] ) && $_SERVER[ 'HTTP_X_FORWARDED_PROTO' ] === 'https' ) {
	$_SERVER[ 'HTTPS' ] = 'on';
}

// SSL 関連のヘッダーを出力
// 本来はサーバー側で設定する
if ( isset( $_SERVER[ 'HTTPS' ] ) && $_SERVER[ 'HTTPS' ] === 'on' ) {
	header( 'X-SSL-Status: https' );
	header( 'Strict-Transport-Security: max-age=63072000; includeSubDomains; preload' );
	header( "Content-Security-Policy: default-src https: data: 'unsafe-eval' 'unsafe-inline'" );
}

// セキュリティポリシーのヘッダーを出力
header( 'X-XSS-Protection: 1; mode=block' );
header( 'X-Content-Type-Options: nosniff' );
header( 'X-Frame-Options: SAMEORIGIN' );
header( 'X-Download-Options: noopen' );
header( 'Referrer-Policy: no-referrer-when-downgrade' );

// web ページ全体の文字コードヘッダーを出力
header( 'Content-type: text/html; charset=' . APP_CHARSET );

header_register_callback( function () {
	header_remove( 'X-Powered-By' );
} );

session_start();
