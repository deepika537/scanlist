<?php
/*
* iTech Empires:  How to Import Data from CSV File to MySQL Using PHP Script
* Version: 1.0.0
* Page: Import.PHP
*/

// Database Connection
 ob_start();
session_start();
 require_once 'dbconnect.php';
 if( !isset($_SESSION['user']) ) {
  header("Location: login.php");
  exit;
 }
 // select loggedin users detail
 $res=mysql_query("SELECT * FROM users WHERE userId=".$_SESSION['user'],$conn);
 $userRow=mysql_fetch_array($res);
//LOAD DATA LOCAL INFILE '$file' REPLACE INTO TABLE products FIELDS TERMINATED BY ',' ENCLOSED BY '\"' ESCAPED BY '\\\' IGNORE 1 LINES (aw_product_id,merchant_id,merchant_image_url,aw_deep_link,description,in_stock,merchant_name,brand_name,display_price,product_name,rrp_price,merchant_category
// View records from the table
$output = '';
$query = "SELECT * FROM CRITERIA WHERE USER='".$userRow['userName']."'";
if (!$resultsec = mysql_query( $query,$conn)) {
    exit(mysql_error($conn));
}
$rows = array();
while($r = mysql_fetch_assoc($resultsec)) {
    $rows[] = $r;
}
//print json_encode($rows);
$response = array(
  'aaData' => $rows,
  'iTotalRecords' => count($rows),
  'iTotalDisplayRecords' => count($rows)
);
header('Content-type: application/json');
echo json_encode($response);
ob_end_flush();?>
