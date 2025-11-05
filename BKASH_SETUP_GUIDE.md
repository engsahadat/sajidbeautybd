# bKash Payment Gateway Setup Guide

## 🎯 Complete Setup Instructions

### Step 1: Sign Up for bKash Business Account

1. **Visit the bKash Business Registration Link:**
   - Go to: https://business.bkash.com/sign-up/?referrerWallet=01743415871
   - This is the merchant registration portal

2. **Complete Registration:**
   - Fill in your business details
   - Provide required documents (Trade License, NID, etc.)
   - Submit application

3. **Wait for Approval:**
   - bKash will review your application
   - You'll receive credentials via email once approved

---

### Step 2: Get Your API Credentials

After approval, you'll receive:
- **App Key** (Username)
- **App Secret** (Password)  
- **Username**
- **Password**
- **Merchant Number**

These are sent to your registered email by bKash.

---

### Step 3: Configure Your .env File

Open your `.env` file and add/update these values:

```env
# bKash Payment Gateway Configuration
BKASH_APP_KEY=your_app_key_here
BKASH_APP_SECRET=your_app_secret_here
BKASH_USERNAME=your_username_here
BKASH_PASSWORD=your_password_here

# Sandbox Mode (true for testing, false for production)
BKASH_SANDBOX=true

# Payment Demo Mode (false to enable real payments)
PAYMENT_DEMO=false
```

**Important:**
- Keep `BKASH_SANDBOX=true` while testing
- Change to `BKASH_SANDBOX=false` when going live
- Set `PAYMENT_DEMO=false` to enable real payment processing

---

### Step 4: Clear Cache

Run these commands in your terminal:

```bash
cd c:/xampp/htdocs/sajidbeautybd
php artisan config:clear
php artisan cache:clear
php artisan view:clear
```

---

### Step 5: Test bKash Integration

#### Sandbox Testing:
1. Go to your checkout page
2. Select bKash as payment method
3. Use bKash sandbox credentials for testing:
   - **Test Wallet:** 01770618575
   - **OTP:** 123456

#### Test Flow:
```
Customer Checkout 
   ↓
Select bKash Payment
   ↓
Redirect to bKash Payment Page
   ↓
Enter bKash Number & OTP
   ↓
Payment Confirmation
   ↓
Redirect Back to Success Page
```

---

### Step 6: Go Live

When ready for production:

1. **Update .env:**
   ```env
   BKASH_SANDBOX=false
   PAYMENT_DEMO=false
   ```

2. **Use Production Credentials:**
   - Replace test credentials with live credentials from bKash
   - Ensure App Key, App Secret, Username, Password are from production

3. **Clear Cache Again:**
   ```bash
   php artisan config:cache
   php artisan cache:clear
   ```

4. **Test with Real Money:**
   - Make a small test transaction
   - Verify payment flows correctly
   - Check order status updates

---

## 🔧 How It Works (Already Implemented)

### Your System Has:

✅ **BkashService** (`app/Services/PaymentGateway/BkashService.php`)
   - Token generation with caching
   - Payment creation
   - Payment execution
   - Payment query/verification

✅ **PaymentController** (`app/Http/Controllers/Front/PaymentController.php`)
   - Initiates bKash payments
   - Handles callbacks
   - Verifies transactions
   - Updates order status

✅ **Configuration** (`config/payment.php`)
   - bKash settings
   - Sandbox/Production URLs
   - Callback URLs

✅ **Routes** (`routes/web.php`)
   - Payment initiation route
   - Callback route

### Payment Flow:

```
1. Customer places order
2. Selects bKash as payment method
3. System calls BkashService->initiate()
4. Customer redirected to bKash payment page
5. Customer enters bKash number and confirms
6. bKash redirects to callback URL
7. System calls BkashService->execute()
8. Payment verified and order updated
9. Customer sees success page
```

---

## 🎨 Frontend Integration

Your checkout page should have a bKash payment option. If not, add this to your checkout form:

```html
<div class="form-check">
    <input class="form-check-input" type="radio" name="payment_method" 
           id="payment_bkash" value="bkash" required>
    <label class="form-check-label" for="payment_bkash">
        <img src="/images/bkash-logo.png" alt="bKash" height="30">
        bKash Payment
    </label>
</div>
```

---

## 📊 Admin Panel - View Payments

Payments are automatically recorded in the database:
- Go to: **Admin → Sales → Transactions**
- View all bKash payments
- Check transaction IDs
- Monitor payment statuses

---

## 🐛 Troubleshooting

### Issue: "Failed to authenticate with bKash"
**Solution:** 
- Check if credentials are correct in .env
- Verify BKASH_SANDBOX setting matches your credentials type
- Clear config cache

### Issue: "Payment creation failed"
**Solution:**
- Ensure amount is in correct format (BDT)
- Check order has valid total_amount
- Review Laravel logs: `storage/logs/laravel.log`

### Issue: "Callback not working"
**Solution:**
- Verify APP_URL in .env is correct
- Check callback URL is accessible
- Ensure no middleware blocking callback route

### Issue: Token expired
**Solution:**
- Token is cached for 1 hour automatically
- If still issues, clear cache: `php artisan cache:clear`

---

## 📞 bKash Support

- **Merchant Helpline:** 16247
- **Email:** merchant@bkash.com
- **Developer Portal:** https://developer.bka.sh/
- **API Documentation:** https://developer.bka.sh/reference

---

## 🔒 Security Notes

✅ **Never commit credentials to Git**
   - Keep .env in .gitignore
   - Use environment variables

✅ **Use HTTPS in Production**
   - bKash requires secure callbacks
   - Update APP_URL to https://

✅ **Validate Callbacks**
   - Already implemented in PaymentController
   - Verifies transaction with bKash API

✅ **Log Everything**
   - Payment attempts logged
   - Failed transactions logged
   - Check logs regularly

---

## ✅ Checklist

- [ ] Signed up at https://business.bkash.com/sign-up/?referrerWallet=01743415871
- [ ] Received bKash credentials via email
- [ ] Updated .env with credentials
- [ ] Set BKASH_SANDBOX=true for testing
- [ ] Set PAYMENT_DEMO=false
- [ ] Cleared config cache
- [ ] Tested with sandbox wallet
- [ ] Verified payment flow works
- [ ] Ready to go live with BKASH_SANDBOX=false

---

## 🎉 You're All Set!

Your bKash integration is **already coded and ready**. Just add your credentials and test!

**Quick Start Command:**
```bash
php artisan tinker
# Test if config loads:
config('payment.bkash')
```

For any issues, check `storage/logs/laravel.log` for detailed error messages.
