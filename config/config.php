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

local.locale                        = en_GB

email.host                          = localhost
email.smtp_auth                     = false
email.username                      = 
email.password                      = 
email.smtpport                      = 25
email.secure                        = 
email.ack                           = false
email.use_local_sendmail            = false

encryption.default.key              = this_is_the_encryption_key_change_it
nonce.key                           = this_should_be_random_and_secret_so_change_it
nonce.timelimit                     = 3600

version.name                        = 2013.1.beta.8

; Header branding (top-left logo and name). Override in custom.config.php or via Docker env SI_APP_NAME / SI_APP_LOGO
app.name                            = Simple Invoices
app.logo                            = 
app.website                         = http://www.simpleinvoices.org
app.website_label                   = Website
app.footer_link1_label              = Simple Invoices
app.footer_link1_url                = http://www.simpleinvoices.org
app.footer_link2_label              = Forum
app.footer_link2_url                = http://www.simpleinvoices.org/+
app.footer_link3_label              = Blog
app.footer_link3_url                = http://www.simpleinvoices.org/blog
app.footer_link4_label              = Support
app.footer_link4_url                = http://www.simpleinvoices.org/forum
app.footer_text                     = Thank you for using
 
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
