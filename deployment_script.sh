#!/bin/bash
#
# Option-Rocket Deployment & Management Script for Debian 12 x64
#
# This script automates the setup of a production-ready environment for the
# Option-Rocket Laravel application. It can be run for a fresh install,
# or re-run to pull the latest updates and restart services.
#
# Usage:
#   - For initial installation: sudo ./deployment_script.sh
#   - To update and restart:   sudo ./deployment_script.sh
#   - To only restart services: sudo ./deployment_script.sh --restart
#

# --- Script Configuration ---
# !!! IMPORTANT: UPDATE THESE VARIABLES BEFORE RUNNING THE SCRIPT !!!

# 1. Application & Domain
GITHUB_REPO_URL="https://github.com/AlexCodeForge/cproject.git" # Your GitHub repository URL
PROJECT_DIR="/var/www/option-rocket"                                   # Directory to install the app
SERVER_DOMAIN="45.76.11.18"                                        # Your server's domain name or IP address

# 2. Database
DB_NAME="option_rocket_db"
DB_USER="option_rocket_user"
DB_PASSWORD="your_strong_and_secret_password" # <-- CHANGE THIS to a secure password

# --- Helper Functions ---
print_info() {
    echo -e "\n\e[34m\e[1m[INFO]\e[0m $1\e[0m"
}

print_success() {
    echo -e "\e[32m\e[1m[SUCCESS]\e[0m $1\e[0m"
}

print_error() {
    echo -e "\e[31m\e[1m[ERROR]\e[0m $1\e[0m" >&2
    exit 1
}

restart_services() {
    print_info "Restarting services..."
    systemctl restart php8.3-fpm
    systemctl restart nginx
    supervisorctl restart all
    print_success "All services have been restarted."
}


# --- Script Execution ---

# Ensure the script is run as root
if [ "$(id -u)" -ne 0 ]; then
    print_error "This script must be run as root. Please use sudo."
fi

# Handle --restart flag
if [ "$1" == "--restart" ]; then
    restart_services
    exit 0
fi


# Check if an old installation exists
if [ -d "$PROJECT_DIR" ]; then
    print_info "Existing installation found. Removing it to ensure a clean state..."
    # Backup .env if it exists
    if [ -f "${PROJECT_DIR}/.env" ]; then
        print_info "Backing up existing .env file..."
        mv "${PROJECT_DIR}/.env" "/tmp/.env.option-rocket.bak"
        print_success ".env file backed up to /tmp/.env.option-rocket.bak"
    fi

    # Remove the old directory
    rm -rf "${PROJECT_DIR}"
    print_success "Previous installation removed."
fi


# --- FRESH INSTALLATION PATH ---
print_info "Starting fresh installation..."

# 1. System Update & Essential Packages
print_info "Updating system and installing essential packages..."
apt-get update
apt-get upgrade -y
apt-get install -y git curl wget unzip software-properties-common nginx

# 2. Install PHP and Extensions
print_info "Installing PHP 8.3 and required extensions..."
apt-get install -y lsb-release ca-certificates apt-transport-https
echo "deb https://packages.sury.org/php/ $(lsb_release -sc) main" | tee /etc/apt/sources.list.d/php.list
wget -qO - https://packages.sury.org/php/apt.gpg | apt-key add -
apt-get update
apt-get install -y php8.3-fpm php8.3-mysql php8.3-mbstring php8.3-xml php8.3-curl php8.3-dom php8.3-zip php8.3-bcmath php8.3-gd php8.3-sqlite3

# 3. Install MariaDB (MySQL) and Create Database
print_info "Installing and configuring MariaDB..."
apt-get install -y mariadb-server
systemctl start mariadb
systemctl enable mariadb

print_info "Creating database and user (if they don't exist)..."
mysql -e "CREATE DATABASE IF NOT EXISTS ${DB_NAME};"
mysql -e "CREATE USER IF NOT EXISTS '${DB_USER}'@'localhost' IDENTIFIED BY '${DB_PASSWORD}';"
mysql -e "GRANT ALL PRIVILEGES ON ${DB_NAME}.* TO '${DB_USER}'@'localhost';"
mysql -e "FLUSH PRIVILEGES;"

