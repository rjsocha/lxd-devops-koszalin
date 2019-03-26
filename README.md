lxc init ubuntu-devops template

lxc file push ttyd template/sbin/ttyd.inhouse

lxc file push ttyd.service template/etc/systemd/system/ttyd.service

lxc file push  ~/.acme.sh/lxd.nauka.ga/fullchain.cer template/etc/ssl/certs/lxd.nauka.ga.full.cer

lxc file push  ~/.acme.sh/lxd.nauka.ga/lxd.nauka.ga.key template/etc/ssl/private/lxd.nauka.ga.key


lxc image delete ubuntu-devops

lxc publish template --alias ubuntu-devops

#### Merge lws i ttyd
https://help.github.com/en/articles/about-git-subtree-merges
