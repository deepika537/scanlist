<?php
/*
* iTech Empires:  How to Import Data from CSV File to MySQL Using PHP Script
* Version: 1.0.0
* Page: Import.PHP
*/

// Database Connection
require 'db_connection.php';

$message = "";
if (isset($_POST['submit'])) {
    $allowed = array('csv');
    $filename = $_FILES['file']['name'];
    $ext = pathinfo($filename, PATHINFO_EXTENSION);
    if (!in_array($ext, $allowed)) {
        // show error message
        $message = 'Invalid file type, please use .CSV file!';
    } else {

        move_uploaded_file($_FILES["file"]["tmp_name"], "files/" . $_FILES['file']['name']);

        $file = "files/" . $_FILES['file']['name'];

        $query = <<<eof
        LOAD DATA LOCAL INFILE '$file'
         INTO TABLE users
         FIELDS TERMINATED BY ','
         LINES TERMINATED BY '\n'
         IGNORE 1 LINES
        (name,mobile,email)
eof;
        if (!$result = mysqli_query($con, $query)) {
            exit(mysqli_error($con));
        }
        $message = "CSV file successfully imported!";
    }
}
// View records from the table
$users = '<table class="table table-bordered">
<tr>
    <th>No</th>
    <th>Name</th>
    <th>Mobile</th>
    <th>Email</th>
</tr>
';
$query = "SELECT * FROM users";
if (!$result = mysqli_query($con, $query)) {
    exit(mysqli_error($con));
}
if (mysqli_num_rows($result) > 0) {
    $number = 1;
    while ($row = mysqli_fetch_assoc($result)) {
        $users .= '<tr>
            <td>' . $number . '</td>
            <td>' . $row['name'] . '</td>
            <td>' . $row['mobile'] . '</td>
            <td>' . $row['email'] . '</td>
        </tr>';
        $number++;
    }
} else {
    $users .= '<tr>
        <td colspan="4">Records not found!</td>
        </tr>';
}
$users .= '</table>';
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Import Data from CSV File to MySQL Tutorial</title>
    <!-- Bootstrap CSS File  -->
    <link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap.min.css"/>
</head>
<body>
<div class="container">
    <h2>
        Tutorial: How to Import Data from CSV File to MySQL Using PHP
    </h2>
    <br><br>

    <div class="row">
        <div class="col-md-6 col-md-offset-0">
            <form enctype="multipart/form-data" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                <div class="form-group">
                    <label for="file">Select .CSV file to Import</label>
                    <input name="file" type="file" class="form-control">
                </div>
                <div class="form-group">
                    <?php echo $message; ?>
                </div>
                <div class="form-group">
                    <input type="submit" name="submit" class="btn btn-primary" value="Submit"/>
                </div>
            </form>
            <div>
           <form class="form-horizontal" action="functions.php" method="post" name="upload_excel"
                     enctype="multipart/form-data">
                 <div class="form-group">
                           <div class="col-md-4 col-md-offset-4">
                               <input type="submit" name="Export" class="btn btn-success" value="export to excel"/>
                           </div>
                  </div>
           </form>
</div>
            <div class="form-group">
                <?php echo $users; ?>
            </div>
        </div>
    </div>
</div>
</body>
</html>
