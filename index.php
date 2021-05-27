<?php
// Initialize the session
session_start();
// Check if the user is already logged in, if yes then redirect him to welcome page

// $_SESSION["loggedin"] = false;
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("location: layout/listView.php");
    exit;
}
 
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$username = $password = "";
$username_err = $password_err = $login_err = "";
 
// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
 
    // Check if username is empty
    if (empty(trim($_POST["username"]))) {
        $username_err = "Please enter username.";
    } else {
        $username = trim($_POST["username"]);
    }
    
    // Check if password is empty
    if (empty(trim($_POST["password"]))) {
        $password_err = "Please enter your password.";
    } else {
        $password = trim($_POST["password"]);
    }
    
    // Validate credentials
    if (empty($username_err) && empty($password_err)) {
        // Prepare a select statement
        $sql = "SELECT `id`, `username`, `password`, `is_admin` FROM automatedbackup_users WHERE username = '$username'";
        
        // Attempt to execute the statement
        $result = $adb->query($sql);
        if ($result) {

            // Check if username exists, if yes then verify password
            if ($adb->num_rows($result) == 1) {
                
                $user = $adb->fetch_array($result);

                $id = $user['id'];
                $username = $user['username'];
                $hashed_password = $user['password'];
                $is_admin = $user['is_admin'];
                
                if (password_verify($password, $hashed_password)) {

                    // Password is correct, so start a new session
                    session_start();
                    
                    // Store data in session variables
                    $_SESSION["loggedin"] = true;
                    $_SESSION["id"] = $id;
                    $_SESSION["username"] = $username;                            
                    $_SESSION["is_admin"] = $is_admin;                            
                    
                    // Log Sign In History
                    chdir('../');
                    loginHistory($username);

                    // Send Email with The Logged in User
                    // TODO: Send only if the logged in user is not the admin
                    // TODO: Replace the strings prams down here
                    $email = 'ahmed.fouad@clavisbs.com';
                    $subject = 'User has logged in at ' . date('Y-m-d H:i:s');
                    $body =  $username.' user has logged in at ' . date('Y-m-d H:i:s');
                    sendEmail_($email, $subject, $body); // Depends on vTiger Configuration
                    chdir($backup_dir);

                    // Redirect user to listView page
                    header("location: layout/listView.php");
                } else {
                    // Password is not valid, display a generic error message
                    $login_err = "Invalid username or password.";
                }
            } else {
                // Username doesn't exist, display a generic error message
                $login_err = "Invalid username or password.";
            }

        } else {
            echo "Oops! Something went wrong. Please try again later.";
        }
    }
}


?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="layout/css/style.css">
    
    <style>
        body { font: 14px sans-serif; }

        .contact-form{ width: 360px; padding: 20px; }

        .center-div { margin: auto; width: 50%; }

        .center-h2 { color: white; text-align: center; }
        
        .form-control { width: 150%; }
    </style>
</head>
<body>
    <div class="wrapper">
        
        <div class="center-div">
            <h2 class="center-h2" >Login</h2>
        </div>

        <?php 
        if(!empty($login_err)){
            echo '<div class="center-div alert alert-danger" style="width: 360px">' . $login_err . '</div>';
        }        
        ?>
        
        <div class="contact-form">
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div class="form-group">
                    <label>Username</label>
                    <input type="text" name="username" class="form-control <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $username; ?>">
                    <span class="invalid-feedback"><?php echo $username_err; ?></span>
                </div>    
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>">
                    <span class="invalid-feedback"><?php echo $password_err; ?></span>
                </div>
                <div class="form-group">
                    <input type="submit" class="btn btn-primary" value="Login">
                </div>
            </form>
        </div>
    </div>
</body>
</html>