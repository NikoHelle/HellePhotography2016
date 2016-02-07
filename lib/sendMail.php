<?php

ini_set("display_errors",E_ALL);
error_reporting(E_ALL);

$time = isset($_SESSION["page_start"]) ? $_SESSION["page_start"] : 0;
$no_value = isset($_REQUEST["v1"]) ? $_REQUEST["v1"] : 0;
$must_value = isset($_REQUEST["v2"]) ? $_REQUEST["v2"] : 0;
$keypresses_value = isset($_REQUEST["kb1"]) ? $_REQUEST["kb1"] : 0;
$session_value = isset($_SESSION["s1"]) ? $_SESSION["s1"] : 0;
$session_form_value = isset($_REQUEST["s1"]) ? $_REQUEST["s1"] : 0;

$_REQUEST["email"] = "niko.helle@hellephotography.com";

$recepient_email = "niko.helle@hyperactive.fi";
$recepient_name = "niko helle";
$sender_name = "Webform";
$sender_email = $_REQUEST["email"];


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

	$msg = "sdfslk"; //$_REQUEST["msg"];

	$mail->MsgHTML($msg);

	$mail->Send();

    die("success=true"); //Boring error messages from anything else!

} catch (phpmailerException $e) {
  die("success=false&error=MAILER&message=".urlencode($e->errorMessage())); //Pretty error messages from PHPMailer
} catch (Exception $e) {
   die("success=false&error=UNKNOWN&message=".urlencode($e->getMessage())); //Boring error messages from anything else!
}









?>