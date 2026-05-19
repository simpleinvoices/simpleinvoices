;<?php @header("location: ../index.php"); exit(0);?>
; Simple Invoices configuration file
; - refer http://www.simpleinvoices.org/config for all options

; Production site configuration data
[production]
database.adapter                    = pdo_mysql
database.utf8                       = true
database.params.host                = localhost
database.params.username            = root
database.params.password            = ''
database.params.dbname              = simple_invoices
database.params.port                = 3306

authentication.enabled              = true
; When true, the staff login page shows Register and anyone can create a new organisation (domain) and domain admin. Set false in production unless you intend open signup. Overridable via SI_AUTHENTICATION_ALLOW_PUBLIC_DOMAIN_REGISTRATION in Docker.
authentication.allow_public_domain_registration = false
authentication.http                 = 

; Locale for number/date formatting (Intl) and UI language: si_system_defaults.language (Settings → System Preferences)

; Export / PDF / precision / confirm line-item delete live in si_system_defaults (Settings → System Preferences)

email.host                          = localhost
email.smtp_auth                     = false
email.username                      = 
email.password                      = 
email.smtpport                      = 25
email.secure                        = 
email.use_local_sendmail            = false

; S3-compatible storage for biller logos (MinIO, AWS S3, etc.)
s3.enabled                          = false
s3.endpoint                         = 
s3.key                              = 
s3.secret                           = 
s3.bucket                           = 
s3.region                           = us-east-1

; 32-byte key for libsodium-encrypted gateway secrets in si_biller (64 hex chars, base64 of 32 bytes, or raw 32 bytes). Leave empty to store secrets as plaintext. Docker: SI_GATEWAY_SECRETS_KEY.
encryption.gateway_secrets.key      = 
nonce.key                           = this_should_be_random_and_secret_so_change_it
nonce.timelimit                     = 3600

version.name                        = 2013.1.beta.8

; Header/footer branding (app name, logo URL, footer links) is stored in the database
; table si_global_config after SQL patch 342. Site administrator: Admin → App appearance.
; Optional overrides in custom.config.php (app.name, app.logo, …) apply only until the DB
; holds a value for each key.
 
debug.level                         = All
debug.error_reporting               = E_ERROR
phpSettings.date.timezone           = Europe/London
phpSettings.display_startup_errors  = 1
phpSettings.display_errors          = 1
phpSettings.log_errors              = 0
phpSettings.error_log               = tmp/log/php.log

; Staging site configuration data inherits from production and
; overrides values as necessary
[staging : production]
database.params.dbname              = simple_invoices_staging
database.params.username            = devuser
database.params.password            = devsecret

[dev : production]
database.params.dbname              = simple_invoices_dev
debug.error_reporting               = E_ALL
phpSettings.display_startup_errors  = 1
phpSettings.display_errors          = 1
