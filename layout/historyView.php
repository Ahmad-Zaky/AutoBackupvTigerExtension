<?php
// Initialize the session
session_start();
 
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: ../index.php");
    exit;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/viewstyle.css">
</head>
<body>
    <div class="container">
        <a class="btn btn-info" role="button" href="listView.php">Back</a>
        <table >
            <tr>
                <th>Username</th>
                <th>IP</th>
                <th>Signed In</th>
                <th>Signed Out</th>
                <th>Status</th>
            </tr>
            <?php
                chdir("../../");
                require_once('include/utils/utils.php');

                global $adb;

                $query = "SELECT * FROM automatedbackup_users_logs order by id desc LIMIT 15";

                $result = $adb->query($query);
                while($row = $adb->fetch_array($result)) {
                    echo "<tr>";
                    echo "<td>" . $row['username'] ."</td>";
                    echo "<td>" . $row['ip'] ."</td>";
                    echo "<td>" . $row['signed_in'] . "</td>";
                    echo "<td>" . $row['signed_out'] ."</td>";
                    echo "<td>" . $row['status'] . "</td>";
                    echo "</tr>";
                }
            ?>
        </table>
    </div>
</body>
</html>
<!-- TODO: Add Pagination Functionality (Prev|Next). -->