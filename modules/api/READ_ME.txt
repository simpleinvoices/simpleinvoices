Made changes so that the services can work:

1. In simpleinvoices\config\config.php - added xapikey=webServices_beta7 

2. Create simpleinvoices\modules\api\Controllers folder where to put the controller files
 
3. In simpleinvoices\.htaccess - substitute "-" with "/" in  

   OLD:
   RewriteRule ^/?([a-zA-Z0-9_]+)-([a-zA-Z0-9_]+)-([a-zA-Z0-9_]+)?$ index.php?module=$1&view=$2&id=$3
   RewriteRule ^/?([a-zA-Z0-9_]+)-([a-zA-Z0-9_]+)$ index.php?module=$1&view=$2 [L]
   NEW:
   RewriteRule ^/?([a-zA-Z0-9_]+)/([a-zA-Z0-9_]+)/([a-zA-Z0-9_]+)?$ index.php?module=$1&view=$2&id=$3
   RewriteRule ^/?([a-zA-Z0-9_]+)/([a-zA-Z0-9_]+)$ index.php?module=$1&view=$2 [L]

   for more convenient path to the services

4. In simpleinvoices\library\pdf\destination_interface.class.php - substitute regex "/[^a-z0-9-]/i" with "/[^\w0-9-]/u" to catch cyrillic characters.

5. In simpleinvoices\modules\invoices\email.php - made changes in order the code to pass trough simpleinvoices\library\pdf\destination_interface.class.php

6. In simpleinvoices\library\encryption.php - delete last row "?>" in order to return the response code 201(created) from the service

7. In simpleinvoices\modules\invoices\save.php - change line 87 substitute "$auth_session->domain_id" with "$domain_id"
   otherwise the products are not updated