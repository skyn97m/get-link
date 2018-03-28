<?php
class yt{
	const CORE_DIRECTORY = __DIR__;
	const JS_BIN = "/signature";
	public $jsbin_directory;
	public $url;
	public $yt_title;
	public $id;
	private $watch;
	public $home = "http://www.youtube.com";
	public $watch_url = "https://www.youtube.com/watch?v=";
	public $js = "/yts/jsbin/player";
	private $pattern = NULL;
	private $dec = NULL;
	public $links;
	public $set = array();
	public $play = NULL;
	public $error = false;
	public $errors;
	
	function __construct($url){
		$this->url = $url;
		$this->id = $this->get_id($this->url);
		$this->jsbin_directory = preg_replace("/core/i", "", self::CORE_DIRECTORY).self::JS_BIN;
	}
	
	public function get_id($url){
        $pattern = "/^(?:http(?:s)?:\/\/)?(?:www\.)?(?:m\.)?(?:youtu\.be\/|youtube\.com\/(?:(?:watch)?\?(?:.*&)?v(?:i)?=|(?:embed|v|vi|user)\/))([^\?&\"'>]+)/";
        preg_match($pattern, $url, $matches);
        $id = (isset($matches[1])) ? $matches[1] : FALSE;
		if(strlen($id)==11){
			return $id;
		}else{
			return false;
		}
    }
	private function get_tag($start_tag, $end_tag, $website){
	$data = file_get_contents($website);
	$search = strpos($data, $start_tag);
	$sub = substr($data, $search);
	$length = strlen($end_tag);
	$end = strpos($sub, $end_tag);
	$new_data = substr($sub, 0,$end+$length);
	return $new_data;
}
	private function contents($url=NUll){
		if(isset($url)){
			$url = $url;
		}else{
			$this->id = $this->get_id($this->url);
			$url = $this->watch_url.$this->get_id($this->url);
		}
		$this->errors = NULL;
		$data = file_get_contents($url);
		return $data;
	}

		 function streamMapToArray($streamMap)
		{
			foreach($streamMap as &$map)
			{
				parse_str($map, $map_info);
				parse_str(urldecode($map_info['url']), $url_info);

				$map = [];
				$map['itag'] = $map_info['itag'];
				$map['type'] = explode(';', $map_info['type']);
				$format = explode('/', $map['type'][0]);
				$encoder = explode('"', $map['type'][1])[1];
				$map['type'] = array_merge($format, [$encoder]);
				$map['expire'] = isset($url_info['expire'])?$url_info['expire']:0;

				if(isset($map_info['bitrate']))
					$map['quality'] = isset($map_info['quality_label'])?$map_info['quality_label']:round($map_info['bitrate']/1000).'k';
				else
					$map['quality'] = isset($map_info['quality'])?$map_info['quality']:'';
		
				$signature = '';

				// The video signature need to be deciphered
				if(isset($map_info['s']))
				{
					if(!isset($this->set['playerID']))
						$this->set['playerID'] = $this->getPlayerScript(false, $this->set['playerID']);
					if(strpos($map_info['url'], 'ratebypass=')===false)
						$map_info['url'] .= '&ratebypass=yes';
	  				$signature = '&signature='.$this->decipherSignature($map_info['s']);
				}
		
				//Change to redirector
				$subdomain = explode(".googlevideo.com", $map_info['url'])[0];
				$subdomain = explode("//", $subdomain)[1];
				$map_info['url'] = str_replace($subdomain, 'redirector', $map_info['url']);

				$map['url'] = $map_info['url'].$signature;
			}
			return $streamMap;
		}
		function getPlayerScript($playerURL, $fromVideoID=false){
			if($fromVideoID){
				$data = $this->watch;
				if(strpos($data, 'Sorry for the interruption')!==false){
					$error = "Need to solve captcha from youtube";
					return false;
				}
				$data = explode("/yts/jsbin/player", $data)[1];
				$data = explode('"', $data)[0];
				$playerURL = "/yts/jsbin/player".$data;
			}
			try{
				$playerID = explode("/yts/jsbin/player", $playerURL)[1];
				$playerID = explode("-", explode("/", $playerID)[0]);
				$playerID = $playerID[count($playerID)-1];
			} catch(\Exception $e){
				$error = "Failed to parse playerID from player url: ".$playerURL;
				return false;
			}

			$playerURL = str_replace('\/', '/', explode('"', $playerURL)[0]);
			if(!file_exists($this->jsbin_directory."/$playerID")) {
				$decipherScript = $this->contents("http://www.youtube.com$playerURL");
				file_put_contents($this->jsbin_directory."/$playerID", $decipherScript);
			}

			$this->set['playerID'] = $playerID;
			return $playerID;
		}

