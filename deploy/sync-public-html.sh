#!/usr/bin/env bash
set -euo pipefail

APP_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"

# Pakai public_html bawaan server (sejajar folder orasi), jangan buat folder baru.
if [[ -z "${ORASI_PUBLIC_HTML_DIR:-}" ]]; then
  if [[ -n "${HOME:-}" && -d "${HOME}/public_html" ]]; then
    PUBLIC_HTML_DIR="${HOME}/public_html"
  else
    PUBLIC_HTML_DIR="$(cd "$APP_DIR/.." && pwd)/public_html"
  fi
else
  PUBLIC_HTML_DIR="${ORASI_PUBLIC_HTML_DIR}"
fi

if [[ ! -d "$PUBLIC_HTML_DIR" ]]; then
  echo "Folder public_html bawaan server tidak ditemukan: $PUBLIC_HTML_DIR" >&2
  exit 1
fi

export ORASI_PUBLIC_HTML_DIR="$PUBLIC_HTML_DIR"

cd "$APP_DIR"
php artisan orasi:sync-public-html --public-html="$PUBLIC_HTML_DIR"

echo ""
echo "Selesai. Document root: $PUBLIC_HTML_DIR"
echo "Storage publik: $PUBLIC_HTML_DIR/storage -> $APP_DIR/storage/app/public"
