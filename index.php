<?php
require_once "admin/config.php";
// Getting data from the website settings table
$statement = $pdo->prepare("SELECT * FROM tbl_settings WHERE id=1");
$statement->execute();
$result = $statement->fetchAll(PDO::FETCH_ASSOC);                           
foreach ($result as $row) {
    $title                 = $row['title'];
    $keywords              = $row['keywords'];
    $description           = $row['description'];
    $enableads              = $row['enable_ads'];
    $adscode              = $row['ads_code'];
    $favicon             = $row['favicon'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $title; ?></title>
    <meta name="description" content="<?php echo $description; ?>">
    <meta name="keywords" content="<?php echo $keywords; ?>">
    <link rel="shortcut icon" type="image/png" href="img/<?php echo $favicon ; ?>" />
    <link href='https://fonts.googleapis.com/css?family=Lato:400,300,700' rel='stylesheet' type='text/css'>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/linearicons.min.css" rel="stylesheet">
    <link href="css/main.css" rel="stylesheet">
    <link href="css/responsive.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <!--[if lt IE 9]>
        <script src="//oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
        <script src="//oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
</head>
<body>
    <section id="home" class="home">
    	<header>
            <nav class="navbar navbar-default main_nav">
                <div class="container">
                    <!-- Brand and toggle get grouped for better mobile display -->
                    <div class="navbar-header">
                        <a class="navbar-brand" href="<?php echo BASE_URL; ?>"><i class="icon-download"></i> <?php echo $title; ?></a>
                    </div>
                    <div class="collapse navbar-collapse navbar-ex1-collapse">
                        <ul class="nav navbar-nav navbar-right">
                            <li><a href="javascript:void(0);" data-target="#contact" id="contactus" data-toggle="modal">Contact Us</a></li>
                        </ul>
                    </div>
                </div>
            </nav>
        </header>
        <div class="header_content" id="header_content">
            <div class="head_content">
                <div class="container">
                    <div class="row">
                        <div class="col-md-8 col-md-offset-2 text-center">
                          <?php if($enableads == 1) {
                      		echo $adscode;
                      		} ?><br>
                            <div class="well">
				<center>
				 <h2>Download Facebook, YouTube, Instagram, Twitter, Vimeo Videos.</h2>
				<div class="ajax-loading"><img src="img/loading.gif" alt="loading..."></div>
				<div class="input-group col-lg-12">
					<input type="url" name="url" id="url" class="form-control" placeholder="Video Link" required>
					<span class="input-group-btn download"><input class="btn btn-primary" id="download" value="Download" type="button"></span>
				</div>
				</center>
			</div>
      <br>
      <?php if($enableads == 1) {
                            echo $adscode;
                            } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="particles_js">
            <div id="particles_js"></div>
        </div>
    </section>
<section id="features">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <div class="feature">
                        <div class="icon">
                            <i><img src="img/facebook.png" width="50px" height="50px" alt="Instagram video Downloader"></i>
                        </div>
                        <h3>Facebook</h3>
                        <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry standard dummy text ever since the 1500s.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature">
                        <div class="icon">
                            <i><img src="img/youtube.png" width="50px" height="50px" alt="Instagram video Downloader"></i>
                        </div>
                        <h3>YouTube</h3>
                        <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry standard dummy text ever since the 1500s.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature">
                        <div class="icon">
                            <i><img src="img/twitter.png" width="50px" height="50px" alt="Instagram video Downloader"></i>
                        </div>
                        <h3>Twitter</h3>
                        <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry standard dummy text ever since the 1500s.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature">
                        <div class="icon">
                            <i><img src="img/vimeo.png" width="50px" height="50px" alt="Instagram video Downloader"></i>
                        </div>
                        <h3>Vimeo</h3>
                        <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry standard dummy text ever since the 1500s.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature">
                        <div class="icon">
                            <i><img src="img/instagram.png" width="50px" height="50px" alt="Instagram video Downloader"></i>
                        </div>
                        <h3>Instagram</h3>
                        <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry standard dummy text ever since the 1500s.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
<section id="trades">
	<div class="container">
		<?php if($enableads == 1) {
                            echo $adscode;
                            } ?>
	</div>
</section>
<div id="footer">
	<div class="container">
		<span class="pull-left copyright"><i class="icon-download"></i> <?php echo $title; ?></span>
		<span class="pull-right footerlinks"><a href="">U2NyaXB0IGRvd25sb2FkZWQgZnJvbSBDT0RFTElTVC5DQw==</a></span>
	</div>
</div>
<script type="text/javascript">
var base_url = "<?php echo BASE_URL; ?>";
</script>
<script type="text/javascript" src="js/jquery.min.js"></script>
<script type="text/javascript" src="js/bootstrap.min.js"></script>
<script type="text/javascript" src="js/particles.min.js"></script>
<script type="text/javascript" src="js/codebird.js"></script>
<script type="text/javascript" src="js/custom.js"></script>
<div class="modal fade" id="contact" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title">Contact Us</h4>
                    </div>
                    <div class="modal-body">
                            <div class="form-group">
                                <label class="control-label">Full Name:
                                </label>
                                <input type="text" class="form-control" id="name">
                            </div>
                            <div class="form-group">
                                <label class="control-label">Email Address:</label>
                                <input type="email" class="form-control" id="email">
                            </div>
                            <div class="form-group">
                                <label class="control-label">Subject:
                                </label>
                                <input type="text" class="form-control" id="subject">
                            </div>
                            <div class="form-group">
                                <label class="control-label">Message:
                                </label>
                                <textarea class="form-control" rows="5" id="message"></textarea>
                            </div>
                    </div>
                    <div class="modal-footer">
                    	<div class="ajax-loadingc"><img src="img/loading.gif" alt="loading..."></div>
                        <button type="button" id="submit" class="btn btn-primary contactform">Submit</button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    </div>
              </div>
       </div>
</div>
</body>
</html>
