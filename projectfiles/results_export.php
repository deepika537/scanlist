<?php
// Database Connection
ob_start();
session_start();
 require_once 'dbconnect.php';
 if( !isset($_SESSION['user']) ) {
  header("Location: login.php");
  exit;
 }
 // select loggedin users detail
 $csv_list = $_SESSION['array_name'];
 $res=mysql_query("SELECT * FROM users WHERE userId=".$_SESSION['user'], $conn);
 $userRow=mysql_fetch_array($res);
if(isset($_POST["Export"])){
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=results.csv');

    $file = fopen("php://output","w");

    foreach ($csv_list as $line)
    {
    fputcsv($file,explode(',',$line));
    }

    fclose($file);
  ob_end_flush();
}
 ?>
