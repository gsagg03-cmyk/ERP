#!/bin/bash

# VPS Update Script for ERP System
# This script pulls latest changes from GitHub and updates the VPS

set -e  # Exit on error

echo "=================================="
echo "ERP System - VPS Update Script"
echo "=================================="
echo ""

# Check if running as root
if [ "$EUID" -ne 0 ]; then 
    echo "Please run as root or with sudo"
    exit 1
fi

# Configuration
PROJECT_DIR="/var/www/erp"
BACKUP_DIR="/var/www/erp-backups"
TIMESTAMP=$(date +%Y%m%d_%H%M%S)

echo "1. Creating backup..."
mkdir -p "$BACKUP_DIR"
if [ -d "$PROJECT_DIR" ]; then
    cp -r "$PROJECT_DIR" "$BACKUP_DIR/erp_backup_$TIMESTAMP"
    echo "   ✓ Backup created at $BACKUP_DIR/erp_backup_$TIMESTAMP"
else
    echo "   ⚠ Project directory not found, skipping backup"
fi

echo ""
echo "2. Navigating to project directory..."
cd "$PROJECT_DIR" || exit 1
echo "   ✓ Current directory: $(pwd)"

echo ""
echo "3. Pulling latest changes from GitHub..."
git fetch origin
git pull origin main
echo "   ✓ Code updated"

echo ""
echo "4. Installing/Updating Composer dependencies..."
composer install --no-dev --optimize-autoloader
echo "   ✓ Composer dependencies updated"

echo ""
echo "5. Installing/Updating NPM dependencies..."
npm install
echo "   ✓ NPM dependencies updated"

echo ""
echo "6. Building assets..."
npm run build
echo "   ✓ Assets compiled"

echo ""
echo "7. Running database migrations..."
php artisan migrate --force
echo "   ✓ Database migrated"

echo ""
echo "8. Clearing and optimizing cache..."
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
echo "   ✓ Cache optimized"

echo ""
echo "9. Optimizing application..."
php artisan optimize
echo "   ✓ Application optimized"

echo ""
echo "10. Setting correct permissions..."
chown -R www-data:www-data "$PROJECT_DIR"
chmod -R 755 "$PROJECT_DIR/storage"
chmod -R 755 "$PROJECT_DIR/bootstrap/cache"
echo "   ✓ Permissions set"

echo ""
echo "11. Restarting services..."
systemctl restart nginx
systemctl restart php8.3-fpm
echo "   ✓ Services restarted"

echo ""
echo "=================================="
echo "✓ Update completed successfully!"
echo "=================================="
echo ""
echo "Backup location: $BACKUP_DIR/erp_backup_$TIMESTAMP"
echo "Current version: $(git log -1 --format='%h - %s (%ar)')"
echo ""
