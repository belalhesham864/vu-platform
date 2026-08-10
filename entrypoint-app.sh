#!/bin/bash
set -e

# Neutralize the base entrypoint's config/route/view cache steps.
# A stale cached config overrides .env values (this was the root cause of
# QUEUE_CONNECTION=sync), so we strip the cache commands before running it.
chmod +x entrypoint.sh
sed -i -E '/(config|route|view):cache/d' entrypoint.sh
./entrypoint.sh

exec "$@"
