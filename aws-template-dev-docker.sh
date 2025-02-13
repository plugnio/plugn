#!/bin/bash
# Update the package repository and install required packages
sudo apt-get update -y
sudo apt-get upgrade -y
sudo apt-get install -y docker.io unzip curl gh docker-compose

# Start and enable Docker
sudo systemctl start docker
sudo systemctl enable docker

# Add the 'ubuntu' user to the Docker group
sudo usermod -aG docker ubuntu

# gir repo setup 1st way
apt install -y openssh-clients
ps -auxc | grep ssh-agent
eval $(ssh-agent)
sudo mkdir -p /home/ubuntu/plugn
sudo chmod 2775 /var/www
cd /home/ubuntu/plugn

echo "github private key" > ~/.ssh/github
chmod go-rw ~/.ssh/github
echo "github public key" > ~/.ssh/github.pub
#or ssh-keygen -y -f ~/.ssh/github > ~/.ssh/github.pub
#sudo chmod a+r ~/.ssh/github
ssh-add ~/.ssh/github
ssh-keyscan github.com >> ~/.ssh/known_hosts
apt install -y git
git clone git@github.com:plugnio/plugn.git /home/ubuntu/plugn
#cd ./plugn
cd /home/ubuntu/plugn
git remote add git@github.com:plugnio/plugn.git
git checkout develop
git config --global --add safe.directory /home/ubuntu/plugn

find /var/www -type d -exec chmod 2775 {} \;
find /var/www -type f -exec chmod 0664 {} \;

docker-compose -f docker-compose-dev.yml -p plugn-dev-server up -d
