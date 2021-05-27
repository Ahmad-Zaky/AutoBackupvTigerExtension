<?php

chdir("../");

require_once('include/utils/utils.php');
require_once('modules/Emails/models/Mailer.php');

class AutoBackup {
    
    protected $hostname = '';
    protected $username = '';
    protected $password = '';
    protected $dbname   = '';
    
    protected $settings = [];
    
    protected $now = 0;
    protected $dumpfname = '';
    
    const TMP_PATH = 'backup/tmp/';
    const OPERATOR = "+";
    const PATH_REG = '~^(/[^/ ]*)+/?$~m';
    const FILE_EXTENSION = 'zip'; 
    
    function __construct() {
        
        // Database configuration
        $this->setDBConfiguration();
        
        // Backup Setting
        $this->setSettings();
        
        // SQL filename Generation
        $this->dumpfname = $this->dbname . "_" . date("Y-m-d_H-i-s", time()+2*60*60).".sql";

        $this->now = time() + 2 * 60 * 60;
    }
   
    function __get($key) {
        return $this->$key;
    }

    function __set($key, $value) {
        $this->$key  = $value;
    }

    public function validate($module="", $action=""){
        
        if ($this->settings['status'] !== "Active")
            return [
                'success' => false,
                'msg' => "The Status is InActive"
            ]; 

        if ($this->now >= strtotime($this->settings['next_triger_time']) || ($module == "AutoBackup" && $action == "run")){
            if (preg_match(self::PATH_REG, $this->settings['path']) && file_exists($this->settings['path'])) {
                return true;
            } else{
                echo "there is a problem in the path !";
                return false;
            }   
        } else{
            echo "there is a problem in the time!";
            return false;
        } 
    }

    public function execute(){

        $filesNames = $this->getFilesName();
		$files = $this->filter($filesNames, $this->dbname);

        if ($this->countfiles($files) < $this->settings['max_backups']) {
            $this->run();
        } else {

            $deleted = $this->getDeleted($files);
            if($this->delete($deleted['deletedFileWithPath'])){
                $this->backupDeleteLog($deleted['deletedFile']);
                $this->run();    
            }
            // TODO: Handle Exceptions
        }
    }

    protected function run(){
        $zipfname = $this->create();
        $this->backupCreateLog($zipfname);
        if (!($module == "AutoBackup" && $action == "run")) {
            $new_next_triger_time = $this->updateTime();
            $status = $this->updateNextTrigerTime($new_next_triger_time);
        }
        $this->sendEmail($zipfname);          
    }

    protected function getFilesName() {
        
        if (empty($this->settings['path']) || empty(self::FILE_EXTENSION)) return false;
        
        $ext = self::FILE_EXTENSION;
        $filesName = [];
        $files = glob($this->settings['path'] . "*.$ext");
        
        foreach($files as $file) {
            $fileName =	basename($file, ".$ext");
            array_push($filesName, $fileName);
        }
        return $filesName;
    }

    protected function filter($filesNames, $key) {
        


        $filterdFiles = [];
    
        foreach($filesNames as $file) {
            if (strpos($file, $key) !== false) {
                array_push($filterdFiles,$file);
            }
        }	
        return $filterdFiles;
    }
    
    protected function countfiles($files){
    
        if (is_array($files))
            return count($files);
    
        return false;
    
    }

    protected function normalize($filteredFiles){
        $normalized_date = [];
        foreach($filteredFiles as $file){
                $date = explode('_', $file);
                $date[sizeof($date) - 1] = str_replace("-",":",$date[sizeof($date) - 1]);
                $normalized_date[] =  $date[sizeof($date) - 2] . ' ' . $date[sizeof($date) - 1];
        }
        return $normalized_date;
    }
    
    protected function denormalize ($date){
        $array = explode (" ", $date);
        $array[1] = str_replace(":","-",$array[1]);
        $fileName = $this->dbname."_".$array[0]."_".$array[1];
        return $fileName;
    }
    
    protected function sortDates($array) {
      
        $compare = function($a,$b) {
            $a_timestamp = strtotime($a); // convert a (string) date/time to a (int) timestamp
            $b_timestamp = strtotime($b);
            // new feature in php 7
            return $a_timestamp <=> $b_timestamp;
        };
        
        usort($array, $compare);
    
        return $array;
    }
    
    protected function delete($filename) {
        if(unlink($filename)){
            return true ;
        }
        return false;	
    }
    
    /**
     * @param {
     *          $this->dumpfname, 
     *          $this->hostname, 
     *          $this->username, 
     *          $this->password, 
     *          $this->dbname, 
     *          $this->settings['path']
     *        }
     * 
     * @return {$zipfname}
     */
    protected function create() {

        // Get MySQL File Name with its path
        $pathWithDumpFName = self::TMP_PATH . $this->dumpfname;

        //command
        $command  = "/opt/lampp/bin/mysqldump --host=$this->hostname --user=$this->username ";
        $command .= ($this->password) ? "--password=$this->password " : "";
        $command .= $this->dbname . " > " . $pathWithDumpFName;
        
        //run command
        exec($command, $output, $retval);

        if($retval === 0){
            // compressed into a ZIP file
            $zipfname = $this->dbname . "_" . date("Y-m-d_H-i-s", time()+2*60*60).".zip";
            $pathWithFName = $this->settings['path'] . $zipfname;
            $zip = new ZipArchive();
            
            if($zip->open($pathWithFName, ZIPARCHIVE::CREATE)){
                // add contents...
                $zip->addFile($pathWithDumpFName, $pathWithDumpFName);
                $zip->close();
                $this->delete($pathWithDumpFName);
                echo "the backup created successfully";
            }
        } else {
            echo "we have a problem and we need to talk !" ;	
        }
        return $zipfname;
    }
    
