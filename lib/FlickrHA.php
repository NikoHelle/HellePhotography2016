<?php
class FlickrHA
{
    // property declaration
	public static $version = "0.1";
    public static $apiKey = false;
	public static $secret = false;
	public static $tokenKey = false;
	public static $tokenSecret = false;
	public $lastError = false;
	
	const API_GATEWAY = "https://api.flickr.com/services/rest/";
	const AUTH_GATEWAY = "https://www.flickr.com/services/oauth/";
	
	
	
	
/*s	small square 75x75
q	large square 150x150
t	thumbnail, 100 on longest side
m	small, 240 on longest side
n	small, 320 on longest side
-	medium, 500 on longest side
z	medium 640, 640 on longest side
c	medium 800, 800 on longest side†
b	large, 1024 on longest side*
o	original image, either a jpg, gif or png, depending on source format
*/

    // method declaration
   function __construct($apiKey,$secret,$tokenKey=false,$tokenSecret=false) {
	   ini_set("display_errors",E_ALL);
		error_reporting(E_ALL);
      FlickrHA::$apiKey = $apiKey;
	  FlickrHA::$secret = $secret;
	  FlickrHA::$tokenKey = $tokenKey;
	   FlickrHA::$tokenSecret = $tokenSecret;
	  require_once "FlickrParamsHA.php";
	  require_once "FlickrResultParserHA.php";
	   require_once "FlickrOAuthParamsHA.php";
	    require_once "FlickrPhotoHA.php";
   }
   
   private function _getResults($url){
	   ///??? try....catch
	   $rsp = file_get_contents($url);

	   $rspObj = json_decode($rsp);
		if($rspObj->stat === 'ok') return $rspObj;
		return FlickrHA::createErrorObject($rspObj,$url);
	}
	
	public static function createErrorObject($rspObj,$url){
		$ret = array(
		"errors" =>true,
		"stat" => $rspObj->stat,
		"code" => $rspObj->code,
		"message" => $rspObj->message,
		"url" => $url
		);	
		return (object) $ret;
	}
	
	 public static function hasError($rspObj) {
		 return (isset($rspObj->errors) && (intval($rspObj->errors)=== 1 || $rspObj->errors===true));
	 }
	 
	  public static function getErrorCode($errorObj){
		  return ($errorObj && isset($errorObj["code"])) ? $errorObj["code"] : 0;
	  }
	
	 public static function errorToString($errorObj,$delimeter="p"){
		 $ret ="";
		if($delimeter) $ret .="<".$delimeter.">";
		 $ret .= "stat:".$errorObj["stat"];
		if($delimeter) $ret .="</".$delimeter.">";
		
		if($delimeter) $ret .="<".$delimeter.">";
		 $ret .="code:".$errorObj["code"];
		if($delimeter) $ret .="</".$delimeter.">";
		
		if($delimeter) $ret .="<".$delimeter.">";
		 $ret .="message:".$errorObj["message"];
		if($delimeter) $ret .="</".$delimeter.">";
		
		if($delimeter) $ret .="<".$delimeter.">";
		 $ret .="url:".$errorObj["url"];
		if($delimeter) $ret .="</".$delimeter.">";
		
		return $ret;
	
	}
	
	 public function getSetPhotos($setId){
		
		//$url = FlickrHA::API_GATEWAY."?".FlickrParamsHA::getSetParams($setId);
		$params =(object) array();
		 $params->method = "flickr.photosets.getPhotos";
		 $params->photoset_id = $setId;


		$url = FlickrOAuthParamsHA::createOAuth(FlickrHA::API_GATEWAY,$params,false,false);
		#die($url);
		return $this->_getResults($url);
		
	
	 }


	public function getPhotoSize($photoId){
		
		//$url = FlickrHA::API_GATEWAY."?".FlickrParamsHA::getPhotoSizeParams($photoId);
		//die($url);
		 if(is_object($photoId)) $photoId = $photoId->id;
		
		#$params = array("method"=>"flickr.photos.getSizes","photo_id"=>$photoId);
		 $params =(object) array();
		 $params->method="flickr.photos.getSizes";
		 $params->photo_id=$photoId;

		$url = FlickrOAuthParamsHA::createOAuth(FlickrHA::API_GATEWAY,$params);
		#die($url);
		$result = $this->_getResults($url);
		if(FlickrHA::hasError($result)) echo FlickrHA::errorToString($result); //???change to = false
		else  {
			return $result->sizes->size;
			#return FlickrResultParserHA::getPhotoSizes($result);
			
		}
		
	
	 }
	 
