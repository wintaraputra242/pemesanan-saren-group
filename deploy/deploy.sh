#!/usr/bin/env bash
# Zero-downtime-ish update script for pemesanan-saren-group, for a server
# managed by CloudPanel. Run this ON THE SERVER as the CloudPanel site user
# (SSH in as that user, not root), from inside the project folder:
#   bash deploy/deploy.sh

set -euo pipefail

APP_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
cd "$APP_DIR"

echo "==> Pulling latest code"
git pull origin main

echo "==> Installing PHP dependencies"
composer install --no-dev --optimize-autoloader --no-interaction

echo "==> Frontend assets are NOT built here."
echo "  If resources/css or resources/js changed, run from your local machine:"
echo "    bash deploy/push-assets.sh <site-user> <server-ip>"

echo "==> Putting app in maintenance mode"
php artisan down || true

echo "==> Running migrations"
php artisan migrate --force

echo "==> Clearing & rebuilding caches"
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

echo "==> Fixing storage permissions"
# No chown needed: CloudPanel's PHP-FPM pool runs as this same site user,
# so files already have the right owner. Just make sure they're writable.
chmod -R ug+rwx storage bootstrap/cache

echo "==> Restarting queue workers"
# Requires root/admin — run separately if this script executes as the
# CloudPanel site user without sudo rights:
#   ssh root@server "supervisorctl restart sarengroup-worker:*"
sudo supervisorctl restart sarengroup-worker:* || echo "  (skipped — run 'supervisorctl restart sarengroup-worker:*' as root/admin manually)"

echo "==> Bringing app back up"
php artisan up

echo "==> Done"
