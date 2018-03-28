<?php
ob_start();
session_start();
include("config.php");
$error_message='';
if(isset($_POST['form'])) {
    //check if details are correct to log in
    if(empty($_POST['email']) || empty($_POST['password'])) {
        $error_message = 'Email and/or Password can not be empty<br>';
    } else {
        $statement = $pdo->prepare("SELECT * FROM tbl_admin WHERE email=?");
        $statement->execute(array($_POST['email']));
        $total = $statement->rowCount();    
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);    
        if($total==0) {
            $error_message .= 'Email Address does not match<br>';
        } else {       
            foreach($result as $row) { 
                $row_password = $row['password'];
            }
        
            if( $row_password != md5($_POST['password']) ) {
                $error_message .= 'Password does not match<br>';
            } else {       
                $_SESSION['user'] = $row;
                header("location: index.php");
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes"> 
    <link href="css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="css/bootstrap-responsive.min.css" rel="stylesheet" type="text/css" />
    <link href="css/font-awesome.css" rel="stylesheet">
    <link href="http://fonts.googleapis.com/css?family=Open+Sans:400italic,600italic,400,600" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet" type="text/css">
    <link href="css/pages/signin.css" rel="stylesheet" type="text/css">
</head>
<body>
<div class="navbar navbar-fixed-top">
<div class="navbar-inner">
</div> <!-- /navbar-inner -->
</div> <!-- /navbar -->

<div class="account-container">
    <div class="content clearfix">
        <form action="" method="post">
            <h1>Login</h1>      
            <div class="login-fields">
                <p>Please provide your details</p> 
                <?php 
                if( (isset($error_message)) && ($error_message!='') ):
                echo '<div class="error">'.$error_message.'</div>';
                endif;
                ?>
                <br>
                <div class="field">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" value="" placeholder="admin1234@gmail.com" class="login username-field"/>
                </div> <!-- /password -->
                <div class="field">
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" value="" placeholder="********" class="login password-field"/>
                </div> <!-- /password -->
            </div> <!-- /login-fields -->
            
            <div class="login-actions">
                <button name="form" class="button btn btn-success btn-large">Sign In</button>               
            </div> <!-- .actions -->
        </form>
    </div> <!-- /content -->
</div> <!-- /account-container -->
<script src="js/jquery-1.7.2.min.js"></script>
<script src="js/bootstrap.js"></script>
<script src="js/signin.js"></script>
</body>
</html>