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


// Database configuration
$hostname  = $dbconfig['db_server'];   // server address MYSQL 
$username  = $dbconfig['db_username']; // username MYSQL
$password  = $dbconfig['db_password']; // Password MYSQL
$dbname    = $dbconfig['db_name'];     // database name MYSQL 

global $adb;

$query = "SELECT * FROM automatedbackup_settings";
$result = $adb->query($query);

$settings = [];

if ($adb->num_rows($result) > 0) {
	while($row = $adb->fetch_array($result)) {
			$settings[$row['key']] = $row['value'];
	}
}

$status 		  = $settings["localbackup_status"];
$frequency 		  = $settings["localbackup_frequency"];
$max_backups 	  = $settings["localbackup_number"];
$path 			  = $settings["localbackup_directory"]; 
$frequency_unit   = $settings["frequency_unit"]; 
$specific_time 	  = $settings["specific_time"];
$next_triger_time = $settings['next_triger_time'];
$emailReport 	  = $settings['emailreport_email'];
$emailSubject 	  = $settings['emailreport_subject'];
$emailBody        = $settings['emailreport_body'];

$tmpPath = $path . 'tmp/';
$dumpfname = $dbname . "_" . date("Y-m-d_H-i-s", time()+2*60*60).".sql";
$operator = "+";
$path_reg = '~^(/[^/ ]*)+/?$~m';

$module = (isset($_REQUEST["module"])) ? $_REQUEST["module"] : "";
$action = (isset($_REQUEST["action"])) ? $_REQUEST["action"] : "";
$now = time() + 2*60*60;

if ($status == "Active") {		
	if ($now >= strtotime($next_triger_time) || ($module == "AutoBackup" && $action == "run")){  
		if (preg_match($path_reg, $path) && file_exists($path)) {
			$filesNames = getFilesName($path, "zip");
			$files = filter($filesNames, $dbname);
			if (countfiles($files) < $max_backups) {
				$zipfname = create($dbname, $path, $tmpPath.$dumpfname, $username, $password, $hostname);
				if ($zipfname) {
				
					backupCreateLog($zipfname, $path);
					if (!($module == "AutoBackup" && $action == "run")) {
						$next_triger_time = updateTime($frequency, $frequency_unit, $specific_time, $operator);
						$status = updateNextTrigerTime($next_triger_time);
					}
					chdir("../");
					sendEmail($emailReport, $emailSubject, $emailBody, $zipfname);
				}
			} else {
				$dates = normalize($files);
				sortDates($dates);
				$deletedFile = denormalize($dates[0], $dbname);
				$delete_Log = $deletedFile.".zip";
				$File = $path.$delete_Log;
				if(delete($File)){
					backupDeleteLog($delete_Log);
					$zipfname = create($dbname, $path, $tmpPath.$dumpfname, $username, $password,$hostname);
					if ($zipfname) {
						echo "</br> deleted then created";
						backupCreateLog($zipfname, $path);
						if (!($module == "AutoBackup" && $action == "run")) {
							// update $next_triger_time
							$next_triger_time = updateTime($frequency, $frequency_unit, $specific_time, $operator);
							$status = updateNextTrigerTime($next_triger_time);
						}
						chdir("../");
						sendEmail($emailReport, $emailSubject, $emailBody, $zipfname);
					}
				}
			}

			// Redirect to Log view
			echo '<br>';
			echo 'Redirect Back ...';
			echo "<script>setTimeout(\"location.href = '$backup_URL/layout/listView.php';\",1500);</script>";
		} else {
			echo "there is a problem in the path !";
		}
	} else {
		echo "there is a problem in the time!";
	}
} else {
	echo "The Status is InActive";
}

function getFilesName($path, $ext) {	
	$filesName = [];
	$files = glob($path . "*.$ext");
	
	foreach($files as $file) {
		$fileName =	basename($file, ".$ext");
		array_push($filesName, $fileName);
	}

	return $filesName;
}

function filter($filesNames, $key){
	$filterdFiles = [];

	foreach($filesNames as $file) {
		if (strpos($file, $key) !== false) {
			array_push($filterdFiles,$file);
		}
	}	
	// var_dump($filterdFiles);
	return $filterdFiles;
}

function countfiles($files){

	if (is_array($files))
		return count($files);

	// echo "There were $filecount files </br>";
	return false;

}

function normalize($filteredFiles){
	$normalized_date = [];
	foreach($filteredFiles as $file){
			$date = explode('_', $file);
			$date[sizeof($date) - 1] = str_replace("-",":",$date[sizeof($date) - 1]);
			$normalized_date[] =  $date[sizeof($date) - 2] . ' ' . $date[sizeof($date) - 1];
	}
	return $normalized_date;
}

