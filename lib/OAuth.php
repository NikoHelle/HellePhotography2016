<?php
class OAuth
{
 
 
	public static function createOAuthParams($params=false) {
		if(!is_array($params)) $params = array();
		//is params already correct param object?
		if(is_object($params) && isset($params->tokenKey)) return $params;
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
   
   public static function createOAuth($url,$oauthParams,$params=false,$paramsFirst=true) {
     		
			$timestamp= time();
			$nonce = md5($timestamp+rand()*100000);
			$consumerKey = $oauthParams->apiKey;
			$version = "1.0";
			$signatureMethod =  "HMAC-SHA1";
			$callbackURL = "";
			$consumerSecret = $oauthParams->apiSecret;
			
			
			//$params=FlickrOAuthParamsHA::createOAuthParams($params);
			
			$key = $consumerSecret."&".$oauthParams->tokenSecret;
			
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
			$ret2 .="&oauth_token=".$oauthParams->tokenKey;
			if(isset($oauthParams->oauth_verifier)) $ret2 .="&oauth_verifier=".$oauthParams->oauth_verifier;
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