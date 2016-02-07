<?php
//error_reporting(E_ALL^E_WARNING^E_NOTICE^E_DEPRECATED);
//include 'db.php';
//DBConnect();


$_REQUEST["email"] = "niko.helle@taivas.fi";

$sender_email = "no-reply@bsag.fi";
$sender_name = "Baltic Sea Action Group";
$recepient_email = strtolower($_REQUEST["email"]);
$recepient_name = $_REQUEST["company"];
$language = $_REQUEST["language"];
if($language !== "fi" && $language !== "en" && $language !== "se") $language = "fi"; 
//$file = "../cards/".$filename;
$fileURLs = "";

$images = isset($_REQUEST["images"]) ? explode(",",$_REQUEST["images"]) : array();


function checkEmailAddress($email,$ignore) {
    if(preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/", $email)) return true;
    return false;
}

if(checkEmailAddress($recepient_email, FILTER_VALIDATE_EMAIL) === FALSE) die("success=false&error=EMAIL&message=".urlencode("Anna sähköpostiosoitteesi"));




require_once('class.phpmailer.php');

$mail = new PHPMailer(true); // the true param means it will throw exceptions on errors, which we need to catch
$mail->IsMail(); // telling the class to use SendMail transport

try {
	
	//$mail->AddReplyTo('no-reply@bsag.fi', 'First Last');
	$mail->AddAddress(utf8_decode($recepient_email), utf8_decode($recepient_name));
	$mail->SetFrom(utf8_decode($sender_email), utf8_decode($sender_name));
	// $mail->AddReplyTo('name@yourdomain.com', 'First Last');

    foreach($images as $key=>$value){


        $mail->AddAttachment("saved/".$value);      // attachment
        $fileURLs .= "http://joululahjaitamerelle.fi/php/saved/".$value;
    }
	if($language === "fi") {
		$mail->Subject = utf8_decode('Joulukortti');
		$mail->AltBody = utf8_decode('Lukeaksesi tämän viestin tarvitset HTML-yhteensopivan sähköpostiohjelman. Voit myös ladata joulukortin täältä:'.$fileURL); // optional - MsgHTML will create an alternate automatically
		$msg = file_get_contents('mail.html');
	}
	else if($language === "en"){
		$mail->Subject = utf8_decode('Christmas Card');
		$mail->AltBody = utf8_decode('You need a HTML compatible email client to read this message. You can also load your christmas card here:'.$fileURL); // optional - MsgHTML will create an alternate automatically
		$msg = file_get_contents('mail_en.html');
	}
	else {
		$mail->Subject = utf8_decode('Julkort');
		$mail->AltBody = utf8_decode('You need a HTML compatible email client to read this message. You can also load your christmas card here:'.$fileURL); // optional - MsgHTML will create an alternate automatically
		$msg = file_get_contents('mail_se.html');
	}
	$msg = str_replace("#SRC#",utf8_decode($file),$msg);
	$mail->MsgHTML($msg);

	
	$mail->Send();
	
	//$log = date("d.m.y H:i:s").",".$recepient_email.",".utf8_encode($recepient_name).",".utf8_encode($fileURL).",".utf8_encode($language)."\n";
	//$bytes = file_put_contents("log.txt",$log,FILE_APPEND | LOCK_EX);
  
  

} catch (phpmailerException $e) {
  die("success=false&error=MAILER&message=".urlencode($e->errorMessage())); //Pretty error messages from PHPMailer
} catch (Exception $e) {
   die("success=false&error=UNKNOWN&message=".urlencode($e->getMessage())); //Boring error messages from anything else!
}









?>