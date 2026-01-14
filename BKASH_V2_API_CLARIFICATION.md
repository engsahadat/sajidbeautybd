# bKash API Version - IMPORTANT CLARIFICATION

## ❌ Common Misconception
**"v2 API"** does not mean the URL path should be `/v2/`

## ✅ The Truth About bKash API Versions

### What bKash Calls "v2" or "Version 2":
bKash's **PGW Tokenized Checkout API Version 2** uses the URL path: **`v1.2.0-beta`**

This is confusing but it's how bKash named their API versions.

### Test Results (Proof):

#### ❌ Using /v2/ path:
```
URL: https://tokenized.pay.bka.sh/v2/tokenized/checkout/token/grant
Result: 403 "Missing Authentication Token"
Reason: This endpoint DOES NOT EXIST
```

#### ✅ Using /v1.2.0-beta/ path:
```
URL: https://tokenized.pay.bka.sh/v1.2.0-beta/tokenized/checkout/token/grant  
Result: 200 "App key does not exist"
Reason: Endpoint EXISTS, credentials need activation by bKash
```

### From Your bKash Credentials Screenshot:
```
Payment Gateway Production API context root:
https://tokenized.pay.bka.sh/v1.2.0-beta
```

## ✅ Correct Configuration

### .env File:
```env
BKASH_BASE_URL=https://tokenized.pay.bka.sh/v1.2.0-beta/
```

**NOT:**
```env
BKASH_BASE_URL=https://tokenized.pay.bka.sh/v2/  ❌ This does NOT exist
```

## 📊 Summary

| What You Call It | Actual URL Path | Status |
|-----------------|----------------|--------|
| "v2 API" | `/v1.2.0-beta/` | ✅ Exists |
| "v2 API" | `/v2/` | ❌ Does NOT exist |

## 🔧 Current Status

✅ **Configuration Fixed**  
✅ **Correct URL Set**: `https://tokenized.pay.bka.sh/v1.2.0-beta/`  
⏳ **Waiting For**: bKash to activate your production credentials

### Next Step:
Contact bKash support to activate your production credentials:
- Email: merchantservice@bka.sh
- Request: Activate App Key `UjiE5T5KwURidvvAfm5Ztc`

Once activated, the "App key does not exist" error will be resolved and you'll receive tokens successfully.

## 🎯 Bottom Line

**You ARE using the v2 API** - it just happens to be at the path `/v1.2.0-beta/` (that's how bKash named it).

There is NO `/v2/` endpoint on bKash servers.
