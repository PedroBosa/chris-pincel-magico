#!/bin/bash

echo "🚀 Chris Pincel Mágico - Build Script"

# Instalar dependências do Composer
composer install --no-dev --optimize-autoloader --no-interaction

# Gerar caches
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "✅ Build concluído!"
