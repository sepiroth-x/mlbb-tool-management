#!/bin/bash

# MLBB Tournament Manager - Deployment Script
# Run this script after pulling from git

echo "🚀 Starting deployment..."

# Pull latest changes
echo "📥 Pulling latest changes..."
git pull origin main

# Clear all caches
echo "🧹 Clearing caches..."
php artisan optimize:clear
php artisan route:clear
php artisan config:clear
php artisan view:clear
php artisan cache:clear

# Run migrations
echo "💾 Running migrations..."
php artisan migrate --force

# Optimize for production
echo "⚡ Optimizing..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Set permissions (if needed)
echo "🔐 Setting permissions..."
chmod -R 775 storage bootstrap/cache

echo "✅ Deployment complete!"
echo ""
echo "🌐 Your site should now be updated at mlbb.vantapress.com"
