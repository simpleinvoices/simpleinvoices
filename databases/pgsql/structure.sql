-- Simple Invoices - PostgreSQL Schema
-- Converted from MySQL structure; requires PostgreSQL 9.5+

CREATE TABLE IF NOT EXISTS si_biller (
  id           SERIAL,
  domain_id    INTEGER NOT NULL DEFAULT 1,
  name         VARCHAR(255) DEFAULT NULL,
  street_address  VARCHAR(255) DEFAULT NULL,
  street_address2 VARCHAR(255) DEFAULT NULL,
  city         VARCHAR(255) DEFAULT NULL,
  state        VARCHAR(255) DEFAULT NULL,
  zip_code     VARCHAR(20) DEFAULT NULL,
  country      VARCHAR(255) DEFAULT NULL,
  phone        VARCHAR(255) DEFAULT NULL,
  mobile_phone VARCHAR(255) DEFAULT NULL,
  fax          VARCHAR(255) DEFAULT NULL,
  email        VARCHAR(255) DEFAULT NULL,
  logo         VARCHAR(255) DEFAULT NULL,
  footer       TEXT,
  paymentsgateway_api_id       VARCHAR(255) DEFAULT NULL,
  notes                        TEXT,
  custom_field1                VARCHAR(255) DEFAULT NULL,
  custom_field2                VARCHAR(255) DEFAULT NULL,
  custom_field3                VARCHAR(255) DEFAULT NULL,
  custom_field4                VARCHAR(255) DEFAULT NULL,
  enabled                      SMALLINT NOT NULL DEFAULT 1,
  stripe_secret_key            VARCHAR(768) DEFAULT NULL,
  stripe_webhook_secret        VARCHAR(768) DEFAULT NULL,
  stripe_test_mode             SMALLINT NOT NULL DEFAULT 1,
  paypal_client_id             VARCHAR(255) DEFAULT NULL,
  paypal_client_secret         VARCHAR(768) DEFAULT NULL,
  paypal_test_mode             SMALLINT NOT NULL DEFAULT 1,
  mollie_api_key               VARCHAR(768) DEFAULT NULL,
  authorizenet_login_id        VARCHAR(768) DEFAULT NULL,
  authorizenet_transaction_key VARCHAR(768) DEFAULT NULL,
  authorizenet_signature_key   VARCHAR(768) DEFAULT NULL,
  authorizenet_test_mode       SMALLINT NOT NULL DEFAULT 1,
  eway_api_key                 VARCHAR(768) DEFAULT NULL,
  eway_api_password            VARCHAR(768) DEFAULT NULL,
  eway_test_mode               SMALLINT NOT NULL DEFAULT 1,
  kofi_username                VARCHAR(100) DEFAULT NULL,
  coinbase_api_key             VARCHAR(768) DEFAULT NULL,
  coinbase_webhook_secret      VARCHAR(768) DEFAULT NULL,
  adyen_api_key                VARCHAR(768) DEFAULT NULL,
  adyen_merchant_account       VARCHAR(255) DEFAULT NULL,
  adyen_hmac_key               VARCHAR(768) DEFAULT NULL,
  adyen_live_prefix            VARCHAR(100) DEFAULT NULL,
  adyen_test_mode              SMALLINT NOT NULL DEFAULT 1,
  bank_account_name            VARCHAR(255) DEFAULT NULL,
  bank_name                    VARCHAR(255) DEFAULT NULL,
  bank_swift_bic               VARCHAR(50) DEFAULT NULL,
  bank_account_number          VARCHAR(100) DEFAULT NULL,
  bank_routing_sort_code       VARCHAR(50) DEFAULT NULL,
  PRIMARY KEY (domain_id, id)
);

CREATE TABLE IF NOT EXISTS si_cron (
  id               SERIAL,
  domain_id        INTEGER NOT NULL,
  invoice_id       INTEGER NOT NULL,
  start_date       DATE NOT NULL,
  end_date         VARCHAR(10) DEFAULT NULL,
  recurrence       INTEGER NOT NULL,
  recurrence_type  VARCHAR(11) NOT NULL,
  email_biller     SMALLINT NOT NULL DEFAULT 0,
  email_customer   SMALLINT NOT NULL DEFAULT 0,
  PRIMARY KEY (domain_id, id)
);

CREATE TABLE IF NOT EXISTS si_cron_log (
  id        SERIAL,
  domain_id INTEGER NOT NULL,
  cron_id   VARCHAR(25) DEFAULT NULL,
  run_date  DATE NOT NULL,
  PRIMARY KEY (domain_id, id),
  UNIQUE (domain_id, cron_id, run_date)
);

