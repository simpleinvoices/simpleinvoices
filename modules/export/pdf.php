<?php

/**
 * Legacy entry: index.php?module=export&view=pdf&id=N
 * Redirects to the supported invoice PDF export route.
 */
$id = $_GET['id'] ?? '';
header('Location: index.php?module=export&view=invoice&id=' . rawurlencode((string) $id) . '&format=pdf');
exit;
