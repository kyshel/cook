#!/bin/bash

# Absolute path to this script, e.g. /home/user/bin/foo.sh
SCRIPT=$(readlink -f "$0")
# Absolute path this script is in, thus /home/user/bin
SCRIPTPATH=$(dirname "$SCRIPT")
# echo $SCRIPTPATH

cd ./ignite/
mkdir -p cmake
chmod 777 cmake
cd cmake
cmake ../cpp/
make
cd $SCRIPTPATH
echo 'Cook compile finish, U can test in browser now!'
