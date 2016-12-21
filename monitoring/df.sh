#! /bin/bash

IFS=`echo -en "\n\b"`
lines=(`df --output='source','pcent','ipcent','target' | sed -e '1d'`)
unset IFS

for i in `seq 0 $[${#lines[*]}-1]`
do
	fields=(${lines[$i]})
	if ! [[ ${fields[3]} == /dev* ]] && ! [[ ${fields[3]} == /sys* ]] && ! [[ ${fields[3]} == /proc* ]]
	then
		fs=${fields[1]}
		fs=$[100-${fs:0:-1}]
		fin=${fields[2]}
		fin=$[100-${fs:0:-1}]
		echo ${fields[0]} $fs $fin ${fields[3]}
	fi
done | awk '{ print > "/var/log/balinux/df"}'
