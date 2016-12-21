# !/bin/bash

apt-get install sysstat -y

useradd balinux
cp monitoring/* /usr/local/sbin/
mkdir -p /var/log/balinux
sed -e '1,2d' /proc/net/dev 1>/var/log/balinux/netshot
chown -R balinux:balinux /var/log/balinux
cp balinux /etc/cron.d/

apt-get install nginx -y
cp servers_config/default /etc/nginx/sites-available/
nginx -s reload

apt-get install apache2 php7.0 libapache2-mod-php7.0 -y
cp servers_config/ports.conf /etc/apache2/
cp servers_config/000-default.conf /etc/apache2/sites-available/
apachectl restart

rm -rf /var/www/html/*
mkdir -p /var/www/html/sysinfo
cp servers_config/index.php /var/www/html/sysinfo/
