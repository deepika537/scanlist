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
 $read=$_POST['read'];
 $sql = "UPDATE CRITERIA SET Mark_read='".$read."' WHERE ID IN(".$_POST["id"].")";
 if(!mysql_query( $sql))
 {
      echo 'Data not updated';
 }
 ob_end_flush();
 ?>
