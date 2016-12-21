#! /usr/bin/awk -f

BEGIN { c = 0 }
NR > 1 && $4 == "0A" { c++ } 
END { print c }