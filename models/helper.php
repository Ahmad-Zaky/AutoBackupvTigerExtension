<?php

function loginHistory($username) {
    if (empty($username)) return false;

    global $adb;

    $ip = $_SERVER['REMOTE_ADDR'];
    $signed_in = date("Y-m-d H:i:s");
    $query = "INSERT INTO automatedbackup_users_logs (`username`, `ip`, `signed_in`, `signed_out`, `status`) VALUES ('$username', '$ip', '$signed_in', '--', 'Signed In')";
    $adb->query($query);
}

function logoutHistory($username) {
    if (empty($username)) return false;
    
    global $adb;
    
    $ip = $_SERVER['REMOTE_ADDR'];
    $signed_out = date("Y-m-d H:i:s");
    
    $loginIdQuery = "SELECT MAX(id) AS id FROM automatedbackup_users_logs WHERE `username`='$username' AND ip='$ip' ";
    $result = $adb->query($loginIdQuery);
    
    if ($result) {
        $loginId = $adb->query_result($result, 0, "id");
    }

    if (!empty($loginId)){
        $query = "UPDATE automatedbackup_users_logs SET `signed_out` = '$signed_out', `status`='Signed Out' WHERE id = $loginId";
        $result = $adb->query($query);
    }
}

function sendEmail_($toEmailId, $subject, $body) {
	
	$date = date("Y-m-d H:i:s", time()+2*60*60);

	$mailer = new Emails_Mailer_Model();
	$mailer->reinitialize();
	$mailer->Body = $body;
	$mailer->Subject = decode_html($subject);

	$replyTo = decode_html('crm@cityclubeg.com');
	$replyToName = decode_html('CityCub');
	$fromEmail = decode_html('crm@cityclubeg.com'); 
	
	$mailer->ConfigSenderInfo($fromEmail, $replyTo, $replyToName);
	$mailer->IsHTML();
	$mailer->AddAddress($toEmailId);

	$response = $mailer->Send(true);
}