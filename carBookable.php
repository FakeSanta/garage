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
$sql = $connect->prepare("SELECT * FROM VEHICULE WHERE id = ?");
$sql->execute([$var]);
$car = $sql->fetch(PDO::FETCH_ASSOC);

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

if($car['reservable'] == 0){
    $user = $connect->prepare("SELECT * FROM USER, reservation, VEHICULE WHERE VEHICULE.id = reservation.id_vehicule AND reservation.id_user = USER.id AND id_vehicule = ? AND CURDATE() < start");
    $user->execute([$var]);
    if($user->rowCount()){
        while($row = $user->fetch(PDO::FETCH_ASSOC)){
            $start = convertDate($row['start']);
            $end = convertDate($row['end']);
            $mail->AddAddress($row['mail'],"Auto-".$brend);
            $mail->IsHTML(true);
            $mail->SetFrom("supervision.decomble@gmail.com", "Auto ".$brend);
            $mail->Subject = "Réservation re-effective";
            $content = "La réservation du ".$start." au ".$end." avec ".$row['modele']." | ".$row['immatriculation']." est re-effective car ce véhicule est de nouveau disponible à la réservation";
            $mail->MsgHTML($content); 
            if(!$mail->Send()) {
            echo "Error while sending Email.";
            } else {
                $mail->clearAllRecipients();
                echo "Email sent successfully";
            }
        }
    }
    $query = $connect->prepare("UPDATE VEHICULE SET reservable = 1 WHERE id = ?");
}else{
    $user = $connect->prepare("SELECT * FROM USER, reservation, VEHICULE WHERE VEHICULE.id = reservation.id_vehicule AND reservation.id_user = USER.id AND id_vehicule =?");
    $user->execute([$var]);
    if($user->rowCount()){
        while($row = $user->fetch(PDO::FETCH_ASSOC)){
            $start = convertDate($row['start']);
            $end = convertDate($row['end']);
            $mail->AddAddress($row['mail'],"Auto-".$brend);
            $mail->IsHTML(true);
            $mail->SetFrom("supervision.decomble@gmail.com", "Auto ".$brend);
            $mail->Subject = "Réservation annulée";
            $content = "La réservation du ".$start." au ".$end." avec ".$row['modele']." | ".$row['immatriculation']." est annulée car ce véhicule ne sera plus disponible à la réservation";
            $mail->MsgHTML($content); 
            if(!$mail->Send()) {
            echo "Error while sending Email.";
            } else {
                $mail->clearAllRecipients();
                echo "Email sent successfully";
            }
        }
    }
    $query = $connect->prepare("UPDATE VEHICULE SET reservable = 0 WHERE id = ?");
}
$query->execute([$var]);
header('Location: ./vehicule_bookable');
exit;
