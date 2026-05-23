# Dapur Kasep - Android APK Installation Guide

## 📱 APK Details
- **File Name**: DapurKasep.apk
- **Location**: `twa-posbontings/app/build/outputs/apk/release/DapurKasep.apk`
- **Size**: 2.4 MB
- **Package**: id.my.posbontings
- **Version**: 1.0.0 (Build 2)
- **Min Android Version**: 5.0 (API 21)
- **Signature**: Signed with Dapur Kasep certificate

---

## ⚠️ Troubleshooting "App Not Installed" Error

If you see **"App is not installed"** when installing the APK, try these solutions:

### 1. Enable Unknown Sources (Most Common Fix)
On your Android phone:
1. Go to **Settings** → **Security** (or **Apps** → **Special access**)
2. Find **"Unknown Sources"** or **"Install unknown apps"**
3. Select **File Manager** or **Downloads** app
4. Toggle **"Allow from this source"** to ON
5. Try installing the APK again

### 2. Check Device Storage
- Make sure you have **at least 100 MB free space** on your phone
- Go to **Settings** → **Storage** to check available space
- If low, delete some unused apps or files

### 3. Clear Download Cache (if installing from Downloads folder)
```bash
# On your computer, delete old APKs from Downloads:
rm /Users/gamatoto/Downloads/*.apk
```

Then re-transfer the fresh APK to your phone.

### 4. Install via Android Debug Bridge (ADB)
If you have Android SDK tools installed on your computer:

```bash
# Enable Developer Mode on phone:
# Settings → About → Tap Build Number 7 times → Developer Options enabled

# Enable USB Debugging:
# Settings → Developer Options → USB Debugging ON

# Connect phone via USB and run:
adb install /Users/gamatoto/Downloads/bontings/twa-posbontings/app/build/outputs/apk/release/DapurKasep.apk
```

### 5. Update System WebView
The app relies on Chrome Custom Tabs. Make sure you have:
- **Google Chrome** installed
- **Android System WebView** updated
- Go to **Google Play Store** and search for "Android System WebView" → Update

### 6. Check App Compatibility
- **Android 5.0+** (API 21) required
- Device CPU architecture supported: ARM (all devices)
- Go to **Settings** → **About** → **Android version** to verify

---

## ✅ Verify APK Signature

To confirm the APK is properly signed:

```bash
cd /Users/gamatoto/Downloads/bontings/twa-posbontings

# Verify signature
export JAVA_HOME=/opt/homebrew/opt/openjdk@17/libexec/openjdk.jdk/Contents/Home
$JAVA_HOME/bin/jarsigner -verify app/build/outputs/apk/release/DapurKasep.apk
```

Expected output: `jar verified.`

---

## 📊 What's Inside the APK

The APK contains:
- ✅ Chromium-based TWA runtime
- ✅ Full Android permissions for web content
- ✅ Launch to your website (posbontings.my.id)
- ✅ Offline support via Service Worker
- ✅ Custom app icon and theme colors

---

## 🔗 Installation Methods

### Method 1: Direct File Transfer
1. Connect phone to computer via USB
2. Copy `DapurKasep.apk` to phone storage
3. Open Files app → navigate to Downloads
4. Tap APK → Install

### Method 2: Send via Email/Chat
1. Email the APK file to yourself
2. Open email on phone → Download APK
3. Tap to install (enable Unknown Sources first)

### Method 3: QR Code Transfer
Generate a QR code pointing to the APK file and scan from phone.

### Method 4: ADB (Recommended for developers)
```bash
adb install DapurKasep.apk
```

---

## 🆘 If Installation Still Fails

1. **Check error code**: Note the exact error message
2. **Try older Android version**: Some devices have bugs - try on a different phone
3. **Uninstall conflicting apps**: Remove any older version of the app
4. **Restart device**: Sometimes helps resolve installation issues
5. **Check certificate**: Verify the app wasn't tampered with

---

## 📝 App Details After Installation

Once installed, the app will:
- **Display name**: Bontings (in app) / Dapur Kasep (in Play Store)
- **Package name**: id.my.posbontings
- **Data**: Connects to https://posbontings.my.id
- **Offline mode**: Works without internet (cached content)
- **Size**: ~2.4 MB + Chrome runtime cache

---

## 🚀 Next Step: Play Store Submission

To publish on Google Play Store:
1. Generate `.aab` bundle instead of APK
2. Sign with the same keystore
3. Upload to Google Play Console
4. Users can then install directly from Play Store

---

**Generated**: May 24, 2026
**App Name**: Dapur Kasep
**Status**: ✅ Ready for Installation
