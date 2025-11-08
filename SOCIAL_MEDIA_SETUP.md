# Social Media Integration - Quick Setup Guide

## ✅ What's Installed

### Database Tables (Migrated Successfully)
- ✅ `social_media_settings` - Store platform API credentials
- ✅ `social_media_pages` - Connected social media pages
- ✅ `social_media_posts` - Shared product tracking

### Models Created
- ✅ `SocialMediaSetting` - Platform credentials management
- ✅ `SocialMediaPage` - Connected pages management  
- ✅ `SocialMediaPost` - Post tracking with analytics

### Service Layer
- ✅ `SocialMediaService` - Handles OAuth, posting, analytics

### Controller
- ✅ `SocialMediaController` - Admin panel management

### Admin Views
- ✅ Settings page - Configure API credentials
- ✅ Connect Pages - Link social media accounts
- ✅ Share Products - Product list with share buttons
- ✅ Posts History - View shared posts and analytics

### Routes Added
- ✅ `/admin/social-media/settings` - API configuration
- ✅ `/admin/social-media/connect-pages` - Connect accounts
- ✅ `/admin/social-media/products` - Share products
- ✅ `/admin/social-media/posts` - View history

### Sidebar Menu
- ✅ Social Media section added to admin sidebar

## 🚀 Next Steps to Use

### 1. Create Facebook App (5 minutes)
1. Go to https://developers.facebook.com/
2. Click "My Apps" → "Create App"
3. Choose "Business" type
4. Fill in app details
5. Add "Facebook Login" product
6. Set redirect URL: `https://yourdomain.com/admin/social-media/callback/facebook`
7. Get your App ID and App Secret

### 2. Configure in Admin (2 minutes)
1. Login to admin panel
2. Go to Social Media → Settings
3. Select Facebook
4. Enter App ID and App Secret
5. Enable platform
6. Click Save

### 3. Connect Your Pages (1 minute)
1. Go to Social Media → Connect Pages
2. Click "Connect Facebook"
3. Login to Facebook (if not already)
4. Select pages to manage
5. Authorize the app

### 4. Share Products (instant!)
1. Go to Social Media → Share Products
2. Find product to share
3. Click "Share" button
4. Select page
5. Customize message (optional)
6. Click "Share Now"

## 📱 Supported Platforms

### Currently Implemented
- ✅ **Facebook** - Full support (Pages posting, analytics)

### Coming Soon
- 🚧 Instagram (requires Facebook Page connection)
- 🚧 Twitter
- 🚧 LinkedIn
- 🚧 Pinterest

## 🔐 Required Facebook Permissions

Your app needs these permissions:
- `pages_show_list` - List user's pages
- `pages_read_engagement` - Read page insights
- `pages_manage_posts` - Post to pages
- `publish_to_groups` - (optional) Post to groups

## 📊 Features

### ✅ OAuth Integration
- Secure Facebook OAuth flow
- Token management and refresh
- Multiple platform support

### ✅ Page Management
- Connect multiple pages
- View page details
- Disconnect pages

### ✅ Product Sharing
- Share with product image
- Auto-generated messages
- Custom message option
- Direct link to product

### ✅ Analytics Tracking
- Likes count
- Comments count
- Shares count
- Reactions count
- Auto-refresh capability

### ✅ Posts History
- View all shared posts
- Status tracking (Published/Failed/Pending)
- Error logging
- Link to view posts

## 📁 Files Created

```
Database Migrations:
✅ 2025_11_08_164826_create_social_media_settings_table.php
✅ 2025_11_08_165505_create_social_media_pages_table.php
✅ 2025_11_08_165626_create_social_media_posts_table.php

Models:
✅ app/Models/SocialMediaSetting.php
✅ app/Models/SocialMediaPage.php
✅ app/Models/SocialMediaPost.php

Services:
✅ app/Services/SocialMedia/SocialMediaService.php

Controllers:
✅ app/Http/Controllers/Admin/SocialMediaController.php

Views:
✅ resources/views/admin/social-media/settings.blade.php
✅ resources/views/admin/social-media/connect-pages.blade.php
✅ resources/views/admin/social-media/products.blade.php
✅ resources/views/admin/social-media/posts.blade.php

Documentation:
✅ SOCIAL_MEDIA_MODULE.md (detailed technical documentation)
✅ SOCIAL_MEDIA_SETUP.md (this quick setup guide)
```

## 🎨 UI Features

- Platform-specific colors
- Font Awesome icons for each platform
- Responsive design
- Modal for sharing customization
- Real-time message preview
- AJAX-based sharing (no page reload)
- Status badges (Success/Failed/Pending)

## 🔒 Security Features

- CSRF protection
- Token encryption
- Hidden sensitive fields
- OAuth state validation
- Exception handling
- Error logging

## 💡 Usage Example

### Share a Product
```
1. Admin navigates to Social Media → Share Products
2. Finds "Beauty Cream - Premium" product
3. Clicks "Share" button
4. Modal opens with:
   - Page selector (e.g., "My Beauty Store")
   - Auto-generated message:
     🛍️ Beauty Cream - Premium
     
     Amazing cream for all skin types...
     
     💰 Price: ৳1,500.00
     ✅ In Stock
     
     🔗 Shop now!
5. Admin can edit message or keep default
6. Clicks "Share Now"
7. Product is posted to Facebook page with image
8. Post tracked in database
9. Analytics can be fetched later
```

## 📈 Future Enhancements

- [ ] Scheduled posts
- [ ] Bulk sharing
- [ ] Post templates
- [ ] Instagram integration
- [ ] Twitter integration
- [ ] Advanced analytics dashboard
- [ ] Auto-share on product create
- [ ] Image editing before posting
- [ ] Video upload support
- [ ] Hashtag suggestions

## ❓ Troubleshooting

### "Platform not configured"
→ Go to Settings and add API credentials

### "Failed to get access token"
→ Check App ID and App Secret are correct

### "Failed to post to Facebook"
→ Check page access token is valid
→ Verify page permissions

### "No pages connected"
→ Click "Connect Facebook" to authorize

## 📞 Support

For detailed technical information, see:
- `SOCIAL_MEDIA_MODULE.md` - Complete technical documentation

## 🎉 You're All Set!

The social media integration module is fully installed and ready to use. Just:
1. Configure Facebook App
2. Connect your pages  
3. Start sharing products!

Happy Sharing! 🚀
