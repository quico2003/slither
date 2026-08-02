#!/bin/sh
set -e

cd /var/www/html

if [ ! -f .env ]; then
  cp .env.example .env
fi

if ! grep -q "^APP_KEY=base64:" .env; then
  php artisan key:generate --force
fi

php artisan storage:link 2>/dev/null || true
php artisan migrate --force

exec "$@"
