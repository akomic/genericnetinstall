<?php
Header('Content-type: text/plain');
echo "#!ipxe\n";
$IP =  $_SERVER['SERVER_ADDR'];
$S  = 'http://' . $IP . ':' . $_SERVER['SERVER_PORT'];
$D  = ucfirst(basename(substr(__FILE__,0,-4)));
echo "set serverpath $S\n";
echo "set serverip $IP\n";
echo "set distrotype $D\n";
?>
# Ensure we have menu-default set to something
isset ${menu-default} || set menu-default back

set menu-timeout 5000

:startmenu
menu Install selection for ${distrotype} based distros

item --gap --                    ------------- Return to main menu ------------
item --key b  back               Back to main menu
item --gap --                    ----------------------------------------------
item          ubuntu1204server   Install Ubuntu Server 12.04
item          ubuntu1204         Install Ubuntu 12.04
item          debian605          Install Debian 6.0.5 Network
item          debian606          Install Debian 6.0.6 Network
item          proxmox22          Install ProxMox Virtualization Platform 2.2 
item --gap --                    -------------------- END ----------------------

choose --timeout ${menu-timeout} --default ${menu-default} selected || goto back
set menu-timeout 0
goto ${selected}

:back
imgfree
chain ${serverpath}/boot/boot.php

:genericubuntu
kernel  ${base-url}/boot/${arch}/loader/linux install=${base-url}
initrd  ${base-url}/boot/${arch}/loader/initrd
boot

:genericdebian
kernel  ${base-url}/install${arch}/vmlinuz video=vesa:ywrap,mtrr vga=788 initrd=/install${arch}/gtk/initrd.gz -- quiet auto=yes
initrd  ${base-url}/install${arch}/initrd.gz
boot

:genericproxmox
kernel  ${base-url}/boot/isolinux/linux26 video=vesa:ywrap,mtrr vga=788 initrd=/boot/isolinux/initrd.img -- quiet auto=yes
initrd  ${base-url}/boot/isolinux/initrd.img
boot

:debian605
set arch .amd
set base-url ${serverpath}/DEB-6.0.5-amd64
goto genericdebian

:debian606
set arch .amd
set base-url ${serverpath}/DEB-6.0.6-amd64
goto genericdebian

:proxmox22
set base-url ${serverpath}/proxmox-2.2-x64
goto genericproxmox

