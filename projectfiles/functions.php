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
 $res=mysql_query("SELECT * FROM users WHERE userId=".$_SESSION['user'],$conn);
 $userRow=mysql_fetch_array($res);
if(isset($_POST["Export"])){
     header('Content-Type: text/csv; charset=utf-8');
      header('Content-Disposition: attachment; filename=data.csv');
      $output = fopen("php://output", "w");
      fputcsv($output, array('ID', 'USER','URL','DEPTH','EMAIL','keyword1','keyword2','keyword3','keyword4','Status','logic1','logic2','logic3','Alerts','Cronvalue','Results','Mark_read','sendemail','Domain'));
      $query = "SELECT * FROM CRITERIA WHERE USER='".$userRow['userName']."'";
      $result = mysql_query($query,$conn);
      while($row = mysql_fetch_assoc($result))
      {
           fputcsv($output, $row);
      }
      fclose($output);
  ob_end_flush();
}
 ?>
