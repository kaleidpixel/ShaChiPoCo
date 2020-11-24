<?php
/**
 * Auth model.
 *
 * @since    1.0.0
 * @author   KUCKLU <kuck1u@users.noreply.github.com>
 * @licenses MIT License.
 * @package  ShaChiPoCo
 */

namespace ShaChiPoCo\Core\Model;

realpath( $_SERVER[ 'SCRIPT_FILENAME' ] ) !== realpath( __FILE__ ) OR exit( 'No direct script access allowed' );

use ShaChiPoCo\Core\Model;

class Auth extends Model {
	public function __construct() {
		parent::__construct();
	}

	public function get_login_user( $login = '' ) {
		$query       = <<<SQL
SELECT
	`users`.`login` AS `login`,
	`users`.`password` AS `password`,
	`users`.`display_name` AS `display_name`
FROM
	`users`
WHERE
	`users`.`login` = :login
	AND `users`.`status` = 1
SQL;
		$bind_params = [
			[ ':login', $login, \PDO::PARAM_STR ],
		];

		return $this->db->query_select( $query, $bind_params, null );
	}
}
