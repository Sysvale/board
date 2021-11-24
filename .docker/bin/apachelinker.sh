#!/bin/bash

PUBLIC_FOLDER=$1

ln -sfn $1 /var/www/dev
service apache2 start
