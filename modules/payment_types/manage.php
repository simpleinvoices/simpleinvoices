<?php

//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

$paymentTypes = getPaymentTypes();

$smarty -> assign('paymentTypes',$paymentTypes);

getRicoLiveGrid("rico_payment_types","{ type:'number', decPlaces:0, ClassName:'alignleft' }");
?>
