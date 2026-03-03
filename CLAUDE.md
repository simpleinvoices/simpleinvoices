# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

Simple Invoices is a PHP-based web application for invoice management that has been running since 2005. It's a traditional LAMP stack application using PHP with the Zend Framework v1.11, MySQL database, and Smarty templating engine.

## Architecture

### Core Structure
- **Entry Point**: `index.php` - Main controller handling all requests via modules/views pattern
- **Configuration**: `config/config.php` - INI-style config file with database, email, and system settings
- **Initialization**: `include/init.php` and `include/init_pre.php` - Bootstrap files that set up Zend Framework, Smarty, and core dependencies
- **Modules**: `modules/` directory contains feature-specific controllers (invoices, customers, billers, products, etc.)
- **Templates**: `templates/default/` contains Smarty template files for UI rendering
- **Classes**: `include/class/` contains business logic classes for core entities

### Key Dependencies
- **Zend Framework 1.24.4 (ZF1-Future)**: Located in `library/Zend/` - PHP 8.1+ compatible community fork
- **PHPMailer 6.10.0**: Modern email library in `library/phpmailer/` - Full PHP 8.1+ compatibility with namespaces
- **Smarty**: Template engine in `library/smarty/`
- **HTML2PDF**: PDF generation library in `library/pdf/`
- **jQuery**: Frontend JavaScript framework with plugins in `include/jquery/`

### Database Support
- Primary: MySQL (configured in `config/config.php`)
- Alternate: PostgreSQL and SQLite schemas available in `databases/` directory
- Database patches and migrations handled via `modules/options/database_sqlpatches.php`

### Module System
The application uses a modular architecture where each feature is organized in `modules/[feature]/`:
- **Core Modules**: invoices, customers, billers, products, payments, reports
- **Admin Modules**: system_defaults, custom_fields, extensions, user management
- **API Modules**: Basic API endpoints for external integrations

### Internationalization
- 41+ language translations in `lang/` directory
- Each language has `info.xml` and `lang.php` files
- Default locale configurable in `config/config.php` (currently en_GB)

### Custom Extensions
- Extension system in `extensions/` directory
- Custom templates and modules can be placed in `custom/` directory
- Template override system checks custom paths first via `GetCustomPath()` function

## Development Workflow

### Local Development Setup
1. Clone repository: `git clone [repo-url]`
2. **Zend Framework**: Now uses ZF1-Future (v1.24.4) which is compatible with PHP 8.1+
3. Configure database settings in `config/config.php`
4. Ensure `tmp/` directory is writable for caching and logs
5. Access via web server (typically Apache/Nginx with PHP 8.1+)

### PHP Compatibility
- **Current**: PHP 8.1+ fully supported (tested with PHP 8.4.5)
- **Zend Framework**: ZF1-Future community fork provides modern PHP compatibility
- **PHPMailer**: Version 6.10.0 with full PHP 8.1+ support including PHP 8.4
- **Email System**: Upgraded from deprecated Zend_Mail to modern PHPMailer with namespaces
- **Previous limitation**: Original ZF1 and PHPMailer 5.2.x were limited to older PHP versions

### Database Operations
- Schema files: `databases/mysql/Full_Simple_Invoices.sql` (complete) or `structure.sql` (schema only)
- Sample data: `databases/json/sample_data.json` and `essential_data.json`
- ERD available: `databases/mysql/SI_Schema_2013.1.beta.5.1_PKFK.png`

### Testing and Quality
- No automated testing framework currently implemented
- Manual testing via web interface
- Database backup functionality in `modules/options/backup_database.php`

### Security Considerations
- CSRF protection implemented (see nonce configuration)
- Input validation via `filenameEscape()` and similar functions
- Authentication system (optional, configurable in config.php)
- File access protection via `BROWSE` constant check

## Important Files and Locations

### Configuration
- `config/config.php` - Main application configuration
- `config/define.php` - System constants and definitions

### Core Business Logic
- `include/class/invoice.php` - Invoice management
- `include/class/customer.php` - Customer management  
- `include/class/product.php` - Product/service management
- `include/class/email.php` - Email handling via PHPMailer 6.10.0
- `include/functions.php` - Utility functions

### Key Templates
- `templates/default/main.tpl` - Main application layout
- `templates/invoices/` - Invoice-specific templates

### Logs and Temp Files
- `tmp/log/` - Application logs (si.log, php.log, paypal_ipn_results.log)
- `tmp/cache/` - Smarty template cache
- `tmp/database_backups/` - Generated database backups

## Version Information
- Current Version: 2013.1.beta.8
- License: GPL v3
- Last Stable: 2011.1 (bleeding edge master branch recommended)

## Notes for Development
- This is legacy PHP code that has been modernized for PHP 8.1+ compatibility
- **Email System**: Uses PHPMailer 6.10.0 with modern namespaces and PHP 8.4 support
- **Framework**: ZF1-Future provides updated Zend Framework 1.x compatibility
- No modern dependency management (Composer) - uses manual includes and git submodules
- Template caching and PHP include paths are critical for proper functioning
- All file access should go through `index.php` - direct file access is blocked
- Multi-language support is extensive but requires careful handling of text strings
- **SSL/TLS Support**: PHPMailer 6.10.0 includes improved encryption and security options