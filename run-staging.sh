#!/bin/bash

php composer.phar update
export APPLICATION_ENV='staging' && php -S 87.76.31.251:8080 -t . router.php