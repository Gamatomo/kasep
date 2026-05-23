#!/bin/bash
set -e

cd /Users/gamatoto/Downloads/bontings

export JAVA_HOME=/opt/homebrew/opt/openjdk@17/libexec/openjdk.jdk/Contents/Home
export PATH="$JAVA_HOME/bin:$PATH"
export BUBBLEWRAP_KEYSTORE_PASSWORD=$(cat twa-posbontings/keystore_password.txt)
export BUBBLEWRAP_KEY_PASSWORD=$BUBBLEWRAP_KEYSTORE_PASSWORD

# Ensure twa-posbontings is a proper directory structure
# Try to update if it exists, otherwise init
if [ -f "twa-posbontings/build.gradle" ]; then
  echo "Android project already exists, skipping init..."
else
  echo "Initializing Android project with Bubblewrap..."
  # Using a named pipe to handle interactive input
  (
    sleep 2
    echo "No"  # JDK install
    sleep 1
    echo "/opt/homebrew/opt/openjdk@17/libexec/openjdk.jdk/Contents/Home"  # JDK path
    sleep 1
    echo "y"  # Continue
  ) | npx --yes @bubblewrap/cli init --manifest=file:$(pwd)/twa-posbontings/twa-manifest.json --directory=./twa-posbontings
fi

# Now build
echo "Running Bubblewrap build..."
npx --yes @bubblewrap/cli build --manifest=./twa-posbontings --signingKeyPath=./twa-posbontings/twa-keystore.jks --signingKeyAlias=twa-key

echo "Build complete!"
ls -lah twa-posbontings/app/build/outputs/