# 4. Install Composer
print_info "Installing Composer..."
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php composer-setup.php --install-dir=/usr/local/bin --filename=composer
php -r "unlink('composer-setup.php');"

# 5. Install Node.js and NPM
print_info "Installing Node.js and NPM..."
curl -fsSL https://deb.nodesource.com/setup_lts.x | bash -
apt-get install -y nodejs

# 6. Clone Application from GitHub
print_info "Cloning application from GitHub repository..."
git clone "${GITHUB_REPO_URL}" "${PROJECT_DIR}"

print_info "Setting git safe directory to avoid ownership errors..."
git config --global --add safe.directory ${PROJECT_DIR}

# 7. Configure Laravel Application
print_info "Configuring Laravel application..."
cd "${PROJECT_DIR}"

# Restore .env if backup exists, otherwise create a new one from example
if [ -f "/tmp/.env.option-rocket.bak" ]; then
    print_info "Restoring backed-up .env file..."
    mv "/tmp/.env.option-rocket.bak" "${PROJECT_DIR}/.env"
    print_success ".env file restored."
else
    print_info "Setting up .env file for the first time (no backup found)..."
    cp .env.example .env

    # Update .env file with production values
    sed -i "s/^APP_ENV=local/APP_ENV=production/" .env
    sed -i "s/^APP_DEBUG=true/APP_DEBUG=false/" .env
    sed -i "s/^APP_URL=.*/APP_URL=http:\/\/${SERVER_DOMAIN}/" .env
    sed -i "s/^DB_CONNECTION=sqlite/DB_CONNECTION=mysql/" .env
    sed -i "s/^DB_DATABASE=.*/DB_DATABASE=${DB_NAME}/" .env
    sed -i "s/^DB_USERNAME=.*/DB_USERNAME=${DB_USER}/" .env
    sed -i "s/^DB_PASSWORD=.*/DB_PASSWORD=${DB_PASSWORD}/" .env
    # Set Reverb variables
    sed -i "s/^REVERB_APP_ID=.*/REVERB_APP_ID=\$(head /dev/urandom | tr -dc A-Za-z0-9 | head -c 8)/" .env
    sed -i "s/^REVERB_APP_KEY=.*/REVERB_APP_KEY=\$(head /dev/urandom | tr -dc A-Za-z0-9 | head -c 16)/" .env
    sed -i "s/^REVERB_APP_SECRET=.*/REVERB_APP_SECRET=\$(head /dev/urandom | tr -dc A-Za-z0-9 | head -c 32)/" .env
    sed -i "s/^REVERB_HOST=.*/REVERB_HOST=\"0.0.0.0\"/" .env
    sed -i "s/^REVERB_PORT=.*/REVERB_PORT=9090/" .env
    # Add placeholders for Stripe variables
    echo "STRIPE_KEY=your_stripe_key" >> .env
    echo "STRIPE_SECRET=your_stripe_secret" >> .env
    echo "STRIPE_WEBHOOK_SECRET=your_webhook_secret_for_production" >> .env
    echo "MONTHLY_PLAN_ID=price_1RiTgBBBlYDJOOlgyqRTNpic" >> .env
    echo "YEARLY_PLAN_ID=price_1RiTfpBBlYDJOOlgKEgmWOjg" >> .env
fi

print_info "Installing Composer dependencies (including dev for seeding)..."
composer install --optimize-autoloader

print_info "Creating swap file for NPM build..."
fallocate -l 2G /swapfile
chmod 600 /swapfile
mkswap /swapfile
swapon /swapfile

print_info "Installing NPM dependencies and building assets..."
npm install
npm run build

print_info "Removing swap file..."
swapoff /swapfile
rm /swapfile

print_info "Running Laravel setup commands..."
php artisan key:generate
php artisan storage:link
php artisan migrate --seed --force

