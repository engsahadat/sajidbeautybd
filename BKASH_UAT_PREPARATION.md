# ✅ bKash UAT (User Acceptance Testing) Preparation Guide

## 📋 Overview
bKash Production integration সম্পন্ন হয়েছে। এখন UAT (User Acceptance Testing) করতে হবে।

---

## 🎯 bKash এর Requirements (ইমেইল থেকে)

### পরবর্তী পদক্ষেপসমূহ:

#### ১. Production এ PGW সেটআপ (কিন্তু লুকান)
- ✅ Production server এ বিকাশ পেমেন্ট চালু করুন
- ⚠️ সাধারণ কাস্টমারদের দেখাবেন না
- ⚠️ বিকাশ এর go-ahead না পাওয়া পর্যন্ত লুকিয়ে রাখুন

**Implementation:**
```php
// config/payment.php এ যোগ করুন:
'bkash_visible' => env('BKASH_VISIBLE_TO_PUBLIC', false),

// Payment option blade file এ:
@if(config('payment.bkash_visible') || auth()->user()?->is_test_user)
    <!-- Show bKash option -->
@endif
```

#### ২. টেস্ট ইউজার ও প্রোডাক্ট তৈরি করুন
- [ ] একটি টেস্ট ইউজার একাউন্ট তৈরি করুন
- [ ] টেস্ট পণ্য তৈরি করুন (বিভিন্ন দামের)
- [ ] প্রোডাক্ট লিংক এবং ইউজার credential বিকাশকে পাঠান

**Required Test Products:**
```
1. Product A: ৫০০ টাকা (duplicate test এর জন্য)
2. Product B: ৫০০ টাকা (duplicate test এর জন্য - একই দাম)
3. Product C: ১০,০০০ টাকা (insufficient balance test এর জন্য)
4. Product D: ১০০ টাকা (সাধারণ test এর জন্য)
```

**Test User Setup:**
```
Email: test@sajidbeautybd.com
Password: Test@123456
Name: Test User
Phone: 01XXXXXXXXX
```

#### ৩. Key Account Manager এর সাথে যোগাযোগ
- [ ] UAT সিডিউল করতে KAM এর সাথে যোগাযোগ করুন
- [ ] Business UAT এর তারিখ ঠিক করুন
- [ ] Technical UAT এর তারিখ ঠিক করুন

---

## 🧪 UAT Test Cases (২টি UAT হবে)

### A. Business UAT (স্ক্রিন শেয়ার করে দেখাতে হবে)

#### Test Case 1: Successful Payment ✅
**Steps:**
1. Test product select করুন (১০০ টাকা)
2. Checkout এ যান
3. bKash payment method select করুন
4. bKash এ পেমেন্ট সম্পূর্ণ করুন (সঠিক OTP দিন)
5. Success page দেখান

**Expected Result:**
- Payment successful
- Order confirmed
- Transaction ID পাওয়া যাবে

---

#### Test Case 2: Duplicate Transaction Check ⚠️ (MANDATORY)
**Purpose:** একই পেমেন্ট দুইবার হওয়া থেকে রক্ষা করা

**Steps:**
1. Product A select করুন (৫০০ টাকা)
2. bKash দিয়ে পেমেন্ট সফলভাবে সম্পূর্ণ করুন
3. **২ মিনিটের মধ্যে** Product B select করুন (৫০০ টাকা - একই দাম)
4. **একই বিকাশ নম্বর** দিয়ে আবার পেমেন্ট করার চেষ্টা করুন

**Expected Result:**
- bKash error দেখাবে: "Duplicate transaction" (Error 2029)
- পেমেন্ট block হবে
- User-friendly error message দেখাবে

**Implementation Status:** ✅ Already implemented
```php
// BkashErrorHandler.php - Error 2029 handled
'2029' => 'Duplicate payment detected. Please wait 2 minutes before trying again.',
```

---

#### Test Case 3: Insufficient Balance
**Purpose:** পর্যাপ্ত টাকা না থাকলে কি হয়

**Steps:**
1. আপনার test bKash wallet এ কম টাকা রাখুন (যেমন: ১০০ টাকা)
2. Product C select করুন (১০,০০০ টাকা)
3. bKash দিয়ে পেমেন্ট করার চেষ্টা করুন

**Expected Result:**
- bKash error দেখাবে: "Insufficient balance"
- Payment failed message
- User বুঝতে পারবে যে balance কম

**Implementation Status:** ✅ Already implemented
```php
// BkashErrorHandler.php - Error 2019 handled
'2019' => 'Insufficient account balance. Please add funds to your bKash account.',
```

---

#### Test Case 4: Failed Cases
**A. Wrong OTP:**
**Steps:**
1. কোনো product select করুন
2. bKash payment initiate করুন
3. **ভুল OTP** দিন

**Expected Result:**
- Error: "Wrong PIN/OTP"
- User can retry

**Implementation Status:** ✅ Already implemented
```php
// BkashErrorHandler.php
'2015' => 'Incorrect PIN entered. Please try again with correct PIN.',
'2017' => 'Invalid OTP provided. Please check and enter correct OTP.',
```

**B. Cancel Payment:**
**Steps:**
1. Product select করুন
2. bKash payment start করুন
3. bKash page এ **Cancel** button press করুন

