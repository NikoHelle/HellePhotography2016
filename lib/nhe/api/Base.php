<?php
class nhe_api_Base
{
    // property declaration
	
   public  $error = "";
   public  $id = "id";
    public  $data = false;

	public  $config = false;
	

    // method declaration
   function __construct($config=false) {
	  $this->config = $config;
   }
   
   public function createError($type=0,$message=false,$data=false){
		$ret = (object) array();
		$ret->error = true;
		$ret->type = $type;
		$ret->message = $message;
		$ret->data = $data;
		
		return $ret;
		
	}
	
	public function printError($error){
		return "type:".$error->type.",message:".$error->message.",data:".$error->data;
	}
	
	public static function isError($obj){
		if(!is_object($obj)) return false;
		if(!isset($obj->error)) return false;
		return $obj->error === true;
	}
   
   public function get($refresh=false){

	}
	
	public function doRefresh($refresh=false){
			return $refresh=== $this->id || $refresh==="all" || $refresh===true;
	}
	
	public function doPreview($preview=false){
			return $preview=== $this->id || $preview==="all" || $preview===true;
	}
	
	
	
	public function isCacheValid(){
		$file = $this->getCacheFileName();
		if(!file_exists($file)) return false;
		return ((time() - filemtime($file)) <= $this->config->cacheTime);
	}
	
	public function getCacheTime(){

	}
	
	public function getFromCache(){
		
		$file = $this->getCacheFileName();
		if(!file_exists($file)) return false;
		return file_get_contents($file);
		
	}
	
	
	public function setToCache($content){
		
		$file = $this->getCacheFileName();
		return (file_put_contents($file,$content) > 0);
	}
	
	public function logError($id,$error){
		//die("ERROR:".$id.", type:".$error);
		file_get_contents(URL_PREFIX."admin/logErrorFile.php?id=".$id."&error=".urlencode($error));
		return false;
	}
		
	public function getCacheFileName(){
		return $this->config->cacheFolder."/".$this->id.".cache";
	}
	
	public function getFeed(){

	}
	
	public function createHTML($obj){
		return ""; 
	}
	
	public function getURLF($url,$method="GET"){
		 $context = stream_context_create(
                    array('http' => 
                        array('method' => $method,
                        )
                    )
                );
 
 
   		 // Hocus Pocus
    	$response = file_get_contents($url, false, $context);
	
    	return $response;	
	}
	public function getURL($url,$method="GET"){
		$ch = curl_init();
		curl_setopt ($ch, CURLOPT_URL, $url);
		//curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, 5);
		curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);
		$response = curl_exec($ch);
		if (curl_errno($ch)) {
		 	return false;
		} else {
		  curl_close($ch);
		}
		
		return $response;
	}
	
	public function parseFeed($data){

	}
	
	public function getEntry($obj){
			return true;
	}
	
	public function getTemplate(){
		$templateFile = $this->config->templateFolder."/".$this->id.".html";
		if(!file_exists($templateFile)) return false;
		
		return file_get_contents($templateFile);
	}
	
	public function processTemplate($template,$data,$maxRows = 0){
		
		foreach ($data as $key => $value) {
			//print_r ("\nrep:".$key);
			$template = str_replace("#".$key."#",$value,$template);
			if(--$maxRows == 0) break;
		}
		
		
		return $template;
	}
	
	
}
?>