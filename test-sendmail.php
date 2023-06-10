<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
require '/var/www/vendor/phpmailer/phpmailer/src/Exception.php';
require '/var/www/vendor/phpmailer/phpmailer/src/PHPMailer.php';
require '/var/www/vendor/phpmailer/phpmailer/src/SMTP.php';

$mail = new PHPMailer();
$mail->IsSMTP();
$mail->Mailer = "smtp";

$mail->SMTPDebug  = 0;  
$mail->SMTPAuth   = TRUE;
$mail->SMTPSecure = "tls";
$mail->Port       = 587;
$mail->Host       = "smtp.gmail.com";
$mail->Username   = "supervision.decomble@gmail.com";
$mail->Password   = "rvnqrxyankxtuegm";

$mail->IsHTML(true);
$mail->AddAddress($_POST['test_id'], "recipient-name");
$mail->SetFrom("supervision.decomble@gmail.com", "Auto Decomble");
$mail->Subject = $_SESSION['subject'];
$content = $_SESSION['content'];

$mail->MsgHTML($content); 
if(!$mail->Send()) {
  echo "Error while sending Email.";
  var_dump($mail);
  header("Location:mail_users");
} else {
  echo "Email sent successfully";
  header("Location:mail_users");
}
?>