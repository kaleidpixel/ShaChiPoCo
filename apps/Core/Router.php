<?php
/**
 * Default router.
 *
 * @since    1.0.0
 * @author   KUCKLU <kuck1u@users.noreply.github.com>
 * @licenses MIT License.
 */

namespace ShaChiPoCo\Core;

use \Teto\Routing\Router;

/**
 * Default router.
 */
return new Router( [
	[ 'GET', '/', [ __NAMESPACE__ . '\Controller\Welcome', 'index' ] ],
	'#404' => [ __NAMESPACE__ . '\Controller\Notfound404', 'index' ],
] );
