# Social Media Module - Database Design

## 📊 Entity Relationship Diagram

```
┌─────────────────────────────┐
│  social_media_settings      │
├─────────────────────────────┤
│ id (PK)                     │
│ platform (UNIQUE)           │  ← facebook, instagram, twitter, etc.
│ app_id                      │
│ app_secret                  │
│ access_token                │
│ access_token_secret         │
│ token_expires_at            │
│ is_active                   │
│ config (JSON)               │
│ created_at                  │
│ updated_at                  │
└─────────────────────────────┘
          │
          │ 1:N
          ▼
┌─────────────────────────────┐
│  social_media_pages         │
├─────────────────────────────┤
│ id (PK)                     │
│ social_media_setting_id (FK)│──┐
│ platform                    │  │
│ page_id                     │  │ UNIQUE (platform, page_id)
│ page_name                   │  │
│ page_username               │  │
│ page_url                    │  │
│ page_access_token           │  │
│ page_picture                │  │
│ is_connected                │  │
│ connected_at                │  │
│ metadata (JSON)             │  │
│ created_at                  │  │
│ updated_at                  │  │
└─────────────────────────────┘  │
          │                       │
          │ 1:N                   │
          ▼                       │
┌─────────────────────────────┐  │
│  social_media_posts         │  │
├─────────────────────────────┤  │
│ id (PK)                     │  │
│ social_media_page_id (FK)   │──┘
│ product_id (FK)             │────────┐
│ user_id (FK)                │──────┐ │
│ platform                    │      │ │
│ post_id                     │      │ │
│ post_url                    │      │ │
│ message (TEXT)              │      │ │
│ media_urls (JSON)           │      │ │
│ status                      │      │ │
│ error_message (TEXT)        │      │ │
│ published_at                │      │ │
│ analytics (JSON)            │      │ │
│ created_at                  │      │ │
│ updated_at                  │      │ │
└─────────────────────────────┘      │ │
                                     │ │
                                     │ │
              ┌──────────────────────┘ │
              │                        │
              ▼                        ▼
    ┌─────────────────┐      ┌─────────────────┐
    │     users       │      │    products     │
    ├─────────────────┤      ├─────────────────┤
    │ id (PK)         │      │ id (PK)         │
    │ name            │      │ name            │
    │ email           │      │ slug            │
    │ ...             │      │ description     │
    └─────────────────┘      │ price           │
                             │ stock_quantity  │
                             │ image           │
                             │ ...             │
                             └─────────────────┘
```

## 🗂️ Table Details

### 1. social_media_settings
**Purpose:** Store platform-level API credentials and configuration

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| id | BIGINT | PRIMARY KEY, AUTO_INCREMENT | Unique identifier |
| platform | VARCHAR(255) | UNIQUE, NOT NULL | Platform name (facebook, instagram, etc.) |
| app_id | VARCHAR(255) | NULLABLE | Application/Client ID from platform |
| app_secret | VARCHAR(255) | NULLABLE | Application/Client Secret (encrypted) |
| access_token | TEXT | NULLABLE | OAuth access token |
| access_token_secret | TEXT | NULLABLE | OAuth token secret (for OAuth 1.0) |
| token_expires_at | TIMESTAMP | NULLABLE | Token expiration datetime |
| is_active | BOOLEAN | DEFAULT true | Whether platform is enabled |
| config | JSON | NULLABLE | Additional platform-specific settings |
| created_at | TIMESTAMP | AUTO | Record creation time |
| updated_at | TIMESTAMP | AUTO | Last update time |

**Indexes:**
- PRIMARY: `id`
- UNIQUE: `platform`

**Sample Data:**
```json
{
  "id": 1,
  "platform": "facebook",
  "app_id": "123456789012345",
  "app_secret": "abc123def456...",
  "access_token": "EAAG...",
  "token_expires_at": "2025-03-08 16:48:26",
  "is_active": true,
  "config": {
    "api_version": "v18.0",
    "permissions": ["pages_manage_posts", "pages_show_list"]
  }
}
```

---

