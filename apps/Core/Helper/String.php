<?php
/**
 * String helper.
 *
 * @since    1.0.0
 * @author   KUCKLU <kuck1u@users.noreply.github.com>
 * @licenses MIT License.
 * @package  ShaChiPoCo
 */

realpath( $_SERVER[ 'SCRIPT_FILENAME' ] ) !== realpath( __FILE__ ) OR exit( 'No direct script access allowed' );

/**
 * 文字列の前後の空白を削除する
 *
 * @see    https://qiita.com/saku/items/2cac8fbd7cfd4c493b52#comment-7e9705f0a7b0441ac203
 * @see    mb_trim()
 *
 * @param string $str
 *
 * @return string|string[]|null
 * @internal
 * @access private
 */
function _mb_trim( $str ) {
	static $chars = "[\\x0-\x20\x7f\xc2\xa0\xe3\x80\x80]";

	return preg_replace( "/\A{$chars}++|{$chars}++\z/u", '', $str );
}

/**
 * 文字列の前後の空白を削除する
 *
 * @param string|array $str
 *
 * @return string|string[]|array|null
 */
function mb_trim( $str ) {
	if ( is_object( $str ) ) {
		$str = (array) $str;
	}

	if ( is_array( $str ) ) {
		foreach ( $str as $k => $v ) {
			if ( is_object( $v ) ) {
				$v = (array) $v;
			}

			if ( is_array( $v ) ) {
				$str[ $k ] = mb_trim( $v );
			} else {
				$str[ $k ] = _mb_trim( $v );
			}
		}
	} else {
		$str = _mb_trim( $str );
	}

	return $str;
}

/**
 * 文字列をスペース区切りで配列にする
 *
 * @see https://qiita.com/saku/items/2cac8fbd7cfd4c493b52#comment-7e9705f0a7b0441ac203
 *
 * @param string $str
 *
 * @return array[]|false|string[]
 */
function mb_str_words( $str = '' ) {
	static $chars = "[\\x0-\x20\x7f\xc2\xa0\xe3\x80\x80]";

	return preg_split( "/{$chars}++/u", $str, -1, PREG_SPLIT_NO_EMPTY );
}

/**
 * 文字列を指定の文字数でカットする
 *
 * @param string $str
 * @param int    $length
 * @param null   $more
 * @param bool   $blank
 *
 * @return string
 */
function trim_words( $str = '', $length = 32, $more = null, $blank = false ) {
	$cut   = 0;
	$index = 0;
	$more  = ( is_null( $more ) ) ? '&hellip;' : $more;
	$str   = h( (string) $str );
	$str   = str_replace( array( "\r\n", "\r" ), "\n", $str );

	if ( $blank === false ) {
		$str = str_replace( "\n", '', $str );
	}

	$str        = preg_split( '//u', $str, -1, PREG_SPLIT_NO_EMPTY );
	$none_blank = array_filter( $str, 'trim' );

	foreach ( $none_blank as $k => $v ) {
		if ( $length > $index ) {
			$cut = $k;
		}

		$index++;
	}

	$str = array_slice( $str, 0, $cut + 1 );

	if ( $length < count( $none_blank ) ) {
		$str[] = $more;
	}

	return implode( '', $str );
}

/**
 * 全角英数字記号->半角、半角カナ->全角へ変換
 *
 * @param string $str
 * @param string $encoding
 *
 * @return string
 */
function convert_str( $str = '', $encoding = 'UTF-8' ) {
	return mb_convert_kana( $str, 'aKVs', $encoding );
}

/**
 * 連続した改行を段落に変換
 *
 * @param string $str
 * @param string $class
 * @param bool   $striptags
 * @param bool   $xhtml
 *
 * @return string
 */
function auto_p_nl2br( $str = '', $class = '', $striptags = true, $xhtml = true ) {
	$result = '';
	$class  = preg_replace( '|%[a-fA-F0-9][a-fA-F0-9]|', '', $class );
	$class  = preg_replace( '/[^A-Za-z0-9_-]/', '', $class );

	if ( ! empty( $class ) ) {
		$class = " class='{$class}'";
	}

	if ( is_string( $str ) ) {
		$str = mb_trim( $str );
		$str = str_replace( array( "\r\n", "\r" ), "\n", $str );
		$arr = preg_split( '/\n\n+/', $str, -1, PREG_SPLIT_NO_EMPTY );

		foreach ( $arr as $value ) {
			if ( $striptags === true ) {
				$value = strip_tags( $value, '<br><a><string><em><b><span>' );
			}

			$result .= '<p' . $class . '>' . nl2br( $value, $xhtml ) . '</p>';
		}
	}

	return $result;
}

/**
 * シリアライズ化された文字列なのかを調べる
 *
 * @param string $str
 *
 * @return bool
 */
function is_serialized( $str = '' ) {
	$result = false;

	if ( is_string( $str ) ) {
		$str  = trim( $str );
		$data = @unserialize( $str );

		if ( $str === 'b:0;' || $data !== false ) {
			$result = true;
		}
	}

	return $result;
}

/**
 * 配列とオブジェクトをシリアライズ化する
 *
 * @param string $str
 *
 * @return string
 */
function maybe_serialize( $str = '' ) {
	$result = $str;

	if ( is_array( $str ) || is_object( $str ) ) {
		$result = serialize( $str );
	}

	return $result;
}

/**
 * シリアライズ化された文字列を配列またはオブジェクトに戻す
 *
 * @param string $str
 *
 * @return mixed
 */
function maybe_unserialize( $str = '' ) {
	$str    = trim( $str );
	$result = $str;

	if ( is_string( $str ) && is_serialized( $str ) ) {
		$result = unserialize( $str );
	}

	return $result;
}

/**
 * パラメーターを文字列にする
 * $hoge = s( $_GET['hoge'] );
 *
 * @see https://qiita.com/mpyw/items/25abc3e51fddc85e0ec0
 *
 * @param string|string[] $var
 *
 * @return string
 */
function s( $var = '' ) {
	if ( is_object( $var ) ) {
		$var = (array) $var;
	}

	if ( is_array( $var ) ) {
		$result = h( $var );
	} else {
		$result = filter_var( $var, FILTER_SANITIZE_FULL_SPECIAL_CHARS );
	}

	return $result;
}

/**
 * 文字列を無害化する
 * Object は Array に変換される
 *
 * @param string|string[] $var
 *
 * @return array|string
 */
function h( $var = '' ) {
	if ( is_object( $var ) ) {
		$var = (array) $var;
	}

	if ( is_array( $var ) ) {
		return array_map( __NAMESPACE__ . '\h', $var );
	} else {
		$var = str_replace( "\0", '', $var );

		return s( $var );
	}
}

/**
 * @param string|string[] $str
 *
 * @return array|null|string|string[]
 */
function q( $str = '' ) {
	if ( is_object( $str ) ) {
		$str = (array) $str;
	}

	if ( is_array( $str ) ) {
		return array_map( __NAMESPACE__ . 'q', $str );
	} else {
		if ( get_magic_quotes_gpc() ) {
			$str = stripslashes( $str );
		}

		if ( ! is_numeric( $str ) ) {
			$str   = addslashes( $str );
			$pre   = array( '/\n/m', '/\r/m', '/\x1a/m' );
			$after = array( '\\\n', '\\\r', '\Z' );
			$str   = preg_replace( $pre, $after, $str );
		}

		return $str;
	}
}
