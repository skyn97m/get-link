<?php
include_once 'VimeoDownloader.php';
class LinkHandler {

    /*
     * store the url pattern and corresponding downloader object
     * @var array
     */

    private $link_array = array();

    public function __construct() {
        $this->link_array = array("/^(?:http(?:s)?:\/\/)?(?:www\.)?vimeo\.com\/\d{8}/"=>new VimeoDownloader());
    }

    /*
     * Get the url pattern
     * return array
     */
    private function getPattern()
    {
        return array_keys($this->link_array);
    }

    /*
     * Get the downloader object if pattern matches else return false
     * @param string
     * return object or bool
     *
     */
    public function getDownloader($url)
    {
        for($i = 0; $i < count($this->getPattern()); $i++)
        {
            $pattern_st = $this->getPattern()[$i];
            /*
             * check the pattern match with the given video url
             */
            if(preg_match($pattern_st, $url))
            {
                return $this->link_array[$pattern_st];
            }
        }
        return $this->link_array[$pattern_st];
    }
}
$failure = "The Video URL is Invalid!<br/>";
$url = $_POST['url'];
$handler = new LinkHandler();
$downloader = $handler->getDownloader($url);
$downloader->setUrl($url);
if($downloader->hasVideo()){
$vimeo = $downloader->getVideoDownloadLink();
$title = $vimeo[0]['title'];
if(isset($vimeo[1]['url'])){
$lvideo = $vimeo[1]['url'];
}
if(isset($vimeo[0]['url'])){
$mvideo = $vimeo[0]['url'];
}
if(isset($vimeo[2]['url'])){
$hvideo = $vimeo[2]['url'];
}
?>
    <div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Download <?php echo $title; ?></h4>
      </div>
      <div class="modal-body">
      <div class="row">
      		<div class="col-md-12">
<video width="100%" height="240" controls>
<source src="<?php echo $lvideo; ?>" type="video/mp4">
Your browser does not support the video tag.
</video>
      		</div>
      	</div>
      <div class="modal-footer" style="padding-right: 0px;padding-left: 0px;">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <a href="<?php echo $lvideo; ?>" download title="Download SD" class="btn btn-primary">Download 360P</a>
        	<?php if($mvideo != ""){ ?>
        <a href="<?php echo $mvideo; ?>" download title="Download 540P" class="btn btn-info">Download 540P</a>
        	<?php } ?>
        	<?php if($hvideo != ""){ ?>
        <a href="<?php echo $hvideo; ?>" download title="Download HD" class="btn btn-success">Download 720P</a>
        	<?php } ?>
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