### 2. social_media_pages
**Purpose:** Store connected social media pages/accounts

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| id | BIGINT | PRIMARY KEY, AUTO_INCREMENT | Unique identifier |
| social_media_setting_id | BIGINT | FOREIGN KEY → settings.id | Link to platform settings |
| platform | VARCHAR(255) | NOT NULL | Platform name |
| page_id | VARCHAR(255) | NOT NULL | Platform-specific page ID |
| page_name | VARCHAR(255) | NOT NULL | Page display name |
| page_username | VARCHAR(255) | NULLABLE | Page username/handle |
| page_url | VARCHAR(255) | NULLABLE | Full URL to page |
| page_access_token | TEXT | NULLABLE | Page-specific access token |
| page_picture | VARCHAR(255) | NULLABLE | Profile picture URL |
| is_connected | BOOLEAN | DEFAULT true | Connection status |
| connected_at | TIMESTAMP | NULLABLE | When page was connected |
| metadata | JSON | NULLABLE | Additional page information |
| created_at | TIMESTAMP | AUTO | Record creation time |
| updated_at | TIMESTAMP | AUTO | Last update time |

**Indexes:**
- PRIMARY: `id`
- FOREIGN KEY: `social_media_setting_id` → `social_media_settings(id)` ON DELETE CASCADE
- UNIQUE: `(platform, page_id)`

**Sample Data:**
```json
{
  "id": 1,
  "social_media_setting_id": 1,
  "platform": "facebook",
  "page_id": "109876543210987",
  "page_name": "My Beauty Store",
  "page_username": "mybeautystore",
  "page_url": "https://facebook.com/mybeautystore",
  "page_access_token": "EAAB...",
  "page_picture": "https://platform-lookaside.fbsbx.com/platform/...",
  "is_connected": true,
  "connected_at": "2025-11-08 16:50:00",
  "metadata": {
    "followers_count": 5420,
    "category": "Shopping & Retail"
  }
}
```

---

### 3. social_media_posts
**Purpose:** Track all products shared to social media with analytics

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| id | BIGINT | PRIMARY KEY, AUTO_INCREMENT | Unique identifier |
| social_media_page_id | BIGINT | FOREIGN KEY → pages.id | Which page was used |
| product_id | BIGINT | FOREIGN KEY → products.id | Which product was shared |
| user_id | BIGINT | FOREIGN KEY → users.id | Admin who shared it |
| platform | VARCHAR(255) | NOT NULL | Platform name |
| post_id | VARCHAR(255) | NULLABLE | Platform post ID |
| post_url | VARCHAR(255) | NULLABLE | Direct link to post |
| message | TEXT | NULLABLE | Post caption/message |
| media_urls | JSON | NULLABLE | Product images shared |
| status | VARCHAR(255) | NOT NULL | pending/published/failed |
| error_message | TEXT | NULLABLE | Error details if failed |
| published_at | TIMESTAMP | NULLABLE | When post was published |
| analytics | JSON | NULLABLE | Likes, shares, comments |
| created_at | TIMESTAMP | AUTO | Record creation time |
| updated_at | TIMESTAMP | AUTO | Last update time |

**Indexes:**
- PRIMARY: `id`
- FOREIGN KEY: `social_media_page_id` → `social_media_pages(id)` ON DELETE CASCADE
- FOREIGN KEY: `product_id` → `products(id)` ON DELETE CASCADE
- FOREIGN KEY: `user_id` → `users(id)` ON DELETE CASCADE
- INDEX: `(product_id, platform)`
- INDEX: `status`

**Sample Data:**
```json
{
  "id": 1,
  "social_media_page_id": 1,
  "product_id": 42,
  "user_id": 1,
  "platform": "facebook",
  "post_id": "109876543210987_123456789",
  "post_url": "https://facebook.com/109876543210987/posts/123456789",
  "message": "🛍️ Beauty Cream Premium\n\nAmazing cream...\n\n💰 Price: ৳1,500.00",
  "media_urls": ["https://yourdomain.com/images/products/cream.jpg"],
  "status": "published",
  "error_message": null,
  "published_at": "2025-11-08 17:00:00",
  "analytics": {
    "likes": 45,
    "comments": 12,
    "shares": 8,
    "reactions": 52,
    "fetched_at": "2025-11-08 18:00:00"
  }
}
```

