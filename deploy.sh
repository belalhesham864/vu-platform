#!/bin/bash
# deploy.sh — Pull latest code from git, rebuild the image, push to the registry,
# and roll out the updated stack.
#
# Usage:
#   ./deploy.sh            Full deploy (git pull + build + push + roll out)
#   ./deploy.sh --local    Skip the image build/push (use when code is served
#                          directly from a mounted volume)
set -e
cd ~/vu-platform

echo "=== [1/6] Pulling latest changes from git ==="
git pull

echo "=== [2/6] Ensuring entrypoint scripts exist ==="
# entrypoint-app.sh: removes the config/route/view cache steps from the base
# entrypoint so cached (stale) config never overrides .env values
if [ ! -f entrypoint-app.sh ]; then
  cat > entrypoint-app.sh << 'APPEOF'
#!/bin/bash
set -e
chmod +x entrypoint.sh
sed -i -E '/(config|route|view):cache/d' entrypoint.sh
./entrypoint.sh
exec "$@"
APPEOF
  chmod +x entrypoint-app.sh
fi

# entrypoint-worker.sh: boots the queue worker (migrate first, never cache config)
if [ ! -f entrypoint-worker.sh ]; then
  cat > entrypoint-worker.sh << 'QWEOF'
#!/bin/bash
set -e
until nc -z db 3306; do sleep 1; done
[ -f .env ] || cp .env.example .env
grep -q "APP_KEY=base64" .env || php artisan key:generate --ansi
php artisan migrate --force --ansi
chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache
rm -f bootstrap/cache/config.php bootstrap/cache/routes.php bootstrap/cache/views.php
exec php artisan queue:work --tries=3 --timeout=90 --sleep=3
QWEOF
  chmod +x entrypoint-worker.sh
fi

SKIP_REBUILD=false
[ "$1" = "--local" ] && SKIP_REBUILD=true

if [ "$SKIP_REBUILD" = false ]; then
  echo "=== [3/6] Building the application image ==="
  docker build -t ghcr.io/belalhesham864/vu-platform:latest .

  echo "=== [4/6] Pushing the image to the registry ==="
  docker push ghcr.io/belalhesham864/vu-platform:latest
fi

echo "=== [5/6] Rolling out the stack ==="
docker compose pull          # pull latest base images for web/db/redis as well
docker compose up -d --force-recreate

echo "=== [6/6] Verification ==="
sleep 8
docker compose ps -a
echo ""
echo "--- app entrypoint ---"
docker inspect vu-platform-app-1 --format '{{json .Config.Entrypoint}}'
echo ""
echo "--- queue-worker process ---"
docker compose exec queue-worker ps aux | grep queue || true
echo ""
echo "--- site check ---"
curl -sI --max-time 10 https://vuplatformzikola.duckdns.org | head -2
echo ""
echo "Deploy complete."
