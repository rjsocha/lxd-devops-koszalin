lxc init ubuntu-devops template
lxc file  push ttyd template/sbin/ttyd.inhouse
lxc image delete ubuntu-devops
lxc publish template --alias ubuntu-devops
