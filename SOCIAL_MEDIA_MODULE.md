# Social Media Integration Module

## Overview
This module allows administrators to connect social media pages/accounts and share products directly to social platforms from the admin panel.

## Supported Platforms
- ✅ Facebook (Pages)
- 🚧 Instagram (Coming soon)
- 🚧 Twitter (Coming soon)
- 🚧 LinkedIn (Coming soon)
- 🚧 Pinterest (Coming soon)

## Database Design

### 1. `social_media_settings` Table
Stores API credentials for each social media platform.

| Column | Type | Description |
|--------|------|-------------|
| id | bigint | Primary key |
| platform | string | Platform name (facebook, instagram, etc.) |
| app_id | string | Platform App/Client ID |
| app_secret | string | Platform App/Client Secret |
| access_token | text | User access token |
| access_token_secret | text | Token secret (for OAuth 1.0) |
| token_expires_at | timestamp | Token expiration time |
| is_active | boolean | Whether platform is enabled |
| config | json | Additional platform-specific configuration |
| created_at | timestamp | Creation timestamp |
| updated_at | timestamp | Last update timestamp |

**Indexes:**
- Unique: `platform`

### 2. `social_media_pages` Table
Stores connected social media pages/accounts.

| Column | Type | Description |
|--------|------|-------------|
| id | bigint | Primary key |
| social_media_setting_id | bigint | Foreign key to settings table |
| platform | string | Platform name |
| page_id | string | Platform-specific page/account ID |
| page_name | string | Page/account display name |
| page_username | string | Page username/handle |
| page_url | string | Full URL to page |
| page_access_token | text | Page-specific access token |
| page_picture | string | Profile picture URL |
| is_connected | boolean | Connection status |
| connected_at | timestamp | When page was connected |
| metadata | json | Additional page information |
| created_at | timestamp | Creation timestamp |
| updated_at | timestamp | Last update timestamp |

**Indexes:**
- Foreign key: `social_media_setting_id`
- Unique: `platform`, `page_id`

### 3. `social_media_posts` Table
Tracks all products shared to social media.

| Column | Type | Description |
|--------|------|-------------|
| id | bigint | Primary key |
| social_media_page_id | bigint | Foreign key to pages table |
| product_id | bigint | Foreign key to products table |
| user_id | bigint | Admin user who shared |
| platform | string | Platform name |
| post_id | string | Platform post ID |
| post_url | string | Direct link to post |
| message | text | Post caption/message |
| media_urls | json | Product images shared |
| status | string | pending, published, failed |
| error_message | text | Error details if failed |
| published_at | timestamp | When post was published |
| analytics | json | Likes, shares, comments, etc. |
| created_at | timestamp | Creation timestamp |
| updated_at | timestamp | Last update timestamp |

**Indexes:**
- Foreign keys: `social_media_page_id`, `product_id`, `user_id`
- Index: `product_id`, `platform`
- Index: `status`

## Models

### SocialMediaSetting
```php
- fillable: platform, app_id, app_secret, access_token, etc.
- casts: config (array), token_expires_at (datetime)
- hidden: app_secret, access_token, access_token_secret
- relationships: pages()
- methods: isTokenExpired()
- constants: PLATFORM_FACEBOOK, PLATFORM_INSTAGRAM, etc.
```

### SocialMediaPage
```php
- fillable: platform, page_id, page_name, page_access_token, etc.
- casts: metadata (array), is_connected (boolean)
- hidden: page_access_token
- relationships: setting(), posts()
- accessors: platform_icon, platform_color
```

### SocialMediaPost
```php
- fillable: page_id, product_id, platform, post_id, message, status, etc.
- casts: media_urls (array), analytics (array)
- relationships: page(), product(), user()
- scopes: published(), failed(), pending()
- constants: STATUS_PENDING, STATUS_PUBLISHED, STATUS_FAILED
```

## Service Layer

### SocialMediaService
Main service class handling all platform integrations.

**Key Methods:**

1. `getAuthorizationUrl($platform, $redirectUri)` - Get OAuth URL
2. `handleCallback($platform, $code, $redirectUri)` - Handle OAuth callback
3. `fetchFacebookPages($setting)` - Fetch user's Facebook pages
4. `shareProduct($product, $page, $message, $userId)` - Share product to social media
5. `disconnectPage($page)` - Disconnect a page
6. `getPostAnalytics($post)` - Fetch post analytics

## Admin Controller

### SocialMediaController
Handles all admin-side social media operations.

**Routes & Methods:**

