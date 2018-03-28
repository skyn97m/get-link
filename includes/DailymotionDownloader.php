<?php
// Turn off error reporting
error_reporting(0);
class dailymotion
{
	public $home = "http://www.dailymotion.com";
	public $watch_url = "http://www.dailymotion.com/video/";
	public $url;
	public $id;
	public $title;
	public $content;
	public $downloadlinks;
	public $videoThumbnail;
	public $downloadlinks144;
	public $downloadlinks240;
	public $downloadlinks380;
	public $downloadlinks480;
	public $downloadlinks720;
	public $downloadlinks1080;
	public $error_code = 0;
	const DAILYMOTION_DOMAIN = 'dailymotion.com';
	
	function __construct($url){
		$this->url = $url;
    		$url = parse_url($url);
    		if (empty($url['host'])) {
      			$this->error_code = -1;
      			return FALSE;
    		}
    		$url['host'] = strtolower($url['host']);
		if ($url['host'] != self::DAILYMOTION_DOMAIN && $url['host'] != 'www.' . self::DAILYMOTION_DOMAIN) {
      			$this->error_code = -2;
      			return FALSE;
    		}
    		if (empty($url['path'])) {
      			$this->error_code = -3;
      			return FALSE;
    		}
    		$pattern = "/video\/([^_]+)/";
		preg_match($pattern, $this->url, $match);
		if(isset($match[1])){
			$id = $match[1];
		} else {
			$this->error_code = -1;
		 	return FALSE;
		}
		$this->id = $id;
      		$this->error_code = 0;
      		$this->getContent();
    		return $url;
	}
	function getThumb(){
		$videoThumbnail = "https://www.dailymotion.com/thumbnail/video/".$this->id;
		$this->videoThumbnail = $videoThumbnail;
		return $videoThumbnail;
	}
	function getContent(){
		$url = $this->watch_url.$this->id;
		$context = [
			'http' => [
				'method' => 'GET',
				'header' => "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.47 Safari/537.36",
				],
			];
		$context = stream_context_create($context);
		$content = file_get_contents($url, false, $context);
		$this->content = $content;
	}
	function getTitle(){
		preg_match("/<title>(.*)<\/title>/is", $this->content, $match);
		$title = $match[1];
		$this->title = $title;
		return $title;
	}
	function getDownload(){
		$content = $this->content;
		preg_match('/,"qualities":(.+),"reporting"/', $content, $match);
		if(empty($match)){
			$this->error_code = -3;
      		return FALSE;
		} else {
    	$link = json_decode($match[1],1);
    	$downloadlinks144=$downloadlinks240=$downloadlinks380=$downloadlinks480=$downloadlinks=$downloadlinks1080='';
    	$downloadlinks = array();
    	if(@$link['144']){
    		$downloadlinks144 = array("url" => $link['144'][1]['url'], "quality" => "144p");
    		$this->downloadlinks144 = $downloadlinks144;
    	}
    	if(@$link['240']){
    		$downloadlinks240 = array("url" => $link['240'][1]['url'], "quality" => "240p");
    		$this->downloadlinks240 = $downloadlinks240;
    	}
    	if(@$link['380']){
    		$downloadlinks380 = array("url" => $link['380'][1]['url'], "quality" => "380p");
    		$this->downloadlinks380 = $downloadlinks380;
    	}
    	if(@$link['480']){
    		$downloadlinks480 = array("url" => $link['480'][1]['url'], "quality" => "480p");
    		$this->downloadlinks480 = $downloadlinks480;
    	}
    	if(@$link['720']){
    		$downloadlinks720 = array("url" => $link['720'][1]['url'], "quality" => "720p");
    		$this->downloadlinks720 = $downloadlinks720;
    	}
    	if(@$link['1080']){
    		$downloadlinks1080 = array("url" => $link['1080'][1]['url'], "quality" => "1080p");
    		$this->downloadlinks1080 = $downloadlinks1080;
    	}
    	$downloadlinks = array_merge_recursive($downloadlinks144, $downloadlinks240, $downloadlinks380, $downloadlinks480, $downloadlinks720, $downloadlinks1080);
    	$this->downloadlinks = $downloadlinks;
    	return $downloadlinks;
    }
	}
	public function getError() {
    if ($this->error_code !== TRUE && $this->error_code !== 0) {
      return self::error($this->error_code);
    }
    return NULL;
  	}
    static function error($id) {
    $errors = array(
      -1 => 'The Video URL is Invalid!<br/>',
      -2 => 'The Video URL is not a Dailymotion URL.<br/>',
      -3 => 'No video found in this URL.<br/>',
    );
    if (isset($errors[$id])) {
      return $errors[$id];
    }
    return 'Unknown Error, Please try again.<br/>';
  }
}
?>