#!/bin/bash

# Enhanced Morning Cricket System Upgrade Script
# This script will upgrade the MorningCricket system with:
# 1. New subscription system
# 2. Batch-based stocking
# 3. Daily workflow shortcuts

echo "🚀 Starting MorningCricket system upgrade..."

# Run database migrations
echo "📦 Running database migrations..."
php artisan migrate

# Clear cache to ensure new routes and views are recognized
echo "🧹 Clearing application cache..."
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Optimize the application
echo "⚡ Optimizing application..."
php artisan optimize

# Run npm to rebuild assets if needed
if [ -f "package.json" ]; then
    echo "🎨 Rebuilding frontend assets..."
    npm run build
fi

echo "✅ System upgrade completed successfully!"
echo ""
echo "New features available:"
echo "- Enhanced subscription control system"
echo "- Batch-based stocking with weighted averages"
echo "- Daily workflow shortcuts for faster operations"
echo ""
echo "Please log in to see the new features in action."
