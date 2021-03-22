<?php

/**
 * This kicks off the whole application.
 * 
 * @see       https://github.com/gordon-ansell/greenfedora-wtcalcs for the canonical source repository.
 * @copyright https://github.com/gordon-ansell/greenfedora-wtcalcs/blob/master/COPYRIGHT.md
 * @license   https://github.com/gordon-ansell/greenfedora-wtcalcs/blob/master/LICENSE.md New BSD License.
 */

require __DIR__ . "/vendor/autoload.php";

use WTCalcs\WTCalcsApplication;

$app = new WTCalcsApplication('dev');
$app->dispatch();