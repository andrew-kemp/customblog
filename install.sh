#!/bin/bash

echo "=== CustomBlog Installer ==="

# Prompt for site name
read -p "Enter site name: " SITENAME
DBNAME="db_$(echo $SITENAME | tr '[:upper:]' '[:lower:]' | tr ' ' '_')"
DBUSER="user_$(echo $SITENAME | tr '[:upper:]' '[:lower:]' | tr ' ' '_')"
DBPASS=$(openssl rand -base64 16)

# Update & install required packages
sudo apt update
sudo apt install -y apache2 mysql-server php libapache2-mod-php php-mysql php-xml php-mbstring php-curl php-zip php-gd git curl unzip certbot python3-certbot-apache postfix mailutils

# Create MySQL DB and user
sudo mysql -e "CREATE DATABASE IF NOT EXISTS \`$DBNAME\`;"
sudo mysql -e "CREATE USER IF NOT EXISTS '$DBUSER'@'localhost' IDENTIFIED BY '$DBPASS';"
sudo mysql -e "GRANT ALL PRIVILEGES ON \`$DBNAME\`.* TO '$DBUSER'@'localhost';"
sudo mysql -e "FLUSH PRIVILEGES;"

# Save DB config
cat > dbconfig.php <<EOF
<?php
define('DB_NAME', '$DBNAME');
define('DB_USER', '$DBUSER');
define('DB_PASS', '$DBPASS');
define('DB_HOST', 'localhost');
EOF

# Set up directory structure
mkdir -p public/assets inc

# Copy dbconfig.php into inc/
cp dbconfig.php inc/dbconfig.php

# Prompt for admin username/email/password
echo "Let's set up your admin user."
read -p "Admin username: " ADMINUSER
read -p "Admin email: " ADMINEMAIL
read -sp "Admin password: " ADMINPASS
echo

# Set up the database schema (simple users, posts, categories, tags, pages)
mysql -u$DBUSER -p$DBPASS $DBNAME < schema.sql

# Insert admin user with create_admin.php
php inc/create_admin.php "$ADMINUSER" "$ADMINEMAIL" "$ADMINPASS"

# Banner upload
read -p "Upload a banner image? (y/N): " UPBANNER
if [[ "$UPBANNER" =~ ^[Yy]$ ]]; then
    read -p "Path to image: " BANNERPATH
    cp "$BANNERPATH" public/assets/banner.jpg
else
    cp public/assets/banner-default.jpg public/assets/banner.jpg
fi

# SSL setup
read -p "Enter your domain (for Let's Encrypt SSL, e.g. blog.example.com): " DOMAIN
sudo certbot --apache -d "$DOMAIN"
sudo systemctl reload apache2

echo "0 3 * * * certbot renew --quiet" | sudo tee -a /etc/crontab

echo "Installation complete! Visit your site to finish MFA setup."
