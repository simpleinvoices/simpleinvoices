<?php

/*
* Script: quantity_sold.php
* 	Quantity reports by product and period
*
* Authors:
*	 Laetitia Debruyne & Benjamin Delangue
*
* Last edited:
* 	 2015-06-05
*
* License:
*	 GPL v3
*
* Website:
* 	http://www.simpleinvoices.org
*/

checkLogin();

function firstOfMonth() {
	return date("Y-m-d", strtotime('01-01-'.date('Y').' 00:00:00'));
}

function lastOfMonth() {
	return date("Y-m-d", strtotime('31-12-'.date('Y').' 00:00:00'));
}

isset($_POST['filter_by_date']) ? $filter_by_date = $_POST['filter_by_date'] : $filter_by_date = 'no';
isset($_POST['start_date']) ? $start_date = $_POST['start_date'] : $start_date = firstOfMonth();
isset($_POST['end_date']) ? $end_date = $_POST['end_date'] : $end_date = lastOfMonth();

isset($_POST['product_id']) ? $product_id = $_POST['product_id'] : $product_id = '';


if (isset($_POST['submit']))
{

  $products_list_sql = "SELECT COUNT(*) as 'count', " . TB_PREFIX . "products.description as 'description'
                          FROM " . TB_PREFIX . "products
                          INNER JOIN " . TB_PREFIX . "invoice_items
                              ON " . TB_PREFIX . "invoice_items.product_id = " . TB_PREFIX . "products.id
                          INNER JOIN " . TB_PREFIX . "invoices
                              ON " . TB_PREFIX . "invoices.id = " . TB_PREFIX . "invoice_items.invoice_id
                          WHERE " . TB_PREFIX . "products.id = :product_id";

  if ($filter_by_date == 'yes') {
    $products_list_sql .= " AND " . TB_PREFIX . "invoices.date BETWEEN :date_start AND :date_end";
    $result = $db->query($products_list_sql, ':product_id', $product_id, ':date_start', $start_date, ':date_end', $end_date);
  } else {
    $result = $db->query($products_list_sql, ':product_id', $product_id);
  }

  $all = $result->fetchAll();

  foreach ($all as $row) {
    $count = $row['count'];
    $description = $row['description'];
  }

}

// Get products list
$products_list_sql = "SELECT * FROM  " . TB_PREFIX . "products";
$products_list = $db->query($products_list_sql);

// Design vars
$smarty -> assign('products_list', $products_list->fetchAll());
$smarty -> assign('filter_by_date', $filter_by_date);
$smarty -> assign('start_date', $start_date);
$smarty -> assign('end_date', $end_date);
$smarty->assign('count', $count);
$smarty->assign('product_id', $product_id);
$smarty->assign('description', $description);

$smarty -> assign('pageActive', 'report');
$smarty -> assign('active_tab', '#home');
$smarty -> assign('menu', $menu);
