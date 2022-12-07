#!/bin/bash
set -ex
HOST=$1
ROOTUSER=$2
ROOTPASS=$3
DB=$4
USER=$5
PASS=$6

echo $0

if [[ ! -x "$(which mysqladmin)" ]]; then
	echo "Could not find mysqladmin";
	./install-maraidb.sh
fi

DBEXISTS=$(echo 'show databases' | mysql -u$ROOTUSER -p$ROOTPASS | grep $DB)
if [[ "$DBEXISTS"=="$DB" ]]; then
	echo "datbase exists"
else 
	mysqladmin create $DB -u$ROOTUSER -p$ROOTPASS -h$HOST
fi;
echo "grant all privileges on $DB.* to $USER@localhost identified by '$PASS'; flush privileges" | mysql -uROOTUSER -p$ROOTPASS

# create-database script is run from our parent directory, so src/sql is not off this directory, but
# off our parent directory

# pushd src/sql
# for s in $(ls betterquiz* | awk '{ if (/^betterquiz([0-9])+.sql/) { print $1; } }'); do 
# 	cat $s | mysql $DB -u$USER -p$PASS -h$HOST
# done

