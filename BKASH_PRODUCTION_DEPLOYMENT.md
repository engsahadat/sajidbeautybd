# bKash Payment Production Deployment Guide

## Pre-Deployment Checklist

### 1. **Get Production Credentials from bKash**
Contact bKash to get your production credentials:
- Production App Key
- Production App Secret
- Production Username
- Production Password
- Production Merchant Number

### 2. **Production Environment Setup**

#### Update `.env` file on production server:
```env
# Disable demo mode
PAYMENT_DEMO=false

# Set to production mode
BKASH_SANDBOX=false

# Production credentials (get from bKash)
BKASH_APP_KEY=your_production_app_key
BKASH_APP_SECRET=your_production_app_secret
BKASH_USERNAME=your_production_username
BKASH_PASSWORD=your_production_password

# Your production domain
APP_URL=https://yourdomain.com
```

#### Clear caches after updating:
```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
```

### 3. **SSL Certificate Required**
- bKash **requires HTTPS** for production
- Install valid SSL certificate on your domain
- Test: `https://yourdomain.com` should work without warnings

### 4. **Callback URL Whitelisting**
Provide these URLs to bKash for whitelisting:
```
Success URL: https://yourdomain.com/payment/callback/bkash
Fail URL: https://yourdomain.com/payment/callback/bkash
Cancel URL: https://yourdomain.com/payment/callback/bkash
```

### 5. **Server Requirements**
- PHP 8.1 or higher
- CURL enabled
- OpenSSL enabled
- Allow outgoing connections to:
  - `https://tokenized.pay.bka.sh` (Production API)
  - Port 443 (HTTPS)

### 6. **Test Checklist Before Going Live**

#### Test in Sandbox First:
- ✅ Create order with bKash payment
- ✅ Redirect to bKash payment page works
- ✅ Complete payment in bKash app
- ✅ Callback receives payment confirmation
- ✅ Order status updates to "paid"
- ✅ Customer receives confirmation email
- ✅ Admin receives order notification

#### Production Testing:
1. Use small amount first (e.g., 10 BDT)
2. Test with real bKash account
3. Verify payment appears in bKash merchant panel
4. Check order status updates correctly
5. Test payment failure scenarios
6. Test payment cancellation

### 7. **Monitoring & Logging**

#### Enable production logging:
```php
// config/logging.php - ensure you have proper logging channels
'channels' => [
    'stack' => [
        'driver' => 'stack',
        'channels' => ['daily', 'slack'], // Add slack for critical errors
    ],
    'daily' => [
        'driver' => 'daily',
        'path' => storage_path('logs/laravel.log'),
        'level' => 'error', // Only log errors in production
        'days' => 14,
    ],
],
```

#### Monitor these logs:
```bash
# Watch payment errors in real-time
tail -f storage/logs/laravel.log | grep -i "payment\|bkash"

# Check for failed payments
grep "ERROR" storage/logs/laravel.log | grep -i "bkash"
```

### 8. **Error Handling**

Current error handling in place:
- Token authentication failures → User redirected to checkout with error message
- Payment creation failures → Logged and user notified
- Callback failures → Logged for manual review
- Network errors → Caught and logged

### 9. **Database Backup**

Before going live:
```bash
# Backup database
mysqldump -u username -p database_name > backup_before_bkash_$(date +%Y%m%d).sql

# Test restore
mysql -u username -p test_database < backup_before_bkash_YYYYMMDD.sql
```

### 10. **Deployment Steps**

#### On Production Server:

```bash
# 1. Pull latest code
git pull origin main

# 2. Install dependencies
composer install --no-dev --optimize-autoloader

# 3. Update .env with production credentials
nano .env
# Set PAYMENT_DEMO=false
# Set BKASH_SANDBOX=false
# Add production credentials

# 4. Clear all caches
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# 5. Optimize for production
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 6. Set proper permissions
chmod -R 755 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

# 7. Restart services
sudo systemctl restart php8.1-fpm  # or your PHP version
sudo systemctl restart nginx       # or apache2
```

### 11. **Security Best Practices**

#### Protect sensitive files:
```bash
# Ensure .env is not accessible
chmod 600 .env

# Block access to .env via web server
# In nginx config:
location ~ /\.env {
    deny all;
}
```

#### Enable rate limiting for payment routes:
```php
// routes/web.php
Route::middleware(['throttle:10,1'])->group(function () {
    // Payment routes already defined
});
```

### 12. **Go Live Checklist**

- [ ] Production credentials received from bKash
- [ ] SSL certificate installed and verified
- [ ] Callback URLs whitelisted by bKash
- [ ] Tested in sandbox successfully
- [ ] Updated .env with production settings
- [ ] All caches cleared and optimized
- [ ] Database backup completed
- [ ] Monitoring/logging configured
- [ ] First test transaction with small amount
- [ ] Customer support notified about new payment method
- [ ] Tested payment failure scenarios
- [ ] Documented rollback procedure

### 13. **Rollback Plan**

If issues occur:
```bash
# 1. Quick rollback - Enable demo mode
# Edit .env:
PAYMENT_DEMO=true

php artisan config:clear

# 2. Full rollback - Revert code
git revert HEAD
composer install
php artisan config:clear && php artisan cache:clear
```

### 14. **Post-Launch Monitoring**

First 24 hours:
- Monitor logs every 2 hours
- Check payment success rate
- Verify order confirmations are sent
- Check bKash merchant panel for settlements
- Monitor customer support tickets

First week:
- Daily log review
- Weekly payment reconciliation
- Customer feedback collection
- Performance monitoring

### 15. **Common Production Issues & Solutions**

| Issue | Solution |
|-------|----------|
| Token authentication fails | Check credentials, verify API access, check server IP whitelisting |
| Payment timeout | Increase PHP timeout, check network connectivity |
| Callback not received | Verify callback URL is whitelisted, check firewall rules |
| SSL errors | Update CA certificates: `apt-get update && apt-get install ca-certificates` |
| Orders stuck in pending | Check bKash merchant panel, manually verify and update |

### 16. **Support Contacts**

- **bKash Merchant Support:** 16247
- **Email:** merchantservice@bkash.com
- **Technical Issues:** Provide transaction ID, order number, timestamp

### 17. **Maintenance Mode**

For updates:
```bash
# Put site in maintenance mode
php artisan down --message="Payment system update in progress"

# Perform updates
# ...

# Bring site back up
php artisan up
```

---

## Quick Production Deploy Command

Save this as `deploy_production.sh`:
```bash
#!/bin/bash
echo "🚀 Deploying to Production..."
git pull origin main
composer install --no-dev --optimize-autoloader
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
chmod -R 755 storage bootstrap/cache
echo "✅ Deployment complete! Remember to update .env with production credentials."
```

Run: `bash deploy_production.sh`

---

## Need Help?

- Review Laravel logs: `storage/logs/laravel.log`
- Check web server logs: `/var/log/nginx/error.log`
- bKash API docs: Contact bKash for production documentation
- Test in sandbox first: Always test changes in sandbox before production
