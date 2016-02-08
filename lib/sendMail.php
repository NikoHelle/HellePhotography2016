<?php

include_once "lib/session.php";
ini_set("display_errors",E_ALL);
error_reporting(E_ALL);

$time = isset($_SESSION["page_start"]) ? $_SESSION["page_start"] : 0;
$no_value = isset($_POST["v1"]) ? $_POST["v1"] : false;
$must_value = isset($_POST["v2"]) ? $_POST["v2"] : 0;
$inputEvent = isset($_POST["ie1"]) ? $_POST["ie1"] : 0;
$textAreaEvent = isset($_POST["ta1"]) ? $_POST["ta1"] : 0;
$session_v1 = isset($_SESSION["v1"]) ? $_SESSION["v1"] : 0;
$session_v2 = isset($_SESSION["v2"]) ? $_SESSION["v2"] : 0;
$session_form_value = isset($_POST["s1"]) ? $_POST["s1"] : 0;

if($no_value || $no_value != $session_v1){
    die("success=true&v=2");
}

if(!$must_value || $must_value != $session_v2){
    die("success=true&v=2");
}

$_POST["email"] = "niko.helle@hellephotography.com";

$recepient_email = "niko.helle@hyperactive.fi";
$recepient_name = "niko helle";
$sender_name = "Webform";
$sender_email = $_POST["email"];


function checkEmailAddress($email,$ignore) {
    if(preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/", $email)) return true;
    return false;
}

if(checkEmailAddress($sender_email, FILTER_VALIDATE_EMAIL) === FALSE) die("success=false&error=EMAIL&message=".urlencode("Anna sähköpostiosoitteesi"));

require_once('class.phpmailer.php');

$mail = new PHPMailer(true); // the true param means it will throw exceptions on errors, which we need to catch
$mail->IsMail(); // telling the class to use SendMail transport

try {
	
	//$mail->AddReplyTo('no-reply@bsag.fi', 'First Last');
	$mail->AddAddress(utf8_decode($recepient_email), utf8_decode($recepient_name));
	$mail->SetFrom(utf8_decode($sender_email), utf8_decode($sender_name));
	// $mail->AddReplyTo('name@yourdomain.com', 'First Last');

	$msg = "sdfslk"; //$_POST["msg"];

	$mail->MsgHTML($msg);

	#$mail->Send();

    die("success=true&v=1"); //Boring error messages from anything else!

} catch (phpmailerException $e) {
  die("success=false&error=MAILER&message=".urlencode($e->errorMessage())); //Pretty error messages from PHPMailer
} catch (Exception $e) {
   die("success=false&error=UNKNOWN&message=".urlencode($e->getMessage())); //Boring error messages from anything else!
}









?>