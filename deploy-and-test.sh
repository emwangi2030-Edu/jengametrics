#!/usr/bin/env bash
# JengaMetrics staging: deploy (clear caches) + smoke test.
# Run from repo root on server or via: ssh linode-email 'bash -s' < deploy-and-test.sh

set -e
CONTAINER="${JM_CONTAINER:-jm-staging-app}"
APP_DIR="${JM_APP_DIR:-/var/www/html}"
BASE_URL="${BASE_URL:-https://staging.jengametrics.com}"

echo "==> Deploy (clear caches)..."
echo "    Normalize Laravel writable permissions (storage, bootstrap/cache)..."
docker exec "$CONTAINER" sh -lc "chown -R www-data:www-data '$APP_DIR/storage' '$APP_DIR/bootstrap/cache' \
  && find '$APP_DIR/storage' '$APP_DIR/bootstrap/cache' -type d -exec chmod 775 {} \; \
  && find '$APP_DIR/storage' '$APP_DIR/bootstrap/cache' -type f -exec chmod 664 {} \;"

echo "    Clear caches as web user (www-data)..."
docker exec "$CONTAINER" sh -lc "su -s /bin/sh www-data -c 'cd \"$APP_DIR\" && php artisan optimize:clear --no-interaction'"

# Extra guard: if any compiled Blade files were created as root by prior deploys, remove them.
docker exec "$CONTAINER" sh -lc "rm -f '$APP_DIR/storage/framework/views/'*.php"

# Keep explicit clears for environments where optimize:clear behavior differs.
docker exec -w "$APP_DIR" "$CONTAINER" php artisan config:clear --no-interaction
docker exec -w "$APP_DIR" "$CONTAINER" php artisan route:clear --no-interaction
docker exec -w "$APP_DIR" "$CONTAINER" php artisan view:clear --no-interaction
echo "    Done."

echo ""
echo "==> Smoke tests (HTTP)..."
test_redirect() {
  local path="$1"
  local expect="$2"
  local code
  code=$(curl -sI -o /dev/null -w "%{http_code}" "$BASE_URL$path" 2>/dev/null || echo "000")
  if [[ "$code" == "$expect" ]]; then
    echo "    OK $path -> $code"
  else
    echo "    FAIL $path -> $code (expected $expect)"
    return 1
  fi
}
test_redirect "/" "302"
test_redirect "/login" "200"
test_redirect "/dashboard" "302"
test_redirect "/materials" "302"
test_redirect "/workers" "302"
test_redirect "/boq" "302"
echo ""
echo "==> All smoke tests passed."
