#! /bin/bash

awk -f "/usr/local/sbin/sockl.awk" /proc/net/tcp > /var/log/balinux/sockl
awk -f "/usr/local/sbin/sockl.awk" /proc/net/udp >> /var/log/balinux/sockl