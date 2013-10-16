;<?php @header("location: ../index.php") and exit(0);?>
; Simple Invoices configuration file
; - refer http://www.simpleinvoices.org/config for all options

; Production site configuration data
[production]
database.adapter        			= pdo_mysql
database.utf8            			= true
database.params.host     			= localhost
database.params.username 			= root
database.params.password 			= ''
database.params.dbname   			= simple_invoices
database.params.port       			= 3306

authentication.enabled	 			= false
authentication.http 				= 

export.spreadsheet	     			= xls
export.wordprocessor	 			= doc
export.pdf.screensize 	 			= 800
export.pdf.papersize  	 			= A4
export.pdf.leftmargin	 			= 15
export.pdf.rightmargin	 			= 15
export.pdf.topmargin	 			= 15
export.pdf.bottommargin 			= 15

local.locale	    				= en_GB
local.precision		    			= 2

email.host 				            = localhost
email.smtp_auth			    		= false
email.username			    		=  
email.password 			    		= 
email.smtpport			    		= 25
email.secure      		    		= 
email.ack 				            = false
email.use_local_sendmail            = false

encryption.default.key 				= this_is_the_encryption_key_change_it
nonce.key                           = this_should_be_random_and_secret_so_change_it
nonce.timelimit                     = 3600

version.name				    	= 2013.1.beta.5
 
debug.level 				    	= All 
debug.error_reporting				= E_ERROR
phpSettings.date.timezone 			= Europe/London
phpSettings.display_startup_errors  = 1
phpSettings.display_errors 			= 1
phpSettings.log_errors   			= 0
phpSettings.error_log   			= tmp/log/php.log

; Explicity confirm delete of line items from invoices? (yes/no)
confirm.deleteLineItem				= no

; Staging site configuration data inherits from production and
; overrides values as necessary
[staging : production]
database.params.dbname 				= simple_invoices_staging
database.params.username			= devuser
database.params.password 			= devsecret

[dev : production]
database.params.dbname   			= simple_invoices_dev 
debug.error_reporting				= E_ALL
phpSettings.display_startup_errors 	= 1
phpSettings.display_errors 			= 1
