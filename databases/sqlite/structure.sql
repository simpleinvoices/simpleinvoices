-- Simple Invoices - SQLite Schema
-- Converted from MySQL structure; requires SQLite 3.24+

CREATE TABLE IF NOT EXISTS si_biller (
  id             INTEGER PRIMARY KEY AUTOINCREMENT,
  domain_id      INTEGER NOT NULL DEFAULT 1,
  name           TEXT DEFAULT NULL,
  street_address  TEXT DEFAULT NULL,
  street_address2 TEXT DEFAULT NULL,
  city           TEXT DEFAULT NULL,
  state          TEXT DEFAULT NULL,
  zip_code       TEXT DEFAULT NULL,
  country        TEXT DEFAULT NULL,
  phone          TEXT DEFAULT NULL,
  mobile_phone   TEXT DEFAULT NULL,
  fax            TEXT DEFAULT NULL,
  email          TEXT DEFAULT NULL,
  logo           TEXT DEFAULT NULL,
  footer         TEXT,
  paypal_business_name   TEXT DEFAULT NULL,
  paypal_notify_url      TEXT DEFAULT NULL,
  paypal_return_url      TEXT DEFAULT NULL,
  eway_customer_id       TEXT DEFAULT NULL,
  paymentsgateway_api_id TEXT DEFAULT NULL,
  notes          TEXT,
  custom_field1  TEXT DEFAULT NULL,
  custom_field2  TEXT DEFAULT NULL,
  custom_field3  TEXT DEFAULT NULL,
  custom_field4  TEXT DEFAULT NULL,
  enabled        INTEGER NOT NULL DEFAULT 1
);
CREATE UNIQUE INDEX IF NOT EXISTS si_biller_pk ON si_biller (domain_id, id);

CREATE TABLE IF NOT EXISTS si_cron (
  id              INTEGER PRIMARY KEY AUTOINCREMENT,
  domain_id       INTEGER NOT NULL,
  invoice_id      INTEGER NOT NULL,
  start_date      TEXT NOT NULL,
  end_date        TEXT DEFAULT NULL,
  recurrence      INTEGER NOT NULL,
  recurrence_type TEXT NOT NULL,
  email_biller    INTEGER NOT NULL DEFAULT 0,
  email_customer  INTEGER NOT NULL DEFAULT 0
);
CREATE UNIQUE INDEX IF NOT EXISTS si_cron_pk ON si_cron (domain_id, id);

CREATE TABLE IF NOT EXISTS si_cron_log (
  id        INTEGER PRIMARY KEY AUTOINCREMENT,
  domain_id INTEGER NOT NULL,
  cron_id   TEXT DEFAULT NULL,
  run_date  TEXT NOT NULL
);
CREATE UNIQUE INDEX IF NOT EXISTS si_cron_log_pk  ON si_cron_log (domain_id, id);
CREATE UNIQUE INDEX IF NOT EXISTS si_cron_log_unq ON si_cron_log (domain_id, cron_id, run_date);

CREATE TABLE IF NOT EXISTS si_custom_fields (
  cf_id           INTEGER PRIMARY KEY AUTOINCREMENT,
  cf_custom_field TEXT DEFAULT NULL,
  cf_custom_label TEXT DEFAULT NULL,
  cf_display      INTEGER NOT NULL DEFAULT 1,
  domain_id       INTEGER NOT NULL
);
CREATE UNIQUE INDEX IF NOT EXISTS si_custom_fields_pk ON si_custom_fields (cf_id, domain_id);

CREATE TABLE IF NOT EXISTS si_customers (
  id             INTEGER PRIMARY KEY AUTOINCREMENT,
  domain_id      INTEGER NOT NULL DEFAULT 1,
  attention      TEXT DEFAULT NULL,
  name           TEXT DEFAULT NULL,
  department     TEXT DEFAULT NULL,
  street_address  TEXT DEFAULT NULL,
  street_address2 TEXT DEFAULT NULL,
  city           TEXT DEFAULT NULL,
  state          TEXT DEFAULT NULL,
  zip_code       TEXT DEFAULT NULL,
  country        TEXT DEFAULT NULL,
  phone          TEXT DEFAULT NULL,
  mobile_phone   TEXT DEFAULT NULL,
  fax            TEXT DEFAULT NULL,
  email          TEXT DEFAULT NULL,
  notes          TEXT,
  custom_field1  TEXT DEFAULT NULL,
  custom_field2  TEXT DEFAULT NULL,
  custom_field3  TEXT DEFAULT NULL,
  custom_field4  TEXT DEFAULT NULL,
  enabled        INTEGER NOT NULL DEFAULT 1
);
CREATE UNIQUE INDEX IF NOT EXISTS si_customers_pk ON si_customers (domain_id, id);

