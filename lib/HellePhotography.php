<?php
class HellePhotography
{
    // property declaration
	public static $version = "0.1";
   	private $db;
	public static $animalsSetId = 0;
	
	private $_viewer = false;
	

    // method declaration
   function __construct($viewer) {
	 
	  require_once "FlickrViewer.php"; 
	  require_once "FlickrHA.php";
	  require_once "DB.php";
	 if(!($_viewer instanceof FlickrViewer)) throw new Exception('viewer must be defined');
	  $this->_viewer = $viewer;
	  
	  
   }
   
  	function getSetData($setId){
		$setId = intval($setId);
	}
	
}
?>