		 function getSignatureParser(){
			$data['signature'] = ['playerID'=>$this->set['playerID']];
			
			
			if(!$this->set['playerID']) return false;

			if(file_exists($this->jsbin_directory."/".$this->set['playerID'])) {
				$decipherScript = file_get_contents($this->jsbin_directory."/".$this->set['playerID']);
			} else{
				echo "Player script was not found for id: ".$data['playerID'];
				
			}
		
			// Some preparation
			$signatureCall = explode('("signature",', $decipherScript);
			$callCount = count($signatureCall);
		
			// Search for function call for example: e.set("signature",PE(f.s));
			// We need to get "PE"
			$signatureFunction = "";
			for ($i=$callCount-1; $i > 0; $i--){
				$signatureCall[$i] = explode(');', $signatureCall[$i])[0];
				if(strpos($signatureCall[$i], '(')){
					$signatureFunction = explode('(', $signatureCall[$i])[0];
					break;
				}
				else if($i==0){
					$error = "Failed to get signature function";
					return false;
				}
			}
			$decipherPatterns = explode($signatureFunction."=function(", $decipherScript)[1];
			$decipherPatterns = explode('};', $decipherPatterns)[0];
			
		
			$deciphers = explode("(a", $decipherPatterns);
			for ($i=0; $i < count($deciphers); $i++) { 
				$deciphers[$i] = explode('.', explode(';', $deciphers[$i])[1])[0];
				if(count(explode($deciphers[$i], $decipherPatterns))>=2){
					$deciphers = $deciphers[$i];
					break;
				}
				else if($i==count($deciphers)-1){
					$error = "Failed to get deciphers function";
					return false;
				}
			}
		
			$deciphersObjectVar = $deciphers;
			$decipher = explode($deciphers.'={', $decipherScript)[1];
			$decipher = str_replace(["\n", "\r"], "", $decipher);
			$decipher = explode('}};', $decipher)[0];
			$decipher = explode("},", $decipher);
			
		
			// Convert pattern to array
			$decipherPatterns = str_replace($deciphersObjectVar.'.', '', $decipherPatterns);
			$decipherPatterns = str_replace('(a,', '->(', $decipherPatterns);
			$decipherPatterns = explode(';', explode('){', $decipherPatterns)[1]);
			$this->set['signature-patterns'] = $decipherPatterns;
		
			// Convert deciphers to object
			$deciphers = [];
			foreach ($decipher as &$function) {
				$deciphers[explode(':function', $function)[0]] = explode('){', $function)[1];
			}
			$this->set['signature-deciphers'] = $deciphers;

			return true;
		}
		