CREATE TABLE IF NOT EXISTS si_extensions (
  id          INTEGER PRIMARY KEY AUTOINCREMENT,
  domain_id   INTEGER NOT NULL,
  name        TEXT NOT NULL,
  description TEXT NOT NULL,
  enabled     INTEGER NOT NULL DEFAULT 0
);
CREATE UNIQUE INDEX IF NOT EXISTS si_extensions_pk ON si_extensions (id, domain_id);

CREATE TABLE IF NOT EXISTS si_index (
  id         INTEGER NOT NULL,
  node       TEXT NOT NULL,
  sub_node   TEXT DEFAULT NULL,
  sub_node_2 TEXT DEFAULT NULL,
  domain_id  INTEGER NOT NULL
);

CREATE TABLE IF NOT EXISTS si_inventory (
  id         INTEGER PRIMARY KEY AUTOINCREMENT,
  domain_id  INTEGER NOT NULL,
  product_id INTEGER NOT NULL,
  quantity   REAL NOT NULL,
  cost       REAL DEFAULT NULL,
  date       TEXT NOT NULL,
  note       TEXT
);
CREATE UNIQUE INDEX IF NOT EXISTS si_inventory_pk ON si_inventory (domain_id, id);

CREATE TABLE IF NOT EXISTS si_invoice_item_tax (
  id              INTEGER PRIMARY KEY AUTOINCREMENT,
  invoice_item_id INTEGER NOT NULL,
  tax_id          INTEGER NOT NULL,
  tax_type        TEXT NOT NULL DEFAULT '%',
  tax_rate        REAL NOT NULL,
  tax_amount      REAL NOT NULL,
  UNIQUE (invoice_item_id, tax_id)
);

CREATE TABLE IF NOT EXISTS si_invoice_items (
  id          INTEGER PRIMARY KEY AUTOINCREMENT,
  invoice_id  INTEGER NOT NULL DEFAULT 0,
  domain_id   INTEGER NOT NULL DEFAULT 1,
  quantity    REAL NOT NULL DEFAULT 0,
  product_id  INTEGER DEFAULT 0,
  unit_price  REAL DEFAULT 0,
  tax_amount  REAL DEFAULT 0,
  gross_total REAL DEFAULT 0,
  description TEXT,
  total       REAL DEFAULT 0,
  attribute   TEXT DEFAULT NULL
);
CREATE INDEX IF NOT EXISTS si_invoice_items_invoice_id ON si_invoice_items (invoice_id);
CREATE INDEX IF NOT EXISTS si_invoice_items_domain_inv ON si_invoice_items (invoice_id, domain_id);

CREATE TABLE IF NOT EXISTS si_invoice_type (
  inv_ty_id          INTEGER PRIMARY KEY AUTOINCREMENT,
  inv_ty_description TEXT NOT NULL DEFAULT ''
);

CREATE TABLE IF NOT EXISTS si_invoices (
  id            INTEGER PRIMARY KEY AUTOINCREMENT,
  index_id      INTEGER NOT NULL,
  domain_id     INTEGER NOT NULL DEFAULT 1,
  biller_id     INTEGER NOT NULL DEFAULT 0,
  customer_id   INTEGER NOT NULL DEFAULT 0,
  type_id       INTEGER NOT NULL DEFAULT 0,
  preference_id INTEGER NOT NULL DEFAULT 0,
  date          TEXT NOT NULL DEFAULT '0000-00-00 00:00:00',
  custom_field1 TEXT DEFAULT NULL,
  custom_field2 TEXT DEFAULT NULL,
  custom_field3 TEXT DEFAULT NULL,
  custom_field4 TEXT DEFAULT NULL,
  note          TEXT
);
CREATE UNIQUE INDEX IF NOT EXISTS si_invoices_pk         ON si_invoices (domain_id, id);
CREATE INDEX IF NOT EXISTS si_invoices_domain_id          ON si_invoices (domain_id);
CREATE INDEX IF NOT EXISTS si_invoices_biller_id          ON si_invoices (biller_id);
CREATE INDEX IF NOT EXISTS si_invoices_customer_id        ON si_invoices (customer_id);
CREATE UNIQUE INDEX IF NOT EXISTS si_invoices_uniq_dib    ON si_invoices (index_id, preference_id, biller_id, domain_id);
CREATE INDEX IF NOT EXISTS si_invoices_idx_di             ON si_invoices (index_id, preference_id, domain_id);

