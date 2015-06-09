#!/bin/bash
set -e
if [ ! `dpkg -s php5-mcrypt `]; then
sudo apt-get update
sudo apt-get install -y software-properties-common
sudo apt-key adv --recv-keys --keyserver hkp://keyserver.ubuntu.com:80 0xcbcb082a1bb943db
sudo add-apt-repository 'deb http://ftp.wa.co.za/pub/mariadb/repo/10.0/ubuntu trusty main'
sudo apt-get update
sudo apt-get install -y mariadb-client-10.0 \
	 vim telnet curl mcrypt \
	 apache2 libapache2-mod-php5 \
	 php5-mysqlnd php5-apcu php5-curl php5-mcrypt
php5enmod mcrypt
sudo a2enmod rewrite
fi

cat <<EOF | sudo tee /etc/apache2/sites-enabled/000-default.conf
<VirtualHost *:80>
        # The ServerName directive sets the request scheme, hostname and port that
        # the server uses to identify itself. This is used when creating
        # redirection URLs. In the context of virtual hosts, the ServerName
        # specifies what hostname must appear in the request's Host: header to
        # match this virtual host. For the default virtual host (this file) this
        # value is not decisive as it is used as a last resort host regardless.
        # However, you must set it for any further virtual host explicitly.
        #ServerName www.example.com

        ServerAdmin webmaster@localhost
        DocumentRoot /opt/betterquiz/public

        # Available loglevels: trace8, ..., trace1, debug, info, notice, warn,
        # error, crit, alert, emerg.
        # It is also possible to configure the loglevel for particular
        # modules, e.g.
        #LogLevel info ssl:warn

        ErrorLog \${APACHE_LOG_DIR}/error.log
        CustomLog \${APACHE_LOG_DIR}/access.log combined

        # For most configuration files from conf-available/, which are
        # enabled or disabled at a global level, it is possible to
        # include a line for only one particular virtual host. For example the
        # following line enables the CGI configuration for this host only
        # after it has been globally disabled with "a2disconf".
        #Include conf-available/serve-cgi-bin.conf
</VirtualHost>

<Directory /opt/betterquiz/public>
        Options Indexes MultiViews FollowSymLinks ExecCGI
        AllowOverride All

        Require all granted	
</Directory>


# vim: syntax=apache ts=4 sw=4 sts=4 sr noet

EOF

sudo service apache2 restart