CREATE TABLE IF NOT EXISTS si_custom_fields (
  cf_id           SERIAL,
  cf_custom_field VARCHAR(255) DEFAULT NULL,
  cf_custom_label VARCHAR(255) DEFAULT NULL,
  cf_display      SMALLINT NOT NULL DEFAULT 1,
  domain_id       INTEGER NOT NULL,
  PRIMARY KEY (cf_id, domain_id)
);

CREATE TABLE IF NOT EXISTS si_customers (
  id             SERIAL,
  domain_id      INTEGER NOT NULL DEFAULT 1,
  attention      VARCHAR(255) DEFAULT NULL,
  name           VARCHAR(255) DEFAULT NULL,
  department     VARCHAR(255) DEFAULT NULL,
  street_address  VARCHAR(255) DEFAULT NULL,
  street_address2 VARCHAR(255) DEFAULT NULL,
  city           VARCHAR(255) DEFAULT NULL,
  state          VARCHAR(255) DEFAULT NULL,
  zip_code       VARCHAR(20) DEFAULT NULL,
  country        VARCHAR(255) DEFAULT NULL,
  phone          VARCHAR(255) DEFAULT NULL,
  mobile_phone   VARCHAR(255) DEFAULT NULL,
  fax            VARCHAR(255) DEFAULT NULL,
  email          VARCHAR(255) DEFAULT NULL,
  notes          TEXT,
  custom_field1  VARCHAR(255) DEFAULT NULL,
  custom_field2  VARCHAR(255) DEFAULT NULL,
  custom_field3  VARCHAR(255) DEFAULT NULL,
  custom_field4  VARCHAR(255) DEFAULT NULL,
  enabled        SMALLINT NOT NULL DEFAULT 1,
  PRIMARY KEY (domain_id, id)
);

CREATE TABLE IF NOT EXISTS si_extensions (
  id          SERIAL,
  domain_id   INTEGER NOT NULL,
  name        VARCHAR(255) NOT NULL,
  description VARCHAR(255) NOT NULL,
  enabled     SMALLINT NOT NULL DEFAULT 0,
  PRIMARY KEY (id, domain_id)
);

CREATE TABLE IF NOT EXISTS si_index (
  id         INTEGER NOT NULL,
  node       VARCHAR(255) NOT NULL,
  sub_node   VARCHAR(255) DEFAULT NULL,
  sub_node_2 VARCHAR(255) DEFAULT NULL,
  domain_id  INTEGER NOT NULL
);

CREATE TABLE IF NOT EXISTS si_inventory (
  id         SERIAL,
  domain_id  INTEGER NOT NULL,
  product_id INTEGER NOT NULL,
  quantity   NUMERIC(25,6) NOT NULL,
  cost       NUMERIC(25,6) DEFAULT NULL,
  date       DATE NOT NULL,
  note       TEXT,
  PRIMARY KEY (domain_id, id)
);

CREATE TABLE IF NOT EXISTS si_invoice_item_tax (
  id              SERIAL,
  invoice_item_id INTEGER NOT NULL,
  tax_id          INTEGER NOT NULL,
  tax_type        CHAR(1) NOT NULL DEFAULT '%',
  tax_rate        NUMERIC(25,6) NOT NULL,
  tax_amount      NUMERIC(25,6) NOT NULL,
  PRIMARY KEY (id),
  UNIQUE (invoice_item_id, tax_id)
);

CREATE TABLE IF NOT EXISTS si_invoice_items (
  id          SERIAL,
  invoice_id  INTEGER NOT NULL DEFAULT 0,
  domain_id   INTEGER NOT NULL DEFAULT 1,
  quantity    NUMERIC(25,6) NOT NULL DEFAULT 0,
  product_id  INTEGER DEFAULT 0,
  unit_price  NUMERIC(25,6) DEFAULT 0,
  tax_amount  NUMERIC(25,6) DEFAULT 0,
  gross_total NUMERIC(25,6) DEFAULT 0,
  description TEXT,
  total       NUMERIC(25,6) DEFAULT 0,
  attribute   VARCHAR(255) DEFAULT NULL,
  PRIMARY KEY (id)
);
CREATE INDEX IF NOT EXISTS si_invoice_items_invoice_id ON si_invoice_items (invoice_id);
CREATE INDEX IF NOT EXISTS si_invoice_items_domain_inv ON si_invoice_items (invoice_id, domain_id);
CREATE INDEX IF NOT EXISTS si_ii_dom_invoice ON si_invoice_items (domain_id, invoice_id);

