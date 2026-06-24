#!/usr/bin/env bash
set -euo pipefail

APP_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
# public_html berada di LUAR folder orasi (satu level di atas)
PUBLIC_HTML_DIR="${ORASI_PUBLIC_HTML_DIR:-$(cd "$APP_DIR/.." && pwd)/public_html}"

export ORASI_PUBLIC_HTML_DIR="$PUBLIC_HTML_DIR"

cd "$APP_DIR"
php artisan orasi:sync-public-html --public-html="$PUBLIC_HTML_DIR"

echo ""
echo "Selesai. Document root: $PUBLIC_HTML_DIR"
echo "Storage publik: $PUBLIC_HTML_DIR/storage -> $APP_DIR/storage/app/public"
