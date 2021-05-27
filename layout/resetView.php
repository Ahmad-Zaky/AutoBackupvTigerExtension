<?php

// Initialize the session
session_start();
 
// Check if the user is logged in, otherwise redirect to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: ../index.php");
    exit;
}
 
// Include config file
chdir('../');
require_once "config.php";
chdir('layout');

// Define variables and initialize with empty values
$old_password = $new_password = $confirm_password = "";
$old_password_err = $new_password_err = $confirm_password_err = "";


// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Validate old password
    if (empty(trim($_POST["old_password"]))) {
        $old_password_err = "Please enter the old password.";     
    } else {
        $old_password = trim($_POST["old_password"]);
    }

    // Validate new password
    if (empty(trim($_POST["new_password"]))) {
        $new_password_err = "Please enter the new password.";     
    } elseif (strlen(trim($_POST["new_password"])) < 6){
        $new_password_err = "Password must have atleast 6 characters.";
    } else {
        $new_password = trim($_POST["new_password"]);
    }
    
    // Validate confirm password
    if (empty(trim($_POST["confirm_password"]))) {
        $confirm_password_err = "Please confirm the password.";
    } else {
        $confirm_password = trim($_POST["confirm_password"]);
        if (empty($new_password_err) && ($new_password != $confirm_password)) {
            $confirm_password_err = "Password did not match.";
        }
    }
        
    // Check input errors before updating the database
    if (empty($old_password_err) && empty($new_password_err) && empty($confirm_password_err)) {
        
        
        // Check Old Password
        $username = $_SESSION['username'];
        $isValid = isOldPasswordValid($old_password, $username);
        if ($isValid>0) {
            
            $new_password = password_hash($new_password, PASSWORD_DEFAULT);
            $sql = "UPDATE automatedbackup_users SET password = '$new_password' WHERE username = '$username'";

            $result = $adb->query($sql);
            if($result) {

                // Unset all of the session variables
                $_SESSION["loggedin"] = false;
                $_SESSION["id"] = '';
                $_SESSION["username"] = '';
                $_SESSION["is_admin"] = false;
                
                header("location: ../index.php");
                exit();
            }
        }
        $old_password_err = ($isValid === -1) ? 'Wrong Password !' : 'Something Wrong !';   
    }
}

function isOldPasswordValid($old_password, $username)
{
    global $adb;

    if (empty($username) || empty($old_password))
        return false;

    $sql = "SELECT `password` FROM automatedbackup_users WHERE username = '$username'";
    $result = $adb->query($sql);

    // Check if username exists, if yes then verify password
    if (!$result || $adb->num_rows($result) != 1)
        return false;
        
    $user = $adb->fetch_array($result);
    $hashed_password = $user['password'];
    
    return (password_verify($old_password, $hashed_password)) ? true : -1; 
}

?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reset Password</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
    <style>
        body{ font: 14px sans-serif; }

        .contact-form{ width: 360px; padding: 20px; }

        .center-div { margin: auto; width: 50%; }

        .center-h2 { color: white; text-align: center; }
        
        .form-control { width: 150%; }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="center-div">
            <h2 class="center-h2">Reset Password</h2>
        </div>
        <div class="contact-form">
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post"> 
                <div class="form-group">
                    <label>Old Password</label>
                    <input type="password" name="old_password" class="form-control <?php echo (!empty($old_password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $old_password; ?>">
                    <span class="invalid-feedback"><?php echo $old_password_err; ?></span>
                </div>
                <div class="form-group">
                    <label>New Password</label>
                    <input type="password" name="new_password" class="form-control <?php echo (!empty($new_password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $new_password; ?>">
                    <span class="invalid-feedback"><?php echo $new_password_err; ?></span>
                </div>
                <div class="form-group">
                    <label>Confirm Password</label>
                    <input type="password" name="confirm_password" class="form-control <?php echo (!empty($confirm_password_err)) ? 'is-invalid' : ''; ?>">
                    <span class="invalid-feedback"><?php echo $confirm_password_err; ?></span>
                </div>
                <div class="form-group">
                    <input type="submit" class="btn btn-primary" value="Submit">
                    <a class="btn btn-link ml-2" href="listView.php">Cancel</a>
                </div>
            </form>
        </div>

    </div>    
</body>
</html>