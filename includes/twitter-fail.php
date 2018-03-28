<?php
$fail = $_POST['fail'];
if(!empty($fail) && $fail != ""){
?>
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Download Error</h4>
      </div>
      <div class="modal-body">
      <div class="row">
        <div class="col-md-12">
          <?php echo $fail; ?>
        </div>
      	</div>
      <div class="modal-footer" style="padding-right: 0px;padding-left: 0px;">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
 </div>
</div>
<?php } ?>