	  public function getPhotoInfo($photoId){
		
		//$url = FlickrHA::API_GATEWAY."?".FlickrParamsHA::getPhotoSizeParams($photoId);
		//die($url);
		if(is_object($photoId)) $photoId = $photoId->id;
		
		#$params = array("method"=>"flickr.photos.getInfo","photo_id"=>$photoId);

		  $params =(object) array();
		  $params->method = "flickr.photos.getInfo";
		  $params->photo_id = $photoId;

		$url = FlickrOAuthParamsHA::createOAuth(FlickrHA::API_GATEWAY,$params);
		  #die($url);
		$result = $this->_getResults($url);
		if(FlickrHA::hasError($result)) return $result;
		
		return $result->sizes;

	 }

	  public function getPhotoComments($photoId){

		//$url = FlickrHA::API_GATEWAY."?".FlickrParamsHA::getPhotoSizeParams($photoId);
		//die($url);
		  if(is_object($photoId)) $photoId = $photoId->id;

		#$params = array("method"=>"flickr.photos.getInfo","photo_id"=>$photoId);

		  $params =(object) array();
		  $params->method = "flickr.photos.comments.getList";
		  $params->photo_id = $photoId;

		$url = FlickrOAuthParamsHA::createOAuth(FlickrHA::API_GATEWAY,$params);
		  #echo($url);
		$result = $this->_getResults($url);
		if(FlickrHA::hasError($result)) return $result;
		if(!isset($result->comments->comment)) return false;
		return $result->comments->comment;

	 }
   
	
	public function getPhotosSizes($photos){
		$ret = array();
		$retArray = true;
		if(!is_array($photos)) {
			$photos = array($photos);
			$retArray = false;
		}
		if(isset($photos["id"])) {
			$photos = array($photos["id"]);
			$retArray = false;
		}
		foreach($photos as $k => $v){
			if(is_object($v) || is_array($v)) $v = $v->id;
			$sizes = $this->_getPhotoSize($v);
			if(!$retArray) return $sizes;
			$ret[$v] = $sizes;
			
		}

		return $ret;
	}
	
	public function createApiSig($var="",$value=""){
		//This is secret + 'api_key' + [api_key] + 'perms' + [perms]. We then take the MD5 sum of the string - this is our [api_sig] value. We can then build our full login URL:
		return md5(FlickrHA::$secret."api_key".FlickrHA::$apiKey.$var.$value);
	}
	
	public static function createApiSig2($arrVals,$sort=true){
		$sig = FlickrHA::$secret;
		if($sort) ksort($arrVals);
		foreach($arrVals as $k => $v){
			$sig .=$k.$v;
			
		}
		return md5($sig);
	}
	
	public static function createApiUrlParameters($arrVals,$sort=true){
		$url ="";
		if($sort) ksort($arrVals);
		foreach($arrVals as $k => $v){
			if($url !=="") $url .="&";
			$url .=$k."=".$v;
			
		}
		return $url;
	}
	
	public function createToken($method="flickr.auth.getToken"){
		//This is secret + 'api_key' + [api_key] + 'perms' + [perms]. We then take the MD5 sum of the string - this is our [api_sig] value. We can then build our full login URL:
		return md5(FlickrHA::$secret."api_key".FlickrHA::$apiKey."frob".FlickrHA::$frob."method".$method);
	}
	
	public function getAuthUrl($perms){
		return "depricated";
		return  FlickrHA::AUTH_GATEWAY."?api_key=".FlickrHA::$apiKey."&perms=".$perms."&api_sig=".$this->createApiSig($perms);
	}
	
	public function checkOAuth(){
		$params = array("method"=>"flickr.prefs.getPrivacy","user_id"=>"1");//,"oauth_token"=>FlickrHA::$tokenKey,"tokenKey"=>"","tokenSecret"=>"");
		$url = FlickrOAuthParamsHA::createOAuth(FlickrHA::API_GATEWAY,$params);
		
		$params = array("method"=>"flickr.prefs.getPrivacy","api_key"=>3483762);
		$url = FlickrOAuthParamsHA::createOAuth(FlickrHA::API_GATEWAY,$params);
		//die($url);
		//return $this->_getResults($url);
		
		//$url = FlickrHA::API_GATEWAY."?method=flickr.auth.oauth.checkToken&?api_key=".FlickrHA::$apiKey."&auth_token=".FlickrHA::$tokenKey."&api_sig=".$this->createApiSig("auth_token",FlickrHA::$tokenKey);
	//http://api.flickr.com/services/rest/?method=flickr.auth.oauth.checkToken&api_key=c282c8e1974f0aa3941065c2bdd7623f&format=rest&auth_token=72157631808368113-f1a3caeb59d59b85&api_sig=4f038d7a865e808c725c2e72065e048a
		// die($url);
		$result = $this->_getResults($url);
		if(FlickrHA::hasError($result)) echo FlickrHA::errorToString($result); //??? = false
		else  {
			return FlickrResultParserHA::getPhotoSizes($result);
			
		}
	}
	
