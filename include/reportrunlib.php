<?php

	$oRpt->setSQL($sSQL);

	ob_start();
	$oRpt->run();
	$showReport = ob_get_contents();
   
	ob_end_clean();
   
	$pageActive = "reports";

	$smarty->assign('pageActive', $pageActive);
	$smarty->assign('showReport', $showReport);
	
?>