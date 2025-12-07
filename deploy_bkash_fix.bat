@echo off
REM Deploy bKash fix to live server - Windows Version
REM Run this script to deploy the changes

echo ===================================
echo bKash Payment Fix Deployment Script
echo ===================================
echo.

REM Step 1: Commit changes
echo Step 1: Committing changes...
git add app/Http/Controllers/Front/PaymentController.php
git add .env-live
git add BKASH_500_ERROR_FIX.md
git commit -m "Fix bKash callback 500 error - Handle failure/cancel statuses"
echo [OK] Changes committed
echo.

REM Step 2: Push to repository
echo Step 2: Pushing to repository...
git push origin main
echo [OK] Pushed to GitHub
echo.

REM Step 3: Instructions for live server
echo Step 3: Deploy on Live Server
echo.
echo SSH to your live server and run:
echo.
echo   cd /path/to/your/project
echo   git pull origin main
echo   php artisan config:clear
echo   php artisan cache:clear
echo   php artisan view:clear
echo.

REM Step 4: Update .env on live
echo Step 4: Update Live .env File
echo.
echo Edit .env on live server and add/update:
echo.
echo BKASH_BASE_URL=https://tokenized.sandbox.bka.sh/v1.2.0-beta/
echo BKASH_APP_KEY=0vWQuCRGiUX7EPVjQDr0EUAYtc
echo BKASH_APP_SECRET=jcUNPBgbcqEDedNKdvE4G1cAK7D3hCjmJccNPZZBq96QIxxwAMEx
echo BKASH_USERNAME=01770618567
echo BKASH_PASSWORD=D7DaC^^<*E*eG
echo BKASH_CALLBACK_URL=https://www.sajidbeautybd.com/payment/callback/bkash
echo BKASH_SANDBOX=true
echo.

REM Step 5: Testing
echo Step 5: Test on Live Site
echo.
echo 1. Go to https://www.sajidbeautybd.com
echo 2. Add product to cart and checkout
echo 3. Select bKash payment
echo 4. Test these scenarios:
echo    - Complete payment successfully
echo    - Cancel payment (no 500 error)
echo    - Let payment fail (no 500 error)
echo.

echo ===================================
echo Deployment preparation complete!
echo ===================================
echo.
echo Read BKASH_500_ERROR_FIX.md for detailed instructions
echo.
pause
