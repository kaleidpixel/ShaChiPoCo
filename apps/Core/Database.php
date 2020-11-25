<?php
/**
 * データベース.
 *
 * @since    1.0.0
 * @author   KUCKLU <kuck1u@users.noreply.github.com>
 * @licenses MIT License.
 * @package  ShaChiPoCo
 */

namespace ShaChiPoCo\Core;

realpath( $_SERVER[ 'SCRIPT_FILENAME' ] ) !== realpath( __FILE__ ) OR exit( 'No direct script access allowed' );

class Database {
	/**
	 * Reference to the Core singleton.
	 *
	 * @var	object
	 */
	private static $instance;

	/**
	 * PDO instance.
	 *
	 * @var bool|object|resource
	 */
	private static $connect = false;

	/**
	 * Class constructor.
	 *
	 * @return	void
	 */
	public function __construct() {
		self::$instance = $this;

		$dns = getenv( 'DB_CONNECTION' ) . ':dbname=' . getenv( 'DB_DATABASE' ) . ';host=' . getenv( 'DB_HOST' ) . ':' . getenv( 'DB_PORT' ) . ';charset=' . getenv( 'APP_CHARSET_STR' );
		$this->db_connect( $dns, getenv( 'DB_USERNAME' ), getenv( 'DB_PASSWORD' ) );
	}

	/**
	 * DB connect.
	 *
	 * @param $dns
	 * @param $user
	 * @param $pass
	 *
	 * @return void
	 */
	protected function db_connect( $dns, $user, $pass ) {
		if ( is_object( self::$connect ) || is_resource( self::$connect ) ) {
			return;
		}

		try {
			self::$connect = new \PDO( $dns, $user, $pass, [
				\PDO::ATTR_ERRMODE                  => \PDO::ERRMODE_EXCEPTION,
				\PDO::ATTR_DEFAULT_FETCH_MODE       => \PDO::FETCH_ASSOC,
				\PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true,
				\PDO::MYSQL_ATTR_INIT_COMMAND       => 'SET sql_mode="NO_ENGINE_SUBSTITUTION,STRICT_TRANS_TABLES"',
				\PDO::ATTR_EMULATE_PREPARES         => false,
				\PDO::ATTR_STRINGIFY_FETCHES        => false,
			] );
		} catch ( \PDOException $e ) {
			error_log( print_r( $e, true ) );
		}
	}

	/**
	 * @param string $query
	 * @param array  $params
	 * @param        $params
	 *
	 * @return bool|\PDOStatement
	 */
	protected function query_execute( $query, $params ) {
		$stmt = self::$connect->prepare( $query );

		if ( ! empty( $params ) && is_array( $params ) ) {
			foreach ( $params as $v ) {
				if ( ! isset( $v[ 2 ] ) || empty( $v[ 2 ] ) ) {
					$v[ 2 ] = \PDO::PARAM_STR;
				}

				/**
				 * @param string Parameter
				 * @param string Value
				 * @param int    Data type
				 */
				$stmt->bindValue( $v[ 0 ], $v[ 1 ], $v[ 2 ] );
			}
		}

		$stmt->execute();

		return $stmt;
	}

