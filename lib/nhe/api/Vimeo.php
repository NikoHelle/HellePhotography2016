<?php
require_once "Base.php";
require_once(dirname(__FILE__)."/../helpers/OAuth.php");

class nhe_api_Vimeo extends nhe_api_Base
{
	

	
    // method declaration
   function __construct($config=false) {
	  $this->id = "vimeo";
	  parent::__construct($config);
   }
   
   
   public function get($refresh=false){

		$refresh = $this->doRefresh($refresh);

		if(!$refresh && $this->isCacheValid()) return $this->getFromCache();

		$feed = $this->getFeed();	
		$entries = $this->parseFeed($feed);
		if($this->isError($entries)) return $entries;
		$entry = $this->getEntry($entries);
		if(!$entry) return $this->createError(1,"missing feed entry or data",$entries);
		$html = $this->createHTML($entry);
		$this->setToCache($html);
		return $html;
	}
	
	public function getEntry($entries){
			if(!is_array($entries) || !$entries[0] || !isset($entries[0]->id)) return false;
			return $entries[0]; 
	}
	
	public function createHTML($obj){
		
		//id checked in getEntry...if(!$template) return $this->createError(1,"missing template",$entries);
		
		return $obj->id;
	}
	
	public function getFeed(){
		
		$url = "http://vimeo.com/api/v2/user6982508/videos.json";
		
		$data = $this->getURL($url);

		return $data;

	}
	
	public function parseFeed($data){
		
		$json = json_decode($data);
		if(!$json) return $this->createError(0,json_last_error());
		
		
		return $json;
	}
	
	
}
?>