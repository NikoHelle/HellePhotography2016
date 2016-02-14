<?php
class FlickrPhotoHA
{
	const SIZE_SQUARE = "Square";
	const SIZE_LARGE_SQUARE = "Large Square";
	const SIZE_THUMBNAIL = "Thumbnail";
	const SIZE_SMALL = "Small";
	const SIZE_SMALL_240 = "Small 240";
	const SIZE_SMALL_320 = "Small 320";
	const SIZE_MEDIUM = "Medium";
	const SIZE_MEDIUM_640 = "Medium 640";
	const SIZE_MEDIUM_800 = "Medium 800";
	const SIZE_LARGE = "Large";
	const SIZE_LARGE_1600= "Large 1600";
	const SIZE_ORIGINAL = "Original";
	
	const SIZE_SQUARE_SHORT = "s";
	const SIZE_LARGE_SQUARE_SHORT= "q";
	const SIZE_THUMBNAIL_SHORT = "t";
	const SIZE_SMALL_SHORT = "?";
	const SIZE_SMALL_240_SHORT = "m";
	const SIZE_SMALL_320_SHORT = "n";
	const SIZE_MEDIUM_SHORT = "-";
	const SIZE_MEDIUM_640_SHORT = "z";
	const SIZE_MEDIUM_800_SHORT = "c";
	const SIZE_LARGE_SHORT = "b";
	const SIZE_LARGE_1600_SHORT = "??";
	const SIZE_ORIGINAL_SHORT = "o";
	
	const SIZE_SQUARE_CODE = 1;
	const SIZE_LARGE_SQUARE_CODE= 2;
	const SIZE_THUMBNAIL_CODE = 4;
	const SIZE_SMALL_CODE = 8;
	const SIZE_SMALL_240_CODE = 32;
	const SIZE_SMALL_320_CODE = 64;
	const SIZE_MEDIUM_CODE = 128;
	const SIZE_MEDIUM_640_CODE = 256;
	const SIZE_MEDIUM_800_CODE = 512;
	const SIZE_LARGE_CODE = 1024;
	const SIZE_LARGE_1600_CODE = 2048;
	//reserved const SIZE_LARGE_2400_CODE = 4096;
	//reserved const SIZE_LARGE_2400_CODE = 4096;
	const SIZE_ORIGINAL_CODE = 8192;
	
	const SIZE_LIST = "SIZE_SQUARE,SIZE_LARGE_SQUARE,SIZE_THUMBNAIL,SIZE_SMALL,SIZE_SMALL_240,SIZE_SMALL_320,SIZE_MEDIUM,SIZE_MEDIUM_640,SIZE_MEDIUM_800,SIZE_LARGE,SIZE_LARGE_1600,SIZE_ORIGINAL";
	
	
	const STATUS_OK = 0;
	const STATUS_REMOVED_IN_SET_UPDATE = 1;
	const STATUS_REMOVED = 2;
	
	private $_data = false;
	
	 function __construct($photo) {
		 $this->setPhoto($photo);
	 }
	 
	public function setPhoto($photo,$sizes) {
     	$this->_data = $photo; //db vs. flickr check
    } 
	
   
   public static function hasSize($photo,$size) {
      return true;
    }
	
	public static function getUrl($photo,$sizeShort) {
		
     	if(!FlickrPhotoHA::hasSize($photo,$sizeShort)) return false;
		else {
			if($sizeShort != FlickrPhotoHA::SIZE_ORIGINAL_SHORT) return "http://farm".$photo->farm.".staticflickr.com/".$photo->server."/".$photo->id."_".$photo->secret."_".$sizeShort.".jpg";
			return "http://farm".$photo->farm_id.".staticflickr.com/".$photo->server_id."/".$photo->image_id."_".$photo->original_secret."_".$sizeShort.".jpg";
		}
    }
	
