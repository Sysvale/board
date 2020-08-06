#!/bin/bash

selectphp 7.3
apachelinker /home/server/public

tail -f /tmp/dev.log
