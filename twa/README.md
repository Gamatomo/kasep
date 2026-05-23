# Trusted Web Activity (TWA) — posbontings.my.id

This folder documents how to package your PWA at `https://posbontings.my.id` as an Android Play Store app using a Trusted Web Activity (TWA).

Prerequisites
- Java JDK 17+
- Android SDK / Android Studio
- Node.js (v14+ recommended)
- `@bubblewrap/cli` (we use `npx` below)
- A TLS-enabled public URL for your site (you already have `https://posbontings.my.id`)

Quick steps

1. Install Bubblewrap (optional — `npx` works too):
```bash
npm install -g @bubblewrap/cli
```

2. Initialize a TWA project (this will read your manifest at `https://posbontings.my.id/manifest.json`):
```bash
# interactive: answers will be prompted
npx @bubblewrap/cli init --manifest=https://posbontings.my.id/manifest.json
```

3. Build the Android project (you can create a new keystore when prompted):
```bash
npx @bubblewrap/cli build
```

4. Extract the release SHA256 fingerprint from the keystore (example):
```bash
keytool -list -v -keystore ./path/to/keystore.jks -alias <alias>
# copy the SHA256 fingerprint
```

5. Update `public/.well-known/assetlinks.json` on your server with your package name and the SHA256 fingerprint (this file is already present in the repo as a placeholder). Example entry:
```json
[{
  "relation": ["delegate_permission/common.handle_all_urls"],
  "target": {
    "namespace": "android_app",
    "package_name": "id.my.posbontings",
    "sha256_cert_fingerprints": ["AA:BB:CC:..."]
  }
}]
```

6. Host `https://posbontings.my.id/.well-known/assetlinks.json` (HTTPS required), then test the installed app on Android or upload the generated `.aab` to the Play Console.

Notes & tips
- The dynamic manifest at `/manifest.json` in this project already includes maskable icons where possible. Bubblewrap will also fetch the manifest for icons — having `https://posbontings.my.id/icons/*` accessible is recommended.
- If you prefer automation, run the init/build steps on a machine with Android SDK + JDK. I can attempt to run Bubblewrap here if you want me to (it will prompt for JDK/keystore choices).

If you want, I can now:
- Generate icon files in `public/icons/` (192x192 and 512x512 maskable PNGs) for you, or
- Run `npx @bubblewrap/cli init` here and create the Android project (I will need to install JDK and may prompt for choices).
