#!/usr/bin/env bash

set -euo pipefail

APP_DIR="${APP_DIR:-/var/www/laravel/app}"
PHP_BIN="${PHP_BIN:-php}"
COMPOSER_BIN="${COMPOSER_BIN:-composer}"
BRANCH="${BRANCH:-main}"
WEB_USER="${WEB_USER:-www-data}"

cd "$APP_DIR"

echo "Deploying branch $BRANCH in $APP_DIR"

git fetch origin "$BRANCH"
git checkout "$BRANCH"
git pull origin "$BRANCH"

# Make writable directories safe for both deploy user and web server.
sudo mkdir -p storage/logs bootstrap/cache
sudo touch storage/logs/laravel.log
sudo chown -R "$USER":"$USER" "$APP_DIR"
sudo chmod -R u+rwX "$APP_DIR"

# Clear stale Laravel caches before Composer triggers package discovery.
rm -f bootstrap/cache/*.php || true
$PHP_BIN artisan optimize:clear || true

$COMPOSER_BIN install --no-interaction --no-dev --prefer-dist --optimize-autoloader

$PHP_BIN artisan migrate --force
$PHP_BIN artisan optimize:clear
$PHP_BIN artisan config:cache
$PHP_BIN artisan route:cache
$PHP_BIN artisan view:cache

# Return runtime-owned directories to the web server.
sudo chown -R "$WEB_USER":"$WEB_USER" storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache

echo "Deployment completed successfully."
