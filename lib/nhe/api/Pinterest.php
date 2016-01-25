<?php
require_once "Base.php";

class nhe_api_Pinterest extends nhe_api_Base
{
	
	
    // method declaration
   function __construct($config=false) {
	  $this->id = "pinterest";
	  parent::__construct($config);
   }
   
   
      public function get($refresh=false){

		$refresh = $this->doRefresh($refresh);
		
		if(!$refresh && $this->isCacheValid()) return $this->getFromCache();

		$feed = $this->getFeed();	
		
		if(!$feed) return $this->createError(1,"missing feed entry or data",$entries);
		$html = $this->createHTML($feed);
		$this->setToCache($html);
		return $html;
	}
	
	
	
	public function createHTML($feed){
		$ret = "";
		$template = $this->getTemplate();
		if(!$template) return $this->createError(1,"missing template",$entries);
		$data = array();
		$data["pins"] = array();
		
		$pos = 2;
		foreach ($feed->get_items() as $item) {
					
			$text = $item->get_content();
			
			$img = "";
			$src = "";
			$link = "";
			$href = "";
			preg_match('/<img[^>]+>/',$text, $img); 
			preg_match('/src="([^"]*)"/',$img[0], $src);
			$src = substr($src[0],5);
			$src = substr($src,0,strlen($src)-1);
			preg_match('/<a[^>]+>/',$text, $link); 
			preg_match('/href="([^"]*)"/',$link[0], $href);
			$href =  $href[1];
			array_push($data["pins"],array("pos"=>$pos,"imgUrl"=>$src,"href"=>$href));
			
			$pos++;
			if($pos>12) break;
			
			
		}
	
		
		return $this->config->mustache->render($template, $data); // "Hello, World!"

		
		//return $ret;
	}
	
	public function getFeed($refresh=false){
		
		require_once(dirname(__FILE__)."/../../autoloader.php");
		
		$feed = new SimplePie();
		
		$url = "http://www.pinterest.com/taivasogilvy/feed.rss";
		
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