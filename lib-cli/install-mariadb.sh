#!/usr/bin/env bash
set -xe
if [ "$EUID" -ne 0 ]; then
  echo "Please run as root"
  exit 1
fi
ROOTPASS=$1
if [[ "" == "$ROOTPASS" ]]; then
	# generate a 12 char random password
	ROOTPASS=$(< /dev/urandom tr -dc _A-Z-a-z-0-9 | head -c${1:-12};echo;);
fi
apt-get install -y apt-transport-https curl
curl -o /etc/apt/trusted.gpg.d/mariadb_release_signing_key.asc 'https://mariadb.org/mariadb_release_signing_key.asc'
sh -c "echo 'deb https://mariadb.mirror.liquidtelecom.com/repo/10.6/ubuntu focal main' >/etc/apt/sources.list.d/mariadb.list"

export DEBIAN_FRONTEND=noninteractive
debconf-set-selections <<< "mariadb-server-10.6 mysql-server/root_password password $ROOTPASS"
debconf-set-selections <<< "mariadb-server-10.6 mysql-server/root_password_again password $ROOTPASS"
apt update
apt-get install -y mariadb-server-10.6 mariadb-client-10.6
#mysql -uroot -p$PASS -e "SET PASSWORD = PASSWORD('');"

cat <<EOFILE | tee ~/.my.cnf
[client]
user=root
password=$ROOTPASS
EOFILE
