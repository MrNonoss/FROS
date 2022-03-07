#!/bin/bash

##################################
# FROS Installer Script ##########
# Author: bruno.bordas[@}gmx.com #
##################################
clear

# Vérification des prérequis #
echo -e "\e[32m
# Vérification des prérequis\e[0m"
if [[ `whoami` != root ]]; then
    echo -e "\e[31mErreur: Le script doit être lancé en sudoer\e[0m"
    exit
fi

echo "Ce script va:
1 - Installer le serveur web Caddy, avec PHP
2 - Installer l'utilitaire ttyd
3 - Créer un répertoire utilisateur pour www-data
4 - Créer un répertoire pour le site web de FROS
5 - Installer les outils de recherches OSINT
6 - Paramétrer le site web
7 - Il vous restera a paramétrer GHunt (https://github.com/mxrch/GHunt#where-i-find-these-5-cookies-)"
echo ""
read -p "Appuyez sur Entrée pour continuer"

## Update OS
apt update && apt full-upgrade -y
## Add Repositories
apt install curl debian-keyring debian-archive-keyring apt-transport-https -y
curl -1sLf 'https://dl.cloudsmith.io/public/caddy/stable/gpg.key' | tee /etc/apt/trusted.gpg.d/caddy-stable.asc
curl -1sLf 'https://dl.cloudsmith.io/public/caddy/stable/debian.deb.txt' | tee /etc/apt/sources.list.d/caddy-stable.list
add-apt-repository ppa:ondrej/php -y
apt update && apt fullsudo -upgrade -y
## Install PHP
apt install php-cli php-fpm php-mbstring -y
## Install Caddy
apt install caddy -y
## Install dependencies
apt install composer colorized-logs git python3-pip chromium-chromedriver -y
## Grab TTYD
curl -sL https://github.com/tsl0922/ttyd/releases/download/1.6.3/ttyd.x86_64 -o /usr/local/bin/ttyd
chmod +x /usr/local/bin/ttyd
## Set default home for www-data
systemctl stop caddy.service
systemctl stop php8.1-fpm.service
mkdir /home/www-data
usermod -d /home/www-data www-data
chown -R www-data:www-data /home/www-data/
systemctl start caddy.service
systemctl start php8.1-fpm.service
## Create website directory
mkdir /var/www
mkdir /var/www/html
chown -R www-data:www-data /var/www/html
## Install Holehe
sudo -u www-data pip3 install holehe
## Install Ignorant
sudo -u www-data pip3 install ignorant
## Install Maigret
sudo -u www-data pip3 install maigret
## Install PhoneInfoga
cd /tmp && sudo -u www-data curl -sSL https://raw.githubusercontent.com/sundowndev/phoneinfoga/master/support/scripts/install | bash
mv ./phoneinfoga /usr/bin/phoneinfoga
## Install Nexfil
cd /home/www-data && sudo -u www-data git clone https://github.com/thewhiteh4t/nexfil.git && cd nexfil
sudo -u www-data pip3 install -r requirements.txt
## Install GHunt
cd /home/www-data && sudo -u www-data git clone https://github.com/mxrch/GHunt.git && cd GHunt
sudo -u www-data python3 -m pip install -r requirements.txt
## Get Website
sudo -u www-data git clone https://github.com/MrNonoss/FROS.git /var/www/html/
mv /var/www/html/Caddyfile /etc/caddy/
chmod 644 /etc/caddy/Caddyfile
systemctl restart caddy.service
## Done
echo -e "\e[32mInstallation réussie\e[0m"
echo "Paramétrons GHunt"
sudo -u www-data python3 /home/www-data/GHunt/check_and_gen.py