#!/bin/bash

# Get the version from the main plugin file
VERSION=$(grep -m 1 "Version: " wp-multisite-waas.php | awk -F' ' '{print $2}')
PLUGIN_SLUG="wp-multisite-waas"
BUILD_DIR="./build"
DIST_DIR="$BUILD_DIR/$PLUGIN_SLUG"

echo "📦 Building $PLUGIN_SLUG version $VERSION..."

# Ensure a clean build directory
rm -rf "$BUILD_DIR"
mkdir -p "$DIST_DIR"

# Copy all necessary files to the distribution directory
echo "🔍 Copying files..."
rsync -rc --exclude-from='.distignore' --exclude="$BUILD_DIR" ./ "$DIST_DIR/" --delete --delete-excluded

# Remove development files
echo "🧹 Removing development files..."
cd "$DIST_DIR" || exit
rm -rf .git .github .gitignore .distignore .eslintrc* .stylelintrc* composer.json composer.lock package.json package-lock.json phpunit.xml jest.config.js tsconfig.json webpack.config.js node_modules tests .vscode .idea bin

# Create the zip file
echo "🗜️ Creating zip file..."
cd "$BUILD_DIR" || exit
ZIP_FILE="$PLUGIN_SLUG-$VERSION.zip"
zip -r "$ZIP_FILE" "$PLUGIN_SLUG" -x "*.DS_Store" -x "*.git*" -x "*node_modules*" -x "*vendor*" -x "*.map"

echo "✅ Build complete: $BUILD_DIR/$ZIP_FILE"
echo "📏 ZIP size: $(du -h "$ZIP_FILE" | cut -f1)"

# Remind about version numbers
echo ""
echo "🔔 Remember to:"
echo "  - Verify the zip contains all necessary files"
echo "  - Ensure version numbers match in:"
echo "    - wp-multisite-waas.php"
echo "    - package.json"
echo "    - readme.txt" 
echo ""

exit 0 