---

## 🔗 Relationships

### One-to-Many Relationships

1. **SocialMediaSetting → SocialMediaPage (1:N)**
   - One platform setting can have many connected pages
   - CASCADE DELETE: Deleting settings removes all pages

2. **SocialMediaPage → SocialMediaPost (1:N)**
   - One page can have many posts
   - CASCADE DELETE: Deleting page removes all its posts

3. **Product → SocialMediaPost (1:N)**
   - One product can be shared multiple times
   - CASCADE DELETE: Deleting product removes its share records

4. **User → SocialMediaPost (1:N)**
   - One user can share many products
   - CASCADE DELETE: Deleting user removes their shares

---

## 📈 Query Examples

### Get all posts for a product
```sql
SELECT 
    smp.*,
    smpage.page_name,
    smpage.platform,
    u.name as shared_by,
    p.name as product_name
FROM social_media_posts smp
JOIN social_media_pages smpage ON smp.social_media_page_id = smpage.id
JOIN users u ON smp.user_id = u.id
JOIN products p ON smp.product_id = p.id
WHERE smp.product_id = 42
ORDER BY smp.published_at DESC;
```

### Get posts with analytics
```sql
SELECT 
    smp.id,
    p.name as product,
    smpage.page_name,
    smp.analytics->>'$.likes' as likes,
    smp.analytics->>'$.comments' as comments,
    smp.analytics->>'$.shares' as shares
FROM social_media_posts smp
JOIN products p ON smp.product_id = p.id
JOIN social_media_pages smpage ON smp.social_media_page_id = smpage.id
WHERE smp.status = 'published'
ORDER BY CAST(smp.analytics->>'$.likes' AS UNSIGNED) DESC
LIMIT 10;
```

### Get connected pages for a platform
```sql
SELECT 
    smpage.*,
    sms.platform,
    sms.is_active
FROM social_media_pages smpage
JOIN social_media_settings sms ON smpage.social_media_setting_id = sms.id
WHERE sms.platform = 'facebook'
AND smpage.is_connected = 1;
```

---

## 🎯 Status Values

### Post Status
- `pending` - Post creation initiated but not yet published
- `published` - Successfully posted to social media
- `failed` - Failed to post (error logged in error_message)

---

## 🔐 Security Considerations

1. **Sensitive Data Encryption:**
   - `app_secret` - Never exposed in API responses
   - `access_token` - Hidden from JSON serialization
   - `page_access_token` - Encrypted in database

2. **Cascade Deletes:**
   - Deleting settings removes all pages and posts
   - Prevents orphaned records
   - Maintains data integrity

3. **Unique Constraints:**
   - One setting per platform
   - One record per unique page
   - Prevents duplicates

---

## 📊 Storage Estimates

### Average Storage per Record
- SocialMediaSetting: ~500 bytes
- SocialMediaPage: ~800 bytes  
- SocialMediaPost: ~1-2 KB (with analytics)

### Estimated for 1000 Products Shared 3x Each
- Settings: 5 platforms × 500B = 2.5 KB
- Pages: 10 pages × 800B = 8 KB
- Posts: 3000 posts × 1.5KB = 4.5 MB

**Total: ~4.5 MB for extensive usage**

---

## 🚀 Performance Optimization

### Recommended Indexes (Already Added)
1. `platform` - Fast platform lookups
2. `(product_id, platform)` - Product share history
3. `status` - Filter by post status
4. `(platform, page_id)` - Unique page identification

### Future Optimizations
- Add index on `published_at` for chronological queries
- Add index on `analytics` for top posts queries
- Consider partitioning posts table by platform

---

This database design supports:
- ✅ Multiple social media platforms
- ✅ Multiple pages per platform
- ✅ Complete post tracking
- ✅ Analytics storage
- ✅ Error logging
- ✅ Data integrity
- ✅ Performance optimization