CREATE TABLE IF NOT EXISTS si_invoice_type (
  inv_ty_id          SERIAL,
  inv_ty_description VARCHAR(25) NOT NULL DEFAULT '',
  PRIMARY KEY (inv_ty_id)
);

CREATE TABLE IF NOT EXISTS si_payment_terms (
  term_id    SERIAL,
  term_code  VARCHAR(32) NOT NULL UNIQUE,
  term_label VARCHAR(120) NOT NULL,
  calc_kind  VARCHAR(32) NOT NULL,
  param_int  INTEGER DEFAULT NULL,
  sort_order INTEGER NOT NULL DEFAULT 0,
  PRIMARY KEY (term_id)
);

CREATE TABLE IF NOT EXISTS si_invoices (
  id            SERIAL,
  index_id      INTEGER NOT NULL,
  domain_id     INTEGER NOT NULL DEFAULT 1,
  biller_id     INTEGER NOT NULL DEFAULT 0,
  customer_id   INTEGER NOT NULL DEFAULT 0,
  type_id       INTEGER NOT NULL DEFAULT 0,
  preference_id INTEGER NOT NULL DEFAULT 0,
  date          TIMESTAMP NOT NULL DEFAULT '0001-01-01 00:00:00',
  custom_field1 VARCHAR(50) DEFAULT NULL,
  custom_field2 VARCHAR(50) DEFAULT NULL,
  custom_field3 VARCHAR(50) DEFAULT NULL,
  custom_field4 VARCHAR(50) DEFAULT NULL,
  note          TEXT,
  payment_term_id INTEGER DEFAULT NULL,
  due_date        DATE DEFAULT NULL,
  currency_sign   VARCHAR(50) DEFAULT NULL,
  currency_id INTEGER DEFAULT NULL,
  show_currency_code SMALLINT NOT NULL DEFAULT 0,
  denorm_invoice_total          NUMERIC(25,6) NOT NULL DEFAULT 0,
  denorm_amount_paid            NUMERIC(25,6) NOT NULL DEFAULT 0,
  denorm_amount_owing           NUMERIC(25,6) NOT NULL DEFAULT 0,
  denorm_biller_name            VARCHAR(255) NOT NULL DEFAULT '',
  denorm_customer_name          VARCHAR(255) NOT NULL DEFAULT '',
  denorm_index_name             VARCHAR(255) NOT NULL DEFAULT '',
  denorm_preference_description VARCHAR(255) NOT NULL DEFAULT '',
  denorm_preference_status      SMALLINT NOT NULL DEFAULT 0,
  PRIMARY KEY (domain_id, id)
);
CREATE INDEX IF NOT EXISTS si_invoices_domain_id  ON si_invoices (domain_id);
CREATE INDEX IF NOT EXISTS si_invoices_biller_id  ON si_invoices (biller_id);
CREATE INDEX IF NOT EXISTS si_invoices_customer_id ON si_invoices (customer_id);
CREATE UNIQUE INDEX IF NOT EXISTS si_invoices_uniq_dib ON si_invoices (index_id, preference_id, biller_id, domain_id);
CREATE INDEX IF NOT EXISTS si_invoices_idx_di ON si_invoices (index_id, preference_id, domain_id);
CREATE INDEX IF NOT EXISTS si_inv_dom_pref_date ON si_invoices (domain_id, preference_id, date);
CREATE INDEX IF NOT EXISTS si_inv_dom_cust ON si_invoices (domain_id, customer_id);
CREATE INDEX IF NOT EXISTS si_inv_dom_biller ON si_invoices (domain_id, biller_id);
CREATE INDEX IF NOT EXISTS si_inv_dom_idxid ON si_invoices (domain_id, index_id);
CREATE INDEX IF NOT EXISTS si_inv_dom_pstat_owing ON si_invoices (domain_id, denorm_preference_status, denorm_amount_owing);

CREATE TABLE IF NOT EXISTS si_log (
  id         BIGSERIAL,
  domain_id  INTEGER NOT NULL DEFAULT 1,
  timestamp  TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  userid     INTEGER NOT NULL DEFAULT 1,
  sqlquerie  TEXT NOT NULL,
  last_id    INTEGER DEFAULT NULL,
  PRIMARY KEY (id, domain_id)
);

