<?php
include('./include/include_main.php');

//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

echo <<<EOD
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<body>
<b>Help</b>
 <hr></hr>
       <div id="left">
<br>
For information regarding the setup,installation, and use of Simple Invoices please refer to the Instructions sub-menu in the Option menu. <br>
<br>For other queries please refer to the Simple Invoices website <a href='http://www.simpleinvoices.org'>http://www.simpleinvoices.org</a> and the Simple Invoices forum at <a href="http://www.simpleinvoices.org/forum">http://www.simpleinvoices.org/forum</a>

</div>
EOD;
?>
<!-- ./src/include/design/footer.inc.php gets called here by controller srcipt -->
