#!/bin/bash

# ./.docker/bin/apachelinker.sh /home/board/public

# chown www-data:$USER bootstrap/cache -R
# chown www-data storage -R

# if ! [ -d /tmp/dev.log ]; then
# 	touch /tmp/dev.log
# fi

php artisan serve
tail -f /tmp/dev.log
