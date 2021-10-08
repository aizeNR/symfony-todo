#!/usr/bin/env bash

/var/www/symfony_docker/bin/console messenger:consume async >&1;