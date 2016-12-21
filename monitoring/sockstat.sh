#!/bin/bash

ss -t -a | sed -e '1d' | awk '{ print $1 }' | sort | uniq -c | awk '{ print $2, $1 }' 1>/var/log/balinux/sockstat
