<?php
class ReturnObject
{
	
   
   public static function create($value,$error=false) {
      	$ret =  array(
		"errors" =>false,
		"error" =>false,
		"stat" =>false,
		"code" =>false,
		"message"	 =>false,
		"url"	 =>false,
		"value"=>$value
		);	
		
		if($error !== false) {
			$ret["errors"] = true;
			$ret["error"] =$error;
		}
		//for Flickr's return object
		if(isset($value["stat"])) $ret["stat"] = $ret["stat"];
		if(isset($value["code"])) $ret["code"] = $ret["code"];
		if(isset($value["message"])) $ret["message"] = $ret["message"];
		if(isset($value["url"])) $ret["url"] = $ret["url"];
    }
	
	 public static function hasError($returnObject) {
		 return (isset($returnObject["errors"]) && $returnObject["errors"]===true);
	 }
	
	
}
?>