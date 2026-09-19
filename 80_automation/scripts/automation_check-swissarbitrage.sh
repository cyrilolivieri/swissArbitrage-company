#!/usr/bin/env bash
# check-swissarbitrage.sh — Wrapper for classification check
# Run from any directory; the Python script knows the workspace path.

set -euo pipefail

# Resolve symlink to get real script directory
SCRIPT_SOURCE="${BASH_SOURCE[0]}"
while [ -L "$SCRIPT_SOURCE" ]; do
    SCRIPT_DIR="$(cd "$(dirname "$SCRIPT_SOURCE")" && pwd)"
    SCRIPT_SOURCE="$(readlink "$SCRIPT_SOURCE")"
    [[ $SCRIPT_SOURCE != /* ]] && SCRIPT_SOURCE="$SCRIPT_DIR/$SCRIPT_SOURCE"
done
SCRIPT_DIR="$(cd "$(dirname "$SCRIPT_SOURCE")" && pwd)"

python3 "${SCRIPT_DIR}/automation_check-swissarbitrage.py" "$@"
