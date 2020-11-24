<?php
/**
 * Array helper.
 *
 * @since    1.0.0
 * @author   KUCKLU <kuck1u@users.noreply.github.com>
 * @licenses MIT License.
 * @package  ShaChiPoCo
 */

realpath( $_SERVER[ 'SCRIPT_FILENAME' ] ) !== realpath( __FILE__ ) OR exit( 'No direct script access allowed' );

/**
 * 配列から最初の要素を取り出す
 *
 * @param array $array
 *
 * @return array
 */
function array_first_value( array $array ) {
	$result = array();

	if ( is_array( $array ) ) {
		$value  = reset( $array );
		$key    = key( $array );
		$result = array( $key => $value );
	}

	return $result;
}

/**
 * 配列から最後の要素を取り出す
 *
 * @param array $array
 *
 * @return array
 */
function array_last_value( array $array ) {
	$result = array();

	if ( is_array( $array ) ) {
		$value  = end( $array );
		$key    = key( $array );
		$result = array( $key => $value );
	}

	return $result;
}

/**
 * 配列から空の値を削除する
 *
 * Object は Array に変換される
 *
 * @param array $var
 *
 * @return array
 */
function array_filter_empty( array $var ) {
	$return = array();

	if ( ! is_array( $var ) ) {
		$var = (array) $var;
	}

	foreach ( $var as $k => $v ) {
		if ( is_object( $v ) ) {
			$v = (array) $v;
		}

		if ( is_array( $v ) ) {
			$return[ $k ] = array_filter_empty( $v );
		} elseif ( ! empty( $v ) || $v === '0' || $v === 0 ) {
			$return[ $k ] = $v;
		}

		unset( $var[ $k ] );
	}

	return $return;
}

/**
 * array_rand の精度をより高くする
 *
 * @see https://qiita.com/mpyw/items/c644dab7d067626fc629
 *
 * @param array $array
 * @param int   $num
 *
 * @return mixed|null
 */
function mt_array_rand( array $array, $num = 1 ) {
	static $max;

	if ( ! $max ) {
		$max = mt_getrandmax() + 1;
	}

	$num   = (int) $num;
	$count = count( $array );

	if ( $num <= 0 || $count < $num ) {
		return null;
	}

	foreach ( $array as $key => $_ ) {
		if ( ! $num ) {
			break;
		}

		if ( mt_rand() / $max < $num / $count ) {
			$retval[] = $key;
			--$num;
		}

		--$count;
	}

	return ! isset( $retval[ 1 ] ) ? $retval[ 0 ] : $retval;
}

/**
 * shuffleの精度をより高くする
 *
 * @see https://qiita.com/mpyw/items/c644dab7d067626fc629
 *
 * @param array $array
 */
function mt_shuffle( array $array ) {
	$array = array_values( $array );

	for ( $i = count( $array ) - 1; $i > 0; --$i ) {
		$j           = mt_rand( 0, $i );
		$tmp         = $array[ $i ];
		$array[ $i ] = $array[ $j ];
		$array[ $j ] = $tmp;
	}
}
