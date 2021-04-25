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
 */

return array(
	'routing'	=> 	array(

		'prefixNamespace'	=>	'WTCalcs\Ui',

		'routes'	=>	array(
			//'^\/test(?P<params>[a-zA-Z0-9_\-\/]*)$'	=>	'IndexAction',
			'/wilks'     	=>  'Wilks\WilksAction',
			'/onerm'     	=>  'Onerm\OnermAction',
			'_default_'		=>  'Index\IndexAction',
			'_404_'			=>	'NotFound\NotFoundAction',
		)
	)
);
