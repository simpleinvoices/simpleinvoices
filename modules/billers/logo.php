<?php

checkLogin();

// Clear any output buffered before us (header, etc.)
while (ob_get_level() > 0) {
    ob_end_clean();
}

$biller_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($biller_id <= 0) {
    http_response_code(400);
    exit(0);
}

$biller = getBiller($biller_id);
if (!$biller || !si_check_record_access($biller)) {
    http_response_code(404);
    exit(0);
}

$logo = $biller['logo'] ?? '';
if (empty($logo)) {
    header('Location: templates/invoices/logos/_default_blank_logo.png');
    exit(0);
}

$is_uuid = preg_match('/^[a-f0-9]{36}\.(png|jpg|jpeg|gif|webp)$/i', $logo);

if ($is_uuid) {
    $domain_id = (int)($biller['domain_id'] ?? 1);
    $stream = S3LogoStore::getStream($domain_id, $logo);
    if ($stream !== null && is_resource($stream)) {
        $mime = S3LogoStore::getMimeType($domain_id, $logo) ?: 'image/png';
        $cacheSeconds = 86400;
        header('Content-Type: ' . $mime);
        header('Cache-Control: public, max-age=' . $cacheSeconds);
        header('ETag: "' . md5($logo) . '"');
        fpassthru($stream);
        fclose($stream);
        exit(0);
    }
}

$localPath = './templates/invoices/logos/' . basename($logo);
if (file_exists($localPath)) {
    $ext = strtolower(pathinfo($localPath, PATHINFO_EXTENSION));
    $mimeMap = [
        'png'  => 'image/png',
        'jpg'  => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'gif'  => 'image/gif',
    ];
    $mime = $mimeMap[$ext] ?? 'image/png';
    $cacheSeconds = 86400;
    header('Content-Type: ' . $mime);
    header('Cache-Control: public, max-age=' . $cacheSeconds);
    header('ETag: "' . md5_file($localPath) . '"');
    header('Content-Length: ' . filesize($localPath));
    readfile($localPath);
    exit(0);
}

http_response_code(404);
exit(0);