CREATE TABLE IF NOT EXISTS si_log (
  id        INTEGER PRIMARY KEY AUTOINCREMENT,
  domain_id INTEGER NOT NULL DEFAULT 1,
  timestamp TEXT NOT NULL DEFAULT (datetime('now')),
  userid    INTEGER NOT NULL DEFAULT 1,
  sqlquerie TEXT NOT NULL,
  last_id   INTEGER DEFAULT NULL
);
CREATE UNIQUE INDEX IF NOT EXISTS si_log_pk ON si_log (id, domain_id);

CREATE TABLE IF NOT EXISTS si_payment (
  id                INTEGER PRIMARY KEY AUTOINCREMENT,
  ac_inv_id         INTEGER NOT NULL,
  ac_amount         REAL NOT NULL,
  ac_notes          TEXT NOT NULL,
  ac_date           TEXT NOT NULL,
  ac_payment_type   INTEGER NOT NULL DEFAULT 1,
  domain_id         INTEGER NOT NULL,
  online_payment_id TEXT DEFAULT NULL
);
CREATE UNIQUE INDEX IF NOT EXISTS si_payment_pk         ON si_payment (domain_id, id);
CREATE INDEX IF NOT EXISTS si_payment_domain_id          ON si_payment (domain_id);
CREATE INDEX IF NOT EXISTS si_payment_ac_inv_id          ON si_payment (ac_inv_id);
CREATE INDEX IF NOT EXISTS si_payment_ac_amount          ON si_payment (ac_amount);

CREATE TABLE IF NOT EXISTS si_payment_types (
  pt_id          INTEGER PRIMARY KEY AUTOINCREMENT,
  domain_id      INTEGER NOT NULL DEFAULT 1,
  pt_description TEXT NOT NULL,
  pt_enabled     INTEGER NOT NULL DEFAULT 1
);
CREATE UNIQUE INDEX IF NOT EXISTS si_payment_types_pk ON si_payment_types (domain_id, pt_id);

CREATE TABLE IF NOT EXISTS si_preferences (
  pref_id                    INTEGER PRIMARY KEY AUTOINCREMENT,
  domain_id                  INTEGER NOT NULL DEFAULT 1,
  pref_description           TEXT DEFAULT NULL,
  pref_currency_sign         TEXT DEFAULT NULL,
  pref_inv_heading           TEXT DEFAULT NULL,
  pref_inv_wording           TEXT DEFAULT NULL,
  pref_inv_detail_heading    TEXT DEFAULT NULL,
  pref_inv_detail_line       TEXT,
  pref_inv_payment_method    TEXT DEFAULT NULL,
  pref_inv_payment_line1_name  TEXT DEFAULT NULL,
  pref_inv_payment_line1_value TEXT DEFAULT NULL,
  pref_inv_payment_line2_name  TEXT DEFAULT NULL,
  pref_inv_payment_line2_value TEXT DEFAULT NULL,
  pref_enabled               INTEGER NOT NULL DEFAULT 1,
  status                     INTEGER NOT NULL,
  locale                     TEXT DEFAULT NULL,
  language                   TEXT DEFAULT NULL,
  index_group                INTEGER NOT NULL,
  currency_code              TEXT DEFAULT NULL,
  include_online_payment     TEXT DEFAULT NULL,
  currency_position          TEXT DEFAULT NULL
);
CREATE UNIQUE INDEX IF NOT EXISTS si_preferences_pk ON si_preferences (domain_id, pref_id);

