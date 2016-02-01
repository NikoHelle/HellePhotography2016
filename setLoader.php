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


include_once "lib/FlickrHA.php";
include_once "lib/FlickrPhotoHA.php";

include_once "lib/FlickrDB.php";


$flickr = new FlickrHA($apiKey,$secret,$tokenKey,$tokenSecret);
$flickrDB = new FlickrDB($flickr);

#$type = isset($_REQUEST["type"]) ? $_REQUEST["type"] : false;
$id = isset($_REQUEST["id"]) ? $_REQUEST["id"] : false;
$error = false;

#$info = $flickr->getPhotoInfo("16077656781");



#$sizes = $flickr->getPhotosSizes($json->photoset->photo);
#$info = $flickr->getPhotoComments($json->photoset->photo[0]->id);

echo "<p>Getting images for set ".$id."...</p>";

$setId = "72157663923690755";
$json = $flickr->getSetPhotos($setId);
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
    #$i++;
    #if($i>4){
     #   break;
    #}
    #continue;
    $photo->sizes = $sizes;
    $photo->width = $originalSize->width;
    $photo->height = $originalSize->height;
    $photo->comments = $description;
    $flickrDB->savePhoto($setId,$photo);
    #print_r($flickrDB->savePhoto($setId,$photo));
    #die();
    #$originalSecret = 	$flickr->getPhotoInfo($photo);
    #if(FlickrHA::hasError($originalSecret)) return FlickrViewer::createErrorObject("Error while getting original secret :".$originalSecret->message);
    #$originalSecret = $originalSecret->originalsecret;
    #$photoId = $this->savePhotoData($setId,$photo,$originalSecret,$sizes);
    #if(FlickrViewer::hasError($photoId)) return FlickrViewer::createErrorObject("Error while saving set photo :".$photoId->message);
    echo "</div>";
}

?>
</body>
</html>


