<?php
//Simpleinvoices requirements
$postMaxSize = 24;
$memoryLimit = 24;
//GD required
//xsl required
//config writeable
//cache writeable

function checkPostMaxSize() {
	global $post_max_size;
	
	$post_max_size = ini_get('post_max_size');
	
    if (substr($post_max_size, 0, strlen($post_max_size)-1) >= $postMaxSize) {
        return true;
    } else if (substr($post_max_size, 0, strlen($post_max_size)-1) < $postMaxSize) {
        return false;
    }
}



function checkMemoryLimit() {
	global $memory_limit;
	
	$memory_limit = ini_get('memory_limit');
	
	
    if (substr($memory_limit, 0, strlen($memory_limit)-1) >= $memoryLimit) {
        return true;
    } else if (substr($memory_limit, 0, strlen($memory_limit)-1) < $memoryLimit) {
        return false;
    }
}

// Control library PDO existence
function checkPDO()
{
    if (extension_loaded('pdo')) {
        return true;
    } else {
        return false;
    }
}

// Control library GD existence
function checkGD()
{
    if (extension_loaded('gd')) {
        return true;
    } else {
        return false;
    }
}

// Control xslt existence
function checkXSLT()
{
    if (extension_loaded('xsl')) {
        return true;
    } else {
        return false;
    }
}

function checkConfigPermissions() {
	
	if(is_writable('../config/config.php')) {
		return true;
	}
	else {
		return false;
	}
}

function checkCachePermissions() {
	
	if(is_writable('../cache/')) {
		return true;
	}
	else {
		return false;
	}
}
?>
