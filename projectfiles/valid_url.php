<?php
ob_start();
session_start();
 require_once 'dbconnect.php';
 if( !isset($_SESSION['user']) ) {
  header("Location: login.php");
  exit;
 }

function urlExists($url) {

    $handle = curl_init($url);
    curl_setopt($handle,  CURLOPT_RETURNTRANSFER, TRUE);

    $response = curl_exec($handle);
    $httpCode = curl_getinfo($handle, CURLINFO_HTTP_CODE);

    if($httpCode >= 200 && $httpCode < 400) {
        return true;
    } else {
        return false;
    }
    curl_close($handle);
}

 // select loggedin users detail
 $res=mysql_query("SELECT * FROM users WHERE userId=".$_SESSION['user'],$conn);
 $userRow=mysql_fetch_array($res);
 $user=$userRow['userName'];
 $url=$_POST['url'];
 $domain=$_POST['domain'];

 $check_url = mysql_query("SELECT * FROM CRITERIA WHERE URL = '$url' AND USER = '$user'",$conn);
 $url_rows = mysql_num_rows($check_url);

 $check_domain = mysql_query("SELECT * FROM CRITERIA WHERE Domain = '$domain' AND USER = '$user'",$conn);
 $domain_rows = mysql_num_rows($check_domain);


 if(!urlExists($url)){
     echo 'invalid';
 }
 elseif($url_rows == 0 && $domain_rows == 0){
     echo 'valid';
 }else{
     echo 'duplicate';
 }
ob_end_flush();
?>