	public function getOAuthVerifierUrl($verifier){
		return  FlickrOAuthParamsHA::createVerifierUrl($verifier);
	}
	
	public function getOAuthUrl($tokenSecret="",$method=""){
		
			
			return  FlickrOAuthParamsHA::createAuthenticationUrl();
			/*$TIMESTAMP= gmdate('U');
			$nonce = md5($TIMESTAMP+rand()*100000);

			$SECRET=FlickrHA::$secret;
			$KEY= FlickrHA::$apiKey;
			
			$CONSUMER_SECRET= $SECRET. "&";
			
			$url_1 = "http://www.flickr.com/services/oauth/request_token";
			
			$url_2 = "oauth_callback=". urlencode("http://localhost/Flickr/login.php"). "&oauth_consumer_key=". $KEY;
			$url_2 .="&oauth_nonce=". $nonce. "&oauth_signature_method=HMAC-SHA1&oauth_timestamp=". $TIMESTAMP. "&oauth_version=1.0";
			
			
			$BASE_STRING ="";
			$BASE_STRING .= "GET&". urlencode($url_1). "&". urlencode($url_2);
			$API_SIG= base64_encode(hash_hmac("sha1",$BASE_STRING,$CONSUMER_SECRET, true) );
			
			$url= $url_1."?";
			$url.= "oauth_callback=". urlencode("http://localhost/Flickr/login.php"). "&oauth_consumer_key=". urlencode($KEY);
			$url.= "&oauth_nonce=". urlencode($nonce). "&oauth_signature_method=HMAC-SHA1&oauth_timestamp=". $TIMESTAMP. "&oauth_version=1.0";
			// append signature
			$url.= "&oauth_signature=". urlencode($API_SIG);*/
			//echo $url; die;
		
			//return $url;
	
			$timestamp= time();
			$nonce = md5($timestamp+rand()*100000);
			$consumerKey = FlickrHA::$apiKey;
			$version = "1.0";
			$signatureMethod =  "HMAC-SHA1";
			$callbackURL = urlencode("http://localhost:8888/test?");
			$consumerSecret = FlickrHA::$secret;
			
			$key = $consumerSecret."&".$tokenSecret;
			
			$ret1 = FlickrHA::AUTH_GATEWAY."request_token";
			$ret2 = "";
			$ret2 .="oauth_callback=".$callbackURL;
			$ret2 .="&oauth_consumer_key=".$consumerKey;
			$ret2 .="&oauth_nonce=".$nonce;
			$ret2 .="&oauth_signature_method=".$signatureMethod;
			$ret2 .="&oauth_timestamp=".$timestamp;
			$ret2 .="&oauth_token=".$tokenSecret;
			$ret2 .="&oauth_version=".$version;
			//$ret2 .="&oauth_signature=";
			
			
			$sig ="";
			$sig .= "GET&". urlencode($ret1). "&". urlencode($ret2);
			$sig = base64_encode(hash_hmac("sha1",$sig,$key, true) );
	
			return $ret1."?".$ret2."&oauth_signature=". urlencode($sig);
			
	}
	
	
	
	public function getOAuthAccessTokenUrl($tokenSecret=""){
		
	}
	
	public function getToken($perms){
		
		
		return  FlickrHA::AUTH_GATEWAY."?api_key=".FlickrHA::$apiKey."&perms=".$perms."&api_sig=".$this->createApiSig($perms);
	}
	
	public function test(){
		
		
		
		$r = "http://api.flickr.com/services/rest
		?nojsoncallback=1 &oauth_nonce=84354935
		&format=json
		&oauth_consumer_key=653e7a6ecc1d528c516cc8f92cf98611
		&oauth_timestamp=1305583871
		&oauth_signature_method=HMAC-SHA1
		&oauth_version=1.0
		&oauth_token=72157631804450795-5574e2da52eb9d42
		&oauth_signature=dh3pEH0Xk1qILr82HyhOsxRv1XA%3D
		&method=flickr.photosets.getPhotos";

	}
	
