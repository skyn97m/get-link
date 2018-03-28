<?php
$failure = "The Video URL is Invalid!<br/>";
$hd = "";
$sd = "";
$title = "";
$url = $_POST['url'];
if($url != "" && !empty($url) && !is_null($url)){
function fbvideoid($url){
	$url =  str_replace(' ', '', $url);
	$pars = parse_url($url);
	if(isset($pars['path'])){
		$path = $pars['path'];
		if($path[strlen($path) - 1] == '/'):
			$path = rtrim($path, '/');
		endif;
		$count = count(explode("/", $path));
		$urltype = "";
		if($pars['path']=="/photo.php" || $pars['path']=="/video.php" || $pars['path']=="/video/video.php" || $pars['path']=="/" || $pars['path']=="/"){
		      $urltype = 2;
		} elseif($count == 4) {
		$urltype = 3;
		} elseif($count == 5) {
	               $urltype = 1;
		}
		if($urltype==1){
			$ex = explode("/", $path);
			return $videoid = $ex[4];
		} elseif($urltype==2) {
		if(isset($pars['query'])) {
                        parse_str($pars['query'], $e);
		      if (array_key_exists('v', $e)) {
			return $videoid = $e['v'];
		       } else {
			return $videoid = null;
		              }
		      } else {
			return $videoid = null;
		      }
		}elseif($urltype==3){
			$ex = explode("/", $path);
			return $videoid = $ex[3];
		} else {
			return $videoid = null;
		}
		} else {
			return $videoid = null;
		}
}
function trymobile($url){
        if (preg_match('/\d+/', $url, $matches)) {
                return $matches[0];
        } else {
                return null;
        }
}
$context = [
    'http' => [
        'method' => 'GET',
        'header' => "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.47 Safari/537.36",
    ],
];
$context = stream_context_create($context);
if(fbvideoid($url) !== null){
        $id = fbvideoid($url);
} else {
        $id = trymobile($url);
}
$data = file_get_contents("https://web.facebook.com/video.php?v=$id", false, $context);
function cleanStr($str){
    return html_entity_decode(strip_tags($str), ENT_QUOTES, 'UTF-8');
}
function hd($curl_content){
    $regex = '/hd_src_no_ratelimit:"([^"]+)"/';
    if (preg_match($regex, $curl_content, $match)) {
        return $match[1];
	} else {
	return;
	}
}
function sd($curl_content){
    $regex = '/sd_src_no_ratelimit:"([^"]+)"/';
    if (preg_match($regex, $curl_content, $match1)) {
        return $match1[1];
	} else {
	return;
	}
}
function title($curl_content){
    $title = null;
    if(preg_match('/h2 class="uiHeaderTitle"?[^>]+>(.+?)<\/h2>/', $curl_content, $matches)) {
        $title = $matches[1];
    } elseif (preg_match('/title id="pageTitle">(.+?)<\/title>/', $curl_content, $matches)) {
        $title = $matches[1];
    }
    return cleanStr($title);
}
$hd = hd($data);
$sd = sd($data);
$title = title($data);
}
if ($sd != "") {
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
      		<div class="col-md-6">
<p class="text-center"><b>Thumbnail</b></p><p class="text-center"><img class="img-thumbnail" src="https://graph.facebook.com/<?php echo $id; ?>/picture"></p>
      		</div>
		<div class="col-md-6">
                  <p class="text-center"><b>Information</b></p>
                  <div class="col-sm-3">Title:  </div>
                  <div class="col-sm-9"><a style="word-wrap:break-word;" href="<?php echo $url; ?>"><?php echo $title; ?></a></div>
              	</div>
      	</div>
      <div class="modal-footer" style="padding-right: 0px;padding-left: 0px;">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <a href="<?php echo $sd; ?>" download title="Download SD" class="btn btn-primary">Download SD</a>
        	<?php if($hd != ""){ ?>
        <a href="<?php echo $hd; ?>" download title="Download HD" class="btn btn-success">Download HD</a>
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