	/**
	 * データベースに問い合わせた結果を配列で返す
	 *
	 * @param string $query
	 * @param array  $bind_params
	 * @param string $mode
	 * @param bool   $insert_id
	 *
	 * @return array
	 */
	public function query_select( $query = '', $bind_params = [], $mode = 'all', $insert_id = false ) {
		$result  = [];
		$mysqlnd = function_exists( 'mysqli_fetch_all' );

		try {
			$i    = 0;
			$stmt = $this->query_execute( $query, $bind_params );

			if ( $mysqlnd === false ) {
				for ( $i = 0; $i < $stmt->columnCount(); $i++ ) {
					$column = $stmt->getColumnMeta( $i );

					if ( isset( $column[ 'native_type' ] ) === true ) {
						switch ( $column[ 'native_type' ] ) {
							case 'TINY':
							case 'SHORT':
							case 'INT24':
							case 'LONG':
							case 'LONGLONG':
								$pdo_type = \PDO::PARAM_INT;
								break;
							default:
								$pdo_type = \PDO::PARAM_STR;
						}
					}

					$row[ $column[ 'name' ] ] = $pdo_type;
				}
			}

			if ( $mode === 'all' ) {
				$tmp = $stmt->fetchAll();

				if ( $mysqlnd === false ) {
					$tmp_count = count( $tmp );

					for ( $i = 0; $i < $tmp_count; $i++ ) {
						foreach ( $tmp[ $i ] as $k => $v ) {
							$result[ $i ][ $k ] = ( $row[ $k ] === \PDO::PARAM_INT ) ? (int) $v : (string) $v;
						}

						unset( $tmp[ $i ] );
					}

					unset( $row );
				} else {
					$result = $tmp;
				}
			} else {
				$tmp = $stmt->fetch();

				if ( $mysqlnd === false ) {
					foreach ( $tmp as $k => $v ) {
						$result[ $k ] = ( $row[ $k ] === \PDO::PARAM_INT ) ? (int) $v : (string) $v;

						unset( $tmp[ $k ], $row[ $k ] );
					}
				} else {
					$result = $tmp;
				}
			}

			if ( $insert_id === true ) {
				$result = array_merge( $result, [
					'insert_id' => self::$connect->lastInsertId( 'id' ),
				] );
			}
		} catch ( \PDOException $e ) {
			$result = [
				'error' => $e,
			];
		}

		unset( $query, $bind_params, $mode, $insert_id, $i, $ii, $row, $column, $stmt );

		$this->query_debug_log( $result );

		return $result;
	}

	/**
	 * データベースのテーブルにデータを挿入する
	 *
	 * @param string $query
	 * @param array  $bind_params
	 * @param bool   $transaction
	 *
	 * @return array|bool
	 */
	public function query_insert( $query = '', $bind_params = [], $transaction = false ) {
		if ( $transaction === true ) {
			self::$connect->beginTransaction();
		}

		try {
			$stmt   = $this->query_execute( $query, $bind_params );
			$result = array(
				'db_insert_id' => self::$connect->lastInsertId( 'id' ),
			);

			if ( $transaction === true ) {
				self::$connect->commit();
			}
		} catch ( \PDOException $e ) {
			if ( $transaction === true ) {
				self::$connect->rollback();
			}

			$result = array(
				'error' => $e,
			);
		}

		unset( $query, $bind_params, $mode, $transaction, $stmt );

		self::query_debug_log( $result );

		return $result;
	}

	/**
	 * データベースの情報を更新する
	 *
	 * @param string $query
	 * @param array  $bind_params
	 * @param bool   $transaction
	 *
	 * @return array|bool
	 */
	public function query_update( $query = '', $bind_params = [], $transaction = false ) {
		return $this->query_insert( $query, $bind_params, $transaction );
	}

	/**
	 * データベースの情報を削除する
	 *
	 * @param string $query
	 * @param array  $bind_params
	 * @param bool   $transaction
	 *
	 * @return array|bool
	 */
	public function query_delete( $query = '', $bind_params = [], $transaction = false ) {
		return $this->query_insert( $query, $bind_params, $transaction );
	}

	/**
	 * エラー内容をログに出力
	 *
	 * @param array $result
	 */
	public function query_debug_log( $result = array() ) {
		if ( $this->is_query_error( $result ) ) {
			ini_set( 'log_errors', 1 );
			error_log( print_r( $result, true ) );
			unset( $result );
		}
	}

	/**
	 * データベースへの問い合わせ結果がエラーか調べる
	 *
	 * @param array $result
	 *
	 * @return bool
	 */
	public function is_query_error( $result = array() ) {
		return isset( $result[ 'error' ] );
	}

	/**
	 * MySQL の IN 関数の引数に入れる名前つきプレースホルダの生成とバインドするパラメーターを生成する
	 *
	 * @param array  $keys
	 * @param string $name
	 * @param array  $bind_params
	 *
	 * @return array
	 */
	public function create_where_in( $keys = [], $name = '', $bind_params = [] ) {
		$count       = count( $keys );
		$placeholder = [];

		for ( $i = 0; $i < $count; $i++ ) {
			if ( is_numeric( $keys[ $i ] ) ) {
				$keys[ $i ] = (int) $keys[ $i ];
			}

			$placeholder[] = ":{$name}_{$i}";
			$bind_params[] = [ ":{$name}_{$i}", $keys[ $i ], is_numeric( $keys[ $i ] ) ? \PDO::PARAM_INT : \PDO::PARAM_STR ];

			unset( $keys[ $i ] );
		}

		return [
			implode( ',', $placeholder ),
			$bind_params,
		];
	}
}
