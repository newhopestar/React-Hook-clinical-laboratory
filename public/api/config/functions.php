<?php  

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function mailSend($to, $subject, $body, $cc="", $bcc="") {
	$headers = "";
	$headers .= "From: " . SITE_EMAIL . "\r\n";
	$headers .= "Reply-To: ". SITE_EMAIL . "\r\n";
	$headers .= "MIME-Version: 1.0\r\n";
	$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
	mail($to,$subject,$body,$headers);
}

function sendEmail($to_email="",$subject="",$message="") {
	include_once '../PHPMailer/src/Exception.php';
	include_once '../PHPMailer/src/PHPMailer.php';
	include_once '../PHPMailer/src/SMTP.php';
	if ($to_email != "" && $subject !="" && $message != "") {
		$mail = new PHPMailer(true);
		try {
			$mail->SMTPDebug = 0;                  
			$mail->isSMTP();                       
			$mail->Host       = 'smtp.gmail.com';  
			$mail->SMTPAuth   = true;                      
			$mail->Username   = 'jafaraliwork14@gmail.com';
			$mail->Password   = 'jxffmutgwhfuxhys';   
			$mail->SMTPSecure = 'tls';           
			$mail->Port       = 587;    
			$mail->setFrom(SITE_EMAIL);
			$mail->addAddress($to_email);

			$mail->isHTML(true);                                  // Set email format to HTML
			$mail->Subject = $subject;
			$mail->Body    = $message;
			$mail->send(); 
			// echo "Message Sent OK\n";
		} catch (phpmailerException $e) {
  			echo $e->errorMessage(); //Pretty error messages from PHPMailer
  		} catch (Exception $e) {
  			echo $e->getMessage(); //Boring error messages from anything else!
  		}
  	}
  }

function pr($content) {
	echo "<pre>";
	print_r($content);
	echo "</pre>";
}

function po($data = '') {
	echo "<pre>";
	if ($data == '') {
		print_r($_POST);
	} else {
		print_r($data);
	}
	echo "</pre>";
	die();
}

function lk($dqr = '') {
	if($dqr == '') {
		echo $statement->debugDumpParams();	
	} else {
		echo $dqr->debugDumpParams();
	}	
}






  ?>
