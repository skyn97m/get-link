<?php
$url = $_POST['url'];
$success = "";
$failure = "";
$valid = 1;
//check if video url is not empty and is valid and getting download link from API
if (!empty($_POST['url'])){
	$valid = 1;
	require_once('InstagramDownloader.php');
	$instagram = new InstagramDownload($url);
	$downloadUrl = $instagram->downloadUrl();
	if ($instagram->error_code == TRUE && $instagram->error_code != 0){
		$valid = 0;
		$failure .= $instagram->getError();
		}
	} else {
	$valid = 0;
	$failure .= "URL field can not be empty!<br/>";
}
if($valid == 1){
?>
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Download</h4>
     </div>
     <div class="modal-body">
			 <?php if($instagram->type == 'video'){ ?>
					<video width="100%" height="240" controls>
					<source src="<?php echo $downloadUrl; ?>" type="video/mp4">
					Your browser does not support the video tag.
					</video>
		<br><br>
	<?php } else { ?>
					<img src="<?php echo $downloadUrl; ?>" style="width:100%">
	<?php } ?>
     </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <a href="<?php echo $downloadUrl; ?>" download title="Download Now" class="btn btn-success">Download Now</a>
    </div>
  </div>
</div>
</div>
<?php } else { ?>
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Link Error</h4>
      </div>
      <div class="modal-body">
        <p><?php echo $failure; ?></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<?php } ?>
