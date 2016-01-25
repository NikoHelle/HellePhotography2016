<?php
error_reporting(E_ALL^E_WARNING^E_NOTICE^E_DEPRECATED);
error_reporting(E_ALL);
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 'On');
// Change these
define('API_KEY',      	'77rggpkbr2jf9r'                                          );
define('API_SECRET',   	'TNY5krrqsluqQBez'                                       );
define('REDIRECT_URI', '	http://' . $_SERVER['SERVER_NAME'] . $_SERVER['SCRIPT_NAME']);
define('SCOPE',        	'r_fullprofile r_emailaddress rw_nus'                        );
define('OAUTH_USER_TOKEN',	'4005c9be-7414-4ec6-b89f-87bdf0bb715f');
define('OAUTH_USER_SECRET',	'9dc9e262-3431-4ab3-bb64-b7fcfa408dac');
define('COMPANY_ID','16019');
// You'll probably use a database
session_name('linkedin');
session_start();

require_once "OAuth.php";
 
 
$oauthParams = (object) array();
$oauthParams->apiKey = API_KEY;
$oauthParams->apiSecret = API_SECRET;
$oauthParams->tokenKey = OAUTH_USER_TOKEN;
$oauthParams->tokenSecret = OAUTH_USER_SECRET;

$params = array();
$params["event-type"] = "status-update";
$params["format"] = "json";

$params = (object) $params;


/*
GET&http%3A%2F%2Fapi.linkedin.com%2Fv1%2Fcompanies%2F16019%2Fupdates
&event-type%3Dstatus-update%26format%3Djson%26
oauth_consumer_key%3D77rggpkbr2jf9r%26
oauth_nonce%3D3334341234%26
oauth_signature_method%3DHMAC-SHA1%26
oauth_timestamp%3D1386345627%26
oauth_token%3D4005c9be-7414-4ec6-b89f-87bdf0bb715f%26
oauth_version%3D1.0




		if(!isset($ret->tokenKey)) $ret->tokenKey = FlickrHA::$tokenKey;
		if(!isset($ret->tokenSecret)) $ret->tokenSecret = FlickrHA::$tokenSecret;
		if(!isset($ret->format)) $ret->format = FlickrParamsHA::PHPFORMAT; 
 */
$url = OAuth::createOAuth("http://api.linkedin.com/v1/companies/16019/updates",$oauthParams,$params);

echo "<br>GET&http%3A%2F%2Fapi.linkedin.com%2Fv1%2Fcompanies%2F16019%2Fupdates&event-type%3Dstatus-update%26format%3Djson%26oauth_consumer_key%3D77rggpkbr2jf9r%26oauth_nonce%3D3334341234%26oauth_signature_method%3DHMAC-SHA1%26oauth_timestamp%3D1386345627%26oauth_token%3D4005c9be-7414-4ec6-b89f-87bdf0bb715f%26oauth_version%3D1.0"; 
echo "<br><br>vpSOM0kdQuH5JZettoRSCTCCskI=<br><br>";
 die($url);
// OAuth 2 Control Flow
/*if (isset($_GET['error'])) {
    // LinkedIn returned an error
    print $_GET['error'] . ': ' . $_GET['error_description'];
    exit;
} elseif (isset($_GET['code'])) {
    // User authorized your application
    if ($_SESSION['state'] == $_GET['state']) {
        // Get token so you can make API calls
        getAccessToken();
    } else {
        // CSRF attack? Or did you mix up your states?
        exit;
    }
} else { 
    if ((empty($_SESSION['expires_at'])) || (time() > $_SESSION['expires_at'])) {
        // Token has expired, clear the state
        $_SESSION = array();
    }
    if (empty($_SESSION['access_token'])) {
        // Start authorization process
        getAuthorizationCode();
    }
}*/
//getAccessToken();
$data = fetch("GET","/v1/companies/".COMPANY_ID."/updates");
print_r($data);
die("?");
// Congratulations! You have a valid token. Now fetch your profile 
$user = fetch('GET', '/v1/people/~:(firstName,lastName)');
print "Hello $user->firstName $user->lastName.";
exit;
 
function getAuthorizationCode() {
    $params = array('response_type' => 'code',
                    'client_id' => API_KEY,
                    'scope' => SCOPE,
                    'state' => uniqid('', true), // unique long string
                    'redirect_uri' => REDIRECT_URI,
              );
 
    // Authentication request
    $url = 'https://www.linkedin.com/uas/oauth2/authorization?' . http_build_query($params);
     
    // Needed to identify request when it returns to us
    $_SESSION['state'] = $params['state'];
 
    // Redirect user to authenticate
    header("Location: $url");
    exit;
}
     
function getAccessToken() {
    $params = array('grant_type' => 'authorization_code',
                    'client_id' => API_KEY,
                    'client_secret' => API_SECRET,
                    'code' => $_GET['code'],
                    'redirect_uri' => REDIRECT_URI,
              );
     
    // Access Token request
    $url = 'https://www.linkedin.com/uas/oauth2/accessToken?' . http_build_query($params);
     
    // Tell streams to make a POST request
    $context = stream_context_create(
                    array('http' => 
                        array('method' => 'POST',
                        )
                    )
                );
 
    // Retrieve access token information
    $response = file_get_contents($url, false, $context);
 
    // Native PHP object, please
    $token = json_decode($response);
 
    // Store access token and expiration time
    $_SESSION['access_token'] = $token->access_token; // guard this! 
    $_SESSION['expires_in']   = $token->expires_in; // relative time (in seconds)
    $_SESSION['expires_at']   = time() + $_SESSION['expires_in']; // absolute time
     
    return true;
}
 
function fetch($method, $resource, $body = '') {
    $params = array('oauth2_access_token' => $_SESSION['access_token'],
                    'format' => 'json',
              );
    /**/ $params = array(
                    'format' => 'json',
              );
    // Need to use HTTPS
    $url = 'https://api.linkedin.com' . $resource . '?' . http_build_query($params);
    // Tell streams to make a (GET, POST, PUT, or DELETE) request
    $context = stream_context_create(
                    array('http' => 
                        array('method' => $method,
                        )
                    )
                );
 
 
    // Hocus Pocus
    $response = file_get_contents($url, false, $context);
	echo "res:".$response;
 	print_r($response);
    // Native PHP object, please
    return json_decode($response);
}