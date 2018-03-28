<?php
ob_start();
session_start();
include("config.php");
$error_message = '';
$success_message = '';

// Check if the user is logged in or not
if(!isset($_SESSION['user'])) {
    header('location: login.php');
    exit;
}

if(isset($_POST['settingsform'])) {
    // updating the database
    $statement = $pdo->prepare("UPDATE tbl_settings SET title=?, keywords=?, description=?, semail=?, remail=?, enable_ads=?, ads_code=? WHERE id=1");
    $statement->execute(array($_POST['title'],$_POST['keywords'],$_POST['description'],$_POST['semail'],$_POST['remail'],$_POST['enableads'],$_POST['adscode']));
    $success_message = 'Site settings is updated successfully.';
}

// Getting data from the website settings table
$statement = $pdo->prepare("SELECT * FROM tbl_settings WHERE id=1");
$statement->execute();
$result = $statement->fetchAll(PDO::FETCH_ASSOC);                           
foreach ($result as $row) {
    $title                 = $row['title'];
    $keywords              = $row['keywords'];
    $description           = $row['description'];
    $semail              = $row['semail'];
    $remail              = $row['remail'];
    $enableads              = $row['enable_ads'];
    $adscode              = $row['ads_code'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Admin Panel</title>
    
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/bootstrap-responsive.min.css" rel="stylesheet">
    <link href="http://fonts.googleapis.com/css?family=Open+Sans:400italic,600italic,400,600" rel="stylesheet">
    <link href="css/font-awesome.css" rel="stylesheet">
   <link href="css/style.css" rel="stylesheet">
    <link href="css/pages/reports.css" rel="stylesheet">
    <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
</head>
<body>
<div class="navbar navbar-fixed-top">
    <div class="navbar-inner">
        <div class="container">
        </div>
    </div>
</div>
    
<div class="subnavbar">
    <div class="subnavbar-inner">
        <div class="container">
            <ul class="mainnav">
                <li class="active"><a href="index.php"><i class="icon-wrench"></i><span>Settings</span></a></li>
                <li><a href="layout.php"><i class="icon-dashboard"></i><span>Layout</span></a></li>
                <li><a href="profile.php"><i class="icon-user"></i><span>Profile</span></a></li>
                <li><a href="logout.php"><i class="icon-question-sign"></i><span>Logout</span></a></li>
            </ul>
        </div>
    </div>
</div>

<div class="main">
    <div class="main-inner">
        <div class="container">
            <div class="row">
        <div class="col-md-12">
            <?php if($error_message): ?>
            <div class="callout callout-danger">
            <h4>Please correct the following errors:</h4>
            <p>
            <?php echo $error_message; ?>
            </p>
            </div>
            <?php endif; ?>

            <?php if($success_message): ?>
            <div class="callout callout-success">
            <h4>Success:</h4>
            <p><?php echo $success_message; ?></p>
            </div>
            <?php endif; ?>

            <div class="span12">            
                <div class="widget ">
                    <div class="widget-header"><i class="icon-wrench"></i><h3>Script settings</h3></div>
                    <div class="widget-content">
            
                        <form action="" id="edit-profile" class="form-horizontal" method="post">
                            <fieldset>
                                <div class="control-group">                                         
                                    <label class="control-label" for="password">Site Title: </label>
                                    <div class="controls">
                                        <input type="text" class="span6" name="title" value="<?php echo $title; ?>">
                                    </div>
                                </div>
                                <div class="control-group">                                         
                                    <label class="control-label" for="password">Site Description: </label>
                                    <div class="controls">
                                        <textarea name="description" class="span6" style="height:150px;"><?php echo $description; ?></textarea>
                                    </div>
                                </div>
                                <div class="control-group">                                         
                                    <label class="control-label" for="password">Site Keywords: </label>
                                    <div class="controls">
                                        <textarea name="keywords" class="span6" style="height:150px;"><?php echo $keywords; ?></textarea>
                                    </div>
                                </div>
                                <div class="control-group">                                         
                                    <label class="control-label" for="password">Server Email: </label>
                                    <div class="controls">
                                        <input type="text" class="span6" name="semail" value="<?php echo $semail; ?>">
                                    </div>
                                </div>
                                <div class="control-group">                                         
                                    <label class="control-label" for="password">Reciever Email: </label>
                                    <div class="controls">
                                        <input type="text" class="span6" name="remail" value="<?php echo $remail; ?>">
                                    </div>
                                </div>
                                <div class="control-group">                                         
                                    <label class="control-label" for="password">Enable Ads? </label>
                                    <div class="controls">
                                        <select class="text" name="enableads">
                                            <option value="1" <?php if($enableads == 1){ echo 'selected="selected"'; } ?>>Yes</option>
                                            <option value="0" <?php if($enableads == 0){ echo 'selected="selected"'; } ?>>No</option>
                                        </select>
                                </div>
                                </div>
                                <div class="control-group">                                         
                                    <label class="control-label" for="password">Ads Code: </label>
                                    <div class="controls">
                                        <textarea name="adscode" class="span6" style="height:200px;"><?php echo $adscode; ?></textarea>
                                    </div>
                                </div>

                                <div class="form-actions">
                                    <input type="submit" name="settingsform" value="Save" class="btn btn-primary" /> 
                                </div>
                            </fieldset>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        </div>
    </div>
</div>
<script src="js/jquery-1.7.2.min.js"></script>
<script src="js/bootstrap.js"></script>
</body>
</html>