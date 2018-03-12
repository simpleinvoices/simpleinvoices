;<?php @header("location: ../index.php"); exit(0);?>
; Simple Invoices configuration file
; - refer http://www.simpleinvoices.org/config for all options

; Production site configuration data
[production]
database.adapter        			= pdo_mysql
database.utf8            			= true
database.params.host     			= localhost
database.params.username 			= simpleinvoicesus
database.params.password 			= '8tQVdtqCfBmPUdr3'
database.params.dbname   			= simpleinvoices
database.params.port       			= 3306

authentication.enabled	 			= true
authentication.http 				=

export.spreadsheet	     			= xls
export.wordprocessor	 			= doc
export.pdf.screensize 	 			= 800
export.pdf.papersize  	 			= A4
export.pdf.leftmargin	 			= 15
export.pdf.rightmargin	 			= 15
export.pdf.topmargin	 			= 15
export.pdf.bottommargin 			= 15

local.locale	    				= en_CA
local.precision		    			= 2

email.host 				            = localhost
email.smtp_auth			    		= false
email.username			    		=
email.password 			    		=
email.smtpport			    		= 25
email.secure      		    		=
email.ack 				            = false
email.use_local_sendmail            = false

encryption.default.key 				= kjsdhfklsjdhflksdjh_shgdjkgfsdjkgfsdjkgfkjasyasdanbsadsd
nonce.key                           = jkhsdfjkshkjfhjk_ljksdfhkjsdfhkjsdhf_ebererberhjsdhusdfy7766yhkshdf663246124
nonce.timelimit                     = 3600

version.name				    	= 2013.1.beta.8

debug.level 				    	= All
debug.error_reporting				= E_ERROR
phpSettings.date.timezone 			= America/Toronto
phpSettings.display_startup_errors  = 1
phpSettings.display_errors 			= 0
phpSettings.log_errors   			= 1
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
