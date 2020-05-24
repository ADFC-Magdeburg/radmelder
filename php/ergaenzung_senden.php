<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/Exception.php';
/* The main PHPMailer class. */
require 'PHPMailer/PHPMailer.php';
/* SMTP class, needed if you want to use SMTP. */
require 'PHPMailer/SMTP.php';
require 'config.php';

$id=$_POST['id'];
$otitel=$_POST['otitel'];
$oproblem=$_POST['oproblem'];
$ergaenzung=$_POST['ergaenzungstext'];
$email=$_POST['email'];

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
  $email = $mailuser;
}

// Instantiation and passing `true` enables exceptions
$mail = new PHPMailer(true);

try {
    //Server settings
	$mail->SMTPOptions = array(
    'ssl' => array(
        'verify_peer' => false,
        'verify_peer_name' => false,
        'allow_self_signed' => true
    )
);
    //$mail->SMTPDebug = SMTP::DEBUG_SERVER;                      // Enable verbose debug output
	$mail->CharSet = "UTF-8";
	$mail->Encoding = 'base64';
    $mail->isSMTP();                                            // Send using SMTP
    $mail->Host       = $mailsmtp;                   		    // Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
    $mail->Username   = $mailuser;                              // SMTP username
    $mail->Password   = $mailpasswort;                          // SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
    $mail->Port       = $mailport;                                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

    //Recipients
    $mail->setFrom($mailuser, $mailsendername);
   //$mail->addAddress('johndoe@johnsdomain.com', 'Joe User');     // Add a recipient
    $mail->addAddress($mailuser);       
    $mail->addReplyTo($email); //Absendeadresse des Nutzers des Formulars
    //$mail->addCC('cc@example.com');
    //$mail->addBCC('bcc@example.com');

    // Attachments
    //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
    //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

    // Content
    $mail->isHTML(true);                                  // Set email format to HTML
    $mail->Subject = "Ergänzung zu Radwegmeldermeldung #".$id;
    //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
	
	$text = "Es wurde eine Ergänzung im Radwegmelder gemacht. Bitte unter '.$verwaltungpath.' ergänzen, falls relevant.<br><br>";
	$text.= "************************* ID: ".$id." *************************";
	$text.="<br><br>";
	$text.= "Originaltitel: ".$otitel;
	$text.="<br><br>";
	$text.= "Originalproblem: ".$oproblem;
	$text.="<br><br>";
	$text.= "Ergänzung: ".$ergaenzung;
	$text.="<br><br>";
	$text.= "Absender: ".$email;

	$mail->Body    = $text;

    $mail->send();
    echo 'Nachricht wurde versendet, vielen Dank!';
} catch (Exception $e) {
    echo "Nachricht konnte nicht versendet werden. Mailer Error: {$mail->ErrorInfo}";
}


echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
   <head>
      <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
      <title>Ergänzungsmeldung</title>
     
   </head>
   <body>   
   </body>
</html>';

?>
    
