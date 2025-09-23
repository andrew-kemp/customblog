#!/bin/bash

set -e

echo "=== CustomBlog Automated Installer ==="

# --- User Prompts with Validation ---
while [[ -z "$DOMAIN" ]]; do
  read -p "Enter your domain name (e.g., www.andykemp.cloud): " DOMAIN
done
while [[ -z "$SITENAME" ]]; do
  read -p "Enter your site name (for display, e.g., Andy Kemp): " SITENAME
done
while [[ -z "$SSLEMAIL" ]]; do
  read -p "Enter your email address for SSL notifications: " SSLEMAIL
done

INSTALL_DIR="/var/www/$DOMAIN"

# Generate DB and user names based on the domain (replace . and - with _)
DOMAIN_DB=$(echo "$DOMAIN" | tr '.' '_' | tr '-' '_')
DBNAME="dm_${DOMAIN_DB}"
DBUSER="dm_${DOMAIN_DB}"

# --- Prompt for DB password, generate if blank ---
read -p "Enter MySQL DB user password (leave blank for random): " DBPASS
if [[ -z "$DBPASS" ]]; then
  DBPASS=$(openssl rand -base64 16)
  echo "Generated MySQL DB user password: $DBPASS"
fi

# Mail domain for Postfix (strip subdomain)
MAILDOMAIN="${DOMAIN#*.}"

# --- Remove existing install if present ---
if [ -d "$INSTALL_DIR" ]; then
  echo "--- Removing existing site files at $INSTALL_DIR ---"
  sudo rm -rf "$INSTALL_DIR"
fi

# Drop existing DB and user if they exist
echo "--- Dropping old DB and user if they exist ---"
sudo mysql -e "DROP DATABASE IF EXISTS \`$DBNAME\`;"
sudo mysql -e "DROP USER IF EXISTS '$DBUSER'@'localhost';"
sudo mysql -e "FLUSH PRIVILEGES;"

# --- Preseed Postfix install ---
echo "--- Configuring Postfix ---"
echo "postfix postfix/main_mailer_type string 'Internet Site'" | sudo debconf-set-selections
echo "postfix postfix/mailname string $MAILDOMAIN" | sudo debconf-set-selections

# --- Install dependencies ---
echo "--- Installing dependencies ---"
sudo apt update
sudo DEBIAN_FRONTEND=noninteractive apt install -y apache2 mysql-server php libapache2-mod-php php-mysql php-xml php-mbstring php-curl php-zip php-gd git curl unzip certbot python3-certbot-apache postfix mailutils

# --- Enable required Apache modules ---
sudo a2enmod ssl
sudo a2enmod rewrite

# --- Clone repo into web root ---
echo "--- Cloning repo ---"
sudo git clone https://github.com/andrew-kemp/customblog.git "$INSTALL_DIR"
sudo chown -R www-data:www-data "$INSTALL_DIR"

# --- Ensure assets directory exists ---
if [ ! -d "$INSTALL_DIR/assets" ]; then
  sudo mkdir -p "$INSTALL_DIR/assets"
  sudo chown -R www-data:www-data "$INSTALL_DIR/assets"
fi

# --- Set Default Banner if None Exists ---
if [ ! -f "$INSTALL_DIR/assets/banner.jpg" ]; then
  if [ -f "$INSTALL_DIR/assets/banner-default.jpg" ]; then
    cp "$INSTALL_DIR/assets/banner-default.jpg" "$INSTALL_DIR/assets/banner.jpg"
  else
    echo "Warning: Default banner image not found. Please add assets/banner.jpg manually."
  fi
fi

# --- Create MySQL database and user ---
echo "--- Creating DB and DB user ---"
sudo mysql -e "CREATE DATABASE \`$DBNAME\`;"
sudo mysql -e "CREATE USER '$DBUSER'@'localhost' IDENTIFIED BY '$DBPASS';"
sudo mysql -e "GRANT ALL PRIVILEGES ON \`$DBNAME\`.* TO '$DBUSER'@'localhost';"
sudo mysql -e "FLUSH PRIVILEGES;"

