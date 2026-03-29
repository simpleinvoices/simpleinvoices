<?php

$auth_session->destroy();
session_regenerate_id(true);

$siBase = rtrim(str_replace('\\', '', dirname($_SERVER['PHP_SELF'])), '/');
header('Location: ' . $siBase . '/?module=auth&view=login');
exit;
