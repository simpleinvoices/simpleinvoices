# Development Setup

Get a local development environment running to contribute to Simple Invoices.

## Prerequisites

| Requirement | Version |
|-------------|---------|
| **PHP** | 8.1+ (8.4 recommended) |
| **Composer** | Latest |
| **Node.js** | 20+ |
| **npm** | 9+ |
| **Database** | MySQL 5.7+, MariaDB 10+, PostgreSQL 12+, or SQLite 3 |

## Quick Start

```bash
# Clone from any mirror
git clone https://github.com/simpleinvoices/simpleinvoices.git
# or:  git clone https://codeberg.org/simpleinvoices/simpleinvoices.git
# or:  git clone https://git.sr.ht/~simpleinvoices/simpleinvoices
# or:  git clone https://git.simpleinvoices.org/simpleinvoices.git

cd simpleinvoices

# PHP dependencies
composer install

# Frontend dependencies
npm install

# Copy vendor assets (Tabler, charts, icons, etc.)
npm run copy-assets

# Build documentation site
npm run build-docs

# Start dev server for docs (optional)
npm run dev-docs
```

## Configuration

Copy the default config and edit for your environment:

```bash
cp config/config.php.example config/config.php
```

Edit `config/config.php` with your database settings:

```php
'database' => 'mysql',     // mysql, pgsql, or sqlite
'dbHost'   => 'localhost',
'dbName'   => 'simpleinvoices',
'dbUser'   => 'root',
'dbPassword' => '',
```

For SQLite (zero-config):

```php
'database' => 'sqlite',
```

No database server needed: the database file is created automatically at `databases/sqlite/simpleinvoices.sqlite`.

## Project Structure

```
simpleinvoices/
├── config/
│   ├── config.php           # Database and system configuration
│   └── define.php           # Constants and definitions
├── include/
│   ├── class/               # Business logic classes
│   │   ├── invoice.php      # Invoice CRUD operations
│   │   ├── customer.php     # Customer management
│   │   ├── product.php      # Product/service handling
│   │   ├── index.php        # Auto-increment ID manager
│   │   ├── email.php        # PHPMailer email wrapper
│   │   └── cron.php         # Recurring invoice engine
│   ├── functions.php        # Utility functions
│   ├── sql_queries.php      # Database query functions
│   ├── init.php             # Bootstrap and autoloading
│   └── js/                  # JavaScript modules
│       └── si-help-modal.js # In-app help modal
├── lang/                    # 41+ language translations
├── library/
│   ├── Zend/                # Zend Framework 1 (ZF1-Future)
│   └── phpmailer/           # PHPMailer 6.10
├── modules/                 # Feature controllers
│   ├── invoices/            # Invoice create/edit/manage
│   ├── payments/            # Payment processing
│   ├── customers/           # Customer management
│   ├── billers/             # Biller management
│   ├── products/            # Product catalog
│   ├── preferences/         # Invoice preferences
│   ├── cron/                # Recurring invoice setup
│   └── api/                 # REST API endpoints
├── templates/
│   ├── default/             # Main app UI (Blade)
│   └── invoices/            # Invoice PDF templates
├── databases/               # SQL schema files
│   ├── mysql/
│   ├── postgresql/
│   └── sqlite/
├── docs-rspress/            # Documentation site (Rspress)
│   ├── docs/                # Markdown source
│   ├── rspress.config.ts    # Site configuration
│   └── package.json         # Build dependencies
├── docs/                    # Built docs output (generated)
├── tmp/                     # Cache, logs, backups
├── Dockerfile               # Docker build
├── composer.json            # PHP dependencies
└── package.json             # NPM dependencies & scripts
```

## NPM Scripts

| Script | Purpose |
|--------|---------|
| `npm run copy-assets` | Copy vendor JS/CSS to `templates/default/vendor/` |
| `npm run build-docs` | Build Rspress documentation to `docs/` |
| `npm run dev-docs` | Start Rspress dev server at `localhost:3000` |

## Contributing Workflow

1. Fork the repo on your preferred platform
2. Create a feature branch: `git checkout -b my-feature`
3. Make your changes following existing conventions
4. Test thoroughly: no automated test suite, test manually
5. Submit a pull request with a clear description

## Conventions

- **PHP**: Follow existing patterns in the codebase. Use PDO prepared statements for all queries.
- **Templates**: Use Laravel Blade (`.blade.php`). Check `templates/default/` for examples.
- **JavaScript**: Vanilla JS or jQuery. New files in `include/js/`.
- **Documentation**: Rspress markdown in `docs-rspress/docs/`. Use `npm run dev-docs` to preview.
