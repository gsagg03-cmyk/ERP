#!/bin/bash

# VPS Production Login Fix Script
# Fixes common login and session issues on production

set -e

echo "=================================="
echo "VPS Production Login Fix Script"
echo "=================================="
echo ""

PROJECT_DIR="/var/www/erp"

cd "$PROJECT_DIR"

echo "1. Checking .env configuration..."
echo ""

# Fix session configuration
echo "   → Setting session driver to database..."
sed -i "s/^SESSION_DRIVER=.*/SESSION_DRIVER=database/" .env

echo "   → Setting session configuration for HTTPS..."
sed -i "s/^SESSION_SECURE_COOKIE=.*/SESSION_SECURE_COOKIE=true/" .env
sed -i "s/^SESSION_SAME_SITE=.*/SESSION_SAME_SITE=lax/" .env

# Ensure APP_URL is set correctly
if ! grep -q "^APP_URL=" .env; then
    echo "APP_URL=https://drishtierp.xyz" >> .env
fi

# Ensure session domain is correct
if ! grep -q "^SESSION_DOMAIN=" .env; then
    echo "SESSION_DOMAIN=drishtierp.xyz" >> .env
else
    sed -i "s/^SESSION_DOMAIN=.*/SESSION_DOMAIN=drishtierp.xyz/" .env
fi

echo "   ✓ Session configuration updated"
echo ""

echo "2. Creating sessions table if not exists..."
php artisan session:table 2>/dev/null || echo "   → Sessions table already exists"
php artisan migrate --force
echo "   ✓ Database migration completed"
echo ""

echo "3. Clearing all caches..."
php artisan optimize:clear
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
echo "   ✓ All caches cleared"
echo ""

echo "4. Clearing old sessions..."
rm -rf storage/framework/sessions/*
php artisan optimize
echo "   ✓ Old sessions cleared"
echo ""

echo "5. Recaching configuration..."
php artisan config:cache
php artisan route:cache
php artisan view:cache
echo "   ✓ Configuration cached"
echo ""

echo "6. Setting correct permissions..."
chown -R www-data:www-data storage
chown -R www-data:www-data bootstrap/cache
chmod -R 775 storage
chmod -R 775 bootstrap/cache
echo "   ✓ Permissions set"
echo ""

echo "7. Restarting services..."
systemctl restart php8.2-fpm
systemctl reload nginx
echo "   ✓ Services restarted"
echo ""

echo "=================================="
echo "✓ Login fix completed!"
echo "=================================="
echo ""
echo "Current configuration:"
echo "  SESSION_DRIVER: database"
echo "  SESSION_SECURE_COOKIE: true"
echo "  SESSION_SAME_SITE: lax"
echo "  SESSION_DOMAIN: drishtierp.xyz"
echo "  APP_URL: https://drishtierp.xyz"
echo ""
echo "Please try logging in again."
echo "If issue persists, check:"
echo "  1. Browser cookies are enabled"
echo "  2. Clear browser cache/cookies"
echo "  3. Try incognito/private mode"
echo ""
