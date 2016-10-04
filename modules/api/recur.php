<?php

$ni = new Invoice();
$ni->id = $_GET['id'];
$ni->recur();
