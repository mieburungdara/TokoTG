sudo apt update
sudo apt install -y software-properties-common
sudo add-apt-repository ppa:ondrej/php -y
sudo apt update
sudo apt install -y php8.2 php8.2-cli php8.2-common

# atur default
sudo update-alternatives --install /usr/bin/php php /usr/bin/php8.2 82
sudo update-alternatives --set php /usr/bin/php8.2
php -v
sudo apt-get install php8.2-curl php8.2-mbstring php8.2-xml php8.2-mysql
