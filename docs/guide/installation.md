# Installation

Simple Invoices can be installed in two ways. Choose the one that fits your setup.

## <span class="badge bg-success me-1">Recommended</span> Docker

Docker is the fastest and simplest way to get up and running. Everything is pre-configured — database, PHP, web server, and frontend assets.

👉 **[Docker Installation →](guide/installation-docker.md)**

- No PHP or database setup required on your host
- Multi-stage build handles frontend assets automatically
- Supports MySQL, PostgreSQL, and SQLite
- Adminer included for database management
- Automatic database migration on startup

## Manual Installation

For advanced users who prefer full control, or when running on shared hosting / existing LAMP stacks.

👉 **[Manual Installation →](guide/installation-manual.md)**

- Requires PHP 8.1+, a web server, and a database
- Manual dependency installation with Composer and npm
- Full control over the server environment

## Post-Installation (both methods)

Once installed, follow the same first-run steps:

- Set the admin password on first login
- Configure email settings under **Settings → System Preferences**
- Add billers, customers, and products before creating invoices
- Review and adjust tax rates and payment types
