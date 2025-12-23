# bKash Payment Integration - Complete API Implementation

## Overview
This document describes the complete implementation of bKash Tokenized Checkout API v1.2.0-beta for the Sajid Beauty BD e-commerce platform.

## API Endpoints Implemented

### 1. Grant Token API
**Purpose:** Authenticate with bKash and obtain an access token for API calls.

**Endpoint:** `POST /tokenized/checkout/token/grant`

**Implementation:** `BkashService::getToken()`

**Request Headers:**
```
Content-Type: application/json
Accept: application/json
username: your_bkash_username
password: your_bkash_password
```

**Request Body:**
```json
{
  "app_key": "your_app_key",
  "app_secret": "your_app_secret"
}
```

**Response (Success):**
```json
{
  "statusCode": "0000",
  "statusMessage": "Successful",
  "id_token": "eyJraWQiOiJvTVJzNU9ZY0wrUnR...",
  "expires_in": 3600,
  "token_type": "Bearer",
  "refresh_token": "eyJjdHkiOiJKV1QiLCJlbmM..."
}
```

**Features:**
- Token is cached for 1 hour (based on `expires_in` value)
- Automatic re-authentication when token expires
- Comprehensive error logging

---

### 2. Create Payment API
**Purpose:** Initialize a payment and get bKash URL for customer to complete payment.

**Endpoint:** `POST /tokenized/checkout/create`

**Implementation:** `BkashService::initiate(Order $order)`

**Request Body:**
```json
{
  "mode": "0011",
  "payerReference": "customer_phone",
  "callbackURL": "https://yoursite.com/payment/callback/bkash?order_id=123",
  "amount": "1499.00",
  "currency": "BDT",
  "intent": "sale",
  "merchantInvoiceNumber": "ORD20250001"
}
```

**Response (Success):**
```json
{
  "statusCode": "0000",
  "statusMessage": "Successful",
  "paymentID": "TR0011DV1567529737718",
  "bkashURL": "https://sandbox.payment.bkash.com/redirect/tokenized/?paymentID=...",
  "callbackURL": "https://yoursite.com/payment/callback/bkash?order_id=123",
  "successCallbackURL": "...&status=success",
  "failureCallbackURL": "...&status=failure",
  "cancelledCallbackURL": "...&status=cancel",
  "amount": "1499",
  "intent": "sale",
  "currency": "BDT",
  "paymentCreateTime": "2019-09-03T22:55:37:813 GMT+0600",
  "transactionStatus": "Initiated",
  "merchantInvoiceNumber": "ORD20250001"
}
```

**Features:**
- Automatic callback URL generation with order ID
- Proper amount formatting (2 decimal places)
- Returns bKash payment URL for redirect

---

### 3. Execute Payment API
**Purpose:** Complete the payment after customer approves it on bKash app/website.

**Endpoint:** `POST /tokenized/checkout/execute`

**Implementation:** `BkashService::execute(string $paymentId)`

**Request Body:**
```json
{
  "paymentID": "TR0011DV1567529737718"
}
```

**Response (Success):**
```json
{
  "statusCode": "0000",
  "statusMessage": "Successful",
  "paymentID": "TR0011DV1567529737718",
  "payerReference": "customer_phone",
  "customerMsisdn": "01770618575",
  "trxID": "6I3801RD1Q",
  "amount": "1499",
  "transactionStatus": "Completed",
  "paymentExecuteTime": "2019-09-03T23:00:58:366 GMT+0600",
  "currency": "BDT",
  "intent": "sale",
  "merchantInvoiceNumber": "ORD20250001"
}
```

**Features:**
- Verifies payment completion
- Returns transaction ID (trxID) for records
- Comprehensive error handling
- Called automatically by callback handler

---

### 4. Query Payment Status API
**Purpose:** Check the status of a payment using paymentID.

**Endpoint:** `POST /tokenized/checkout/payment/status`

**Implementation:** `BkashService::queryPayment(string $paymentId)`

**Request Body:**
```json
{
  "paymentID": "TR0011DV1567529737718"
}
```

