# Cook
Cook images by various tricks.

## Require
- OpenCV 3.2 compiled

## Install
Deploy from a minimal CentOS7 installation 
1. `yum install httpd php`
1. `chkconfig httpd on && service httpd start`
1. `firewall-cmd --permanent --zone=public --add-service=http && firewall-cmd --reload`
1. `cd /var/www/html && git clone https://github.com/kyshel/cook.git`
1. `cd cook && chown apache:apache fridge/ plate/ `
1. `./install.sh`

## Credit
Made with ❤ by [kyshel](http://github.com/kyshel)  