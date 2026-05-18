# Multi-Database Support

Simple Invoices supports four database engines. Choose the one that fits your infrastructure.

## Supported Databases

| Database | Best For |
|----------|----------|
| **MySQL 5.7+** | Traditional LAMP stacks, shared hosting |
| **MariaDB 10+** | Drop-in MySQL replacement, better performance |
| **PostgreSQL 12+** | Advanced features, JSON support, enterprise |
| **SQLite 3** | Single-user, testing, portable installations |

## SQLite for Zero-Config Setup

SQLite requires **no database server**: the entire database is a single file. Perfect for:

- Quick evaluation and testing
- Single-user installations
- Portable setups (copy the `.sqlite` file to migrate)
- Docker development environments

Enable SQLite by setting `database=sqlite` in `config/config.php`. The database file lives at `databases/sqlite/simpleinvoices.sqlite`.

## Switching Databases

Schema files are provided for each engine:

```
databases/
├── mysql/
│   ├── Full_Simple_Invoices.sql
│   └── structure.sql
├── postgresql/
│   └── structure.sql
└── sqlite/
    └── structure.sql
```

The database abstraction layer in `include/init.php` handles the differences between engines transparently.

## Docker Support

The Docker image supports all four database backends out of the box. Set `DB_ENGINE` to `mysql`, `mariadb`, `pgsql`, or `sqlite` in your environment variables.
