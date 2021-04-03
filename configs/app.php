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
 * Application configs.
 */

return array(
	'logger'	=>	array(
		'level'		=>	'trace4',
	),
	'locale' 	=>	array(
		'timezone'	=>	'Europe/London',
		'lang'		=>	'en',
		'langFull'	=>	'en_GB',	
	),	
	'templateType'	=>	'plates',
	'template'	=> array(
		'templateDir'	=>	'layouts'
	),
	'locations' => array(
		'webroot'	=>	'https://wtcalcs.gordonansell.com/',
		'assets'	=>	'https://wtcalcs.gordonansell.com/public/'
	),
    'session' => array(
        'cookie_lifetime'   =>  '86400',
        'cookie_path'       =>  '/',
        'gc_maxlifetime'    =>  '86400',
        'gc_probability'    =>  '1',
        'gc_divisor'        =>  '100',
        'prefix'            =>  '',
        'save_path'			=>	'',
    ),
);
