<?php
// Database Connection
require 'db_connection.php';
if(isset($_POST["Export"])){

       header('Content-Type: text/csv; charset=utf-8');
       header('Content-Disposition: attachment; filename=data.csv');
       $eoutput = fopen("php://output", "w");
       fputcsv($eoutput, array('No', 'Name', 'Mobile', 'Email'));
       $equery = "SELECT * FROM users";
       $eresult = mysqli_query($con, $equery);
       while($erow = mysqli_fetch_assoc($eresult))
       {
            fputcsv($eoutput, $erow);
       }
       fclose($eoutput);
  }
 ?>
