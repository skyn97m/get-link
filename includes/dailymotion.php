<?php
$url = $_POST['url'];
$failure = "";
$valid = 1;
//check if video url is not empty and is valid and getting download link from API
if (!empty($_POST['url'])){
	$valid = 1;
	require_once('DailymotionDownloader.php');
	$dailymotion = new dailymotion($url);
	$videourl = $dailymotion->getDownload();
	if ($dailymotion->error_code == TRUE && $dailymotion->error_code != 0){
		$valid = 0;
		$failure .= $dailymotion->getError();
	}
} else {
	$valid = 0;
	$failure .= "The URL field can not be empty!<br/>";
}
if($valid == 1){
?>
    <div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Download <?php echo $dailymotion->getTitle(); ?></h4>
      </div>
      <div class="modal-body">
      <div class="row">
      		<div class="col-md-12">
			<img src="<?php echo $dailymotion->getThumb(); ?>" style="width: 100%" />
      <br><br>
      		</div>
      	</div>
      <div class="modal-footer" style="padding-right: 0px;padding-left: 0px;">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <?php
        if($dailymotion->downloadlinks144){
        echo "<a download href='dailymotion/".base64_encode($dailymotion->downloadlinks144["url"])."'><button type='button' style='margin-left: 5px;' class='btn btn-success'>".$dailymotion->downloadlinks144["quality"]."</button></a>";
        }
        if($dailymotion->downloadlinks240){
        echo "<a download href='dailymotion/".base64_encode($dailymotion->downloadlinks240["url"])."'><button type='button' style='margin-left: 5px;' class='btn btn-success'>".$dailymotion->downloadlinks240["quality"]."</button></a>";
        }
        if($dailymotion->downloadlinks380){
        echo "<a download href='dailymotion/".base64_encode($dailymotion->downloadlinks380["url"])."'><button type='button' style='margin-left: 5px;' class='btn btn-success'>".$dailymotion->downloadlinks380["quality"]."</button></a>";
        }
        if($dailymotion->downloadlinks480){
        echo "<a download href='dailymotion/".base64_encode($dailymotion->downloadlinks480["url"])."'><button type='button' style='margin-left: 5px;' class='btn btn-success'>".$dailymotion->downloadlinks480["quality"]."</button></a>";
        }
        if($dailymotion->downloadlinks720){
        echo "<a download href='dailymotion/".base64_encode($dailymotion->downloadlinks720["url"])."'><button type='button' style='margin-left: 5px;' class='btn btn-success'>".$dailymotion->downloadlinks720["quality"]."</button></a>";
        }
        if($dailymotion->downloadlinks1080){
        echo "<a download href='dailymotion/".base64_encode($dailymotion->downloadlinks1080["url"])."'><button type='button' style='margin-left: 5px;' class='btn btn-success'>".$dailymotion->downloadlinks1080["quality"]."</button></a>";
        }
        ?>
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
