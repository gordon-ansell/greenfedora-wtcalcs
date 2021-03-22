<?php

/**
 * Router for PHP's in-built server.
 * 
 * @see       https://github.com/gordon-ansell/greenfedora-wtcalcs for the canonical source repository.
 * @copyright https://github.com/gordon-ansell/greenfedora-wtcalcs/blob/master/COPYRIGHT.md
 * @license   https://github.com/gordon-ansell/greenfedora-wtcalcs/blob/master/LICENSE.md New BSD License.
 */


$rawpath = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
$ext = pathinfo($rawpath, PATHINFO_EXTENSION);
if (empty($ext)) {
    $path = rtrim($rawpath, "/") . "/index.html";
    if (file_exists($_SERVER["DOCUMENT_ROOT"] . $path)) {
        return false;
    }
    $path = $rawpath . ".html";

    if (file_exists($_SERVER["DOCUMENT_ROOT"] . $path)) {
        include $_SERVER["DOCUMENT_ROOT"] . $path;
        return true;
    }
}
return false;