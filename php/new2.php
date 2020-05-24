<?php
include("config.php");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/Exception.php';
/* The main PHPMailer class. */
require 'PHPMailer/PHPMailer.php';
/* SMTP class, needed if you want to use SMTP. */
require 'PHPMailer/SMTP.php';


$mysql = new mysqli($host, $user, $password, $database);
$mysql->set_charset("utf8");

$data = json_decode(file_get_contents('php://input'), true);
$lat = $data['lat']!='' ? $data['lat'] : "NULL";
$lng = $data['lng']!='' ? $data['lng'] : "NULL";
$Titel = $data['Titel']!='' ? "'".$data['Titel']."'" : "NULL";
//$Sachverhalt = $data['Sachverhalt']!='' ? "'".$data['Sachverhalt']."'" : "NULL";
$Problem = $data['Problem']!='' ? "'".$data['Problem']."'" : "NULL";
$Loesung = $data['Loesung']!='' ? "'".$data['Loesung']."'" : "NULL";
//$Bild = $data['Bild']!='' ? "'".$data['Bild']."'" : "NULL";
$Absender = $data['mail']!='' ? "'".$data['mail']."'" : "NULL";
$position_text = $data['position_text']!='' ? "'".$data['position_text']."'" : "NULL";
$query=$mysql->query("INSERT INTO stellen (lat, lng, position_text, Titel, Problem, Loesung, Status) VALUES ($lat, $lng, $position_text, $Titel, $Problem, $Loesung, 'Eingereicht')");
$id = $mysql->insert_id;
$mysql->query("INSERT INTO mails (fk_stelle_id, mail) VALUES ($id, $Absender);");


$Absender=str_replace("'","",$Absender);

if ($Absender=='NULL'){
	$Absender=$mailuser;
	}
	


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
	

	
    $email->SMTPDebug = SMTP::DEBUG_SERVER;                      // Enable verbose debug output
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
    $email->addAddress($mailuser);       
    $email->addReplyTo($Absender); //Absendeadresse des Nutzers des Formulars
    //$email->addCC('cc@example.com');
    //$mail->addBCC('bcc@example.com');

    // Attachments
    //$email->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
    //$email->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

    // Content
    $email->isHTML(true);                                  // Set email format to HTML
    $email->Subject = "Neue Radwegmelder-Eintragung #".$id;
    //$email->AltBody = 'This is the body in plain text for non-HTML mail clients';
	
	$text = "Es wurde eine neue Eintragung im ".$sitetitle." gemacht. Bitte unter ".$verwaltungpath." freischalten.<br><br>";
	$text.= "Titel: ".$Titel;
	$text.="<br><br>";
	$text.= "Ort: ".$position_text;
	$text.="<br><br>";
	$text.= "Problem: ".$Problem;
	$text.="<br><br>";
	$text.= "Absender: ".$Absender;

	$email->Body    = $text;

    $email->send();
    
} catch (Exception $e) {
    echo "Nachricht konnte nicht versendet werden. Mailer Error: {$email->ErrorInfo}";
}






/*******Ende Mailfunktion****************/





if($data['BildURI']!="") {
  $DataUriRaw = $data['BildURI'];
  $DataUriSep = explode(',', $DataUriRaw);
  $DataImage = base64_decode($DataUriSep[1]);
  $filename = $id."-".date("YmdHis").".jpg";
  $filepath = $_SERVER['DOCUMENT_ROOT']."/".$uploadpath."/".$filename;
  file_put_contents($filepath,$DataImage);
  $mysql->query("UPDATE stellen SET Bild='$filename' WHERE id=$id;");
  //$mysql->query("insert into debugtabelle(debug) value('$filepath');");
  
}

?>
