<?php

/**
 * This kicks off the whole application.
 * 
 * @see       https://github.com/gordon-ansell/greenfedora-wtcalcs for the canonical source repository.
 * @copyright https://github.com/gordon-ansell/greenfedora-wtcalcs/blob/master/COPYRIGHT.md
 * @license   https://github.com/gordon-ansell/greenfedora-wtcalcs/blob/master/LICENSE.md New BSD License.
 */

require __DIR__ . "/vendor/autoload.php";

use WTCalcs\Application\WTCalcsApplication;
use GreenFedora\Application\Input\HttpApplicationInput;
use GreenFedora\Application\Output\HttpApplicationOutput;

// This is assumed to be the base path.
define('APP_PATH', dirname(__FILE__));
define('APP_VERSION', '1.0.0.dev1');

// Kick off the application
$output = new HttpApplicationOutput();

$app = new WTCalcsApplication(new HttpApplicationInput, $output, 'dev');

// Display output.
return $output->send();
