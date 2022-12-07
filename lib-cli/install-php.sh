#!/usr/bin/env bash
set -x
if [ "$EUID" -ne 0 ]
  then echo "Please run as root"
  exit 1
fi
apt install -y software-properties-common
add-apt-repository ppa:ondrej/php
apt update
apt install -y php8.0 php8.0-fpm php8.0-sqlite3 php8.0-cli php8.0-curl php8.0-intl php8.0-mbstring php8.0-mcrypt php8.0-mysql php8.0-xml php8.0-yaml
