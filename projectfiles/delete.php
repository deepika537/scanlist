<?php
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
 $sql = "DELETE FROM CRITERIA WHERE ID IN(".$_POST["ids"].")";
 if(mysql_query( $sql,$conn))
 {
      echo 'Data Deleted';
 }
 else {
   echo mysql_error($conn);
 }
 ob_end_flush();
 ?>
