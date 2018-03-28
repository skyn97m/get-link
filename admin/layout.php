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
    
// Getting data from the website settings table
$statement = $pdo->prepare("SELECT * FROM tbl_settings WHERE id=1");
$statement->execute();
$result = $statement->fetchAll(PDO::FETCH_ASSOC);                           
foreach ($result as $row) {
    $favicon             = $row['favicon'];
}

if(isset($_POST['layoutform'])) {
$valid = 1;
    $path2 = $_FILES['favicon']['name'];
    $path_tmp2 = $_FILES['favicon']['tmp_name'];
    if($path2 == '') {
            $valid = 0;
            $error_message .= 'You must select a file<br>';
        } else {
            $ext2 = pathinfo( $path2, PATHINFO_EXTENSION );
            $file_name2 = basename( $path2, '.' . $ext2 );
            if( $ext2!='jpg' && $ext2!='png' && $ext2!='jpeg' && $ext2!='ico' ) {
            $valid = 0;
            $error_message .= 'You must have to upload jpg, jpeg, ico or png file<br>';
            }
        }
if($valid == 1) {
    if($path2 != '') {
        unlink('../img/'.$favicon);
        $final_name2 = 'favicon'.'.'.$ext2;
        move_uploaded_file( $path_tmp2, '../img/'.$final_name2 );
        // updating the database
        $statement = $pdo->prepare("UPDATE tbl_settings SET favicon=? WHERE id=1");
        $statement->execute(array($final_name2));
        $success_message = 'Layout settings is updated successfully.';
        }
    }
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
                <li><a href="index.php"><i class="icon-wrench"></i><span>Settings</span></a></li>
                <li class="active"><a href="layout.php"><i class="icon-dashboard"></i><span>Layout</span></a></li>
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
                    <div class="widget-header"><i class="icon-wrench"></i><h3>Layout settings</h3></div>
                    <div class="widget-content">
            
                        <form action="" id="edit-profile" class="form-horizontal" method="post" enctype="multipart/form-data">
                            <fieldset>
                                <div class="control-group">                                         
                                    <label class="control-label" for="password">Site Favicon: </label>
                                    <div class="controls">
                                        <input type="file" class="span6" id="favicon" name="favicon" value="<?php echo $favicon; ?>">
                                    </div>
                                </div>
                                
                                <div class="form-actions">
                                    <input type="submit" name="layoutform" value="Save" class="btn btn-primary" /> 
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