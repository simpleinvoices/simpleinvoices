<?php
// Serve markdown files from docs/ — bypasses web server static-file config issues.
// Docsify fetches .md files via XHR; this endpoint reads and returns raw markdown.

$file = isset($_GET['file']) ? trim($_GET['file']) : '';
$file = ltrim($file, '/');
$file = urldecode($file);

if (empty($file)) {
    http_response_code(400);
    die('Missing file parameter');
}

// Security: only allow a-z, 0-9, hyphens, underscores, dots, and forward slashes
if (preg_match('/[^a-zA-Z0-9_\-\/\.]/', $file)) {
    http_response_code(403);
    die('Invalid file path');
}
// Prevent directory traversal attacks
if (strpos($file, '..') !== false || strpos($file, '//') !== false) {
    http_response_code(403);
    die('Invalid file path');
}

// Resolve to the docs/ directory alongside the project root
$path = __DIR__ . '/../../docs/' . $file;

if (!file_exists($path) || !is_file($path)) {
    http_response_code(404);
    header('Content-Type: text/plain; charset=utf-8');
    die('File not found: ' . $file);
}

// Only serve specific file types from the docs/ directory
$ext = pathinfo($path, PATHINFO_EXTENSION);
if (!in_array($ext, ['md', 'html'], true)) {
    http_response_code(403);
    die('File type not allowed');
}

header('Content-Type: text/markdown; charset=utf-8');
header('Cache-Control: public, max-age=3600');
readfile($path);
exit;
