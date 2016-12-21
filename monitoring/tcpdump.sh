#! /bin/bash

t=`date +%s`
/usr/sbin/tcpdump -Z balinux -G 60 -W 1 -w /var/log/balinux/dump$t.pcap
/usr/sbin/tcpdump -Z balinux -nnqt -r /var/log/balinux/dump$t.pcap 1>/var/log/balinux/dump$t.txt
mv /var/log/balinux/dump$t.txt /var/log/balinux/dump.log
/usr/local/sbin/tcpdump.awk /var/log/balinux/dump.log
rm /var/log/balinux/dump*
sort -nr -k4 /var/log/balinux/pps.log | awk '{print >"/var/log/balinux/pps.log"}'
sort -nr -k4 /var/log/balinux/bps.log | awk '{print >"/var/log/balinux/bps.log"}'
#chown balinux:balinux /var/log/balinux/*.log
