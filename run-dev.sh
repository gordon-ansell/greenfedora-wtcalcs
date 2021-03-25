#!/bin/bash

composer update
php -S 0.0.0.0:8080 -t . router.php