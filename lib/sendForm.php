<?php

include_once "session.php";
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
$sender_email = isset($_POST["email"]) ? $_POST["email"] : 0;
$message = isset($_POST["message"]) ? $_POST["message"] : 0;


if($no_value || $no_value != $session_v1){
    die("success=true&v=2");
}

if(!$must_value || $must_value != $session_v2){
    die("success=true&v=2");
}

if($inputEvent != "change" && $inputEvent != "focus"){
    die("success=true&v=3");
}

if($textAreaEvent != "change" && $textAreaEvent != "focus"){
    die("success=true&v=3");
}
#$_POST["email"] = "niko.helle@hellephotography.com";
$recepient_email = "niko.helle@hellephotography.com";
$recepient_name = "niko helle";
$sender_name = "Webform";

/*
function checkEmailAddress($email) {
    if(preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/", $email)) return true;
    return false;
}
*/

if(strlen($message) < 20 || filter_var($sender_email, FILTER_VALIDATE_EMAIL) === FALSE) die("success=false&error=EMAIL&message=".urlencode("Anna sähköpostiosoitteesi:".$sender_email."message:".strlen($message)));

require_once('class.phpmailer.php');

$mail = new PHPMailer(true); // the true param means it will throw exceptions on errors, which we need to catch
//$mail->IsMail(); // telling the class to use SendMail transport

try {

    $mail->AddAddress(utf8_decode($recepient_email), utf8_decode($recepient_name));
    $mail->AddAddress(utf8_decode($sender_email), utf8_decode($sender_name));
    $mail->SetFrom(utf8_decode("noreply@hellephotography.com"), utf8_decode($sender_name));

    $HTML = "<p>Kiitos viestistäsi! Tämä on automaattinen vastaus ja vastaan henkilökohtaisesti mahdollisimman pian!</p><p>Terveisin,<br>Niko Helle</p>";
    $HTML .= "<p>Alkuperäinen viesti:</p>";
    $HTML .= "<p>".preg_replace("/\r\n|\r|\n/",'<br/>',$message)."</p>";

    $mail->Subject = "Kiitos yhteydenotostasi!";
    $mail->MsgHTML($HTML);

    $mail->Send();

    $filename = "../data/contacts/".time()."_".$sender_email.".txt";

    file_put_contents($filename,$HTML);

    die("success=true&a=1");

} catch (phpmailerException $e) {
    die("success=false&error=MAILER&message=".urlencode($e->errorMessage())); //Pretty error messages from PHPMailer
} catch (Exception $e) {
    die("success=false&error=UNKNOWN&message=".urlencode($e->getMessage())); //Boring error messages from anything else!
}
?>