CREATE TABLE IF NOT EXISTS si_products (
  id                   INTEGER PRIMARY KEY AUTOINCREMENT,
  domain_id            INTEGER NOT NULL DEFAULT 1,
  description          TEXT NOT NULL,
  unit_price           REAL DEFAULT 0,
  default_tax_id       INTEGER DEFAULT NULL,
  default_tax_id_2     INTEGER DEFAULT NULL,
  cost                 REAL DEFAULT 0,
  reorder_level        INTEGER DEFAULT NULL,
  custom_field1        TEXT DEFAULT NULL,
  custom_field2        TEXT DEFAULT NULL,
  custom_field3        TEXT DEFAULT NULL,
  custom_field4        TEXT DEFAULT NULL,
  notes                TEXT DEFAULT NULL,
  enabled              INTEGER NOT NULL DEFAULT 1,
  visible              INTEGER NOT NULL DEFAULT 1,
  attribute            TEXT DEFAULT NULL,
  notes_as_description TEXT DEFAULT NULL,
  show_description     TEXT DEFAULT NULL
);
CREATE UNIQUE INDEX IF NOT EXISTS si_products_pk ON si_products (domain_id, id);

CREATE TABLE IF NOT EXISTS si_products_attribute_type (
  id   INTEGER PRIMARY KEY AUTOINCREMENT,
  name TEXT NOT NULL
);

CREATE TABLE IF NOT EXISTS si_products_attributes (
  id      INTEGER PRIMARY KEY AUTOINCREMENT,
  name    TEXT NOT NULL,
  type_id TEXT NOT NULL,
  enabled INTEGER NOT NULL DEFAULT 1,
  visible INTEGER NOT NULL DEFAULT 1
);

CREATE TABLE IF NOT EXISTS si_products_values (
  id           INTEGER PRIMARY KEY AUTOINCREMENT,
  attribute_id INTEGER NOT NULL,
  value        TEXT NOT NULL,
  enabled      INTEGER NOT NULL DEFAULT 1
);

CREATE TABLE IF NOT EXISTS si_sql_patchmanager (
  sql_id        INTEGER PRIMARY KEY AUTOINCREMENT,
  sql_patch_ref INTEGER NOT NULL,
  sql_patch     TEXT DEFAULT NULL,
  sql_release   TEXT DEFAULT NULL,
  sql_statement TEXT DEFAULT NULL
);

CREATE TABLE IF NOT EXISTS si_system_defaults (
  id           INTEGER PRIMARY KEY AUTOINCREMENT,
  name         TEXT NOT NULL,
  value        TEXT DEFAULT NULL,
  domain_id    INTEGER NOT NULL DEFAULT 0,
  extension_id INTEGER NOT NULL DEFAULT 0,
  UNIQUE (domain_id, name)
);
CREATE UNIQUE INDEX IF NOT EXISTS si_system_defaults_pk ON si_system_defaults (domain_id, id);

CREATE TABLE IF NOT EXISTS si_tax (
  tax_id          INTEGER PRIMARY KEY AUTOINCREMENT,
  tax_description TEXT DEFAULT NULL,
  tax_percentage  REAL DEFAULT 0,
  type            TEXT NOT NULL DEFAULT '%',
  tax_enabled     INTEGER NOT NULL DEFAULT 1,
  domain_id       INTEGER NOT NULL
);
CREATE UNIQUE INDEX IF NOT EXISTS si_tax_pk ON si_tax (domain_id, tax_id);

CREATE TABLE IF NOT EXISTS si_user (
  id        INTEGER PRIMARY KEY AUTOINCREMENT,
  email     TEXT DEFAULT NULL,
  name      TEXT DEFAULT NULL,
  role_id   INTEGER DEFAULT NULL,
  domain_id INTEGER NOT NULL DEFAULT 0,
  password  TEXT DEFAULT NULL,
  enabled   INTEGER NOT NULL DEFAULT 1,
  user_id   INTEGER NOT NULL DEFAULT 0,
  UNIQUE (email)
);
CREATE UNIQUE INDEX IF NOT EXISTS si_user_pk ON si_user (domain_id, id);

CREATE TABLE IF NOT EXISTS si_user_domain (
  id   INTEGER PRIMARY KEY AUTOINCREMENT,
  name TEXT NOT NULL,
  UNIQUE (name)
);

CREATE TABLE IF NOT EXISTS si_user_role (
  id   INTEGER PRIMARY KEY AUTOINCREMENT,
  name TEXT NOT NULL,
  UNIQUE (name)
);