	public static function numSizeToShort($numSize) {
		if($numSize===FlickrPhotoHA::SIZE_SQUARE_CODE) return  FlickrPhotoHA::SIZE_SQUARE_SHORT;
		if($numSize===FlickrPhotoHA::SIZE_LARGE_SQUARE_CODE) return FlickrPhotoHA::SIZE_LARGE_SQUARE_SHORT;
		if($numSize===FlickrPhotoHA::SIZE_THUMBNAIL_CODE) return FlickrPhotoHA::SIZE_THUMBNAIL_SHORT;
		if($numSize===FlickrPhotoHA::SIZE_SMALL_CODE) return FlickrPhotoHA::SIZE_SMALL_SHORT;
		if($numSize===FlickrPhotoHA::SIZE_SMALL_240_CODE) return FlickrPhotoHA::SIZE_SMALL_240_SHORT;
		if($numSize===FlickrPhotoHA::SIZE_SMALL_320_CODE) return FlickrPhotoHA::SIZE_SMALL_320_SHORT;
		if($numSize===FlickrPhotoHA::SIZE_MEDIUM_CODE) return FlickrPhotoHA::SIZE_MEDIUM_SHORT;
		if($numSize===FlickrPhotoHA::SIZE_MEDIUM_640_CODE) return FlickrPhotoHA::SIZE_MEDIUM_640_SHORT;
		if($numSize===FlickrPhotoHA::SIZE_MEDIUM_800_CODE) return FlickrPhotoHA::SIZE_MEDIUM_800_SHORT;
		if($numSize===FlickrPhotoHA::SIZE_LARGE_CODE) return FlickrPhotoHA::SIZE_LARGE_SHORT;
		if($numSize===FlickrPhotoHA::SIZE_LARGE_1600_CODE) return FlickrPhotoHA::SIZE_LARGE_1600_SHORT;
		if($numSize===FlickrPhotoHA::SIZE_ORIGINAL_CODE) return FlickrPhotoHA::SIZE_ORIGINAL_SHORT;
		return false;
     	
    }

	public static function sizeListToValue($sizes){
		$ret = 0;
		foreach ($sizes as $size){
			if($size->label ==FlickrPhotoHA::SIZE_SQUARE) $ret += FlickrPhotoHA::SIZE_SQUARE_CODE;
			else if($size->label ==FlickrPhotoHA::SIZE_LARGE_SQUARE) $ret +=FlickrPhotoHA::SIZE_LARGE_SQUARE_CODE;
			else if($size->label ==FlickrPhotoHA::SIZE_THUMBNAIL) $ret +=FlickrPhotoHA::SIZE_THUMBNAIL_CODE;
			else if($size->label ==FlickrPhotoHA::SIZE_SMALL) $ret +=FlickrPhotoHA::SIZE_SMALL_CODE;
			else if($size->label ==FlickrPhotoHA::SIZE_SMALL_240) $ret +=FlickrPhotoHA::SIZE_SMALL_240_CODE;
			else if($size->label ==FlickrPhotoHA::SIZE_SMALL_320) $ret +=FlickrPhotoHA::SIZE_SMALL_320_CODE;
			else if($size->label ==FlickrPhotoHA::SIZE_MEDIUM) $ret +=FlickrPhotoHA::SIZE_MEDIUM_CODE;
			else if($size->label ==FlickrPhotoHA::SIZE_MEDIUM_640) $ret +=FlickrPhotoHA::SIZE_MEDIUM_640_CODE;
			else if($size->label ==FlickrPhotoHA::SIZE_MEDIUM_800) $ret +=FlickrPhotoHA::SIZE_MEDIUM_800_CODE;
			else if($size->label ==FlickrPhotoHA::SIZE_LARGE) $ret +=FlickrPhotoHA::SIZE_LARGE_CODE;
			else if($size->label ==FlickrPhotoHA::SIZE_LARGE_1600) $ret +=FlickrPhotoHA::SIZE_LARGE_1600_CODE;
			else if($size->label ==FlickrPhotoHA::SIZE_ORIGINAL) $ret +=FlickrPhotoHA::SIZE_ORIGINAL_CODE;
		}
		return $ret;

	}

	public static function getOriginalSize($sizes){
		$ret  =(object) array();
		$ret->width = 0;
		$ret->height = 0;
		foreach ($sizes as $size){
			if($size->label ==FlickrPhotoHA::SIZE_ORIGINAL) {
				$ret->width = $size->width;
				$ret->height = $size->height;
			}

		}
		return $ret;

	}
	public static function getNamedSize($sizeName,$sizes){
		foreach ($sizes as $size){
			if($size->label ==$sizeName) {
				return $size;
			}

		}
		return false;

	}

	public static function getSizeList_NOT_TESTED($sizes){
		$ret  = array();
		foreach ($sizes as $size){
			$source = $size->source;

			$s = explode("_",$source);
			$s = array_pop($s);
			$ret[] = substr($s,0,1);


		}
		return $ret;

	}
	
	public static function getClosestSize($photo,$size) {
     	
    }
	
}
?>