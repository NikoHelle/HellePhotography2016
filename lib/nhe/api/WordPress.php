<?php
require_once "Base.php";

class nhe_api_WordPress extends nhe_api_Base
{
	
	
    // method declaration
   function __construct($config=false) {
	  $this->id = "wordPress";
	  parent::__construct($config);
   }
   
   
      public function get($refresh=false){


		$refresh = $this->doRefresh($refresh);

		if(!$refresh && $this->isCacheValid()) return $this->getFromCache();

		$feed = $this->getFeed();	
		
		if(!$feed) return $this->createError(1,"missing feed entry or data",$feed);
		$html = $this->createHTML($feed);
		$this->setToCache($html);
		return $html;
	}
	
	
	
	public function createHTML($feed){
		$ret = "";
		$template = $this->getTemplate();
		if(!$template) return $this->createError(1,"missing template",$entries);
		
		$item = $feed->get_item(0);
		$data = array("title"=>$item->get_title(),"description"=>strip_tags($item->get_content()));
		
		
	
		
		return $this->config->mustache->render($template, $data); // "Hello, World!"

		
		//return $ret;
	}
	
	public function getFeed($refresh=false){
		
		require_once(dirname(__FILE__)."/../../autoloader.php");
		
		$feed = new SimplePie();
		
		$url = "http://taivasfi.blogspot.fi/feeds/posts/default";
		$url = "http://blogi.taivas.fi/?feed=rss2";
		
		$feed->set_feed_url($url);
		if($refresh) {
			$feed->enable_cache(false);
		}
		else {
			$feed->set_cache_location($this->config->cacheFolder);
			$feed->set_cache_duration($this->config->cacheTime);
		}
		$feed->enable_order_by_date(false);
		$feed->init();
		$feed->handle_content_type();
		
		
		if ($feed->error())
		{
			
			return false;
		}
		else {
			return $feed;
		
		}	

	}
	
	
	
	
}
?>