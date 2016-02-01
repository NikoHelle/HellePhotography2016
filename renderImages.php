<?php


ini_set("display_errors",E_ALL);
error_reporting(E_ALL);

include_once "lib/config.php";
include_once "lib/FlickrHA.php";
include_once "lib/FlickrPhotoHA.php";
include_once "lib/FlickrDB.php";

$flickr = new FlickrHA($config->flickr->apiKey,$config->flickr->secret,$config->flickr->tokenKey,$config->flickr->tokenSecret);
$flickrDB = new FlickrDB($flickr,$config->db);


require_once 'lib/Mustache/Autoloader.php';
Mustache_Autoloader::register();
$engine = new Mustache_Engine();

function getImageRatio($width,$height){
    $width = intval($width);
    $height = intval($height);
    if(!$width || !$height) return "3-2";
    $ratio = $width / $height;
    if($ratio ===1 || ($ratio >1 && $ratio <1.1) || ($ratio <1 && $ratio >0.9)) return "1-1";
    if($ratio>1){
        $diff_3_2 = abs($ratio - 3/2);
        $diff_4_3 = abs($ratio - 4/3);
        $diff_16_9 = abs($ratio - 16/9);
        if($diff_4_3 < $diff_3_2 && $diff_4_3 < $diff_16_9 ) return "4-3";
        if($diff_16_9 < $diff_3_2 && $diff_16_9 < $diff_4_3 ) return "16-9";
        return "3-2";

    }
    if($ratio<1){
        $diff_2_3 = abs($ratio - 2/3);
        $diff_3_4 = abs($ratio - 3/4);
        $diff_9_16 = abs($ratio - 9/16);
        if($diff_3_4 < $diff_2_3 && $diff_3_4 < $diff_9_16 ) return "3-4";
        if($diff_9_16 < $diff_2_3 && $diff_9_16 < $diff_3_4 ) return "9-16";

        return "2-3";
    }

}
$setName = isset($_REQUEST["setName"]) ? $_REQUEST["setName"] : false;
if(!$setName || !$config->sets[$setName]) die("no set");
$setId = $config->sets[$setName]->setId;
$data = $flickrDB->getDBSetPhotos($setId);
$template = file_get_contents("lib/templates/image_template.html");

$html = "";
foreach($data as $photo){
    //echo "url:".$photo->url."<br/>";
    $photo->ratio = getImageRatio($photo->original_width,$photo->original_height);
    $photo->url = FlickrPhotoHA::getUrl($photo,FlickrPhotoHA::SIZE_LARGE_SHORT);
    $photo->classes = array();
    $photo->hashtags = array();
    if($photo->comments !== ""){
        $meta = explode(" ",$photo->comments);
        foreach($meta as $tag){
            if(strpos($tag,"#") !== false) $photo->hashtags[] = $tag;
            else $photo->classes[] = $tag;
        }
    }
    if(!count($photo->hashtags)) $photo->classes[] = "noHashtags";
    $photo->classes = implode(" ",$photo->classes);
    $photo->base64Image = "data:image/gif;base64,R0lGODlhAQABAIAAAP///wAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw==";
    $html .= $engine->render($template,$photo);
}

file_put_contents("data/".$setName.".html",$html);

echo $html;