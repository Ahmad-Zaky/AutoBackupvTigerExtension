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
        <a class="btn btn-info" role="button" href="settingsView.php">Setting</a> 
        <a class="btn btn-primary" role="button" href="../models/run.php?module=AutoBackup&action=run">Run Backup</a>
        <a class="btn btn-secondary" role="button" href="resetView.php">Reset Password</a>
        
        <?php if($_SESSION['is_admin']): ?>
            <a class="btn btn-secondary" role="button" href="historyView.php">Login History</a>
        <?php endif; ?>
        
        <a class="btn btn-danger" role="button" href="../models/logout.php" >Log Out</a>
        <table >
            <tr>
                <th>ID</th>
                <th>File Name</th>
                <th>Date</th>
                <th>File Size</th>
                <th>Deleted</th>
            </tr>
            <?php
                chdir("../../");
                require_once('include/utils/utils.php');
                
                global $adb;

                $query = "SELECT * FROM automatedbackup_logs order by id desc LIMIT 15";
                $result = $adb->query($query);
                $id = 1;
                while($row = $adb->fetch_array($result)) {
                   
                    
                    $filesize = $row['filesize'];
                    $deleted = " ";
                    if($row['deleted'] == 0) {
                        $deleted = "No";
                    } else {
                        $deleted = "yes";
                    }
                    echo "<tr>";
                    echo "<td>" . $id ."</td>";
                    echo "<td>" . $row['filename']."</td>";
                    echo "<td>" . $row['createdtime'] . "</td>";
                    echo "<td>" . formatSizeUnits($filesize) ."</td>";
                    echo "<td>" . $deleted . "</td>";
                    echo "</tr>";
                    $id += 1;
                }
            ?>
        </table>
    </div>
</body>
</html>


<?php
    function formatSizeUnits($bytes) {
        if ($bytes >= 1073741824) {
            $bytes = number_format($bytes / 1073741824, 2) . ' GB';
        }
        elseif ($bytes >= 1048576) {
            $bytes = number_format($bytes / 1048576, 2) . ' MB';
        }
        elseif ($bytes >= 1024) {
            $bytes = number_format($bytes / 1024, 2) . ' KB';
        }
        elseif ($bytes > 1) {
            $bytes = $bytes . ' bytes';
        }
        elseif ($bytes == 1) {
            $bytes = $bytes . ' byte';
        }
        else {
            $bytes = '0 bytes';
        }

        return $bytes;
    }
?>
<!-- TODO: Add Pagination Functionality (Prev|Next). -->