1. `GET /admin/social-media/settings` - settings()
2. `POST /admin/social-media/settings` - updateSettings()
3. `GET /admin/social-media/connect-pages` - connectPages()
4. `GET /admin/social-media/connect/{platform}` - initiateConnection()
5. `GET /admin/social-media/callback/{platform}` - handleCallback()
6. `DELETE /admin/social-media/pages/{page}` - disconnectPage()
7. `GET /admin/social-media/products` - products()
8. `POST /admin/social-media/share` - shareProduct()
9. `GET /admin/social-media/posts` - posts()
10. `POST /admin/social-media/posts/{post}/analytics` - refreshAnalytics()

## Views

### 1. Settings Page (`admin/social-media/settings.blade.php`)
- Configure API credentials for each platform
- Enable/disable platforms
- Save App ID and App Secret

### 2. Connect Pages (`admin/social-media/connect-pages.blade.php`)
- List of configured platforms
- "Connect" button for each platform
- List of connected pages with disconnect option
- Page details (name, username, profile picture)

### 3. Products Page (`admin/social-media/products.blade.php`)
- List all products with pagination
- "Share" button for each product
- Modal to select page and customize message
- Share history for each product

### 4. Posts History (`admin/social-media/posts.blade.php`)
- List all shared posts
- Status badges (Published, Failed, Pending)
- Post analytics (likes, shares, comments)
- Refresh analytics button
- Link to view post on social platform

## Setup Instructions

### 1. Run Migrations
```bash
php artisan migrate
```

### 2. Facebook App Setup
1. Go to https://developers.facebook.com/
2. Create a new app (Business type)
3. Add Facebook Login product
4. Configure OAuth redirect URL: `https://yourdomain.com/admin/social-media/callback/facebook`
5. Required permissions:
   - pages_show_list
   - pages_read_engagement
   - pages_manage_posts
   - publish_to_groups
6. Copy App ID and App Secret to settings

### 3. Configure Settings in Admin
1. Go to Social Media Settings
2. Select Facebook
3. Enter App ID and App Secret
4. Enable the platform
5. Click Save

### 4. Connect Facebook Page
1. Go to Connect Pages
2. Click "Connect Facebook"
3. Authorize the app
4. Select pages to manage
5. Pages will appear in connected list

### 5. Share Products
1. Go to Social Media Products
2. Find product to share
3. Click "Share" button
4. Select page
5. Customize message (optional)
6. Click "Share Now"

## API Endpoints Used

### Facebook Graph API v18.0

**OAuth:**
- Authorization: `https://www.facebook.com/v18.0/dialog/oauth`
- Access Token: `https://graph.facebook.com/v18.0/oauth/access_token`

**Pages:**
- List Pages: `GET /me/accounts`
- Post to Feed: `POST /{page-id}/feed`
- Post Photo: `POST /{page-id}/photos`

**Analytics:**
- Post Insights: `GET /{post-id}?fields=shares,likes,comments,reactions`

## Security Considerations

1. **Token Storage**: Access tokens are encrypted and hidden from API responses
2. **Validation**: All inputs validated before processing
3. **CSRF Protection**: State parameter used in OAuth flow
4. **Exception Handling**: All API calls wrapped in try-catch
5. **Logging**: Failed posts logged for debugging

## Error Handling

All errors are logged and displayed to users:
- OAuth failures → Redirect with error message
- API errors → Caught and stored in post record
- Network issues → Retry mechanism can be implemented

## Future Enhancements

1. Instagram integration (requires Facebook Page connection)
2. Twitter integration (OAuth 2.0)
3. LinkedIn integration
4. Pinterest integration
5. Scheduled posts
6. Bulk sharing
7. Post templates
8. Advanced analytics dashboard
9. Automatic sharing when product is created/updated
10. Image editing before posting

## File Structure
```
app/
├── Models/
│   ├── SocialMediaSetting.php
│   ├── SocialMediaPage.php
│   └── SocialMediaPost.php
├── Services/
│   └── SocialMedia/
│       └── SocialMediaService.php
└── Http/
    └── Controllers/
        └── Admin/
            └── SocialMediaController.php

database/
└── migrations/
    ├── xxxx_create_social_media_settings_table.php
    ├── xxxx_create_social_media_pages_table.php
    └── xxxx_create_social_media_posts_table.php

resources/
└── views/
    └── admin/
        └── social-media/
            ├── settings.blade.php
            ├── connect-pages.blade.php
            ├── products.blade.php
            └── posts.blade.php

routes/
└── web.php (admin routes)
```

## Dependencies Required

Add to composer.json if not already present:
```json
{
    "require": {
        "guzzlehttp/guzzle": "^7.0"
    }
}
```

Laravel HTTP client is used (built-in).

## Testing

1. Test OAuth flow with Facebook
2. Test page connection
3. Test product sharing
4. Test error handling
5. Test token expiration
6. Test analytics fetching

## Support

For issues or questions, please refer to:
- Facebook Platform Documentation: https://developers.facebook.com/docs
- Laravel HTTP Client: https://laravel.com/docs/http-client
- Laravel Documentation: https://laravel.com/docs
