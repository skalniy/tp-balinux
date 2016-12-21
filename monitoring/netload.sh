#!/bin/bash

{
	IFS=`echo -en "\n\b"`
	arr1=(`cat /var/log/balinux/netshot`)
	arr2=(`sed -e '1,2d' /proc/net/dev | tee /var/log/balinux/netshot`)
	unset IFS

	for i in `seq 0 $[${#arr1[*]}-1]`
	do
		arr3=(${arr1[$i]})
		arr4=(${arr2[$i]})
		str="${arr3[0]}"
		for j in `seq 1 $[${#arr3[*]}-1]`
		do
			rez=$[${arr4[$j]}-${arr3[$j]}]
			str+=" $rez"
		done
		echo $str
	done
} | awk '{ print > "/var/log/balinux/netload" }'