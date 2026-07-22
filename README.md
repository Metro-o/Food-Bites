# FoodBites — Setup Guide for XAMPP

## Quick Start (2 steps)

### Step 1: Configure XAMPP to serve this project

**Option A — Symlink (Recommended)**
Open Command Prompt as Administrator and run:
```cmd
mklink /D "C:\xampp\htdocs\FoodBites" "D:\Projects\FoodBites"
```
Then access the site at: http://localhost/FoodBites

**Option B — Copy the folder**
Copy `D:\Projects\FoodBites` → `C:\xampp\htdocs\FoodBites`

**Option C — Virtual Host**
Add to `C:\xampp\apache\conf\extra\httpd-vhosts.conf`:
```apache
<VirtualHost *:80>
    DocumentRoot "D:/Projects/FoodBites"
    ServerName foodbites.local
    <Directory "D:/Projects/FoodBites">
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```
Then add `127.0.0.1 foodbites.local` to `C:\Windows\System32\drivers\etc\hosts`

### Step 2: Import the Database

1. Start XAMPP — Apache + MySQL
2. Open http://localhost/phpmyadmin
3. Click **Import** → Choose file → Select `D:\Projects\FoodBites\database\foodbites.sql`
4. Click **Go**

### Step 3: Visit the Site

- **Customer site**: http://localhost/FoodBites/
- **Admin panel**: http://localhost/FoodBites/admin/login.php
- **Kitchen KDS**: http://localhost/FoodBites/kds/index.php

## Demo Login Credentials

| Role     | Email                       | Password    |
|----------|-----------------------------|-------------|
| Admin    | admin@foodbites.co.tz       | Admin@1234  |
| Kitchen  | kitchen@foodbites.co.tz     | Admin@1234  |
| Customer | amina@example.com           | Admin@1234  |

## Project Structure

```
FoodBites/
├── index.php              Landing page
├── .htaccess              Apache security config
├── config/
│   └── db.php             DB connection + app constants
├── database/
│   └── foodbites.sql      DB schema + seed data
├── includes/
│   ├── functions.php      Helper functions (auth, format, etc.)
│   ├── auth_guard.php     Role-based access control
│   ├── header.php         Customer nav header
│   ├── footer.php         Customer footer
│   ├── admin_header.php   Admin sidebar + topbar
│   └── admin_footer.php   Admin footer JS
├── auth/
│   ├── login.php
│   ├── register.php
│   └── logout.php
├── customer/
│   ├── menu.php           Browse + filter menu
│   ├── cart.php           Cart management
│   ├── checkout.php       Checkout + payment
│   ├── order_confirm.php  Order confirmation
│   ├── order_tracking.php Real-time order tracker
│   └── order_history.php  Past orders
├── admin/
│   ├── index.php          Dashboard + analytics
│   ├── login.php          Admin login
│   ├── products.php       Manage menu items
│   ├── product_form.php   Add/edit product
│   ├── orders.php         Manage all orders
│   ├── users.php          View customers
│   └── logout.php
├── kds/
│   ├── index.php          Kitchen Display System
│   └── api.php            KDS data + status API
├── api/
│   ├── cart.php           Cart AJAX operations
│   ├── order.php          Order AJAX operations
│   └── products.php       Product search/filter
├── uploads/
│   └── products/          Uploaded food images
└── assets/
    ├── css/
    │   ├── main.css        Design system + utilities
    │   ├── customer.css    Customer pages
    │   ├── admin.css       Admin panel
    │   └── kds.css         KDS dark mode
    ├── js/
    │   ├── main.js         Global JS
    │   └── cart.js         Cart interactions
    ├── images/
    │   └── default_food.jpg  Fallback food image
    └── sounds/
        └── alert.mp3       KDS new order sound
```

## Changing the Base URL

If using a VirtualHost, update `BASE_URL` in `config/db.php`:
```php
define('BASE_URL', 'http://foodbites.local');
```