**Response (Success):**
```json
{
  "statusCode": "0000",
  "statusMessage": "Successful",
  "paymentID": "TR0011DV1567529737718",
  "mode": "0011",
  "paymentCreateTime": "2019-09-03T22:55:37:813 GMT+0600",
  "paymentExecuteTime": "2019-09-03T23:00:58:366 GMT+0600",
  "amount": "1499",
  "currency": "BDT",
  "intent": "sale",
  "merchantInvoice": "ORD20250001",
  "trxID": "6I3801RD1Q",
  "transactionStatus": "Completed",
  "verificationStatus": "Complete",
  "payerReference": "customer_phone"
}
```

**Features:**
- Query payment status at any time
- Useful for reconciliation and verification
- Returns complete payment information

---

### 5. Search Transaction API
**Purpose:** Search for a transaction using bKash transaction ID (trxID).

**Endpoint:** `POST /tokenized/checkout/general/searchTransaction`

**Implementation:** `BkashService::searchTransaction(string $trxId)`

**Request Body:**
```json
{
  "trxID": "6I3801RD1Q"
}
```

**Response (Success):**
```json
{
  "statusCode": "0000",
  "statusMessage": "Successful",
  "trxID": "6I3801RD1Q",
  "paymentID": "TR0011DV1567529737718",
  "transactionStatus": "Completed",
  "amount": "1499",
  "currency": "BDT",
  "intent": "sale",
  "merchantInvoice": "ORD20250001",
  "mode": "0011",
  "initiationTime": "2019-09-03T22:55:37:813 GMT+0600",
  "completedTime": "2019-09-03T23:00:58:366 GMT+0600"
}
```

**Response (Failure):**
```json
{
  "statusCode": "2003",
  "statusMessage": "Process Failed"
}
```

**Features:**
- Search by transaction ID
- Useful for customer support queries
- Note: May return "Process Failed" for recent or non-existent transactions

---

## Configuration

### Environment Variables (.env)
```env
# bKash Configuration
BKASH_BASE_URL=https://tokenized.sandbox.bka.sh/v1.2.0-beta/
BKASH_USERNAME=your_bkash_username
BKASH_PASSWORD=your_bkash_password
BKASH_APP_KEY=4f6o0cjiki2rfm34kfdadl1eqq
BKASH_APP_SECRET=2is7hdktrekvrbljjh44ll3d9l1dtjo4pasmjvs5vl5qr3fug4b
BKASH_CALLBACK_URL=https://www.sajidbeautybd.com/payment/callback/bkash
BKASH_SANDBOX=true
BKASH_TIMEOUT=30
```

**Important:** All four credentials are required:
- `username` and `password` - Used in request headers for authentication
- `app_key` and `app_secret` - Used in request body for token generation

### Production Configuration
For production, change:
```env
BKASH_BASE_URL=https://tokenized.pay.bka.sh/v1.2.0-beta/
BKASH_USERNAME=your_production_username
BKASH_PASSWORD=your_production_password
BKASH_APP_KEY=your_production_app_key
BKASH_APP_SECRET=your_production_app_secret
BKASH_SANDBOX=false
```

---

## Payment Flow

### Customer Journey:
1. **Checkout**: Customer selects bKash as payment method
2. **Create Payment**: System calls `initiate()` to create payment
3. **Redirect**: Customer redirected to bKash payment page
4. **Authorize**: Customer authorizes payment in bKash app
5. **Callback**: bKash redirects back with status (success/failure/cancel)
6. **Execute**: System calls `execute()` to complete payment
7. **Confirmation**: Order confirmed and customer notified

### Status Flow Diagram:
```
[Order Created]
      ↓
[Create Payment] → paymentID generated → transactionStatus: "Initiated"
      ↓
[Customer Redirected to bKash]
      ↓
[Customer Authorizes Payment]
      ↓
[Callback with status=success]
      ↓
[Execute Payment] → trxID generated → transactionStatus: "Completed"
      ↓
[Order Completed]
```

---

## Callback Handling

The system handles three callback scenarios:

### 1. Success Callback
**URL Format:** `https://yoursite.com/payment/callback/bkash?order_id=123&paymentID=TR00...&status=success`

**Action:**
- Execute payment using paymentID
- Verify transaction completion
- Update order status to paid
- Send confirmation email/SMS
- Clear shopping cart

### 2. Failure Callback
**URL Format:** `https://yoursite.com/payment/callback/bkash?order_id=123&paymentID=TR00...&status=failure`

**Action:**
- Log failure reason
- Redirect to checkout page
- Show error message
- Allow customer to retry

