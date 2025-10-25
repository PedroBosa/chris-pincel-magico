#!/usr/bin/env bash
set -euo pipefail

# Ensure storage link exists
php artisan storage:link || true

# Run outstanding migrations
php artisan migrate --force

# Cache configuration/routes/views for performance
php artisan config:cache
php artisan route:cache
php artisan view:cache

exec "$@"
