<?php
$failure = "";
$url = $_POST['url'];
$valid = 1;
if (!isset($url) || $url == '') {
    $valid = 0;
    $failure .= "The Video URL must be entered!<br/>";
} else {
        require 'YoutubeDownloader.php';
        $yt = new yt($url);
        if ($yt->get_id($url) == FALSE) {
        $valid = 0;
        $failure .= "The Youtube Video URL is Invalid!<br/>";
        }
        $yt->get_url();
        if ($yt->error == TRUE) {
        $valid = 0;
        $failure .= "The System failed to get download links, Please try again.<br/>";
        }
}
if($valid == 1){
foreach($yt->links as $key => $value){
  $yt->links[$key]['url'] = "youtube/".base64_encode($value['url']);
}
?>
    <div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Download <?php echo $yt->yt_title; ?></h4>
      </div>
      <div class="modal-body">
      <div class="row">
          <div class="col-md-12">
      <img src="https://i.ytimg.com/vi/<?php echo $yt->id; ?>/hqdefault.jpg" style="width: 100%" />
      <br><br>
          </div>
        </div>
      <div class="modal-footer" style="padding-right: 0px;padding-left: 0px;">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <?php
        for($i=0;$i<count($yt->links);$i++){
          echo "<a download href='".$yt->links[$i]["url"]."'><button type='button' style='margin-left: 5px;' class='btn btn-success'>".$yt->links[$i]["quality"]."</button></a>";
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