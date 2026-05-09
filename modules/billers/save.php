<?php
/*
* Script: save.php
* 	Biller save page
*
* Authors:
*	 Justin Kelly, Nicolas Ruflin
*
* Last edited:
* 	 2007-07-19
*
* License:
*	 GPL v2 or above
*
* Website:
* 	http://www.simpleinvoices.org
 */

//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

# Deal with op and add some basic sanity checking

$op = $_POST['op'] ?? null;

# Handle logo file upload (S3 storage)
$uploadedFile = $_FILES['logo_file'] ?? null;
if ($uploadedFile && !empty($uploadedFile['tmp_name']) && $uploadedFile['error'] === UPLOAD_ERR_OK) {
	$maxSize = 2 * 1024 * 1024; // 2MB
	if ($uploadedFile['size'] <= $maxSize) {
		$ext = strtolower(pathinfo($uploadedFile['name'], PATHINFO_EXTENSION));
		$allowed = ['png', 'jpg', 'jpeg', 'gif', 'webp'];
		if (in_array($ext, $allowed, true)) {
			$uuidFilename = S3LogoStore::upload($uploadedFile['tmp_name'], $uploadedFile['name']);
			if ($uuidFilename !== null) {
				// Delete old S3 logo if editing
				if ($op === 'edit_biller' && !empty($_POST['existing_logo'])) {
					$oldLogo = $_POST['existing_logo'];
					if (preg_match('/^[a-f0-9]{36}\.(png|jpg|jpeg|gif|webp)$/i', $oldLogo)) {
						S3LogoStore::delete($oldLogo);
					}
				}
				$_POST['logo'] = $uuidFilename;
			}
		}
	}
}

#insert biller

$saved = false;

if ( $op === 'insert_biller') {
	
	if (insertBiller()) {
 		$saved = true;
 		invoice_denorm::refreshAllForBiller((int) lastInsertId());
 	}
}

if ($op === 'edit_biller' ) {
	if (isset($_POST['save_biller']) && updateBiller()) {
		$saved = true;
		invoice_denorm::refreshAllForBiller((int) $_GET['id']);
	}
}


$bladeView -> assign('saved',$saved);

$bladeView -> assign('pageActive', 'biller');
$bladeView -> assign('active_tab', '#people');
