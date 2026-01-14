@echo off
REM Meta Pixel Implementation - Post-Installation Script (Windows)
REM Run this after the implementation is complete

echo ======================================
echo Meta Pixel Post-Installation Setup
echo ======================================
echo.

REM Step 1: Regenerate Composer Autoload
echo Step 1: Regenerating Composer autoload files...
call composer dump-autoload
if %errorlevel% neq 0 (
    echo X Error regenerating autoload. Please run 'composer dump-autoload' manually.
    exit /b 1
)
echo √ Autoload regenerated successfully
echo.

REM Step 2: Clear caches
echo Step 2: Clearing Laravel caches...
call php artisan cache:clear
call php artisan config:clear
call php artisan view:clear
call php artisan route:clear
echo √ Caches cleared
echo.

REM Step 3: Check files exist
echo Step 3: Verifying implementation files...
set ALL_EXIST=1

if exist "app\Services\MetaPixelService.php" (
    echo √ app\Services\MetaPixelService.php
) else (
    echo X app\Services\MetaPixelService.php NOT FOUND
    set ALL_EXIST=0
)

if exist "app\Helpers\MetaPixelHelper.php" (
    echo √ app\Helpers\MetaPixelHelper.php
) else (
    echo X app\Helpers\MetaPixelHelper.php NOT FOUND
    set ALL_EXIST=0
)

if exist "resources\views\components\meta-pixel.blade.php" (
    echo √ resources\views\components\meta-pixel.blade.php
) else (
    echo X resources\views\components\meta-pixel.blade.php NOT FOUND
    set ALL_EXIST=0
)

if %ALL_EXIST% equ 0 (
    echo.
    echo Warning: Some files are missing. Please check the implementation.
    exit /b 1
)
echo.

REM Step 4: Configuration reminder
echo ======================================
echo Next Steps:
echo ======================================
echo.
echo 1. Go to your Admin panel → Settings
echo 2. Scroll to 'Facebook Meta Pixel' section
echo 3. Enable Meta Pixel (toggle ON)
echo 4. Enter your Pixel ID from Facebook Events Manager
echo 5. (Optional) Enter Access Token for server-side events
echo 6. Click 'Save Settings'
echo.
echo ======================================
echo Testing:
echo ======================================
echo.
echo 1. Install Meta Pixel Helper Chrome extension
echo    https://chrome.google.com/webstore/detail/meta-pixel-helper/fdgfkebogiimcoedlicjlajpkdmockpc
echo.
echo 2. Browse your site and check if pixel fires
echo.
echo 3. Check Events Manager → Test Events tab
echo    https://business.facebook.com/events_manager2
echo.
echo ======================================
echo Documentation:
echo ======================================
echo.
echo √ META_PIXEL_IMPLEMENTATION.md - Complete guide
echo √ META_PIXEL_QUICK_REFERENCE.md - Quick reference
echo √ META_PIXEL_SUMMARY.md - Implementation summary
echo.
echo ======================================
echo √ Installation Complete!
echo ======================================
echo.
pause
