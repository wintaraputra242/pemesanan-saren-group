#!/usr/bin/env bash
# Build frontend assets LOCALLY and ship them to the server — no Node.js
# needed on the server at all. Run this from your dev machine (works in Git
# Bash on Windows) whenever CSS/JS changes, before or after deploy.sh.
#
# Usage: bash deploy/push-assets.sh <site-user> <server-ip> [domain]

set -euo pipefail

SITE_USER="${1:?Usage: bash deploy/push-assets.sh <site-user> <server-ip> [domain]}"
SERVER_IP="${2:?Usage: bash deploy/push-assets.sh <site-user> <server-ip> [domain]}"
DOMAIN="${3:-pemesanan-saren-group.my.id}"

echo "==> Building frontend assets locally"
npm install
npm run build

echo "==> Packaging public/build"
tar -czf public/build.tar.gz -C public build

echo "==> Uploading to server"
scp public/build.tar.gz "$SITE_USER@$SERVER_IP:~/htdocs/$DOMAIN/pemesanan-saren-group/public/build.tar.gz"

echo "==> Extracting on server"
ssh "$SITE_USER@$SERVER_IP" "cd ~/htdocs/$DOMAIN/pemesanan-saren-group/public && rm -rf build && tar -xzf build.tar.gz && rm build.tar.gz"

echo "==> Cleaning up local archive"
rm public/build.tar.gz

echo "==> Done — frontend assets updated on $DOMAIN"
