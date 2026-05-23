#!/bin/bash
set -e

cd /Users/gamatoto/Downloads/bontings

export JAVA_HOME=/opt/homebrew/opt/openjdk@17/libexec/openjdk.jdk/Contents/Home
export PATH="$JAVA_HOME/bin:$PATH"
export BUBBLEWRAP_KEYSTORE_PASSWORD=$(cat twa-posbontings/keystore_password.txt)
export BUBBLEWRAP_KEY_PASSWORD=$BUBBLEWRAP_KEYSTORE_PASSWORD

echo "Running Bubblewrap init and build with here-document input..."

npx --yes @bubblewrap/cli init --manifest=file:$(pwd)/twa-posbontings/twa-manifest.json --directory=./twa-posbontings << 'EOF'
No
/opt/homebrew/opt/openjdk@17/libexec/openjdk.jdk
Y
EOF

echo "Init complete, now running build..."

npx --yes @bubblewrap/cli build --manifest=./twa-posbontings --signingKeyPath=./twa-posbontings/twa-keystore.jks --signingKeyAlias=twa-key << 'EOF'
No
Y
EOF

echo "Build complete!"
ls -lah twa-posbontings/app/build/outputs/ 2>/dev/null || echo "Output directory not yet available"
