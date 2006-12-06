 		<link rel="stylesheet" href="menu/layersmenu-gtk2.css" type="text/css"></link> 
 		 
 		<script language="JavaScript" type="text/javascript"> 
 		<!-- 
 		<?php require_once 'menu/include/layersmenu-browser_detection.js'; ?> 
 		// --> 
 		</script> 
 		<script language="JavaScript" type="text/javascript" src="menu/include/layersmenu-library.js"></script> 
 		<script language="JavaScript" type="text/javascript" src="menu/include/layersmenu.js"></script> 
 		<?php 
 		require_once 'menu/lib/PHPLIB.php'; 
 		require_once 'menu/lib/layersmenu-common.inc.php'; 
 		require_once 'menu/lib/layersmenu.inc.php'; 
 		//$mid = new LayersMenu(6, 7, 2, 5, 140); 
 		$mid = new LayersMenu(6, 7, 2, 1);      // Gtk2-like 
 		//$mid->setDownArrowImg('down-arrow.png'); 
 		//$mid->setForwardArrowImg('forward-arrow.png'); 
 		$mid->setMenuStructureFile("lang/$language.menu_text.txt"); 
$mid->setHorizontalMenuTpl('menu/templates/layersmenu-horizontal_menu-full.ihtml'); 
$mid->setIconsize(16, 16); 
$mid->parseStructureForMenu('hormenu1'); 
$mid->newHorizontalMenu('hormenu1'); 
$mid->printHeader(); 
?> 
