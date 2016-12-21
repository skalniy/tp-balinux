#! /usr/bin/awk -f

{ 
	split($0, arr, ": ")

	split(arr[1], arr1, " ")
	src = arr1[2] 
	dest = arr1[4]

	split(arr[2], arr2, " ")
	proto = arr2[1]

	if ( substr(proto, length(proto)) == "," ) 
		proto = substr(proto, 0, length(proto)-1)
	if (proto != "") {
		if ($NF ~ /[0-9]+/) {
			len[proto] += $NF
			if (dest in bsrc)
                bsrc[dest][src][proto] += $NF
            else
                bsrc[src][dest][proto] += $NF
		}
		if (dest in psrc)
            psrc[dest][src][proto]++
        else
            psrc[src][dest][proto]++
	}
}

END {
	for (proto in len) {
		sum += len[proto]
		r[len[proto]] = proto
	}

	OFS="\t"
	for (src in psrc)
		for (dest in psrc[src])
			for (proto in psrc[src][dest])
				print src, dest, proto, psrc[src][dest][proto] > "/var/log/balinux/pps.log"
	for (src in bsrc)
		for (dest in bsrc[src])
			for (proto in bsrc[src][dest])
				print src, dest, proto, bsrc[src][dest][proto] > "/var/log/balinux/bps.log"
	unset OFS

	asort(len, slen)
	for (l = length(slen); l > 0; l--)
		printf("%s\t%d\t%.2f\n", r[slen[l]], len[r[slen[l]]], (100*len[r[slen[l]]]/sum)) > "/var/log/balinux/proto.log"
}
