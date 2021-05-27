<?php


chdir('../');
require_once('include/utils/utils.php');
require_once('modules/Emails/models/Mailer.php');

global $adb;

$backup_dir = 'autoBackup';
$backup_URL = $site_URL . $backup_dir;

require_once($backup_dir . '/models/helper.php');

chdir($backup_dir);