CREATE TABLE IF NOT EXISTS si_payment (
  id                SERIAL,
  ac_inv_id         INTEGER NOT NULL,
  ac_amount         NUMERIC(25,6) NOT NULL,
  ac_notes          TEXT NOT NULL,
  ac_date           TIMESTAMP NOT NULL,
  ac_payment_type   INTEGER NOT NULL DEFAULT 1,
  domain_id         INTEGER NOT NULL,
  online_payment_id VARCHAR(255) DEFAULT NULL,
  denorm_invoice_index_name VARCHAR(255) NOT NULL DEFAULT '',
  denorm_biller_name        VARCHAR(255) NOT NULL DEFAULT '',
  denorm_customer_name    VARCHAR(255) NOT NULL DEFAULT '',
  denorm_currency_sign      VARCHAR(50) NOT NULL DEFAULT '',
  PRIMARY KEY (domain_id, id)
);
CREATE INDEX IF NOT EXISTS si_payment_domain_id ON si_payment (domain_id);
CREATE INDEX IF NOT EXISTS si_payment_ac_inv_id ON si_payment (ac_inv_id);
CREATE INDEX IF NOT EXISTS si_payment_ac_amount ON si_payment (ac_amount);
CREATE INDEX IF NOT EXISTS si_pay_dom_ac_date ON si_payment (domain_id, ac_date);
CREATE INDEX IF NOT EXISTS si_pay_dom_ac_inv ON si_payment (domain_id, ac_inv_id);

CREATE TABLE IF NOT EXISTS si_payment_types (
  pt_id          SERIAL,
  domain_id      INTEGER NOT NULL DEFAULT 1,
  pt_description VARCHAR(250) NOT NULL,
  pt_enabled     SMALLINT NOT NULL DEFAULT 1,
  PRIMARY KEY (domain_id, pt_id)
);

CREATE TABLE IF NOT EXISTS si_currencies (
  id                SERIAL PRIMARY KEY,
  domain_id         INTEGER DEFAULT 1,
  currency_code     VARCHAR(10) DEFAULT '',
  currency_sign     VARCHAR(50) DEFAULT '',
  currency_position VARCHAR(25) DEFAULT 'left',
  is_default        SMALLINT DEFAULT 0,
  enabled           SMALLINT DEFAULT 1
);
CREATE INDEX IF NOT EXISTS idx_currencies_domain ON si_currencies (domain_id);

CREATE TABLE IF NOT EXISTS si_preferences (
  pref_id                    SERIAL,
  domain_id                  INTEGER NOT NULL DEFAULT 1,
  pref_description           VARCHAR(255) DEFAULT NULL,
  pref_currency_sign         VARCHAR(255) DEFAULT NULL,
  pref_inv_heading           VARCHAR(255) DEFAULT NULL,
  pref_inv_wording           VARCHAR(255) DEFAULT NULL,
  pref_inv_detail_heading    VARCHAR(255) DEFAULT NULL,
  pref_inv_detail_line       TEXT,
  pref_inv_payment_method    VARCHAR(255) DEFAULT NULL,
  pref_inv_payment_line1_name  VARCHAR(255) DEFAULT NULL,
  pref_inv_payment_line1_value VARCHAR(255) DEFAULT NULL,
  pref_inv_payment_line2_name  VARCHAR(255) DEFAULT NULL,
  pref_inv_payment_line2_value VARCHAR(255) DEFAULT NULL,
  pref_enabled               SMALLINT NOT NULL DEFAULT 1,
  status                     SMALLINT NOT NULL,
  locale                     VARCHAR(255) DEFAULT NULL,
  language                   VARCHAR(255) DEFAULT NULL,
  index_group                INTEGER NOT NULL,
  currency_id                INTEGER DEFAULT NULL,
  show_currency_code         SMALLINT NOT NULL DEFAULT 0,
  payment_term_id            INTEGER DEFAULT NULL,
  payment_bank_name          VARCHAR(255) DEFAULT NULL,
  payment_reference          VARCHAR(255) DEFAULT NULL,
  PRIMARY KEY (domain_id, pref_id)
);

CREATE TABLE IF NOT EXISTS si_products (
  id                   SERIAL,
  domain_id            INTEGER NOT NULL DEFAULT 1,
  description          TEXT NOT NULL,
  unit_price           NUMERIC(25,6) DEFAULT 0,
  default_tax_id       INTEGER DEFAULT NULL,
  default_tax_id_2     INTEGER DEFAULT NULL,
  cost                 NUMERIC(25,6) DEFAULT 0,
  reorder_level        INTEGER DEFAULT NULL,
  custom_field1        VARCHAR(255) DEFAULT NULL,
  custom_field2        VARCHAR(255) DEFAULT NULL,
  custom_field3        VARCHAR(255) DEFAULT NULL,
  custom_field4        VARCHAR(255) DEFAULT NULL,
  notes                TEXT DEFAULT NULL,
  enabled              SMALLINT NOT NULL DEFAULT 1,
  visible              SMALLINT NOT NULL DEFAULT 1,
  attribute            VARCHAR(255) DEFAULT NULL,
  notes_as_description CHAR(1) DEFAULT NULL,
  show_description     CHAR(1) DEFAULT NULL,
  PRIMARY KEY (domain_id, id)
);

