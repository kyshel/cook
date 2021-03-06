#!/bin/bash

# Absolute path to this script, e.g. /home/user/bin/foo.sh
SCRIPT=$(readlink -f "$0")
# Absolute path this script is in, thus /home/user/bin
SCRIPTPATH=$(dirname "$SCRIPT")
# echo $SCRIPTPATH
echo '--- Step 1, mkdir imgs/'
mkdir -p imgs
chown apache:apache imgs/

echo '--- Step 2, mkdir cmake/ in ignite/'
cd ./ignite/
mkdir -p cmake
chmod 777 cmake

echo '--- Step 3, cmake'
cd cmake
cmake ../cpp/

echo '--- Step 4, make'
make
cd $SCRIPTPATH
echo 'Compile finish, if no error occurs, test in broswer!'
