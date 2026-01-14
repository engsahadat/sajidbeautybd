# 🚀 bKash UAT - Quick Reference (বাংলা)

## ✅ সম্পন্ন হয়েছে (Implementation Complete)

### 🔧 Timeout Handling (MANDATORY - এইমাত্র যোগ করা হয়েছে)
```
✅ Execute Payment API - 30 second timeout
✅ Timeout detection - ConnectionException catch
✅ Auto Query Payment API call - যদি timeout হয়
✅ Status check:
   - "Completed" = পেমেন্ট সফল
   - "Initiated" = এখনো সম্পূর্ণ হয়নি
   - অন্যান্য = Failed
```

**কোড লোকেশন:**
- File: `app/Services/PaymentGateway/BkashService.php`
- Method: `execute()`
- Lines: 182-305

---

## 📋 UAT Test Cases চেকলিস্ট

### ১. Successful Payment ✅
- [x] কোড লেখা হয়েছে
- [ ] UAT এ টেস্ট করতে হবে
- **Expected:** Payment successful, order confirmed

### ২. Duplicate Transaction ✅
- [x] কোড লেখা হয়েছে (Error 2029)
- [ ] UAT এ টেস্ট করতে হবে
- **Expected:** "Duplicate payment detected" error
- **Note:** ২ মিনিটের মধ্যে একই amount, একই wallet

### ৩. Insufficient Balance ✅
- [x] কোড লেখা হয়েছে (Error 2019)
- [ ] UAT এ টেস্ট করতে হবে
- **Expected:** "Insufficient account balance" error

### ৪. Wrong OTP ✅
- [x] কোড লেখা হয়েছে (Error 2015, 2017)
- [ ] UAT এ টেস্ট করতে হবে
- **Expected:** "Incorrect PIN/OTP" error

### ৫. Cancel Payment ✅
- [x] কোড লেখা হয়েছে
- [ ] UAT এ টেস্ট করতে হবে
- **Expected:** "Payment Cancelled" message

### ৬. Timeout Handling ✅ (NEW)
- [x] কোড লেখা হয়েছে (আজই)
- [ ] UAT এ টেস্ট করতে হবে
- **Expected:** Query Payment API automatically call হবে

---

## 🎯 এখন কি করতে হবে

### ১. টেস্ট ইউজার ও প্রোডাক্ট তৈরি করুন
```bash
# Admin panel এ যান এবং তৈরি করুন:

Test User:
- Email: test@sajidbeautybd.com
- Password: Test@123456
- Phone: 01XXXXXXXXX

Test Products:
1. Product A - ৫০০ টাকা
2. Product B - ৫০০ টাকা (duplicate test এর জন্য)
3. Product C - ১০,০০০ টাকা (insufficient balance test)
4. Product D - ১০০ টাকা (সাধারণ test)
```

### ২. Production এ Deploy করুন
```bash
# Local থেকে push করুন:
git add .
git commit -m "bKash UAT ready with mandatory timeout handling"
git push origin main

# Server এ:
cd /path/to/sajidbeautybd
git pull origin main
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
```

### ৩. .env Check করুন
```env
# Production .env এ confirm করুন:
BKASH_APP_KEY=your_production_app_key
BKASH_APP_SECRET=your_production_app_secret
BKASH_USERNAME=your_production_username
BKASH_PASSWORD=your_production_password
BKASH_BASE_URL=https://tokenized.pay.bka.sh/v2/
BKASH_TIMEOUT=30

# bKash লুকিয়ে রাখতে:
BKASH_VISIBLE_TO_PUBLIC=false
```

### ৪. bKash কে ইমেইল পাঠান
```
Subject: UAT Ready - Sajid Beauty BD

Dear bKash Team,

We are ready for UAT.

Website: https://sajidbeautybd.com
Test User: test@sajidbeautybd.com
Password: Test@123456

Test Products:
- Product A (500 BDT): [Link]
- Product B (500 BDT): [Link]
- Product C (10000 BDT): [Link]
- Product D (100 BDT): [Link]

✅ All 5 APIs implemented
✅ Timeout handling with Query Payment API
✅ Duplicate transaction prevention
✅ All error codes handled

Ready for Business & Technical UAT.

Please schedule at your convenience.

Best regards,
Sajid Beauty BD
```

---

## 🧪 UAT এ কি দেখাতে হবে

### Screen Share করে:

**Test 1: Successful Payment**
1. Product D (100 BDT) cart এ add করুন
2. Checkout → bKash select
3. Payment করুন (সঠিক PIN দিন)
4. Success page দেখান

