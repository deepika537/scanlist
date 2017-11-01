<?php

 // this will avoid mysql_connect() deprecation error.
 error_reporting( ~E_DEPRECATED & ~E_NOTICE );
 // but I strongly suggest you to use PDO or MySQLi.

 define('DBHOST', '');
 define('DBUSER', '');
 define('DBPASS', '');
 define('DBNAME', 'scanlist');

 $conn = mysql_connect(DBHOST,DBUSER,DBPASS);

 if ( !$conn ) {
  die("Connection failed : " . mysql_error());
 }

 $dbcon = mysql_select_db(DBNAME, $conn);

 if ( !$dbcon ) {
  die("Database Connection failed : " . mysql_error());
 }
 ?>
