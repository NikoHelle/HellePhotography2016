<?php

ini_set("display_errors",E_ALL);
error_reporting(E_ALL);

$apiKey = "e9c2d11b4b0c9861766632f00320b128";
$secret = "25e5fcd971be9bb9";
//$tokenKey= "72157631808364528-6b6d2e85e9679674";
//$tokenSecret = "27c1b928d9306c59";

$oauthToken = "72157631808364528-6b6d2e85e9679674";
$oauthTokenSecret="27c1b928d9306c59";
$oauthVerifier="4cad8c1d31b1b110";

//oauth_callback_confirmed=true&oauth_token=72157650041968019-0535d8a55453fb1c&oauth_token_secret=576094bc71f03b93
//oauth_callback_confirmed=true&oauth_token=72157650463584725-f0885c8d37c81087&oauth_token_secret=9bc4d848e474d7ea
//oauth_callback_confirmed=true&oauth_token=72157650619169756-86de102c9d520dcb&oauth_token_secret=55b03daeec277500
//?oauth_token=72157650619169756-86de102c9d520dcb&oauth_verifier=4cad8c1d31b1b110

//fullname=Niko%20Helle&oauth_token=72157631808364528-6b6d2e85e9679674&oauth_token_secret=27c1b928d9306c59&user_nsid=24251872%40N00&username=HellePhotography
//fullname=Niko%20Helle&oauth_token=72157631808364528-6b6d2e85e9679674&oauth_token_secret=27c1b928d9306c59&user_nsid=24251872%40N00&username=HellePhotography
//fullname=Niko%20Helle&oauth_token=72157631808364528-6b6d2e85e9679674&oauth_token_secret=27c1b928d9306c59&user_nsid=24251872%40N00&username=HellePhotography
include_once "lib/FlickrHA.php";
include_once "lib/FlickrPhotoHA.php";

include_once "lib/FlickrViewer.php";


$flickr = new FlickrHA($apiKey,$secret,$oauthToken,$oauthTokenSecret);



//die($flickr->getOAuthUrl());


//die($flickr->getOAuthAuthorizeUrl($oauthToken,"read"));

//die($flickr->getOAuthCheckUrl());
//die($flickr->getOAuthUrl());
//die($flickr->getOAuthAuthorizeUrl($oauthToken,"read"));
//die($flickr->getOAuthVerifierUrl($oauthVerifier));


//die($flickr->test3($oauthToken,"flickr.test.login"));

$set = $flickr->getSetPhotos("72157649851892598");
$photos = $set->photoset->photo;
$data = array();
//print_r($photos);
//die();
foreach($photos as $photo){
    $info = $flickr->getPhotoInfo($photo->id);
    //print_r($info);
    //die();
    $url = FlickrPhotoHA::getUrl($photo,FlickrPhotoHA::SIZE_LARGE_SHORT);
    $sizes= $flickr->getPhotoSizes($photo->id);
    $originalSize = array_pop($sizes->size);
    //print_r($originalSize);
    //die();
    //print_r($url."<br/>");
    //print_r($info->description->_content."<br/>");
    $img = array();
    $img["url"] = $url;
    $img["meta"] = "";
    $img["width"] = $originalSize->width;
    $img["height"] = $originalSize->height;
    if($info && property_exists($info,"description") && property_exists($info->description,"_content")){
        $img["meta"] = $info->description->_content;
    }
    $data[] = $img;


}

file_put_contents("data/dogs.json",json_encode($data));

print_r(json_encode($data));
/*

https://api.flickr.com/services/rest
?nojsoncallback=1&oauth_nonce=84354935
&format=json
&oauth_consumer_key=653e7a6ecc1d528c516cc8f92cf98611
&oauth_timestamp=1305583871
&oauth_signature_method=HMAC-SHA1
&oauth_version=1.0
&oauth_token=72157626318069415-087bfc7b5816092c
&oauth_signature=dh3pEH0Xk1qILr82HyhOsxRv1XA%3D
&method=flickr.test.login

http://api.flickr.com/services/rest/
?oauth_callback=&
oauth_consumer_key=e9c2d11b4b0c9861766632f00320b128
&oauth_nonce=1f30350656d8bbc64d4707eb02582ff9
&oauth_signature_method=HMAC-SHA1
&oauth_timestamp=1422103897
&oauth_token=72157650041150820-b888ed9efe47af6d
&oauth_version=1.0
&method=flickr.photosets.getPhotos
&photoset_id=72157649851892598
&format=php_serial
&oauth_signature=BM1nVps17LVejIRJGQ4AkPJmM1U%3D

*/