### 3. Cancel Callback
**URL Format:** `https://yoursite.com/payment/callback/bkash?order_id=123&paymentID=TR00...&status=cancel`

**Action:**
- Log cancellation
- Redirect to checkout page
- Show cancellation message
- Allow customer to retry

---

## Error Handling

### Common Status Codes:
- `0000`: Successful
- `2001`: Insufficient balance
- `2003`: Process failed
- `2004`: Invalid merchant
- `2005`: Invalid app key
- `2006`: Invalid app secret
- `2007`: Invalid token
- `2008`: Payment already executed
- `2009`: Payment expired
- `2010`: Payment cancelled

### Error Response Format:
```json
{
  "statusCode": "2005",
  "statusMessage": "Invalid App Key",
  "errorMessage": "The provided app key is invalid"
}
```

---

## Testing

### Sandbox Credentials
Use these credentials for testing:
```
Username: (provided by bKash)
Password: (provided by bKash)
App Key: 4f6o0cjiki2rfm34kfdadl1eqq
App Secret: 2is7hdktrekvrbljjh44ll3d9l1dtjo4pasmjvs5vl5qr3fug4b
Base URL: https://tokenized.sandbox.bka.sh/v1.2.0-beta/
```

**Note:** The username and password for sandbox are provided separately by bKash when you register for sandbox access.

### Test Payment
1. Set `BKASH_SANDBOX=true` in .env
2. Use sandbox credentials
3. Create a test order
4. Complete payment in sandbox environment
5. Verify order status updates correctly

### Useful Testing Routes:
- Initiate payment: `/payment/initiate/bkash/{order_id}`
- Callback handler: `/payment/callback/bkash`

---

## Security Best Practices

1. **Token Caching**: Tokens are cached to reduce API calls and improve performance
2. **HTTPS Required**: Always use HTTPS for callback URLs in production
3. **Callback Verification**: Payment is executed and verified before order confirmation
4. **Error Logging**: All errors are logged for debugging and auditing
5. **Timeout Configuration**: API timeout set to 30 seconds to handle network issues
6. **Credential Protection**: Never commit credentials to version control

---

## Additional Features

### Refund Support
The service includes refund functionality (if needed):
```php
$bkashService->refund($paymentId, $trxId, $amount, $reason);
```

### Query Refund Status
```php
$bkashService->queryRefund($paymentId, $trxId);
```

---

## Troubleshooting

### Issue: Token not generated
**Solution:**
- Verify app_key and app_secret are correct
- Check BKASH_BASE_URL is correct for environment
- Review logs in `storage/logs/laravel.log`

### Issue: Payment creation fails
**Solution:**
- Ensure callback URL is HTTPS in production
- Verify amount format (2 decimal places, string)
- Check merchantInvoiceNumber is unique

### Issue: Payment execution fails
**Solution:**
- Verify paymentID is correct
- Check if payment was already executed
- Ensure payment wasn't cancelled or expired

### Issue: Callback not received
**Solution:**
- Verify callback URL is publicly accessible
- Check firewall/security settings
- Ensure HTTPS is enabled in production
- Review bKash dashboard for callback logs

---

## Support & Documentation

- **bKash Merchant Portal**: https://merchant.bkash.com
- **API Documentation**: Contact bKash support for complete API docs
- **Technical Support**: support@bkash.com
- **Integration Support**: Call bKash helpline

---

## Files Modified

1. `app/Services/PaymentGateway/BkashService.php` - Complete API implementation
2. `app/Http/Controllers/Front/PaymentController.php` - Callback handling (already implemented)
3. `config/payment.php` - Configuration settings
4. `.env` - Environment variables
5. `routes/web.php` - Payment routes (already configured)

---

## Next Steps for Production

1. **Request Production Credentials** from bKash
2. **Update .env** with production credentials
3. **Set BKASH_SANDBOX=false**
4. **Update BKASH_BASE_URL** to production URL
5. **Test with small amounts** first
6. **Monitor logs** for any issues
7. **Set up alerts** for payment failures
8. **Regular reconciliation** with bKash reports

---

## Version History

- **v1.0** - Initial implementation with all 5 APIs (December 22, 2025)
  - Grant Token API
  - Create Payment API
  - Execute Payment API
  - Query Payment Status API
  - Search Transaction API

---

*Document last updated: December 22, 2025*
