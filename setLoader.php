<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"  lang="en" xmlns:fb="https://www.facebook.com/2008/fbml"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"  lang="en" xmlns:fb="https://www.facebook.com/2008/fbml"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"  lang="en" xmlns:fb="https://www.facebook.com/2008/fbml"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"  lang="en" xmlns:fb="https://www.facebook.com/2008/fbml"> <!--<![endif]-->
<head>
    <meta charset="UTF-8">
    <title>GET SET</title>


</head>
<body>
<?php

ini_set("display_errors",E_ALL);
error_reporting(E_ALL);

$apiKey = "e9c2d11b4b0c9861766632f00320b128";
$secret = "25e5fcd971be9bb9";
$tokenKey= "72157631808364528-6b6d2e85e9679674";
$tokenSecret = "27c1b928d9306c59";

include_once "lib/config.php";

include_once "lib/FlickrHA.php";
include_once "lib/FlickrPhotoHA.php";

include_once "lib/FlickrDB.php";


$flickr = new FlickrHA($config->flickr->apiKey,$config->flickr->secret,$config->flickr->tokenKey,$config->flickr->tokenSecret);
$flickrDB = new FlickrDB($flickr,$config->db);

$setName = isset($_REQUEST["setName"]) ? $_REQUEST["setName"] : false;
if(!$setName || !$config->sets[$setName]) die("no set");

echo "<p>Getting images for set ".$setName."...</p>";

#$setId = "72157663923690755";
$json = $flickr->getSetPhotos($config->sets[$setName]->setId);
$i = 0;
foreach ($json->photoset->photo as $photo){
    set_time_limit(20);
    echo "<div>";
    echo "<img src=\"".FlickrPhotoHA::getUrl($photo,FlickrPhotoHA::SIZE_SMALL_240_SHORT)."\" />";
    $sizes = $flickr->getPhotoSize($photo);
    #var_dump($sizes);
    $originalSize = FlickrPhotoHA::getOriginalSize($sizes);
    $sizes = FlickrPhotoHA::sizeListToValue($sizes);
    echo("<p>Sizes:".$sizes."</p>");
    echo("<p>Id:".$photo->id."</p>");
    #$comments = $flickr->getPhotoComments($photo);
    $info = $flickr->getPhotoInfo($photo->id);
    $description = $info->photo->description;


    #print_r($info);

    if(!$description){
        $description = "";
    }
    else{
        $description= $description->_content;
    }
    echo("<p>description:".$description."</p>");

    if($description && strpos($description,"hide") === 0) {
        $flickrDB->deletePhoto($config->sets[$setName]->setId,$photo);
        continue;
    }

    #$i++;
    #if($i>4){
     #   break;
    #}
    $photo->sizes = $sizes;
    $photo->width = $originalSize->width;
    $photo->height = $originalSize->height;
    $photo->comments = $description;
    $flickrDB->savePhoto($config->sets[$setName]->setId,$photo);
    echo "</div>";
}

?>
</body>
</html>


