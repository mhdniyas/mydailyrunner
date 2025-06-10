#!/bin/bash

# Script to fix permissions in the Shop Manager application
echo "🔒 Fixing permissions for Shop Manager..."

# Fix shell script permissions
echo "📝 Making shell scripts executable..."
find /var/www/ard5 -name "*.sh" -exec chmod +x {} \;

# Fix node_modules binary permissions
echo "🔧 Fixing node_modules binary permissions..."
find /var/www/ard5/node_modules/.bin -type f -exec chmod +x {} \;
find /var/www/ard5/node_modules -name "*.sh" -exec chmod +x {} \;
find /var/www/ard5/node_modules/@esbuild -name "esbuild" -exec chmod +x {} \;

# Fix storage permissions
echo "📁 Setting proper storage permissions..."
chmod -R 775 /var/www/ard5/storage
chmod -R 775 /var/www/ard5/bootstrap/cache

# Fix web server permissions if needed
echo "🌐 Setting proper web server permissions..."
chown -R www-data:www-data /var/www/ard5/storage
chown -R www-data:www-data /var/www/ard5/bootstrap/cache

echo "✅ Permissions fixed successfully!"
