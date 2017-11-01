<?php
require_once 'dbconnect.php';
$sql = "SELECT * FROM childurls where url_id=".$_POST['urlid'];
 	$res = mysql_query($sql,$conn);
 	$returnarray = array();
 	while($row = mysql_fetch_assoc($res)) {
 		$rowarray = array(
 			"childurl"=> $row['childurl']
 		);
 		$returnarray[] = $rowarray;
 	}
 	mysql_free_result($res);
 	echo json_encode($returnarray);
 ?>
