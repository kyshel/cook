# Cook
Cook images by various tricks.

## Require
- Cmake >= 2.8
- OpenCV 3.2 manual compiled
- PHP >= 5.4

## Install
Deploy from a minimal CentOS7 installation 
1. [Install OpenCV 3.2](http://kyshel.me/2017/04/27/install-opencv3.2-on-centos7/)
1. `yum install httpd php`
1. `chkconfig httpd on && service httpd start`
1. `firewall-cmd --permanent --zone=public --add-service=http && firewall-cmd --reload`
1. `cd /var/www/html && git clone https://github.com/kyshel/cook.git`
1. `cd cook`
1. `./install.sh`
1. Input `your.server.address/cook` in browser, enjoy ~

## Addtionals
Core Principle Inspired By
- Github API (REST)
- Django (url match)


## Credit
Made with ❤ by [kyshel](http://github.com/kyshel)  