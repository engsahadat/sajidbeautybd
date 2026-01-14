# bKash 403 Error - "Missing Authentication Token" - SOLUTION

## ❌ Problem
Getting 403 error with message "Missing Authentication Token" when calling bKash token grant API.

## ✅ Root Cause
**Wrong API Version in Base URL**

- ❌ **WRONG:** `BKASH_BASE_URL=https://tokenized.pay.bka.sh/v2/`
- ✅ **CORRECT:** `BKASH_BASE_URL=https://tokenized.pay.bka.sh/v1.2.0-beta/`

The bKash API version should be **`v1.2.0-beta`**, not `v2`.

## 🔧 Solution Applied

### 1. Updated .env Configuration

#### For Sandbox (Testing):
```env
BKASH_BASE_URL=https://tokenized.sandbox.bka.sh/v1.2.0-beta/
BKASH_USERNAME=01770618567
BKASH_PASSWORD=D7DaC<*E*eG
BKASH_APP_KEY=0vWQuCRGiUX7EPVjQDr0EUAYtc
BKASH_APP_SECRET=jcUNPBgbcqEDedNKdvE4G1cAK7D3hCjmJccNPZZBq96QIxxwAMEx
BKASH_SANDBOX=true
```

#### For Production (When Ready):
```env
BKASH_BASE_URL=https://tokenized.pay.bka.sh/v1.2.0-beta/
BKASH_USERNAME=01648022175
BKASH_PASSWORD=9;0#[5c;O6$
BKASH_APP_KEY=UjiE5T5KwURidvvAfm5Ztc
BKASH_APP_SECRET=nQEkgJarQG0VaV2ADHdOcVi6BeHKxtrpsWEAZBK8g6sPYVPTDJX4
BKASH_SANDBOX=false
```

### 2. Clear Caches
```bash
php artisan config:clear
php artisan cache:clear
```

### 3. Test Result
```bash
php test_bkash_v2_auth.php
```

**Output:**
```
✅ Test 1 PASSED - Token received!
Status: 200
Token: eyJraWQiOiJvTVJzNU9ZY0wrUnR...
```

## 📋 Full Endpoint URLs

### Sandbox (Testing):
- Token Grant: `https://tokenized.sandbox.bka.sh/v1.2.0-beta/tokenized/checkout/token/grant`
- Create Payment: `https://tokenized.sandbox.bka.sh/v1.2.0-beta/tokenized/checkout/create`
- Execute Payment: `https://tokenized.sandbox.bka.sh/v1.2.0-beta/tokenized/checkout/execute`
- Query Payment: `https://tokenized.sandbox.bka.sh/v1.2.0-beta/tokenized/checkout/payment/status`

### Production (Live):
- Token Grant: `https://tokenized.pay.bka.sh/v1.2.0-beta/tokenized/checkout/token/grant`
- Create Payment: `https://tokenized.pay.bka.sh/v1.2.0-beta/tokenized/checkout/create`
- Execute Payment: `https://tokenized.pay.bka.sh/v1.2.0-beta/tokenized/checkout/execute`
- Query Payment: `https://tokenized.pay.bka.sh/v1.2.0-beta/tokenized/checkout/payment/status`

## ⚠️ Important Notes for Production

### Before Switching to Production Credentials:

1. **IP Whitelisting Required**
   - Contact bKash support to whitelist your server IP
   - Provide: Your production server IP address
   - Without IP whitelisting, you'll get 403 errors even with correct credentials

2. **Verify Credentials Are Activated**
   - Confirm with bKash that production credentials are activated
   - Test credentials may take 1-2 business days to activate

3. **SSL Certificate Required**
   - Production requires HTTPS
   - Ensure `https://www.sajidbeautybd.com` has valid SSL

4. **Test First**
   - Keep sandbox enabled initially
   - Once confirmed working in sandbox, contact bKash for:
     - IP whitelisting
     - Production credential activation
   - Then switch to production

## 🧪 Testing Steps

### 1. Test Authentication
```bash
php test_bkash_v2_auth.php
```
Expected: ✅ Token received successfully

### 2. Test Payment Creation
```bash
php test_bkash.php
```
Expected: ✅ Payment ID and bKash URL received

### 3. Test Full Payment Flow
1. Go to website
2. Add product to cart
3. Select bKash payment
4. Complete checkout
5. Use test wallet: `01770618575`
6. Use test OTP: `123456`
7. Verify order marked as paid

## 📞 Next Steps for Production

### Contact bKash Support

**Email:** merchantservice@bka.sh

**Request:**
```
Subject: IP Whitelisting Request for Production API

Dear bKash Team,

Merchant: Sajid Beauty BD
Website: https://www.sajidbeautybd.com
App Key: UjiE5T5KwURidvvAfm5Ztc

Please whitelist the following IP address for production API access:
IP Address: [Your Production Server IP]

Please also confirm that our production credentials are activated.

Callback URL: https://www.sajidbeautybd.com/payment/callback/bkash

Thank you.
```

### Find Your Server IP
```bash
# On your production server, run:
curl ifconfig.me

# Or
curl ipinfo.io/ip
```

## ✅ Summary

1. ✅ **Issue Identified:** Wrong API version (`v2` instead of `v1.2.0-beta`)
2. ✅ **Solution Applied:** Updated `.env` to use correct version
3. ✅ **Sandbox Working:** Authentication test passed
4. ⏳ **Production Pending:** Need IP whitelisting from bKash

Once bKash whitelists your IP, simply change `BKASH_SANDBOX=true` to `BKASH_SANDBOX=false` and update the base URL to production in `.env`.