**Test 2: Duplicate Transaction**
1. Product A (500 BDT) দিয়ে payment করুন
2. Success হওয়ার পর **২ মিনিটের মধ্যে**
3. Product B (500 BDT) দিয়ে আবার payment করুন
4. **একই bKash number** দিয়ে
5. Error দেখাবে: "Duplicate payment detected"

**Test 3: Insufficient Balance**
1. Product C (10000 BDT) select করুন
2. Wallet এ কম টাকা রাখুন (100 BDT)
3. Payment try করুন
4. Error দেখাবে: "Insufficient balance"

**Test 4: Wrong OTP**
1. কোনো product select করুন
2. Payment শুরু করুন
3. ভুল PIN দিন
4. Error দেখাবে: "Incorrect PIN"

**Test 5: Cancel Payment**
1. Product select করুন
2. Payment শুরু করুন
3. bKash page এ Cancel button press করুন
4. Message দেখাবে: "Payment Cancelled"

---

## 📊 যা যা Implementation করা হয়েছে

### APIs (5/5) ✅
1. ✅ Grant Token API
2. ✅ Create Payment API
3. ✅ Execute Payment API
4. ✅ Query Payment API
5. ✅ Search Transaction API

### Error Handling (60+ codes) ✅
- ✅ 2001-2999: Payment errors
- ✅ 2015, 2017: Wrong OTP
- ✅ 2019: Insufficient balance
- ✅ 2029: Duplicate transaction
- ✅ All errors with user-friendly messages

### Timeout Handling ✅ (NEW - Today)
- ✅ 30-second timeout detection
- ✅ Auto Query Payment API call
- ✅ Status verification (Completed/Initiated)
- ✅ Comprehensive logging

### Callbacks ✅
- ✅ Success callback - Order processing
- ✅ Failure callback - Error message
- ✅ Cancel callback - Cancellation message

### Security ✅
- ✅ Duplicate transaction prevention
- ✅ Race condition handling
- ✅ Database locking
- ✅ Status verification

---

## 🔍 Log Check করার কমান্ড

```bash
# Real-time log দেখতে:
tail -f storage/logs/laravel.log | grep bKash

# Timeout related logs খুঁজতে:
grep "timeout" storage/logs/laravel.log

# Query Payment logs খুঁজতে:
grep "Query Payment" storage/logs/laravel.log

# আজকের logs দেখতে:
tail -n 500 storage/logs/laravel-$(date +%Y-%m-%d).log
```

---

## ⚠️ UAT এর আগে চেক করুন

- [ ] Production server এ deploy হয়েছে?
- [ ] .env এ সব credentials সঠিক?
- [ ] Test user account তৈরি হয়েছে?
- [ ] ৪টা test products তৈরি হয়েছে?
- [ ] HTTPS enabled আছে?
- [ ] bKash option লুকানো আছে (BKASH_VISIBLE_TO_PUBLIC=false)?
- [ ] Test bKash wallet এ টাকা আছে?
- [ ] Screen sharing software ready?
- [ ] Product links copy করা আছে?
- [ ] Test user credentials copy করা আছে?

---

## 📞 যোগাযোগ

**UAT Schedule করতে:**
- Key Account Manager এর সাথে যোগাযোগ করুন
- Business UAT এর date/time ঠিক করুন
- Technical UAT এর date/time ঠিক করুন

**Support:**
- bKash Merchant Support: merchant.support@bkash.com
- Technical Issues: [Your technical contact]

---

## 🎉 Final Status

```
✅ All 5 APIs implemented
✅ Timeout handling (MANDATORY) - Just Added Today
✅ Query Payment fallback
✅ Duplicate transaction detection
✅ All error codes handled
✅ Success/Failure/Cancel callbacks
✅ Security measures
✅ Comprehensive logging

🚀 Ready for UAT!
```

---

**তৈরি:** ১১ জানুয়ারি, ২০২৬
**স্ট্যাটাস:** UAT এর জন্য সম্পূর্ণ প্রস্তুত ✅

---

## 📝 Important Notes

1. **Timeout Handling** আজই implement করা হয়েছে - এটা bKash এর **mandatory** requirement
2. Query Payment API **automatically** call হবে যদি Execute Payment timeout হয়
3. **2 minutes** এর মধ্যে duplicate transaction block করবে
4. **60+ error codes** সব handle করা আছে
5. UAT pass করলে **BKASH_VISIBLE_TO_PUBLIC=true** করে সবার জন্য চালু করতে হবে
