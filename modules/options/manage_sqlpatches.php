<?php

//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();


$smarty -> assign("patches",getSQLPatches());

getRicoLiveGrid("rico_sqlpatches","{ type:'number', decPlaces:0, ClassName:'alignleft' },,{ type:'number', decPlaces:0, ClassName:'alignleft'}");

?>
