#!/bin/bash

# Meta Pixel Implementation - Post-Installation Script
# Run this after the implementation is complete

echo "======================================"
echo "Meta Pixel Post-Installation Setup"
echo "======================================"
echo ""

# Step 1: Regenerate Composer Autoload
echo "Step 1: Regenerating Composer autoload files..."
composer dump-autoload
if [ $? -eq 0 ]; then
    echo "✓ Autoload regenerated successfully"
else
    echo "✗ Error regenerating autoload. Please run 'composer dump-autoload' manually."
    exit 1
fi
echo ""

# Step 2: Clear caches
echo "Step 2: Clearing Laravel caches..."
php artisan cache:clear
php artisan config:clear
php artisan view:clear
php artisan route:clear
echo "✓ Caches cleared"
echo ""

# Step 3: Check files exist
echo "Step 3: Verifying implementation files..."
FILES=(
    "app/Services/MetaPixelService.php"
    "app/Helpers/MetaPixelHelper.php"
    "resources/views/components/meta-pixel.blade.php"
)

ALL_EXIST=true
for file in "${FILES[@]}"; do
    if [ -f "$file" ]; then
        echo "✓ $file"
    else
        echo "✗ $file NOT FOUND"
        ALL_EXIST=false
    fi
done

if [ "$ALL_EXIST" = false ]; then
    echo ""
    echo "⚠ Some files are missing. Please check the implementation."
    exit 1
fi
echo ""

# Step 4: Configuration reminder
echo "======================================"
echo "Next Steps:"
echo "======================================"
echo ""
echo "1. Go to your Admin panel → Settings"
echo "2. Scroll to 'Facebook Meta Pixel' section"
echo "3. Enable Meta Pixel (toggle ON)"
echo "4. Enter your Pixel ID from Facebook Events Manager"
echo "5. (Optional) Enter Access Token for server-side events"
echo "6. Click 'Save Settings'"
echo ""
echo "======================================"
echo "Testing:"
echo "======================================"
echo ""
echo "1. Install Meta Pixel Helper Chrome extension"
echo "   https://chrome.google.com/webstore/detail/meta-pixel-helper/fdgfkebogiimcoedlicjlajpkdmockpc"
echo ""
echo "2. Browse your site and check if pixel fires"
echo ""
echo "3. Check Events Manager → Test Events tab"
echo "   https://business.facebook.com/events_manager2"
echo ""
echo "======================================"
echo "Documentation:"
echo "======================================"
echo ""
echo "✓ META_PIXEL_IMPLEMENTATION.md - Complete guide"
echo "✓ META_PIXEL_QUICK_REFERENCE.md - Quick reference"
echo "✓ META_PIXEL_SUMMARY.md - Implementation summary"
echo ""
echo "======================================"
echo "✓ Installation Complete!"
echo "======================================"
