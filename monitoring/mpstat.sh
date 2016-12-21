#! /bin/bash

mpstat 1 60 | tail -n 1 | awk '{ print $3+$4, $5, $12, $6 > "/var/log/balinux/mpstat" }'
