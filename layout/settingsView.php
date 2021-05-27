<?php
// Initialize the session
session_start();
 
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AutoBackup</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
    <style>
        ::placeholder {
            color: #39b7dd;
            opacity: 1; /* Firefox */
        }
    </style>
</head>

<body>
    <?php 
        chdir("../../");
        require_once('include/utils/utils.php');
        
        global $adb;

        $query = "SELECT * FROM automatedbackup_settings";
        $result = $adb->query($query);
        $record = [];

        while($row = $adb->fetch_array($result))
            $record[$row['key']] = $row['value'];
    ?>
    <div class="wrapper">
        <form action="../models/edit.php" method="POST">
            <div class="contact-form">
                <div class="btn-back">
                    <a class="btn btn-info" role="button" href="listView.php" >History</a> 
                </div>
                
                <div class="text">
                    <h3>Basic Information</h3>
                </div>
                <div class="input-fields">
                    
                    <select class="input" id="status" title="Status" name="status" required>
                        <option value>Select Status</option>
                        <option value="Active">Active</option>
                        <option value="InActive">InActive</option>
                    </select>
                    <input class="input" title="Buckup Frequency" value="<?php echo $record['localbackup_frequency']?>" type="number" placeholder="Buckup Frequency" name="frequency" required>
                    <select class="input" id="unit" title="Frequency Unit" onchange="validateSpecificTime()" name="unit"  required>
                        <option value>Select Frequency Unit</option>
                        <option value="hours">Hours</option>
                        <option value="days">Days</option>
                    </select>

                    <input id="specific_time" class="input" name="specific_time" type="time" title="Specific time" value="<?php echo $record['specific_time']?>" placeholder="Specific time">

                    <input class="input" name="max_backups" type="number" title = "Max Backups" value="<?php echo $record['localbackup_number']?>" placeholder="Max Backups" required>
    
                    <input id="directory-path" class="input" name="path" type="text" title = "Backup Directory"  placeholder="Backup Directory" required>
    
                    <input id="next-trigger-time" class="input" name="next_trigger_time" type="datetime-local" title = "Next Triger Time" placeholder="Next Triger Time" disabled>
                </div>
            
                <div class="text">
                    <h3>Email Information</h3>
                </div>
                <div class="input-fields">

                    <input id="email" class="input" name="email" type="email" title="Email" value="<?php echo $record['emailreport_email']?>" placeholder="Email" oninput="validateEmail()">
    
                    <input id='subject' class="input" name="email_sub" type="text" title="Email Subject" value="<?php echo $record['emailreport_subject']?>" placeholder="Subject">
    
                    <div class="msg">
                        <textarea id='msg' cols="30" rows="10" name="email_body" title="Email Body" placeholder="Email Body"><?php echo $record['emailreport_body']?>
                        </textarea>
                    </div>
                    <input id="submit" type="submit" class="btn" value="Save" />
                </div>
                
            </div>
        </form>
    </div>
    <script>
        document.getElementById('status').value = "<?php echo $record['localbackup_status']?>"
        document.getElementById('unit').value = "<?php echo $record['frequency_unit']?>"
        document.getElementById('next-trigger-time').value = "<?php echo str_replace(' ', 'T', $record['next_triger_time'])?>"
        
        
        const form = document.querySelector('form');
        form.addEventListener('submit', event => {
            validateDirectoryFormat('js')
        });

        validateDirectoryFormat('php')
        function validateDirectoryFormat(source) {
            var pathElem = document.getElementById('directory-path')
            
            if (source == 'php')
                pathElem.value ="<?php echo $record['localbackup_directory']?>"

            var path = pathElem.value
            
            const path_reg = '^(/[^/ ]*)+/?$'
            if (path.search(path_reg) == -1) {
                event.preventDefault();
                alert('Please Provide a Valid Directory !!!')
            }            
        }

        validateEmail();
        function validateEmail() {
            
            var email = document.getElementById('email').value;
            
            if (email) {
                document.getElementById('subject').setAttribute("required", true);
                document.getElementById('msg').setAttribute("required", true);
            } 
            
            if (!email) {
                document.getElementById('subject').required = false;
                document.getElementById('msg').required = false;
            }
        }

        validateSpecificTime();
        function validateSpecificTime(){
            
            var unit = document.getElementById('unit').value;
            
            if (unit == 'days') {
                document.getElementById('specific_time').setAttribute("required", true);
            }

            if (unit == 'hours') {
                document.getElementById('specific_time').required = false;
            }
            
        }
    </script>
</body>

</html>