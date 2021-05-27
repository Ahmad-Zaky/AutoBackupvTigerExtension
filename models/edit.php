<?php

// Initialize the session
session_start();
 
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

chdir("../");
require_once('config.php');


chdir("../");
require_once('include/utils/utils.php');

// General Settings
$settings['localbackup_status']    = $_POST["status"]; 
$settings['localbackup_frequency'] = $_POST["frequency"];
$settings['frequency_unit']        = $_POST["unit"];
$settings['specific_time']         = $_POST["specific_time"];
$settings['localbackup_number']    = $_POST["max_backups"];
$settings['localbackup_directory'] = $_POST["path"];

$settings['emailreport_email']     = $_POST["email"];
$settings['emailreport_subject']   = $_POST["email_sub"];
$settings['emailreport_body']      = $_POST["email_body"];

$response = save($settings);

if ($response) {
    echo 'Saved Successfully';
    echo '<br>';
    echo 'Redirect Back ...';
    echo "<script>setTimeout(\"location.href = '$backup_URL/layout/settingsView.php';\",1500);</script>";
} else
    echo 'Something Wrong !';

function save($settings) {

    if (empty($settings)) return false;

    foreach ($settings as $key => $setting) {
        global $adb;
        
        $query = "UPDATE automatedbackup_settings SET `value` = '" .$setting. "' WHERE `key` = '" .$key. "'";
        if (!$adb->query($query))
            return false;
    }

    return true;
}

