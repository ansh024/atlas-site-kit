#!/usr/bin/env bash
set -euo pipefail
source "$(dirname "$0")/wp-local-guard.sh"
echo "Destroying only this repository's disposable wp-env containers and volumes."
"$WP_ENV_BIN" destroy
