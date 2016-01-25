<?php
require_once "Base.php";
require_once(dirname(__FILE__)."/../helpers/OAuth.php");

class nhe_api_Twitter extends nhe_api_Base
{
	
	private $API_KEY = "syDdtzTB5SYMhaNC9gPVw";
	private $API_SECRET="4MFxO6zFyTx43NhDOOwkanGWjI0wOdutQPWSMGdefho";
	//private $SCOPE="r_fullprofile r_emailaddress rw_nus";
	private $OAUTH_USER_TOKEN="200926464-3EP6perjevuiT8uOXNRimj1RfnEiPkZPGdrebkTU";
	private $OAUTH_USER_SECRET="v1t8vaA3ei3soB6VQ45bZKGuDDR7kWRAJCjyyGN9dAqmV";
	
    // method declaration
   function __construct($config=false) {
	  $this->id = "twitter";
	  parent::__construct($config);
   }
   
   
 /*  	
GET&http%3A%2F%2Fapi.twitter.com%2F1%2Fstatuses%2Fuser_timeline.rss&oauth_consumer_key%3DsyDdtzTB5SYMhaNC9gPVw%26oauth_nonce%3D91622ca759580b02feb33f2f20b7c817%26oauth_signature_method%3DHMAC-SHA1%26oauth_timestamp%3D1386542639%26oauth_token%3D200926464-3EP6perjevuiT8uOXNRimj1RfnEiPkZPGdrebkTU%26oauth_version%3D1.0%26screen_name%3DHelsinkiUni
Authorization header	
Authorization: OAuth oauth_consumer_key="syDdtzTB5SYMhaNC9gPVw", oauth_nonce="91622ca759580b02feb33f2f20b7c817", oauth_signature="fvs8NeRkXxG%2FbSgh9Xtbx0BcD6s%3D", oauth_signature_method="HMAC-SHA1", oauth_timestamp="1386542639", oauth_token="200926464-3EP6perjevuiT8uOXNRimj1RfnEiPkZPGdrebkTU", oauth_version="1.0"
cURL command	
curl --get 'http://api.twitter.com/1/statuses/user_timeline.rss' --data 'screen_name=HelsinkiUni' --header 'Authorization: OAuth oauth_consumer_key="syDdtzTB5SYMhaNC9gPVw", oauth_nonce="91622ca759580b02feb33f2f20b7c817", oauth_signature="fvs8NeRkXxG%2FbSgh9Xtbx0BcD6s%3D", oauth_signature_method="HMAC-SHA1", oauth_timestamp="1386542639", oauth_token="200926464-3EP6perjevuiT8uOXNRimj1RfnEiPkZPGdrebkTU", oauth_version="1.0"' --verbose
 */  
   
   public function get($refresh=false){

		$refresh = $this->doRefresh($refresh);

		if(!$refresh && $this->isCacheValid()) return $this->getFromCache();

		$feed = $this->getFeed();	
		$entries = $this->parseFeed($feed);
		
		if($this->isError($entries)) return $entries;
		
		if(!is_array($entries) || !$entries[0]) return $this->createError(1,"missing feed entry or data",$entries);
		$html = $this->createHTML($entries);
		$this->setToCache($html);
		return $html;
	}
	
	
	
	public function createHTML($entries){
		
		$ret = "";
		$template = $this->getTemplate();
		if(!$template) return $this->createError(1,"missing template",$entries);
		$data = array();
		$data["tweets"] = array();
		$pos = 2;
		//var_dump($entries);
		foreach($entries as $tweet){
				if(!isset($tweet->id)) continue;
				
				array_push($data["tweets"],array("pos"=>$pos,"text"=>$tweet->text,"id"=>$tweet->id_str,"user"=>"taivasfi"));
				$pos++;
				if($pos>12) break;
				
		}
		return $this->config->mustache->render($template, $data); // "Hello, World!"

		
		//return $ret;
	}
	
	public function getFeed(){
		
		$oauthParams = (object) array();
		$oauthParams->apiKey = $this->API_KEY;
		$oauthParams->apiSecret = $this->API_SECRET;
		$oauthParams->tokenKey = $this->OAUTH_USER_TOKEN;
		$oauthParams->tokenSecret = $this->OAUTH_USER_SECRET;
		
		$params = array();
		$params["screen_name"] = "taivasfi";
		//$params["count"] = "8";
		$params = (object) $params;
		
		$url = OAuth::createOAuth("https://api.twitter.com/1.1/statuses/user_timeline.json",$oauthParams,$params,false);
		
		$data = $this->getURL($url);

		return $data;

	}
	
	public function parseFeed($data){
		
		$json = json_decode($data);
		if(!$json || !$json[0]) return $this->createError(0,json_last_error());
		

		
		return $json;
	}
	
	public function convertTweetLinks($tweet){
	
	$search = array('|(http(s?)://[^ ]+)|', '/(^|[^a-z0-9_])@([a-z0-9_]+)/i');
	$replace = array('<a href="$1" target="_blank">$1</a>', '$1<a href="http://twitter.com/$2" target="_blank">@$2</a>');
	return preg_replace($search, $replace, $tweet);
	}
	
	
}
?>