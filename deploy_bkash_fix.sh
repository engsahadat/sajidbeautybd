#!/bin/bash
# Deploy bKash fix to live server
# Run this script to deploy the changes

echo "==================================="
echo "bKash Payment Fix Deployment Script"
echo "==================================="
echo ""

# Colors for output
GREEN='\033[0;32m'
RED='\033[0;31m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Step 1: Commit changes
echo -e "${YELLOW}Step 1: Committing changes...${NC}"
git add app/Http/Controllers/Front/PaymentController.php
git add .env-live
git add BKASH_500_ERROR_FIX.md
git commit -m "Fix bKash callback 500 error - Handle failure/cancel statuses"
echo -e "${GREEN}✓ Changes committed${NC}"
echo ""

# Step 2: Push to repository
echo -e "${YELLOW}Step 2: Pushing to repository...${NC}"
git push origin main
echo -e "${GREEN}✓ Pushed to GitHub${NC}"
echo ""

# Step 3: Instructions for live server
echo -e "${YELLOW}Step 3: Deploy on Live Server${NC}"
echo ""
echo "SSH to your live server and run:"
echo ""
echo "  cd /path/to/your/project"
echo "  git pull origin main"
echo "  php artisan config:clear"
echo "  php artisan cache:clear"
echo "  php artisan view:clear"
echo ""

# Step 4: Update .env on live
echo -e "${YELLOW}Step 4: Update Live .env File${NC}"
echo ""
echo "Edit .env on live server and add/update:"
echo ""
echo "BKASH_BASE_URL=https://tokenized.sandbox.bka.sh/v2/"
echo "BKASH_APP_KEY=0vWQuCRGiUX7EPVjQDr0EUAYtc"
echo "BKASH_APP_SECRET=jcUNPBgbcqEDedNKdvE4G1cAK7D3hCjmJccNPZZBq96QIxxwAMEx"
echo "BKASH_USERNAME=01770618567"
echo "BKASH_PASSWORD=D7DaC<*E*eG"
echo "BKASH_CALLBACK_URL=https://www.sajidbeautybd.com/payment/callback/bkash"
echo "BKASH_SANDBOX=true"
echo ""

# Step 5: Testing
echo -e "${YELLOW}Step 5: Test on Live Site${NC}"
echo ""
echo "1. Go to https://www.sajidbeautybd.com"
echo "2. Add product to cart and checkout"
echo "3. Select bKash payment"
echo "4. Test these scenarios:"
echo "   - Complete payment successfully"
echo "   - Cancel payment (no 500 error)"
echo "   - Let payment fail (no 500 error)"
echo ""

echo -e "${GREEN}==================================="
echo "Deployment preparation complete!"
echo "===================================${NC}"
echo ""
echo "Read BKASH_500_ERROR_FIX.md for detailed instructions"
