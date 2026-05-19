# Database Backup

The Database Backup page provides two export formats for backing up your Simple Invoices data.

## SQL Backup

Full database dump for the current database type (MySQL, PostgreSQL, or SQLite).

- **Download**: Saves a `.sql` file to your computer
- **View SQL**: Opens a modal with syntax-highlighted SQL you can copy or download
- **Includes**: All tables, data, indexes, and constraints

SQL backups are best for regular backups and restoring to the same database type.

## JSON Export

Cross-database data export in a database-independent format.

- **Download JSON**: Saves a `.json` file with all your data
- **View JSON**: Opens a modal with syntax-highlighted JSON you can copy or download

JSON exports are best for migrating between database types (MySQL → PostgreSQL, etc.). The format can be imported on any supported database.

## How to Use

1. Go to **Settings → Backup Database**
2. For SQL backup: click **Download Backup** or **View SQL** to preview
3. For JSON export: click **Download JSON** or **View JSON** to preview
4. Store downloaded files securely off-server

## Restoring from a Backup

**SQL backup restore:**

Via database management tool (phpMyAdmin, Adminer):

- Import the `.sql` file

Via command line:

```bash
mysql -u username -p database_name < backup.sql
```

**JSON backup restore:**

- Use the Import tool under **Settings → Options**

## Best Practices

- Schedule regular SQL backups
- Use JSON exports before migrating databases
- Store backups off-server
- Test restores periodically
- Keep multiple backup versions, not just the latest
