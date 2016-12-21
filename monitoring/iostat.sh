#!/bin/bash

iostat -xkd 60 2 | awk 'BEGIN{RS="\n\n"} NR>2{print}' | awk 'BEGIN{RS="\n"} NR>1{print $1,$4,$5,$6,$7,$10,$14 > "/var/log/balinux/iostat"}'
