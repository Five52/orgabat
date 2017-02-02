#!/bin/bash

php bin/console doctrine:database:drop --force
php bin/console doctrine:database:create
php bin/console doctrine:schema:update --force
yes y | php bin/console doctrine:fixtures:load
