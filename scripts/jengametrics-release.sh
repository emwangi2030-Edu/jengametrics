#!/usr/bin/env bash
# Run inside app container after deploy (live or staging), from /var/www/html
set -euo pipefail
cd /var/www/html
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache 2>/dev/null || true
php artisan optimize
