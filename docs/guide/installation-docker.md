# Docker Installation <span class="badge bg-success">Recommended</span>

Docker is the fastest way to run Simple Invoices. The included `Dockerfile` and `docker-compose.yml` handle everything — PHP, Nginx, database, and frontend assets.

## Quick Start

```bash
# Clone the repository
git clone https://github.com/simpleinvoices/simpleinvoices.git
cd simpleinvoices

# Copy and configure environment
cp .env .env.local

# Edit .env.local — set your database credentials and configuration
# The default settings use MySQL/MariaDB with sensible defaults.

# Start with MySQL/MariaDB (default)
docker compose up -d

# Open http://localhost:8888 in your browser
```

The application will be available at `http://localhost:8888`. Login with the default credentials if this is your first run.

## Choosing a Database

Simple Invoices supports three database backends. Switch by editing your `.env.local` file and using the matching compose command:

### MySQL / MariaDB (default)

```bash
# .env.local — leave the MySQL block uncommented (defaults)
docker compose up -d
```

### PostgreSQL

Comment out the MySQL block in `.env.local`, uncomment the PostgreSQL block, then:

```bash
docker compose -f docker-compose.yml -f docker-compose.pgsql.yml up -d
```

### SQLite (no separate DB container)

Comment out the MySQL block, uncomment the SQLite block:

```bash
docker compose -f docker-compose.yml -f docker-compose.sqlite.yml up -d
```

## Environment Configuration

Copy `.env` to `.env.local` and adjust the following key settings:

### Database

| Variable | Description | Default |
|----------|-------------|---------|
| `SI_DATABASE_ADAPTER` | `pdo_mysql`, `pdo_pgsql`, or `pdo_sqlite` | `pdo_mysql` |
| `SI_DB_HOST` | Database hostname | `db` |
| `SI_DB_PORT` | Database port | `3306` |
| `SI_DB_USER` | Database username | `root` |
| `SI_DB_PASSWORD` | Database password | `rootpassword` |
| `SI_DB_NAME` | Database name | `simple_invoices` |

### Application

| Variable | Description | Default |
|----------|-------------|---------|
| `APP_ENV` | Environment (`local`, `production`) | `local` |
| `APP_DEBUG` | Enable debug mode | `true` |
| `SI_APP_PORT` | Host port for the web app | `8888` |
| `DB_PORT` | Host port for the database | `3307` |
| `ADMINER_PORT` | Host port for Adminer | `8081` |

### Authentication

| Variable | Description | Default |
|----------|-------------|---------|
| `SI_AUTHENTICATION_ENABLED` | Enable user login | `true` |
| `SI_AUTHENTICATION_ALLOW_PUBLIC_DOMAIN_REGISTRATION` | Allow self-registration | `false` |
| `SI_NONCE_KEY` | CSRF protection key | Random string |

### Email

| Variable | Description | Default |
|----------|-------------|---------|
| `SI_EMAIL_HOST` | SMTP server hostname | `localhost` |
| `SI_EMAIL_SMTP_AUTH` | Require SMTP authentication | `false` |
| `SI_EMAIL_USERNAME` | SMTP username | (empty) |
| `SI_EMAIL_PASSWORD` | SMTP password | (empty) |
| `SI_EMAIL_SMTPPORT` | SMTP port | `25` |
| `SI_EMAIL_SECURE` | Encryption (`tls`, `ssl`, or empty) | (empty) |
| `SI_EMAIL_USE_LOCAL_SENDMAIL` | Use server sendmail | `false` |

### Debug & Logging

| Variable | Description | Default |
|----------|-------------|---------|
| `SI_DEBUG_LEVEL` | Log level (`ALL`, `DEBUG`, `INFO`, `WARN`, `ERROR`, `FATAL`, `OFF`) | `All` |
| `SI_DEBUG_ERROR_REPORTING` | PHP error reporting level | `E_ERROR` |
| `SI_PHP_DATE_TIMEZONE` | Server timezone | `Europe/London` |
| `SI_PHP_DISPLAY_ERRORS` | Show errors in browser | `1` |
| `SI_PHP_ERROR_LOG` | Error log path | `tmp/log/php.log` |
| `SI_DB_WAIT_MAX` | Seconds to wait for DB before timeout | `30` |
| `SI_AUTO_MIGRATE` | Auto-apply DB patches on startup | `true` |

### Security

| Variable | Description |
|----------|-------------|
| `SI_GATEWAY_SECRETS_KEY` | 32-byte hex key for encrypting payment gateway credentials. Generate with: `php -r 'echo bin2hex(random_bytes(32)), PHP_EOL;'` |

### S3 Storage (optional)

For storing biller logos on S3-compatible object storage:

| Variable | Description | Default |
|----------|-------------|---------|
| `SI_S3_ENABLED` | Enable S3 logo upload | `false` |
| `SI_S3_ENDPOINT` | S3 endpoint URL | (empty) |
| `SI_S3_KEY` | Access key | (empty) |
| `SI_S3_SECRET` | Secret key | (empty) |
| `SI_S3_BUCKET` | Bucket name | `si-biller-logos` |
| `SI_S3_REGION` | Region | `garage` |

> **Garage (self-hosted S3):** Run `docker compose -f docker-compose.yml -f docker-compose.s3.yml up -d`, then `bash scripts/setup-garage.sh` to create the bucket and generate credentials.

## Included Services

| Service | Description | Port |
|---------|-------------|------|
| **simpleinvoices** | PHP-FPM + Nginx serving the application | `8888` |
| **db** | MariaDB database (optional with SQLite) | `3307` |
| **adminer** | Web-based database management tool | `8081` |

Access Adminer at `http://localhost:8081` to browse or manage your database directly.

## Architecture

The Docker image is built with a **multi-stage Dockerfile**:

1. **Stage 1 (Node.js)** — Installs npm dependencies and copies frontend vendor assets into `templates/default/vendor/`. This stage is throwaway — only the built assets are kept.

2. **Stage 2 (PHP-FPM + Nginx)** — Alpine Linux with PHP 8.2, Nginx, and all required PHP extensions. Copies frontend assets from Stage 1, installs Composer dependencies, and configures Nginx to serve the application.

The final image contains **no Node.js** — only the compiled vendor assets. This keeps the image small and secure.

## Updating

To update to the latest version:

```bash
git pull
docker compose build --no-cache simpleinvoices
docker compose up -d
```

Database patches are applied automatically on startup (when `SI_AUTO_MIGRATE=true`).

## Stopping

```bash
# Stop all services (keeps volumes)
docker compose down

# Stop and remove volumes (deletes database data)
docker compose down -v
```

## Troubleshooting

### Port already in use

Change the `SI_APP_PORT` in `.env.local` to a free port (e.g., `8080`).

### Database connection refused

The app waits up to `SI_DB_WAIT_MAX` seconds (default 30) for the database to become ready. If you see connection errors, check that:

- The database service is running: `docker compose ps`
- Database credentials match those in `.env.local`
- The database adapter matches your chosen compose profile

### Permission errors on `tmp/`

The Dockerfile sets correct permissions automatically (`chown -R www-data:www-data`). If you manually bind-mount the `tmp/` directory, ensure the host directory is writable by UID 82 (www-data in Alpine).

### Rebuilding after dependency changes

```bash
docker compose build --no-cache simpleinvoices
docker compose up -d
```

No-cache builds ensure fresh npm and composer installs.
