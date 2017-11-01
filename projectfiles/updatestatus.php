<?php
ob_start();
session_start();
require_once 'dbconnect.php';
// if session is not set this will redirect to login page
if( !isset($_SESSION['user']) ) {
 header("Location: login.php");
 exit;
}
// select loggedin users detail
$res=mysql_query("SELECT * FROM users WHERE userId=".$_SESSION['user'],$conn);
$userRow=mysql_fetch_array($res);
$date = date('Y-m-d H:i:s');
$url=$_POST['url'];
$depth=$_POST['depth'];
$response=$_POST['response'];
//echo $response;
if($response=="no new content added")
{mysql_query("UPDATE CRITERIA SET Mark_read=0,Status='".$date."' ,Results='No change' WHERE URL='".$url."' and DEPTH=".$depth." and USER='".$userRow['userName']."'",$conn);}
elseif($response=="no new content added for searched keywords")
{mysql_query("UPDATE CRITERIA SET Mark_read=0,Status='".$date."' ,Results='No new results found for specified keywords' WHERE URL='".$url."' and DEPTH=".$depth." and USER='".$userRow['userName']."'",$conn);}
elseif($response=="page not found"){
  mysql_query("UPDATE CRITERIA SET Mark_read=0,Status='".$date."' ,Results='Error occured (404)' WHERE URL='".$url."' and DEPTH=".$depth." and USER='".$userRow['userName']."'",$conn);
}
elseif(strpos($response,"The following page has changed!")!==false){
mysql_query("UPDATE CRITERIA SET Mark_read=0,Status='".$date."' ,Results='New results' WHERE URL='".$url."' and DEPTH=".$depth." and USER='".$userRow['userName']."'",$conn);
}
elseif(strpos($response,"childurl not Inserted")!==false){
mysql_query("UPDATE CRITERIA SET Mark_read=0,Status='".$date."' ,Results='Error Occured' WHERE URL='".$url."' and DEPTH=".$depth." and USER='".$userRow['userName']."'",$conn);
}
else {
  mysql_query("UPDATE CRITERIA SET Mark_read=0,Status='".$date."' ,Results='Error occured (Time out)' WHERE URL='".$url."' and DEPTH=".$depth." and USER='".$userRow['userName']."'",$conn);
}
//echo $response;
    $dir = 'Results/';
    $file = md5($url.$depth);
    $filename = $dir.$file.'.html';
    file_put_contents($filename, $response);
?>