CREATE TABLE IF NOT EXISTS si_products_attribute_type (
  id   SERIAL,
  name VARCHAR(255) NOT NULL,
  PRIMARY KEY (id)
);

CREATE TABLE IF NOT EXISTS si_products_attributes (
  id        SERIAL,
  domain_id INTEGER NOT NULL DEFAULT 1,
  name      VARCHAR(255) NOT NULL,
  type_id   VARCHAR(255) NOT NULL,
  enabled   SMALLINT NOT NULL DEFAULT 1,
  visible   SMALLINT NOT NULL DEFAULT 1,
  PRIMARY KEY (id)
);
CREATE INDEX IF NOT EXISTS idx_pa_domain_id ON si_products_attributes (domain_id);

CREATE TABLE IF NOT EXISTS si_products_values (
  id           SERIAL,
  domain_id    INTEGER NOT NULL DEFAULT 1,
  attribute_id INTEGER NOT NULL,
  value        VARCHAR(255) NOT NULL,
  enabled      SMALLINT NOT NULL DEFAULT 1,
  PRIMARY KEY (id)
);
CREATE INDEX IF NOT EXISTS idx_pv_domain_id ON si_products_values (domain_id);

CREATE TABLE IF NOT EXISTS si_sql_patchmanager (
  sql_id        SERIAL,
  sql_patch_ref INTEGER NOT NULL,
  sql_patch     VARCHAR(255) DEFAULT NULL,
  sql_release   VARCHAR(25) DEFAULT NULL,
  sql_statement TEXT DEFAULT NULL,
  PRIMARY KEY (sql_id)
);

CREATE TABLE IF NOT EXISTS si_system_defaults (
  id           SERIAL,
  name         VARCHAR(30) NOT NULL,
  value        VARCHAR(30) DEFAULT NULL,
  domain_id    INTEGER NOT NULL DEFAULT 0,
  extension_id INTEGER NOT NULL DEFAULT 0,
  PRIMARY KEY (domain_id, id),
  UNIQUE (domain_id, name)
);

CREATE TABLE IF NOT EXISTS si_global_config (
  name  VARCHAR(64) NOT NULL PRIMARY KEY,
  value TEXT
);

CREATE TABLE IF NOT EXISTS si_tax (
  tax_id          SERIAL,
  tax_description VARCHAR(50) DEFAULT NULL,
  tax_percentage  NUMERIC(25,6) DEFAULT 0,
  type            CHAR(1) NOT NULL DEFAULT '%',
  tax_enabled     SMALLINT NOT NULL DEFAULT 1,
  domain_id       INTEGER NOT NULL,
  PRIMARY KEY (domain_id, tax_id)
);

CREATE TABLE IF NOT EXISTS si_user (
  id        SERIAL,
  email     VARCHAR(255) DEFAULT NULL,
  name      VARCHAR(255) DEFAULT NULL,
  role_id   INTEGER DEFAULT NULL,
  domain_id INTEGER NOT NULL DEFAULT 0,
  password  VARCHAR(64) DEFAULT NULL,
  enabled   SMALLINT NOT NULL DEFAULT 1,
  user_id   INTEGER NOT NULL DEFAULT 0,
  auth_staff_email   VARCHAR(255) DEFAULT NULL,
  auth_customer_key  VARCHAR(384) DEFAULT NULL,
  preferred_language VARCHAR(32) DEFAULT NULL,
  PRIMARY KEY (domain_id, id)
);
CREATE UNIQUE INDEX IF NOT EXISTS si_user_unq_auth_staff_email ON si_user (auth_staff_email);
CREATE UNIQUE INDEX IF NOT EXISTS si_user_unq_auth_customer_key ON si_user (auth_customer_key);

CREATE TABLE IF NOT EXISTS si_user_domain (
  id   SERIAL,
  name VARCHAR(191) NOT NULL,
  PRIMARY KEY (id),
  UNIQUE (name)
);

CREATE TABLE IF NOT EXISTS si_user_role (
  id   SERIAL,
  name VARCHAR(191) NOT NULL,
  PRIMARY KEY (id),
  UNIQUE (name)
);
