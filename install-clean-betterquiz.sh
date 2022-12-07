#!/usr/bin/env bash
set -x
cd lib-cli; ./install-mariadb.sh; ./install-php.sh; cd ..
./betterquiz.php 