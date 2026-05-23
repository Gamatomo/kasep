#!/usr/bin/env bash
set -euo pipefail

# Helper script to start Bubblewrap TWA init (interactive)
# Usage: ./scripts/bubblewrap-init.sh

MANIFEST_URL="https://posbontings.my.id/manifest.json"

echo "Starting Bubblewrap init for manifest: $MANIFEST_URL"
echo "If you don't have Bubblewrap installed globally, this will run via npx."

npx --yes @bubblewrap/cli init --manifest="$MANIFEST_URL"

echo "Bubblewrap init finished. Follow the prompts to generate the Android project."
