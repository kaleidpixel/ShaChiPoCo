<?php
/**
 * Integer helper.
 *
 * @since    1.0.0
 * @author   KUCKLU <kuck1u@users.noreply.github.com>
 * @licenses MIT License.
 * @package  ShaChiPoCo
 */

/**
 * パーセントを計算する
 *
 * @param int $num1
 * @param int $num2
 * @param int $precision
 *
 * @return float
 */
function num_per( $num1 = 0, $num2 = 0, $precision = 0 ) {
	$num1      = filter_var( $num1, FILTER_VALIDATE_INT );
	$num2      = filter_var( $num2, FILTER_VALIDATE_INT );
	$precision = filter_var( $precision, FILTER_VALIDATE_INT );

	if ( is_null( $precision ) || $precision === false ) {
		$precision = 0;
	}

	$res = ( empty( $num1 ) || empty( $num2 ) ) ? 0 : round( ( $num1 / $num2 * 100 ), $precision );

	return $res;
}

/**
 * 割り算をする
 *
 * @param int $num1
 * @param int $num2
 * @param int $precision
 *
 * @return float
 */
function num_div( $num1 = 0, $num2 = 0, $precision = 0 ) {
	$num1      = filter_var( $num1, FILTER_VALIDATE_INT );
	$num2      = filter_var( $num2, FILTER_VALIDATE_INT );
	$precision = filter_var( $precision, FILTER_VALIDATE_INT );

	if ( is_null( $precision ) || $precision === false ) {
		$precision = 0;
	}

	$res = ( empty( $num1 ) || empty( $num2 ) ) ? 0 : round( ( $num1 / $num2 ), $precision );

	return $res;
}

/**
 * 合計値から指定された倍数の数値を配列で返す
 *
 * @param int $total
 * @param int $multiple
 *
 * @return array
 */
function num_multiple( $total = 100, $multiple = 5 ) {
	$result = [];

	for ( $i = $multiple; $i < $total; $i = $i + $multiple ) {
		$result[] = $i;
	}

	return $result;
}
