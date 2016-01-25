<?php
require_once "Base.php";
require_once(dirname(__FILE__)."/../helpers/OAuth.php");

class nhe_api_LinkedIn extends nhe_api_Base
{
	
	private $API_KEY = "77rggpkbr2jf9r";
	private $API_SECRET="TNY5krrqsluqQBez";
	//private $SCOPE="r_fullprofile r_emailaddress rw_nus";
	private $OAUTH_USER_TOKEN="728f1696-c190-4190-bce3-34da88585408";
	private $OAUTH_USER_SECRET="a4bdb3fb-6c40-44df-9f5a-20ddd68b3dde";
	
    // method declaration
   function __construct($config=false) {
	  $this->id = "linkedIn";
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
			if(!is_array($entries) || !$entries[0] || !isset($entries[0]->updateContent) || !isset($entries[0]->updateContent->companyStatusUpdate) || !isset($entries[0]->updateContent->companyStatusUpdate->share) || !isset($entries[0]->updateContent->companyStatusUpdate->share->content)) return false;
			return $entries[0]->updateContent->companyStatusUpdate->share->content; 
	}
	
	public function createHTML($obj){
		$data = array();
		$data["title"] = isset($obj->title) ? $obj->title : "";
		$data["text"] = isset($obj->description) ? $obj->description : "";
		$data["image"] = isset($obj->submittedImageUrl) ? $obj->submittedImageUrl : "";
		
		$template = $this->getTemplate();
		if(!$template) return $this->createError(1,"missing template",$template);
		
		return $this->config->mustache->render($template, $data);
	}
	
	public function getFeed(){
		
		$oauthParams = (object) array();
		$oauthParams->apiKey = $this->API_KEY;
		$oauthParams->apiSecret = $this->API_SECRET;
		$oauthParams->tokenKey = $this->OAUTH_USER_TOKEN;
		$oauthParams->tokenSecret = $this->OAUTH_USER_SECRET;
		
		$params = array();
		$params["event-type"] = "status-update";
		$params["format"] = "json";
		
		$params = (object) $params;
		
		$url = OAuth::createOAuth("http://api.linkedin.com/v1/companies/16019/updates",$oauthParams,$params);
		//die($url);
		$data = $this->getURL($url);

		return $data;

	}
	
	public function parseFeed($data){
		
		$json = json_decode($data);
		if(!$json || !isset($json->values)) return $this->createError(1,"JSON error",$data);
		
		$entries = $json->values;
		
		return $entries;
	}
	
	
}
?>