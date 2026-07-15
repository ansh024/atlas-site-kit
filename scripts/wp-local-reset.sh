#!/usr/bin/env bash
set -euo pipefail
source "$(dirname "$0")/wp-local-guard.sh"
echo "Resetting only the disposable wp-env database at $WP_LOCAL_URL."
"$WP_ENV_BIN" reset development
"$WP_ENV_BIN" start
bash "$(dirname "$0")/wp-local-bootstrap.sh"
