<?php
class FlickrParamsHA
{
	
	const PHPFORMAT = "php_serial";
   
   public static function getSetParams($setId,$extras = "date_taken") {
       $params = array(
			'api_key'	=>FlickrHA::$apiKey,
			'method'	=> 'flickr.photosets.getPhotos',
			'photoset_id'	=> $setId,
			'extras'	=> $extras,
			'format'	=> FlickrParamsHA::PHPFORMAT
		);
		
		return implode('&',FlickrParamsHA::encodeParams($params));
    }
	
	 public static function getPhotoSizeParams($photoId) {
       $params = array(
			'api_key'	=>FlickrHA::$apiKey,
			'method'	=> 'flickr.photos.getSizes',
			'photo_id'	=> $photoId,
			'format'	=> FlickrParamsHA::PHPFORMAT
		);
		
		return implode('&',FlickrParamsHA::encodeParams($params));
    }
	
	public static function encodeParams($params){
		$encoded_params = array();
		
		foreach ($params as $k => $v){
		
			$encoded_params[] = urlencode($k).'='.urlencode($v);
		}	
		return $encoded_params;
	}
}
?>