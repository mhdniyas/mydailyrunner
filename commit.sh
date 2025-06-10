#!/bin/bash

# Quick commit script with Laravel cache clearing and optimization
COMMIT_MSG=${1:-"Update: auto-commit changes"}

echo "🧹 Clearing Laravel caches..."
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

echo "⚡ Optimizing Laravel..."
php artisan optimize

echo "📝 Adding all changes..."
git add .

echo "💾 Committing with message: '$COMMIT_MSG'"
git commit -m "$COMMIT_MSG"

echo "🚀 Pushing to remote..."
git push origin main

echo "✅ Changes committed and pushed successfully!"