	public function test3($uid,$tokenSecret="",$method=""){
		
	
			$timestamp= time();
			$nonce = md5($timestamp+rand()*100000);
			$consumerKey = FlickrHA::$apiKey;
			$version = "1.0";
			$signatureMethod =  "HMAC-SHA1";
			$callbackURL = "";
			$consumerSecret = FlickrHA::$secret;
			
			$key = $consumerSecret."&".$tokenSecret;
			
			$ret1 = FlickrHA::API_GATEWAY;
			$ret2 = "";
			$ret2 .="api_key=".FlickrHA::$apiKey;
			$ret2 .="&auth_token=".FlickrHA::$tokenKey;
			$ret2 .="&format=".FlickrParamsHA::PHPFORMAT;
			$ret2 .="&oauth_callback=".$callbackURL;
			$ret2 .="&oauth_consumer_key=".$consumerKey;
			$ret2 .="&oauth_nonce=".$nonce;
			$ret2 .="&oauth_signature_method=".$signatureMethod;
			$ret2 .="&oauth_timestamp=".$timestamp;
			$ret2 .="&oauth_token=".$tokenSecret;
			$ret2 .="&oauth_version=".$version;
			$ret2 .="&method=".$method;
			//$ret2 .="&user_id=".$uid;
			//$ret2 .="&oauth_signature=";
			
			
			$sig ="";
			$sig .= "GET&". urlencode($ret1). "&". urlencode($ret2);
			$sig = base64_encode(hash_hmac("sha1",$sig,$key, true) );
	
			return $ret1."?".$ret2."&oauth_signature=". urlencode($sig);
	
		
	}
	
	public function checkOAuthToken(){
		
			$params = array();
			$params["method"] = "flickr.auth.oauth.checkToken";
			$params["api_key"] = FlickrHA::$apiKey;
			$params["oauth_token"] = FlickrHA::$tokenKey;
			$params["format"] = FlickrParamsHA::PHPFORMAT;
			$url = FlickrHA::API_GATEWAY."?".FlickrHA::createApiUrlParameters($params)."&api_sig=".FlickrHA::createApiSig2($params);
			$result = $this->_getResults($url);
			if(FlickrHA::hasError($result)) return FlickrHA::getErrorCode($result);
			return $result["stat"] ==="ok";
	
		
	}
	
	public function getPrivacy(){
		
			$params = array();
			$params["method"] = "flickr.prefs.getPrivacy";
			$params["api_key"] = FlickrHA::$apiKey;
			$params["oauth_token"] = FlickrHA::$tokenKey."x";
			$params["format"] = FlickrParamsHA::PHPFORMAT;
			$url = FlickrHA::API_GATEWAY."?".FlickrHA::createApiUrlParameters($params)."&api_sig=".FlickrHA::createApiSig2($params);
			
			$result = $this->_getResults($url);
			if(FlickrHA::hasError($result)) return FlickrHA::getErrorCode($result);
			return $result;//["stat"] ==="ok";
	
		
	}
	
	public function test2($photosetId,$tokenSecret="",$method=""){
	//http://api.flickr.com/services/rest/?oauth_callback=&oauth_consumer_key=e9c2d11b4b0c9861766632f00320b128&oauth_nonce=325b21ec66d6c7c3198385c9acb99903&oauth_signature_method=HMAC-SHA1&oauth_timestamp=1350726014&oauth_token=72157631808364528-6b6d2e85e9679674&oauth_version=1.0&method=flickr.prefs.getPrivacy&api_key=3483762&format=php_serial&oauth_signature=Du2WjQ3IeVz7IUxH1xlQKZKQKUM%3D	
	
			$timestamp= time();
			$nonce = md5($timestamp+rand()*100000);
			$consumerKey = FlickrHA::$apiKey;
			$version = "1.0";
			$signatureMethod =  "HMAC-SHA1";
			$callbackURL = "";
			$consumerSecret = FlickrHA::$secret;
			
			$key = $consumerSecret."&".$tokenSecret;
			
			$ret1 = FlickrHA::API_GATEWAY;
			$ret2 = "";
			$ret2 .="format=".FlickrParamsHA::PHPFORMAT;
			$ret2 .="&oauth_callback=".$callbackURL;
			$ret2 .="&oauth_consumer_key=".$consumerKey;
			$ret2 .="&oauth_nonce=".$nonce;
			$ret2 .="&oauth_signature_method=".$signatureMethod;
			$ret2 .="&oauth_timestamp=".$timestamp;
			$ret2 .="&oauth_token=".$tokenSecret;
			$ret2 .="&oauth_version=".$version;
			$ret2 .="&method=".$method;
			$ret2 .="&photoset_id=".$photosetId;
			//$ret2 .="&oauth_signature=";
			
			
			$sig ="";
			$sig .= "GET&". urlencode($ret1). "&". urlencode($ret2);
			$sig = base64_encode(hash_hmac("sha1",$sig,$key, true) );
	
			return $ret1."?".$ret2."&oauth_signature=". urlencode($sig);
	
		
	}
	
	
	
}
?>