		 function decipherSignature($signature){
			$this->getSignatureParser();

			if(!isset($this->set['signature-patterns'])){
				$error = "Signature patterns not found";
				return false;
			}
			$patterns = $this->set['signature-patterns'];
			$deciphers = $this->set['signature-deciphers'];

			
			// Execute every $patterns with $deciphers dictionary
			$processSignature = $signature;
			for ($i=0; $i < count($patterns); $i++) {
				// This is the deciphers dictionary, and should be updated if there are different pattern
				// as PHP can't execute javascript
		
				//Handle non deciphers pattern
				if(strpos($patterns[$i], '->')===false){
					if(strpos($patterns[$i], '.split("")')!==false)
					{
						$processSignature = str_split($processSignature);
						
					}
					else if(strpos($patterns[$i], '.join("")')!==false)
					{
						$processSignature = implode('', $processSignature);
						
					}
					else{
						$error = "Decipher dictionary was not found #1";
						return false;
					}
				} 
				else
				{
					//Separate commands
					$executes = explode('->', $patterns[$i]);
		
					// This is parameter b value for 'function(a,b){}'
					$number = intval(str_replace(['(', ')'], '', $executes[1]));
					// Parameter a = $processSignature
		
					$execute = $deciphers[$executes[0]];
		
					//Find matched command dictionary
					
					switch($execute){
						case "a.reverse()":
							$processSignature = array_reverse($processSignature);
							
						break;
						case "var c=a[0];a[0]=a[b%a.length];a[b]=c":
							$c = $processSignature[0];
							$processSignature[0] = $processSignature[$number%count($processSignature)];
							$processSignature[$number] = $c;
							
						break;
						case "a.splice(0,b)":
							$processSignature = array_slice($processSignature, $number);
							
						break;
						default:
							$error = "Decipher dictionary was not found #2";
							return false;
						break;
					}
				}
			}
		
			

			return $processSignature;
		}
		public function new_url(){
		$id = $this->get_id($this->url);
	$data = $this->get_tag('<div id="downloadlist"', '</div></div>', 'http://video.genyoutube.net/'.$id);
$pattern = '/<a.*?href="(.*?)".*?>.*?<\/a>/i';
$regex = preg_match_all($pattern, $data, $match);
$test = array();
//print_r($match[1]);
foreach($match[1] as $link){
	//$res = preg_split ("/.*?.*?\//", $link, NULL,
        //PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
		
	$domain = 'http://redirector.googlevideo.com/';
	$rep = str_replace("GenYoutube.net_", "", $link);
	$encoded = $rep;
	if(preg_match('/.*?itag=22&.*?/i', $link, $new)){
		$test[] = array("url" => $encoded, "quality" => "720p", "type" => "mp4");
	}
	if(preg_match('/.*?itag=18&.*?/i', $link, $new)){
		$test[] = array("url" => $encoded, "quality" => "360p", "type" => "mp4");
		$this->play = $encoded;
	}
	if(preg_match('/.*?itag=36&.*?/i', $link, $new)){
		$test[] = array("url" => $encoded, "quality" => "240p", "type" => "3gp");
	}
	if(preg_match('/.*?itag=17&.*?/i', $link, $new)){
		$test[] = array("url" => $encoded, "quality" => "144p", "type" => "3gp");
	}
}
$this->links = $test;
		}
	public function get_url(){
		$data = $this->contents();
		$this->watch = $data;
		if(isset(explode('ytplayer.config = ', $data)[1])){
			$data = explode('ytplayer.config = ', $data)[1];
			$data = explode(';ytplayer.load', $data);
			$data = $data[0];
			$data = json_decode($data, true);
			unset($data['args']['fflags']);
			$this->getPlayerScript($data['assets']['js']);
			$data['title'] = $data['args']['title'];
			$data['duration'] = $data['args']['length_seconds'];
			$data['viewCount'] = $data['args']['view_count'];
			$data['channelID'] = $data['args']['ucid'];

			$streamMap = [[],[]];
				if(isset($data['args']['url_encoded_fmt_stream_map']) && isset($data['args']['title'])){
					$this->yt_title =  $data['args']['title'];
					$streamMap[0] = explode(',', $data['args']['url_encoded_fmt_stream_map']);
					if(count($streamMap[0])) $streamMap[0] =  $this->streamMapToArray($streamMap[0]);
				}
				if(isset($data['args']['adaptive_fmts'])){
					$streamMap[1] = explode(',', $data['args']['adaptive_fmts']);
					if(count($streamMap[1])) $streamMap[1] =  $this->streamMapToArray($streamMap[1]);
				}
			$title = "&title=".urlencode($this->yt_title);
			$new_links = array();
			for($i=0;$i<count($streamMap[0]);$i++){
				if($streamMap[0][$i]["itag"]==22){
					$new_links[] = array("url" => urldecode($streamMap[0][$i]["url"]).$title, "quality" => "720p", "type" => "mp4");
				}
				if($streamMap[0][$i]["itag"]==18){
					$new_links[] = array("url" => urldecode($streamMap[0][$i]["url"]).$title, "quality" => "360p", "type" => "mp4");
					$this->play = urldecode($streamMap[0][$i]["url"]);
				}
				if($streamMap[0][$i]["itag"]==36){
					$new_links[] = array("url" => urldecode($streamMap[0][$i]["url"]).$title, "quality" => "240p", "type" => "3gp");
				}
				if($streamMap[0][$i]["itag"]==17){
					$new_links[] = array("url" => urldecode($streamMap[0][$i]["url"]).$title,"quality" => "144p", "type" => "3gp");
				}
			}
			$this->links = $new_links;
		}else{
			$this->links = NULL;
			$this->error = TRUE;
		}
	}
}