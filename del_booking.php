<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
require '/var/www/html/vendor/phpmailer/phpmailer/src/Exception.php';
require '/var/www/html/vendor/phpmailer/phpmailer/src/PHPMailer.php';
require '/var/www/html/vendor/phpmailer/phpmailer/src/SMTP.php';
require 'model/functions.php';
require 'config.php';

$var = $_GET['id'];

$getUser = $connect->prepare("SELECT * FROM USER, reservation, VEHICULE WHERE VEHICULE.id = reservation.id_vehicule AND USER.id = reservation.id_user AND reservation.id = :id");
$getUser->execute(array('id' => $var));
$user = $getUser->fetch(PDO::FETCH_ASSOC);


$dateTime = strtotime($user['start']) ;
$start = strftime('%d-%B-%Y %H:%M',$dateTime);
$start = str_replace( 
    array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'),
    array('Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'),
    $start
);
$dateTime = strtotime($user['end']) ;
$end = strftime('%d-%B-%Y %H:%M',$dateTime);
$end = str_replace( 
    array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'),
    array('Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'),
    $end
);

$contentDiscord ="**".strtoupper($_SESSION['username'])."** - Réservation rejetée pour ".$user['modele']." ".$user['marque']." | ".$user['immatriculation']." du ".$start." au ".$end;
$color="FF0000";
sendDiscordAlert($contentDiscord,$color);

$mail = new PHPMailer();
$mail->CharSet = "UTF-8";
$mail->IsSMTP();
$mail->Mailer = "smtp";

$mail->SMTPDebug  = 0;  
$mail->SMTPAuth   = TRUE;
$mail->SMTPSecure = "tls";
$mail->Port       = 587;
$mail->Host       = "smtp.gmail.com";
$mail->Username   = "supervision.decomble@gmail.com";
$mail->Password   = "rvnqrxyankxtuegm";
$mail->AddAddress($user['mail'],"Auto ".$brend);
$mail->SetFrom("supervision.decomble@gmail.com", "Auto ".$brend);
$mail->Subject = "Reservation rejetée pour ".$user['modele']." ".$user['marque']." | ".$user['immatriculation']." du ".$start." au ".$end;
$content = "Reservation rejetée pour ".$user['modele']." ".$user['marque']." | ".$user['immatriculation']." du ".$start." au ".$end;
$mail->MsgHTML($content); 
if(!$mail->Send()) {
    echo "Error while sending Email.";
    echo "<script>console.log(" . json_encode($mail) . ")</script>";
}else{
    $query = $connect->prepare("DELETE FROM reservation WHERE id = :id");
    $query->execute(array('id' => $var));
}
?>
<script>
location.href='./reservation';
</script>