function denormalize($date, $dbname){
	$array = explode (" ", $date);
	$array[1] = str_replace(":","-",$array[1]);
	$fileName = $dbname."_".$array[0]."_".$array[1];
	
	return $fileName;
}

function sortDates($array) {
	
	$compare = function($a,$b) {
		$a_timestamp = strtotime($a); // convert a (string) date/time to a (int) timestamp
		$b_timestamp = strtotime($b);
		
		// new feature in php 7
		// return $a_timestamp <=> $b_timestamp;
	
		// php 5.6 version
		$return = ($a_timestamp < $b_timestamp) ? -1 : '';
		$return = ($a_timestamp == $b_timestamp) ? 0 : '';
		$return = ($a_timestamp > $b_timestamp) ? 1 : '';
		 
		return $return;
	};

	usort($array, $compare);

	return $array;	
}


function delete($filename) {
	
	if(unlink($filename)){
		return true ;
	}

	return false;	
}

function create($dbname, $path, $dumpfname, $username, $password, $hostname){
	//command
	$command  = "/opt/lampp/bin/mysqldump --host=$hostname --user=$username ";
	$command .= ($password) ? "--password=$password " : "";
	$command .= $dbname . " > " . $dumpfname;
	
	//run command
	exec($command, $output, $retval);

	if($retval === 0){
		// compressed into a ZIP file
		$zipfname = $dbname . "_" . date("Y-m-d_H-i-s", time()+2*60*60).".zip";
		$path .= $zipfname;
		$zip = new ZipArchive();
			
		if($zip->open($path, ZIPARCHIVE::CREATE)){
			// add contents...
			$zip->addFile($dumpfname, $dumpfname);
			$zip->close();
			delete($dumpfname);
			echo "the backup created successfully";
		}
	} else {
		echo "The Backup has Not been created !!!" ;
		return false;	
	}
	return $zipfname;
}

function updateTime($frequency, $unit, $time = "00:00:00", $operator = '+'){
	$next_triger_time = false;
	
	if (empty($frequency))
		return false;

	if(!is_numeric($frequency))
		return false;
	
	switch ($unit) {
		case "hours":
			$frequency = time() + ($frequency * 60 * 60);
			$next_triger_time = date('Y-m-d H:i:s', $frequency);
			break;
		case "days":
			$frequency = $operator . $frequency . " " . $unit; 
			$next_triger_time = date('Y-m-d '.$time, strtotime($frequency));
			break;
	}
	
	return $next_triger_time;
}

function updateNextTrigerTime($next_triger_time){
	global $adb;
	$query = "UPDATE automatedbackup_settings SET `value` = '$next_triger_time'
			  WHERE `key` LIKE 'next_triger_time'";
	
	if($adb->query($query))
		return true;
	
	return false;
}

function backupCreateLog($fileName, $filePath){
	global $adb;

	$createdTime = date('Y-m-d H:i:s', time()+2*60*60);
	$fileType  	 = 'Database';
	$fileSize    = filesize($filePath.$fileName); // bytes
	$is_Delete   = 0;
	$fileType    = "Localbackup";

	$query = "INSERT INTO automatedbackup_logs 
			 (`createdtime`, `filename`, `filetype`, `filesize`, `path`, `deleted`, `type`) VALUES 
			 ('$createdTime', '$fileName', '$fileType', '$fileSize', '$filePath', '$is_Delete', '$fileType')";

	$adb->query($query);	
}

function backupDeleteLog($fileName){
	global $adb;

	$is_Delete = 1;

	$query = "UPDATE automatedbackup_logs SET `deleted` = $is_Delete
			  WHERE `filename` = '$fileName'";
	
	$adb->query($query);		  

}

function sendEmail($toEmailId, $subject, $body, $filename) {
		
	
	$date = date("Y-m-d H:i:s", time()+2*60*60);
	$username = $_SESSION["username"];

	$mailer = new Emails_Mailer_Model();
	$mailer->reinitialize();
	$mailer->Body = "User '$username' did an Action: " . sprintf($body, $filename, $date);
	$mailer->Subject = decode_html($subject);

	$replyTo = decode_html('crm@cityclubeg.com');
	$replyToName = decode_html('CityCub');
	$fromEmail = decode_html('crm@cityclubeg.com'); 
	
	$mailer->ConfigSenderInfo($fromEmail, $replyTo, $replyToName);
	$mailer->IsHTML();
	$mailer->AddAddress($toEmailId);

	$response = $mailer->Send(true);
}