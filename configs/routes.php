<?php

/**
 * This file is part of the WTCalcs package.
 *
 * (c) Gordon Ansell <contact@gordonansell.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
 
/**
 * Routing configs.
 * :any - This matches anything from that point on in the URI, does not match "nothing".
 * :everything - Like :any, but also matches "nothing".
 * :segment - This matches only 1 segment in the URI, but that segment can be anything.
 * :num - This matches any numbers.
 * :alpha - This matches any alpha characters, including UTF-8.
 * :alnum - This matches any alphanumeric characters, including UTF-8.
 */

return array(
	'routes'	=>	array(
		'_default_'	=>  '/index',
        'wilks'     =>  '/wilks',
        'onerm'     =>  '/onerm'
	)
);
