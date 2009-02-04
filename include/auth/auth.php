<?php

if (!isset($auth_session->id))
{
	if  ($_GET['module'] !== "auth")  
	{
		header('Location: index.php?module=auth&view=login');       
		exit;
	}

}
