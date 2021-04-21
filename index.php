<?php

/**
 * This kicks off the whole application.
 * 
 * @see       https://github.com/gordon-ansell/greenfedora-wtcalcs for the canonical source repository.
 * @copyright https://github.com/gordon-ansell/greenfedora-wtcalcs/blob/master/COPYRIGHT.md
 * @license   https://github.com/gordon-ansell/greenfedora-wtcalcs/blob/master/LICENSE.md New BSD License.
 */

$env = isset($_ENV['APPLICATION_ENV']) ? $_ENV['APPLICATION_ENV'] : 'prod';

if ('prod' != $env) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}

require __DIR__ . "/vendor/autoload.php";

use WTCalcs\Application\WTCalcsApplication;
use GreenFedora\Http\Request;
use GreenFedora\Http\Response;

// This is assumed to be the base path.
define('APP_PATH', dirname(__FILE__));
define('APP_VERSION', '1.0.0.dev1');

// Output response.
$output = new Response();

// Kick off the application
$app = new WTCalcsApplication($env, new Request, $output);
$app->bootstrap()->main();

// Display output.
$output->send();
