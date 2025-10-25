#!/usr/bin/env bash
set -euo pipefail

# Ensure required storage and cache directories exist with correct ownership
mkdir -p storage/framework/{cache,data,sessions,testing,views} storage/app/public storage/logs bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

# Ensure storage link exists
php artisan storage:link || true

# Discover service providers and packages after containers boot
php artisan package:discover --ansi || true

# Run outstanding migrations
php artisan migrate --force

# Cache configuration/routes/views for performance
php artisan config:cache
php artisan route:cache
php artisan view:cache

exec "$@"
