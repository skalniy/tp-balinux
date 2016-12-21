#! /usr/bin/awk -f

{ print $1, $2, $3 > "/var/log/balinux/loadavg" }
