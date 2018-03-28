<?php
$url = $_POST['url'];
if(!empty($url) && $url != ""){
?>
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Download Video</h4>
      </div>
      <div class="modal-body">
      <div class="row">
        <div class="col-md-12">
<video width="100%" height="240" controls>
<source src="<?php echo $url; ?>" type="video/mp4">
Your browser does not support the video tag.
</video><br/><br/>
        </div>
      	</div>
      <div class="modal-footer" style="padding-right: 0px;padding-left: 0px;">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <a download href="<?php echo $url; ?>"><button type="button" class="btn btn-success">Download</button></a>
      </div>
    </div>
  </div>
 </div>
</div>
<?php } ?>
