#!/bin/bash
set -e
if [[ "" == "$1" ]]; then
	echo "USAGE: database-up <databasename>";
	exit 1
fi
for s in $(ls betterquiz* | awk '{ if (/^betterquiz([0-9])+/) { print $1; } }'); do 
	cat $s | mysql $1
done
