---
title: "PHP 8.1+ Upgrade Complete"
published_at: 2026-04-15 14:00:00
author: Simple Invoices Team
---

# PHP 8.1+ Upgrade Complete

Simple Invoices now fully supports PHP 8.1, 8.2, 8.3, and 8.4. Here's what changed under the hood.

## Key Upgrades

### Zend Framework → ZF1-Future

The original Zend Framework 1 reached end-of-life years ago and didn't support PHP 8+. We've migrated to the **ZF1-Future** community fork (v1.24.4) which provides full PHP 8.x compatibility while maintaining the same API.

### Email: Zend_Mail → PHPMailer 6.10

The old Zend_Mail library was deprecated and insecure. We've upgraded to **PHPMailer 6.10**: a modern, actively maintained email library with:

- Full SMTP authentication
- TLS 1.2/1.3 encryption
- HTML email with plain-text fallback
- Proper namespace support (PHPMailer 6 uses namespaces)

### Database Abstraction

All database queries now use PDO with prepared statements, providing better security and compatibility across MySQL, MariaDB, PostgreSQL, and SQLite.

### Modern PHP Features

The codebase now takes advantage of modern PHP features:

- Type declarations and return types
- Null coalescing operator (`??`)
- Namespaced classes for new components
- Composer autoloading for modern libraries

## Testing

Tested and verified on:
- PHP 8.1.0+
- PHP 8.2.0+
- PHP 8.3.0+
- PHP 8.4.5

## What This Means for You

- Your hosting provider's latest PHP version is supported
- Better performance (PHP 8.x is significantly faster than 7.x)
- Ongoing security updates from the PHP team
- Access to modern PHP libraries and tools
