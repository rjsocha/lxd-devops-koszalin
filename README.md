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
mkdir /var/www
cd /var/www
git clone repo .
sudo ln -s /var/www/system/systemd/ttyd@.service /etc/systemd/system/
sudo systemctl daemon-reload
systemctl list-unit-files | grep ttyd
sudo install -m 0444 /var/www/system/sudo/terminal /etc/sudoers.d/terminal
cd /var/www/deps/libwebsockets
mkdir build
cd build
cmake -D LWS_WITH_HTTP2=OFF -D LWS_IPV6=ON -D LWS_WITH_STATIC=ON -D LWS_WITH_SHARED=OFF ..
make
sudo make install
# bo wymaga websockets_shared przy static build-dzie
sudo  sed -i -e 's/websockets_shared//g' /usr/local/lib/cmake/libwebsockets/LibwebsocketsConfig.cmake
cd /var/www/deps/ttyd
mkdir build
cd build
cmake ..
make
sudo make install

```


