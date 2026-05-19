# Manual Installation

For environments where Docker is not available or when you prefer full control over the server stack.

## System Requirements

| Component | Requirement |
|-----------|-------------|
| **PHP** | 8.1 or higher |
| **Web Server** | Apache (with mod_rewrite) or Nginx |
| **Database** | MySQL 5.7+ / MariaDB 10.3+, PostgreSQL 13+, or SQLite 3 |
| **PHP Extensions** | `pdo`, `pdo_mysql` (or `pdo_pgsql` / `pdo_sqlite`), `mbstring`, `gd`, `zip`, `xml`, `dom`, `intl` |
| **Node.js** | 18+ (for building frontend vendor assets) |
| **Composer** | 2.x (for PHP dependencies) |

> **Note:** If you already have Node.js and Composer on another machine, you can build the vendor assets there and copy `templates/default/vendor/` and `vendor/` to the server. Node.js is only needed at build time, not runtime.

## Step-by-Step Installation

### 1. Extract Files

Place the Simple Invoices files in your web server's document root:

```bash
git clone https://github.com/simpleinvoices/simpleinvoices.git /var/www/html/simpleinvoices
cd /var/www/html/simpleinvoices
```

### 2. Create the Database

Create a database and user in your database server:

**MySQL / MariaDB:**
```sql
CREATE DATABASE simple_invoices CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'simpleuser'@'localhost' IDENTIFIED BY 'your_password';
GRANT ALL PRIVILEGES ON simple_invoices.* TO 'simpleuser'@'localhost';
FLUSH PRIVILEGES;
```

**PostgreSQL:**
```sql
CREATE USER simpleuser WITH PASSWORD 'your_password';
CREATE DATABASE simple_invoices OWNER simpleuser;
```

> **SQLite:** No separate database server is required. The database file will be created automatically in `databases/sqlite/`.

### 3. Configure the Application

Copy the example environment file and edit it:

```bash
cp .env .env.local
```

For manual (non-Docker) installations, set these key variables in `.env.local`:

```bash
# Database
SI_DATABASE_ADAPTER=pdo_mysql     # or pdo_pgsql / pdo_sqlite
SI_DB_HOST=localhost
SI_DB_PORT=3306
SI_DB_USER=simpleuser
SI_DB_PASSWORD=your_password
SI_DB_NAME=simple_invoices

# Security
SI_NONCE_KEY=your_random_secret_string
SI_AUTHENTICATION_ENABLED=true

# Server
SI_PHP_DATE_TIMEZONE=Your/Timezone
```

Alternatively, you can edit `config/config.php` directly to set database credentials and other settings.

### 4. Install Dependencies

```bash
# PHP dependencies (via Composer)
composer install --no-dev --optimize-autoloader

# Frontend vendor assets (via npm)
npm install
npm run copy-assets
```

> On a production server, you may want to run these commands on your development machine and copy the generated `vendor/` and `templates/default/vendor/` directories to the server, especially if Node.js or Composer are not available.

### 5. Set Permissions

The `tmp/` directory must be writable by the web server:

```bash
chmod -R 775 tmp/
chown -R www-data:www-data tmp/

# If using SQLite, also:
chmod -R 775 databases/sqlite/
chown -R www-data:www-data databases/sqlite/
```

### 6. Configure the Web Server

#### Apache

The application uses `index.php` as the front controller. Enable `mod_rewrite` and configure:

```apache
<VirtualHost *:80>
    DocumentRoot /var/www/html/simpleinvoices
    ServerName invoices.yourdomain.com

    <Directory /var/www/html/simpleinvoices>
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

The included `.htaccess` already handles URL rewriting for clean URLs.

#### Nginx

```nginx
server {
    listen 80;
    server_name invoices.yourdomain.com;
    root /var/www/html/simpleinvoices;
    index index.php index.html;

    client_max_body_size 10000M;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location /docs/ {
        try_files $uri $uri/ =404;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
    }
}
```

### 7. Run the Application

Open `http://your-server/` in your browser. The application will:

1. Detect that the database needs initializing
2. Run the first-run wizard to create tables and default data
3. Prompt you to set the administrator password

## Post-Installation

After the first run completes:

- Log in with your administrator credentials
- Configure email settings under **Settings → System Preferences**
- Add billers (**People → Billers**), customers (**People → Customers**), and products (**Products → Manage Products**)
- Review and adjust tax rates and payment types

## Updating

To update an existing manual installation:

```bash
git pull
composer install --no-dev --optimize-autoloader
npm install && npm run copy-assets
```

Then visit the application — database patches will be detected and can be applied manually from **Settings → Options → Database Patches**, or automatically if configured.

## Troubleshooting

### Blank page or 500 error

Check the PHP error log:

```bash
tail -f tmp/log/php.log
```

Enable display errors temporarily by setting `SI_PHP_DISPLAY_ERRORS=1` in `.env.local`.

### Database connection error

Verify:

- Database credentials in `.env.local` match your database setup
- The database server is running and accessible
- PHP extensions are installed: `php -m | grep pdo`

### Permission denied on tmp/

The `tmp/cache/`, `tmp/log/`, and `tmp/database_backups/` directories must be writable:

```bash
chmod -R 775 tmp/
chown -R www-data:www-data tmp/
```

### Missing vendor assets (broken CSS/JS)

Frontend assets are built with npm:

```bash
npm install
npm run copy-assets
```

Verify that `templates/default/vendor/` contains the expected subdirectories (tabler-core, tabler-icons, tom-select, etc.).

### mod_rewrite not working (Apache)

Check that:

- `AllowOverride All` is set in your Apache virtual host configuration
- The `.htaccess` file exists in the application root
- `mod_rewrite` is enabled: `a2enmod rewrite && systemctl restart apache2`

### Cron for recurring invoices

Recurring invoices require a cron job to trigger generation:

```bash
*/5 * * * * /usr/bin/php /var/www/html/simpleinvoices/cli/cron.php
```

Add this to your crontab to automatically generate recurring invoices every 5 minutes.
