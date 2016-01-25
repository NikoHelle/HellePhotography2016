<?php
class FlickrResultParserHA
{
	
	
	
   
   public static function getSetPhotos($result) {
    
		$photos = array();
		
		if(!isset($result["photoset"]) || !isset($result["photoset"]["photo"])) return $photos;
		
		foreach ($result["photoset"]["photo"] as $k => $v){
			$photos[] = $v;
		}	
		
		return $photos;
	}
	
	 public static function getPhotoSizes($result) {
    
		$sizes = array();
		
		if(!isset($result->sizes) || !isset($result->sizes->size)) return $photos;
		
		foreach ($result["sizes"]["size"] as $k => $v){
			
			$sizes[$v["label"]] = array("width"=>$v["width"],"height"=>$v["height"]);
		}	
		
		return $sizes;
	}

	
	 public static function getUrl($photo,$size="m") {
    	return "http://farm".$photo["farm"].".static.flickr.com/".$photo["server"]."/".$photo["id"]."_".$photo["secret"]."_".$size.".jpg";
	}
}
?>