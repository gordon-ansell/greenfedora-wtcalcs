#!/bin/bash

composer update
export APPLICATION_ENV='dev' && php -S 0.0.0.0:8080 -t . router.php