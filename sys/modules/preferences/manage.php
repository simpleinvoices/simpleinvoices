<?php

//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

$SI_PREFERENCES = new SimpleInvoices_Db_Table_Preferences();

$preferences = $SI_PREFERENCES->fetchAll();

$smarty -> assign("preferences",$preferences);

$smarty -> assign('pageActive', 'preference');
$smarty -> assign('active_tab', '#setting');
?>