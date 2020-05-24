<?php
include("../php/config.php");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require '../php/PHPMailer/Exception.php';
/* The main PHPMailer class. */
require '../php/PHPMailer/PHPMailer.php';
/* SMTP class, needed if you want to use SMTP. */
require '../php/PHPMailer/SMTP.php';

$mysql = new mysqli($host, $user, $password, $database);
$mysql->set_charset("utf8");

$data = json_decode(file_get_contents('php://input'), true);



$id = $data['id'];
$message = $data['message'];
$mail = $data['address'];
$subject = $data['subject'];
$header = 'From: '.$mailuser.'\r\n Reply-To: '.$mailuser;



/****Mailfunktion zur Beanchrichtigung***/

// Instantiation and passing `true` enables exceptions
$email = new PHPMailer(true);

try {
    //Server settings
	$email->SMTPOptions = array(
		'ssl' => array(
			'verify_peer' => false,
			'verify_peer_name' => false,
			'allow_self_signed' => true
		)
	);
	

	
    //$email->SMTPDebug = SMTP::DEBUG_SERVER;                      // Enable verbose debug output
	$email->CharSet = "UTF-8";
	$email->Encoding = 'base64';
    $email->isSMTP();                                            // Send using SMTP
    $email->Host       = $mailsmtp;                   		    // Set the SMTP server to send through
    $email->SMTPAuth   = true;                                   // Enable SMTP authentication
    $email->Username   = $mailuser;                              // SMTP username
    $email->Password   = $mailpasswort;                          // SMTP password
    $email->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
    $email->Port       = $mailport;                                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above


    //Recipients
    $email->setFrom($mailuser, $mailsendername);
   //$email->addAddress('johndoe@johnsdomain.com', 'Joe User');     // Add a recipient
    $email->addAddress($mail);       
    $email->addReplyTo($mailuser); //Absendeadresse des Nutzers des Formulars
    //$email->addCC('cc@example.com');
    //$mail->addBCC('bcc@example.com');

    // Attachments
    //$email->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
    //$email->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

    // Content
    $email->isHTML(true);                                  // Set email format to HTML
    $email->Subject = $subject;
    //$email->AltBody = 'This is the body in plain text for non-HTML mail clients';
	
	$message=nl2br($message);//ersetze Umbrüche gegen <br> Hintergrund ist,dass es Umbrücke für die Verwaltungsoberfläche sein müssen, in de Mail aber ognoriert werden, hier also gegen <br> ersetzt werden

	$email->Body    = $message;

    $email->send();
    
} catch (Exception $e) {
    echo "Nachricht konnte nicht versendet werden. Mailer Error: {$email->ErrorInfo}";
}

/*******Ende Mailfunktion****************/









$test = $mysql->query("SELECT * FROM mails WHERE fk_stelle_id=$id AND mailing_time IS NOT NULL;");
if($test->num_rows==0) {
  // Mailfunktion hierher $mailing = mail($mail, $subject, $message, $header);
  $mailing=1;
  if($mailing) {
    $mysql->query("UPDATE mails SET mailing=".$message.", mailing_time=NOW() WHERE fk_stelle_id=$id;");
    echo "Mail versendet!";
  }
  else {
    echo "Es ist ein Problem aufgetreten.";
  }
}
else {
  echo "Es wurde schon eine Mail versendet. Aus Sicherheitsgründen bitte manuell über das Konto versenden.";
}

?>
