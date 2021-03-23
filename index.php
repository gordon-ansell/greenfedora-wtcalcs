<?php

/**
 * This kicks off the whole application.
 * 
 * @see       https://github.com/gordon-ansell/greenfedora-wtcalcs for the canonical source repository.
 * @copyright https://github.com/gordon-ansell/greenfedora-wtcalcs/blob/master/COPYRIGHT.md
 * @license   https://github.com/gordon-ansell/greenfedora-wtcalcs/blob/master/LICENSE.md New BSD License.
 */

require __DIR__ . "/vendor/autoload.php";

use WRCalcs\Application\WTCalcsApplication;
use GreenFedora\Application\Input\ArrayApplicationInput;
use GreenFedora\Application\Output\ReturnCodeApplicationOutput;

// This is assumed to be the base path.
define('APP_PATH', dirname(__FILE__));

// Kick off the application
$output = new ReturnCodeApplicationOutput();

$app = new SbsApplication('dev');
$app->main(new ArrayApplicationInput, $output);

// Returns an integer code. 0 is success, anything else is a failure.
return $output->getOutput();
