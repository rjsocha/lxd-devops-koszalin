lxc init ubuntu-devops template

lxc file push ttyd template/sbin/ttyd.inhouse

lxc file push ttyd.service template/etc/systemd/system/ttyd.service

lxc file push  ~/.acme.sh/lxd.nauka.ga/fullchain.cer template/etc/ssl/certs/lxd.nauka.ga.full.cer

lxc file push  ~/.acme.sh/lxd.nauka.ga/lxd.nauka.ga.key template/etc/ssl/private/lxd.nauka.ga.key


lxc image delete ubuntu-devops

lxc publish template --alias ubuntu-devops

#### Merge lws i ttyd
https://help.github.com/en/articles/about-git-subtree-merges


# Instlacja

Dirty way, very dirty. Hardcoded paths, hardcoded username (socha)

```
apt-get install -y build-essential make git cmake libssl-dev zlib1g-dev libjson-c-dev pkg-config curl socat php-fpm  php-json  php-mbstring  php-xml  php-intl php-cli
mkdir /var/www
cd /var/www
git clone git@github.com:rjsocha/lxd-devops-koszalin.git .
sudo ln -s /var/www/system/systemd/ttyd@.service /etc/systemd/system/
sudo systemctl daemon-reload
systemctl list-unit-files | grep ttyd
sudo install -m 0444 /var/www/system/sudo/terminal /etc/sudoers.d/terminal
cd /var/www/deps/libwebsockets
mkdir build
cd build
cmake -D LWS_WITH_HTTP2=OFF -D LWS_IPV6=ON -D LWS_WITH_STATIC=ON -D LWS_WITH_SHARED=OFF ..
make -j 4
sudo make install
# bo wymaga websockets_shared przy static build-dzie
sudo  sed -i -e 's/websockets_shared//g' /usr/local/lib/cmake/libwebsockets/LibwebsocketsConfig.cmake
cd /var/www/deps/ttyd
mkdir build
cd build
cmake ..
make
sudo make install
mkdir -p /home/socha/.acme-webroot
cd 
curl -s https://get.acme.sh | sh
sudo install -m 644 /var/www/system/nginx/default /etc/nginx/sites-available/
sudo nginx -t && sudo service nginx reload
~/.acme.sh/acme.sh --issue -d linux.nauka.ga -w ~/.acme-webroot/
sudo install -m 644 /var/www/system/nginx/default-ssl /etc/nginx/sites-available/
sudo ln -s /etc/nginx/sites-available/default-ssl /etc/nginx/sites-enabled
sudo apt install -y haveged
sudo openssl dhparam -out /etc/nginx/dhparam.pem 2048
sudo nginx -t && sudo service nginx reload
sudo install /dev/null /etc/php/7.2/fpm/pool.d/www.conf
sudo install -m 0644 /var/www/system/php-fpm/socha.conf /etc/php/7.2/fpm/pool.d/socha.conf
sudo install -o socha -g socha -m 755 -d /var/log/php
sudo service php7.2-fpm restart




```


