<?php
require_once "Base.php";

class nhe_api_LogoPage extends nhe_api_Base
{
	
	
    // method declaration
   function __construct($config=false) {
	  $this->id = "logoPage";
	  parent::__construct($config);
   }
   
   
   public function get($refresh=false,$preview=false){


		$refresh = $this->doRefresh($refresh);
		$preview = $this->doPreview($preview);

		if(!$refresh && !$preview && $this->isCacheValid()) return $this->getFromCache();

		$html = $this->getFeed();	
		
		if(!$html || strlen($html) < 100) return $this->createError(1,"missing feed entry or data",$html);
		
		$this->setToCache($html);
		return $html;
	}
	
	
	
	
	
	public function getFeed($preview=false){
		
		$url = $this->config->server."/cms/?hap=1&component=logoPage&content=html&columns=18&rowDiff=1";
		if($preview) $url .="&preview=1";
		
		$data = $this->getURL($url);

		return $data;

	}
	
	
	
	
}
?>