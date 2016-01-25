<?php
class FlickrOAuthParamsHA
{
 
	public static function createOAuthParams($params=false) {
		if(!is_array($params)) $params = array();
		//is params already correct param object?
		if(is_object($params) && isset($params->tokeyKey)) return $params;
		$ret = (object) $params;
		if(!isset($ret->tokenKey)) $ret->tokenKey = FlickrHA::$tokenKey;
		if(!isset($ret->tokenSecret)) $ret->tokenSecret = FlickrHA::$tokenSecret;
		if(!isset($ret->format)) $ret->format = FlickrParamsHA::PHPFORMAT;
		return $ret;
	
	}
    public static function createAuthenticationUrl() {
		return FlickrOAuthParamsHA::createOAuth(FlickrHA::AUTH_GATEWAY."request_token",array("tokenKey"=>"","tokenSecret"=>""));
	}
	public static function createVerifierUrl($verifier) {
		return FlickrOAuthParamsHA::createOAuth(FlickrHA::AUTH_GATEWAY."access_token",array("oauth_verifier"=>$verifier));
	}
   
   public static function createOAuthOLD($url,$params=false) {
     		
			$timestamp= time();
			$nonce = md5($timestamp+rand()*100000);
			$consumerKey = FlickrHA::$apiKey;
			$version = "1.0";
			$signatureMethod =  "HMAC-SHA1";
			$callbackURL = "";
			$consumerSecret = FlickrHA::$secret;
			
			$params=FlickrOAuthParamsHA::createOAuthParams($params);
			
			$key = $consumerSecret."&".$params->tokenSecret;
			
			$ret1 = $url;
			$ret2 = "";
			$ret2 .="oauth_callback=".$callbackURL;
			$ret2 .="&oauth_consumer_key=".$consumerKey;
			$ret2 .="&oauth_nonce=".$nonce;
			$ret2 .="&oauth_signature_method=".$signatureMethod;
			$ret2 .="&oauth_timestamp=".$timestamp;
			$ret2 .="&oauth_token=".$params->tokenKey;
			if(isset($params->oauth_verifier)) $ret2 .="&oauth_verifier=".$params->oauth_verifier;
			$ret2 .="&oauth_version=".$version;
			
			if(isset($params->method) && $params->method != "") {
				foreach ($params as $k => $v){
					if($k === "tokenKey") continue;
					if($k === "tokenSecret") continue;
					$ret2 .="&".$k."=".$v;
				}	
			}
			
			//$ret2 .="&oauth_signature=";
			
			
			$sig ="";
			$sig .= "GET&". urlencode($ret1). "&". urlencode($ret2);
			$sig = base64_encode(hash_hmac("sha1",$sig,$key, true) );
	
			return $ret1."?".$ret2."&oauth_signature=". urlencode($sig);
    }

	public static function createOAuth($url,$params=false,$oauth_verifier=false,$paramsFirst=true) {

		$timestamp= time();
		$nonce = md5($timestamp+rand()*100000);
		$consumerKey = FlickrHA::$apiKey;
		$version = "1.0";
		$signatureMethod =  "HMAC-SHA1";
		$callbackURL = "";
		$consumerSecret = FlickrHA::$secret;

		if(!$params) {
			$params = (object) array();
		}
		if(!isset($params->format)){
			$params->nojsoncallback = 1;
			$params->format = "json";
		}

		//$params=FlickrOAuthParamsHA::createOAuthParams($params);

		$key = $consumerSecret."&".FlickrHA::$tokenSecret;

		$ret1 = $url;
		$ret2 = "";

		if($params && is_object($params) && $paramsFirst) {

			foreach ($params as $k => $v){
				//if($k === "tokenKey") continue;
				//if($k === "tokenSecret") continue;
				if($ret2 !== "") $ret2 .="&";
				$ret2 .= $k."=".$v;
			}
			$ret2 .="&";
		}

		//$ret2 .="oauth_callback=".$callbackURL;
		$ret2 .="oauth_consumer_key=".$consumerKey;
		$ret2 .="&oauth_nonce=".$nonce;
		$ret2 .="&oauth_signature_method=".$signatureMethod;
		$ret2 .="&oauth_timestamp=".$timestamp;
		$ret2 .="&oauth_token=".FlickrHA::$tokenKey;
		if($oauth_verifier) $ret2 .="&oauth_verifier=".$oauth_verifier;
		$ret2 .="&oauth_version=".$version;

		if($params && is_object($params) && !$paramsFirst) {
			foreach ($params as $k => $v){
				//if($k === "tokenKey") continue;
				//if($k === "tokenSecret") continue;

				$ret2 .= "&".$k."=".$v;
			}

		}

		//$ret2 .="&oauth_signature=";


		$sig ="";
		$sig .= "GET&". urlencode($ret1). "&". urlencode($ret2);
		//return $sig;
		$sig = base64_encode(hash_hmac("sha1",$sig,$key, true) );

		return $ret1."?".$ret2."&oauth_signature=". urlencode($sig);
	}
	
	
}
?>