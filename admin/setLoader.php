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

include_once "../lib/config.php";

include_once "../lib/FlickrHA.php";
include_once "../lib/FlickrPhotoHA.php";

include_once "../lib/FlickrDB.php";


$flickr = new FlickrHA($config->flickr->apiKey,$config->flickr->secret,$config->flickr->tokenKey,$config->flickr->tokenSecret);
$flickrDB = new FlickrDB($flickr,$config->db);

$setName = isset($_REQUEST["setName"]) ? $_REQUEST["setName"] : false;
$startPos = isset($_REQUEST["startPos"]) ? $_REQUEST["startPos"] : 0;
$perPage = 3;
if(!$setName || !$config->sets[$setName]) die("no set");

if($perPage && $startPos==0){
    echo "<p>Delete old...</p>";
    $flickrDB->deleteSetPhotos($config->sets[$setName]->setId);

}
echo "<p>Getting images for set ".$setName."...</p>";

#$setId = "72157663923690755";
$json = $flickr->getSetPhotos($config->sets[$setName]->setId);
$i = 0;
$saveCount = 0;
foreach ($json->photoset->photo as $photo){
    if($perPage && $startPos>$i){
        $i++;
        continue;
    }
    set_time_limit(20);
    echo "<div>";
    echo "<img src=\"".FlickrPhotoHA::getUrl($photo,FlickrPhotoHA::SIZE_SMALL_240_SHORT)."\" />";
    $sizes = $flickr->getPhotoSize($photo);
    //var_dump($sizes);
    $originalSize = FlickrPhotoHA::getOriginalSize($sizes);
    $originalSource = FlickrPhotoHA::getNamedSize(FlickrPhotoHA::SIZE_ORIGINAL,$sizes);
    $xlSource = FlickrPhotoHA::getNamedSize(FlickrPhotoHA::SIZE_LARGE_1600,$sizes);
    if($originalSource) $originalSource= $originalSource->source;
    if($xlSource) $xlSource= $xlSource->source;
    $sizes = FlickrPhotoHA::sizeListToValue($sizes);
    echo("<p>Sizes:".$sizes."</p>");
    echo("<p>Id:".$photo->id."</p>");
    echo("<p>originalSource:".$originalSource."</p>");
    echo("<p>xlSource:".$xlSource."</p>");
    echo "<p>perPage:".$perPage."</p>";
    echo "<p>saveCount:".$saveCount."</p>";
    echo "<p>startPos:".$startPos."</p>";
    #$comments = $flickr->getPhotoComments($photo);
    $info = $flickr->getPhotoInfo($photo->id);
    $description = $info->photo->description;

    if(!$description){
        $description = "";
    }
    else{
        $description= $description->_content;
    }
    echo("<p>description:".$description."</p>");
    if($description && (strpos($description,"hidden") !== false || strpos($description,"hide") !== false)) {
        $flickrDB->deletePhoto($config->sets[$setName]->setId,$photo);
        continue;
    }

    $i++;
    #if($i>4){
     #   break;
    #}
    $photo->sizes = $sizes;
    $photo->width = $originalSize->width;
    $photo->height = $originalSize->height;
    $photo->comments = $description;
    $flickrDB->savePhoto($config->sets[$setName]->setId,$photo,$originalSource,$xlSource);
    //die("x");
    echo "</div>";
    $saveCount++;
    if($saveCount==$perPage){
        break;
    }
}
if ($saveCount==0){
    die("ALL DONE");
}
?>
<script>
    window.location.href="/admin/setLoader.php?setName=potretit&startPos=<?php echo ($startPos+$perPage);?>"
</script>
</body>
</html>


