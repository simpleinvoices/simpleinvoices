<?php

/*
API calls don't use the auth module 
*/
if ($module != 'api'){
	if (!isset($auth_session->id)){
	  if(!isset($_GET['module'])) {
	    $_GET['module'] = '';
	  }
		if  ($_GET['module'] !== "auth") {
		    error_log('login from '. isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '');
			header('Location: index.php?module=auth&view=login');       
			exit;
		}

	}
}
