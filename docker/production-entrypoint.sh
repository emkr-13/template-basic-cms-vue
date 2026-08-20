#!/bin/sh
set -e

mkdir -p storage/app/public storage/framework/cache storage/framework/sessions storage/framework/views storage/logs
php artisan storage:link --force || true
chown -R www-data:www-data storage bootstrap/cache

exec "$@"
