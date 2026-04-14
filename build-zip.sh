#!/bin/bash
#
# Build a production-ready .zip of the plugin.
#
# Usage:
#   ./build-zip.sh           Build the zip.
#   ./build-zip.sh --clean   Remove the dist/ directory.
#
# Output: dist/{plugin-slug}-{version}.zip
#
set -e

# ---------- resolve plugin metadata ----------

# Find the main plugin file by looking for the "Plugin Name:" header in root PHP files.
MAIN_FILE=$(grep -l "Plugin Name:" ./*.php 2>/dev/null | head -1)

if [ -z "$MAIN_FILE" ]; then
	echo "Error: no main plugin file found (no .php file with 'Plugin Name:' header)."
	exit 1
fi

MAIN_FILE=$(basename "$MAIN_FILE")
PLUGIN_SLUG="${MAIN_FILE%.php}"

VERSION=$(grep -i "^ \* Version:" "$MAIN_FILE" | head -1 | sed 's/.*Version:[[:space:]]*//' | tr -d '[:space:]')

if [ -z "$VERSION" ]; then
	echo "Error: could not extract version from '${MAIN_FILE}'."
	exit 1
fi

# ---------- handle --clean flag ----------

if [ "$1" = "--clean" ]; then
	rm -rf dist
	echo "Cleaned dist/ directory."
	exit 0
fi

echo "Building ${PLUGIN_SLUG} v${VERSION}..."

# ---------- create temp build directory ----------

TEMP_DIR=$(mktemp -d)
BUILD_DIR="${TEMP_DIR}/${PLUGIN_SLUG}"
mkdir -p "$BUILD_DIR"

cleanup() {
	rm -rf "$TEMP_DIR"
}
trap cleanup EXIT

# ---------- install & compile ----------

echo "  Installing production PHP dependencies..."
composer install --no-dev --optimize-autoloader --quiet 2>/dev/null

echo "  Compiling JavaScript assets..."
npm run build --silent > /dev/null 2>&1

# ---------- copy production files ----------

echo "  Assembling plugin files..."

# Root PHP files.
for f in *.php; do
	[ -f "$f" ] && cp "$f" "$BUILD_DIR/"
done

# PHP source classes (exclude src/js/ — that's uncompiled React source).
cp -r src/ "$BUILD_DIR/src/"
rm -rf "$BUILD_DIR/src/js/"

# Compiled block output.
if [ -d build/ ]; then
	cp -r build/ "$BUILD_DIR/build/"
fi

# Static assets.
cp -r assets/ "$BUILD_DIR/assets/"

# View templates.
cp -r views/ "$BUILD_DIR/views/"

# Composer autoloader (production-only vendor).
cp -r vendor/ "$BUILD_DIR/vendor/"

# Translations.
if [ -d languages/ ] && [ "$(ls -A languages/)" ]; then
	cp -r languages/ "$BUILD_DIR/languages/"
fi

# Individual files.
[ -f LICENSE ] && cp LICENSE "$BUILD_DIR/"
[ -f readme.txt ] && cp readme.txt "$BUILD_DIR/"
[ -f README.md ] && cp README.md "$BUILD_DIR/" 2>/dev/null || true

# ---------- clean up artifacts ----------

# Remove vendor dev leftovers (binaries, cache files).
rm -rf "$BUILD_DIR/vendor/bin"

# Remove .gitkeep placeholders.
find "$BUILD_DIR" -name ".gitkeep" -delete 2>/dev/null || true

# Remove empty directories (left behind after cleanup).
find "$BUILD_DIR" -type d -empty -exec rmdir {} + 2>/dev/null || true

# ---------- create the zip ----------

mkdir -p dist

(cd "$TEMP_DIR" && zip -r -q "${PLUGIN_SLUG}-${VERSION}.zip" "${PLUGIN_SLUG}/")
mv "${TEMP_DIR}/${PLUGIN_SLUG}-${VERSION}.zip" "dist/"

# ---------- restore dev dependencies ----------

echo "  Restoring dev dependencies..."
composer install --quiet 2>/dev/null

# ---------- done ----------

ZIP_PATH="dist/${PLUGIN_SLUG}-${VERSION}.zip"
ZIP_SIZE=$(du -h "$ZIP_PATH" | cut -f1 | tr -d '[:space:]')

echo ""
echo "Done: ${ZIP_PATH} (${ZIP_SIZE})"
