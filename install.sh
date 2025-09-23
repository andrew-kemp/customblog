#!/bin/bash
set -e

echo "=== Minimal PHP CMS Auto Installer ==="

# --- Ask user for config ---
read -p "Domain name (e.g. example.com): " DOMAIN
read -p "Site name (e.g. Andy's Tech Blog): " SITENAME
read -p "MySQL root password: " MYSQL_ROOT_PASS
read -p "New DB name (e.g. blogdb): " DBNAME
read -p "New DB user: " DBUSER
read -p "New DB user password: " DBPASS
read -p "Admin username: " ADMINUSER
read -p "Admin email: " ADMINEMAIL
read -p "Admin password (leave blank to auto-generate): " ADMINPASS

if [[ -z "$DOMAIN" || -z "$SITENAME" || -z "$MYSQL_ROOT_PASS" || -z "$DBNAME" || -z "$DBUSER" || -z "$DBPASS" || -z "$ADMINUSER" || -z "$ADMINEMAIL" ]]; then
  echo "All fields required!"
  exit 1
fi

if [[ -z "$ADMINPASS" ]]; then
  ADMINPASS=$(openssl rand -base64 12)
  echo "Admin password generated: $ADMINPASS"
fi

INSTALL_ROOT="/var/www/$DOMAIN"
PUBLIC_ROOT="$INSTALL_ROOT/public"
ASSETS_DIR="$PUBLIC_ROOT/assets"
INC_DIR="$INSTALL_ROOT/inc"

# --- Install dependencies ---
sudo apt update
sudo apt install -y apache2 mysql-server php libapache2-mod-php php-mysql php-xml php-mbstring php-curl php-zip php-gd git unzip

# --- Setup directories and copy files ---
sudo mkdir -p "$PUBLIC_ROOT/assets" "$INC_DIR"
sudo cp schema.sql "$INSTALL_ROOT/"
sudo cp -r public/* "$PUBLIC_ROOT/"
sudo cp -r inc/* "$INC_DIR/"
sudo cp install.sh "$INSTALL_ROOT/"

# --- Place a placeholder banner if not present ---
if [ ! -f "$ASSETS_DIR/banner.jpg" ]; then
  wget -qO "$ASSETS_DIR/banner.jpg" "https://via.placeholder.com/1200x200.png?text=$(echo $SITENAME | sed 's/ /+/g')"
fi
sudo chown -R www-data:www-data "$INSTALL_ROOT"

# --- Setup database ---
echo "--- Creating DB and user ---"
sudo mysql -uroot -p"$MYSQL_ROOT_PASS" -e "CREATE DATABASE IF NOT EXISTS \`$DBNAME\`;"
sudo mysql -uroot -p"$MYSQL_ROOT_PASS" -e "CREATE USER IF NOT EXISTS '$DBUSER'@'localhost' IDENTIFIED BY '$DBPASS';"
sudo mysql -uroot -p"$MYSQL_ROOT_PASS" -e "GRANT ALL PRIVILEGES ON \`$DBNAME\`.* TO '$DBUSER'@'localhost';"
sudo mysql -uroot -p"$MYSQL_ROOT_PASS" -e "FLUSH PRIVILEGES;"
sudo mysql -u"$DBUSER" -p"$DBPASS" "$DBNAME" < "$INSTALL_ROOT/schema.sql"

# --- Setup config ---
cat > "$INC_DIR/dbconfig.php" <<EOPHP
<?php
define('DB_NAME', '$DBNAME');
define('DB_USER', '$DBUSER');
define('DB_PASS', '$DBPASS');
define('DB_HOST', 'localhost');
define('SITE_NAME', '$SITENAME');
EOPHP
sudo chown www-data:www-data "$INC_DIR/dbconfig.php"

# --- Create admin user ---
php "$INC_DIR/create_admin.php" "$ADMINUSER" "$ADMINEMAIL" "$ADMINPASS"

# --- Apache vhost ---
VHOST="/etc/apache2/sites-available/$DOMAIN.conf"
sudo bash -c "cat > $VHOST" <<EOF
<VirtualHost *:80>
    ServerName $DOMAIN
    DocumentRoot $PUBLIC_ROOT

    <Directory $PUBLIC_ROOT>
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
EOF

sudo a2ensite "$DOMAIN.conf"
sudo a2dissite 000-default.conf || true
sudo systemctl reload apache2

echo
echo "========= INSTALL COMPLETE ========"
echo "Site:  http://$DOMAIN/"
echo "Admin login: $ADMINUSER"
echo "Admin pass: $ADMINPASS"
echo "==================================="