**Expected Result:**
- Message: "Payment Cancelled. You cancelled the payment."
- User checkout page এ ফিরে যাবে

**Implementation Status:** ✅ Already implemented
```php
// PaymentController.php - Line 125
if ($status === 'cancel') {
    return redirect()->route('checkout.show')
        ->with('warning', 'Payment Cancelled. You cancelled the payment.');
}
```

---

### B. Technical UAT
bKash এর technical team আপনার API implementation verify করবে:

#### Checklist:
- ✅ All 5 APIs implemented (Grant Token, Create, Execute, Query, Search)
- ✅ 30-second timeout handling (NEW - Just implemented)
- ✅ Query Payment API called on timeout (MANDATORY)
- ✅ Duplicate transaction prevention (Error 2029)
- ✅ All error codes handled (60+ codes)
- ✅ Proper callback URLs (success, failure, cancel)
- ✅ Logging enabled
- ✅ HTTPS enabled on production

---

## 🚨 CRITICAL: Timeout Handling (MANDATORY)

### bKash Requirement:
> "Every bKash API timeout is 30 sec. When you get no response within 30 sec from Execute Payment API, then you will have to call Query Payment API with payment ID."

### Implementation Status: ✅ **JUST IMPLEMENTED**

#### How it works:
1. Execute Payment API called with 30-second timeout
2. If timeout occurs (ConnectionException):
   - Query Payment API automatically called
   - Check transaction status
3. Response handling:
   - `Completed` = Payment successful ✅
   - `Initiated` = Not completed yet ⏳
   - Other = Failed ❌

#### Code Location:
```
File: app/Services/PaymentGateway/BkashService.php
Method: execute()
Lines: 182-305
```

#### Log Messages:
```
[WARNING] bKash Execute Payment API timeout - Calling Query Payment API (Mandatory)
[INFO] Query Payment API response after timeout
[INFO] Payment still initiated after timeout - not completed at bKash
```

---

## 📝 UAT Preparation Checklist

### Before UAT:
- [ ] Deploy to production server
- [ ] Set BKASH_VISIBLE_TO_PUBLIC=false in .env
- [ ] Create test user account
- [ ] Create 4 test products (different prices)
- [ ] Test all scenarios in staging first
- [ ] Prepare screen sharing setup
- [ ] Keep bKash test wallet ready
- [ ] Keep product links ready
- [ ] Keep user credentials ready

### During Business UAT:
- [ ] Share screen
- [ ] Show successful payment
- [ ] Show duplicate transaction error
- [ ] Show insufficient balance error
- [ ] Show wrong OTP error
- [ ] Show cancel payment
- [ ] Answer bKash team questions

### After UAT:
- [ ] Get approval from bKash business team
- [ ] Get approval from bKash technical team
- [ ] Wait for go-ahead email
- [ ] Set BKASH_VISIBLE_TO_PUBLIC=true
- [ ] Enable bKash for all customers

---

## 📧 Email Template for bKash

**Subject:** UAT Ready - Sajid Beauty BD

**Body:**
```
Dear bKash Team,

We have successfully completed the production integration and are ready for UAT.

Test Environment Details:
- Website URL: https://sajidbeautybd.com
- Test User Email: test@sajidbeautybd.com
- Test User Password: Test@123456

Test Product Links:
1. Product A (500 BDT): [URL]
2. Product B (500 BDT): [URL] 
3. Product C (10,000 BDT): [URL]
4. Product D (100 BDT): [URL]

Implementation Status:
✅ All 5 bKash APIs integrated
✅ 30-second timeout handling with Query Payment API fallback
✅ Duplicate transaction detection
✅ All error codes handled
✅ Success/Failure/Cancel callbacks
✅ HTTPS enabled

We are ready for both Business UAT and Technical UAT at your convenience.

Please let us know your available schedule.

Best regards,
Sajid Beauty BD
```

---

## 🔧 Quick Commands

### Deploy to Production:
```bash
# Upload files
git add .
git commit -m "bKash UAT ready with timeout handling"
git push origin main

# On server
php artisan config:clear
php artisan cache:clear
php artisan route:clear
```

### Test Timeout Handling (Staging):
```php
// Temporarily set timeout to 2 seconds for testing
'timeout' => 2,

// Then test a payment - should trigger Query Payment API
```

### Check Logs:
```bash
tail -f storage/logs/laravel.log | grep bKash
```

---

## 📞 Support Contact

- **Key Account Manager:** [Your KAM Name]
- **bKash Support:** merchant.support@bkash.com
- **Technical Issue:** [Your bKash technical contact]

---

## ✅ Final Status

### Implemented Features:
1. ✅ All 5 bKash APIs
2. ✅ **30-second timeout with Query Payment fallback (MANDATORY)**
3. ✅ Duplicate transaction prevention (Error 2029)
4. ✅ Insufficient balance handling (Error 2019)
5. ✅ Wrong OTP handling (Errors 2015, 2017)
6. ✅ Cancel payment handling
7. ✅ 60+ error codes with user-friendly messages
8. ✅ Success/Failure/Cancel callbacks
9. ✅ Comprehensive logging
10. ✅ Race condition prevention

### Ready for UAT: ✅ YES

---

**Last Updated:** January 11, 2026
**Status:** UAT Ready 🎉
