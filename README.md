# Thakshi Photography — Deployment Guide
## Hostinger Basic Plan

---

## FOLDER STRUCTURE
```
thakshi/
├── index.php              ← Homepage
├── service.php            ← Service page template (Phase 2)
├── config.php             ← DB config (EDIT BEFORE UPLOAD)
├── .htaccess              ← Clean URLs + Security
├── database.sql           ← Run this in phpMyAdmin FIRST
├── includes/
│   └── functions.php      ← Shared PHP helpers
├── assets/
│   ├── css/style.css
│   ├── js/               ← (Phase 2+)
│   └── images/
├── uploads/               ← Gallery images go here
│   ├── wedding/
│   ├── newborn/
│   ├── model-shoot/
│   ├── maternity/
│   ├── corporate/
│   └── couple-portraits/
├── admin/                 ← Admin dashboard (Phase 4)
│   ├── index.php
│   ├── login.php
│   └── pages/
└── api/                   ← AJAX endpoints (Phase 3)
```

---

## STEP 1 — Hostinger Setup

1. Log in to Hostinger hPanel
2. Go to **Databases → MySQL Databases**
3. Create a new database, e.g. `u123456_thakshi`
4. Create a DB user and note the password
5. Go to **phpMyAdmin**, select your database
6. Click **Import**, upload `database.sql`

---

## STEP 2 — Edit config.php

Open `config.php` and update:
```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'u123456_thakshi');   // your DB name
define('DB_USER', 'u123456_user');      // your DB user
define('DB_PASS', 'your_password');     // your DB password
define('SITE_URL', 'https://www.yourdomain.com');
```

---

## STEP 3 — Upload Files

Using Hostinger File Manager or FTP (FileZilla):
- Upload ALL files to `public_html/`
- Set `uploads/` folder permissions to **755**
- Set all PHP files to **644**

---

## STEP 4 — Default Admin Login

URL: `https://yourdomain.com/admin`
- Username: `admin`
- Password: `Admin@1234`
**⚠️ CHANGE THIS PASSWORD IMMEDIATELY after first login!**

---

## STEP 5 — Add Hero Images

For each service, upload a hero image to:
- `uploads/wedding/hero.jpg`
- `uploads/newborn/hero.jpg`
- etc.

Recommended size: 800×1067px (3:4 ratio), JPEG, max 300KB

---

## PHASES COMPLETED

| Phase | Status | Contents |
|-------|--------|----------|
| Phase 1 | ✅ Done | Foundation + Homepage |
| Phase 2 | ⏳ Next | Service pages + Gallery |
| Phase 3 | ⏳ | User popup + Access control |
| Phase 4 | ⏳ | Admin dashboard |
| Phase 5 | ⏳ | Security + Mobile + Deploy |

---

## CONTINUE BUILDING

In your next Claude session, paste:
> "Continue building Thakshi Photography website — Phase 2: Service pages and gallery"

All code is split into clean separate files — easy to continue anytime.