# --- Write DB config ---
cat > "$INSTALL_DIR/dbconfig.php" <<EOF
<?php
define('DB_NAME', '$DBNAME');
define('DB_USER', '$DBUSER');
define('DB_PASS', '$DBPASS');
define('DB_HOST', 'localhost');
EOF

# --- Import DB schema ---
echo "--- Importing database schema ---"
mysql -u"$DBUSER" -p"$DBPASS" "$DBNAME" < "$INSTALL_DIR/schema.sql"

# --- Create Apache HTTP VirtualHost (no SSL yet) ---
echo "--- Setting up Apache HTTP VirtualHost ---"
VHOST_CONF="/etc/apache2/sites-available/$DOMAIN.conf"
sudo bash -c "cat > $VHOST_CONF" <<EOF
<VirtualHost *:80>
    ServerName $DOMAIN
    DocumentRoot $INSTALL_DIR

    <Directory $INSTALL_DIR>
        AllowOverride All
        DirectoryIndex index.php
        Require all granted
    </Directory>

    ErrorLog \${APACHE_LOG_DIR}/$DOMAIN-error.log
    CustomLog \${APACHE_LOG_DIR}/$DOMAIN-access.log combined
</VirtualHost>
EOF

sudo a2ensite "$DOMAIN.conf"
sudo a2dissite 000-default.conf || true

echo "--- Starting Apache for HTTP config ---"
sudo systemctl start apache2 || sudo systemctl restart apache2

# --- Obtain SSL Certificate with Certbot ---
echo "--- Obtaining Let's Encrypt certificate ---"
sudo certbot --apache -d "$DOMAIN" --non-interactive --agree-tos -m "$SSLEMAIL"

# --- Force certificate renewal check and permissions ---
sudo chown -R www-data:www-data /etc/letsencrypt

# --- Update Apache VirtualHost for SSL ---
echo "--- Updating Apache VirtualHost with SSL ---"
sudo bash -c "cat > $VHOST_CONF" <<EOF
<VirtualHost *:80>
    ServerName $DOMAIN
    Redirect permanent / https://$DOMAIN/
</VirtualHost>

<VirtualHost *:443>
    ServerName $DOMAIN
    DocumentRoot $INSTALL_DIR

    <Directory $INSTALL_DIR>
        AllowOverride All
        DirectoryIndex index.php
        Require all granted
    </Directory>

    ErrorLog \${APACHE_LOG_DIR}/$DOMAIN-error.log
    CustomLog \${APACHE_LOG_DIR}/$DOMAIN-access.log combined

    SSLEngine on
    SSLCertificateFile /etc/letsencrypt/live/$DOMAIN/fullchain.pem
    SSLCertificateKeyFile /etc/letsencrypt/live/$DOMAIN/privkey.pem
</VirtualHost>
EOF

sudo systemctl reload apache2

# --- Certbot auto-renewal ---
if ! crontab -l 2>/dev/null | grep -q 'certbot renew'; then
  (crontab -l 2>/dev/null; echo "0 3 * * * certbot renew --quiet") | crontab -
fi

# --- Permissions ---
sudo chown -R www-data:www-data "$INSTALL_DIR"

# --- Review of all key settings ---
echo ""
echo "===== INSTALLATION COMPLETE ====="
echo "  SITE SETTINGS"
echo "  -------------"
echo "  Site URL:           https://$DOMAIN/"
echo "  Site Name:          $SITENAME"
echo "  Site Root:          $INSTALL_DIR"
echo ""
echo "  DATABASE SETTINGS"
echo "  -----------------"
echo "  DB Name:           $DBNAME"
echo "  DB User:           $DBUSER"
echo "  DB Password:       $DBPASS"
echo ""
echo "  MAIL SETTINGS"
echo "  -------------"
echo "  Postfix Mailname:  $MAILDOMAIN"
echo ""
echo "  SSL SETTINGS"
echo "  ------------"
echo "  Certificate Path:  /etc/letsencrypt/live/$DOMAIN/"
echo "  SSL Email:         $SSLEMAIL"
echo ""
echo "When you visit your site for the first time, you will be guided to create the admin account via the web interface."
echo "================================="