print_info "Setting file permissions..."
chown -R www-data:www-data "${PROJECT_DIR}"
chmod -R 775 "${PROJECT_DIR}/storage"
chmod -R 775 "${PROJECT_DIR}/bootstrap/cache"

# 8. Configure Nginx
print_info "Configuring Nginx..."
cat > /etc/nginx/sites-available/option-rocket <<EOF
server {
    listen 80;
    server_name ${SERVER_DOMAIN};
    root ${PROJECT_DIR}/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-XSS-Protection "1; mode=block";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files \$uri \$uri/ /index.php?\$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.3-fpm.sock;
        fastcgi_param SCRIPT_FILENAME \$realpath_root\$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
EOF

if [ ! -L /etc/nginx/sites-enabled/option-rocket ]; then
    ln -s /etc/nginx/sites-available/option-rocket /etc/nginx/sites-enabled/
fi

unlink /etc/nginx/sites-enabled/default
systemctl restart nginx

# 9. Configure Supervisor for Reverb and Queue Worker
print_info "Installing and configuring Supervisor..."
apt-get install -y supervisor

# Reverb Supervisor Config
cat > /etc/supervisor/conf.d/reverb.conf <<EOF
[program:reverb]
process_name=%(program_name)s
command=php ${PROJECT_DIR}/artisan reverb:start --host=0.0.0.0 --port=9090
autostart=true
autorestart=true
user=www-data
redirect_stderr=true
stdout_logfile=${PROJECT_DIR}/storage/logs/reverb.log
EOF

# Queue Worker Supervisor Config
cat > /etc/supervisor/conf.d/laravel-worker.conf <<EOF
[program:laravel-worker]
process_name=%(program_name)s_%(process_num)02d
command=php ${PROJECT_DIR}/artisan queue:work --sleep=3 --tries=3
autostart=true
autorestart=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=${PROJECT_DIR}/storage/logs/worker.log
EOF

# Stripe Listener Supervisor Config
cat > /etc/supervisor/conf.d/stripe-listen.conf <<EOF
[program:stripe-listener]
process_name=%(program_name)s
command=/usr/local/bin/stripe listen --forward-to http://${SERVER_DOMAIN}/stripe/webhook
autostart=true
autorestart=true
user=www-data
redirect_stderr=true
stdout_logfile=${PROJECT_DIR}/storage/logs/stripe.log
EOF

print_info "Starting Supervisor processes..."
supervisorctl reread
supervisorctl update
supervisorctl start all

# 10. Configure Firewall (UFW)
print_info "Configuring Firewall (UFW)..."
apt-get install -y ufw
ufw allow 22/tcp  # SSH
ufw allow 80/tcp  # HTTP/Nginx
ufw allow 9090/tcp # Laravel Reverb
ufw --force enable
ufw status verbose


# --- Final Instructions ---
print_success "Deployment script finished successfully!"
echo -e "\n--- NEXT STEPS ---\n"
echo "1. IMPORTANT: If this was a fresh install, SSH into your server and edit the .env file with your real Stripe keys:"
echo "   sudo nano ${PROJECT_DIR}/.env"
echo ""
echo "2. Your application should be available at: http://${SERVER_DOMAIN}"
echo ""
echo "3. To setup Stripe CLI for testing on your VPS:"
echo "   - Download it: 'wget https://github.com/stripe/stripe-cli/releases/download/v1.20.0/stripe_1.20.0_linux_x86_64.tar.gz' (check for latest version)"
echo "   - Extract and install: 'tar -xvf stripe_1.20.0_linux_x86_64.tar.gz && sudo mv stripe /usr/local/bin/'"
echo "   - Login ONCE: 'stripe login'"
echo "   - The Stripe listener is now managed by Supervisor. Check its log with: 'tail -f ${PROJECT_DIR}/storage/logs/stripe.log'"
echo ""
print_success "Enjoy your freshly deployed Option-Rocket application!"
