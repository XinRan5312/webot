#!/bin/bash

# 参考资料：http://www.opinionatedprogrammer.com/2011/06/chef-solo-tutorial-managing-a-single-server-with-chef/
# 使用方法: ./deploy.sh [host]
host="${1:-root@SNDA-172-17-12-151}"

# 每次创建新的虚机时，主机上的私钥都是不同的，所以每次都创建新的密钥
ssh-keygen -R "${host#*@}" 2> /dev/null

# 把当前目录中的所有文件打包，并上传到云主机上，然后开始部署
tar cj . | ssh -o 'StrictHostKeyChecking no' "$host" '
sudo rm -rf ~/chef &&
mkdir ~/chef &&
cd ~/chef &&
tar xj &&
sudo bash install.sh'