    protected function updateTime() {
        $next_triger_time = false;
        
        if (empty($this->settings['frequency']))
            return false;
    
        if(!is_numeric($this->settings['frequency']))
            return false;
        
        switch ($this->settings['frequency_unit']) {
            case "hours":
                $this->settings['frequency'] = time() + ($this->settings['frequency'] * 60 * 60);
                $next_triger_time = date('Y-m-d H:i:s', $this->settings['frequency']);
                break;
            case "days":
                $this->settings['frequency'] = self::OPERATOR . $this->settings['frequency'] . " " . $this->settings['frequency_unit']; 
                $next_triger_time = date('Y-m-d '.$this->settings['specific_time'], strtotime($this->settings['frequency']));
                break;
        }
        
        return $next_triger_time;
    }
    
    protected function updateNextTrigerTime($new_next_triger_time){
        global $adb;
        $query = "UPDATE automatedbackup_settings SET `value` = '$new_next_triger_time'
                  WHERE `key` LIKE 'next_triger_time'";
        
        if($adb->query($query))
            return true;
        
        return false;
    }
    
    protected function backupCreateLog($fileName){
        
        global $adb;
        
        $filePath    = $this->settings['path'];
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
    
    protected function backupDeleteLog($fileName){
        global $adb;
    
        $is_Delete = 1;
    
        $query = "UPDATE automatedbackup_logs SET `deleted` = $is_Delete
                  WHERE `filename` = '$fileName'";
        
        $adb->query($query);		  
    
    }
    
    protected function sendEmail($fileName) {
        
        $date = date("Y-m-d H:i:s", time()+2*60*60);
        
        $mailer = new Emails_Mailer_Model();
        $mailer->reinitialize();
        $mailer->Body = sprintf($this->settings['emailBody'], $fileName, $date);
        $mailer->Subject = decode_html($this->settings['emailSubject']);
    
        $replyTo = decode_html('crm@cityclubeg.com');
        $replyToName = decode_html('CityCub');
        $fromEmail = decode_html('crm@cityclubeg.com'); 
        
        $mailer->ConfigSenderInfo($fromEmail, $replyTo, $replyToName);
        $mailer->IsHTML();
        $mailer->AddAddress($this->settings['emailReport']);
    
        $response = $mailer->Send(true);
    }
    
    protected function getDeleted($files) {

        $dates = $this->normalize($files);
        
        $this->sortDates($dates);
        
        $deletedFile = $this->denormalize($dates[0]);
        
        $delete_Log = $deletedFile.".zip";
        
        return [
            'deletedFile' => $deletedFile,
            'deletedFileWithPath' => $this->settings['path'].$delete_Log
        ];
    }

    protected function redirect() {
        // Redirect to Log view
        echo '<br>';
        echo 'Redirect Back ...';
        echo "<script>setTimeout(\"location.href = 'http://localhost/vtigercrm/database_backup/Layout/listView.php';\",1500);</script>";

    }

    protected function setSettings() {
        global $adb;

        $settingMap = [
            "localbackup_status"    => "status",  
            "localbackup_frequency" => "frequency",
            "localbackup_number"    => "max_backups",
            "localbackup_directory" => "path",
            "frequency_unit"        => "frequency_unit",
            "specific_time"         => "specific_time",
            "next_triger_time"      => "next_triger_time",
            "emailreport_email"     => "emailReport",
            "emailreport_subject"   => "emailSubject",
            "emailreport_body"      => "emailBody"
        ];
        
        $query = "SELECT * FROM automatedbackup_settings";
        $result = $adb->query($query);

        if ($adb->num_rows($result) > 0) {
            while($row = $adb->fetch_array($result)) {
                if(isset($settingMap[$row['key']])){
                    $keyMap = $settingMap[$row['key']];
                    $this->settings[$keyMap] = $row['value'];
                }
            }
        }
    }

    protected function setDBConfiguration() {

        global $dbconfig;

        $this->hostname  = $dbconfig['db_server'];   // MYSQL server address
        $this->username  = $dbconfig['db_username']; // your username MYSQL
        $this->password  = $dbconfig['db_password']; // Password
        $this->dbname    = $dbconfig['db_name'];     // database name
    }
}

$backupInstance = new AutoBackup();		

$module = (isset($_REQUEST["module"])) ? $_REQUEST["module"] : "";
$action = (isset($_REQUEST["action"])) ? $_REQUEST["action"] : "";

if ($backupInstance->validate($module, $action))
    $backupInstance->execute();

/**
 * @param {}
 * @return {}
 */