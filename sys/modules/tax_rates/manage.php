<?php

//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

$SI_TAX = new SimpleInvoices_Db_Table_Tax();

$smarty -> assign("taxes", $SI_TAX->fetchAll());

$smarty -> assign('pageActive', 'tax_rate');
$smarty -> assign('active_tab', '#setting');
?>