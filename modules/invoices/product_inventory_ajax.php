<?php
global $output, $pdoDb;

if($_GET['id']) {
    $domain_id = domain_id::get();
    $pdoDb->addSimpleWhere("id", $_GET['id'], "AND");
    $pdoDb->addSimpleWhere("domain_id", $domain_id);
    $pdoDb->setLimit(1);
    $pdoDb->setSelectList("cost");
    $rows = $pdoDb->request("SELECT", "products");
    if (!empty($rows)) {
        $row = $rows;
        // Format with decimal places with precision for user's locale
        $output['cost'] = siLocal::number($row['cost']);
    } else {
        $output .= '';
    }

    echo json_encode($output);
    exit();
}

echo "";
