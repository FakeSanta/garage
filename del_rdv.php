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


$rdv_pris = $connect->prepare("SELECT V.id AS vehicule_id, V.immatriculation AS vehicule_immatriculation, V.marque AS vehicule_marque, V.modele AS vehicule_modele, V.motorisation AS vehicule_motorisation, V.kilometrage AS vehicule_kilometrage, V.utilitaire AS vehicule_utilitaire, V.rdv_pris AS vehicule_rdv_pris
FROM VEHICULE V
INNER JOIN RDV R ON V.id = R.id_vehicule
WHERE R.id = :id                      
");
$rdv_pris->execute(
array(
    'id' => $var
)
);
$id_vehicule = $rdv_pris->fetch(PDO::FETCH_ASSOC);
$content = "**".strtoupper($_SESSION['username'])."** - RDV annulé pour le véhicule : **".$id_vehicule['vehicule_immatriculation']."** ".$id_vehicule['vehicule_marque']." ".$id_vehicule['vehicule_modele'];
$color ="6495ed";
sendDiscordAlert($content,$color);

$update_vehicule = $connect->prepare("UPDATE VEHICULE SET rdv_pris = 0 WHERE id = :id");
$update_vehicule->execute(
array(
    'id' => $id_vehicule['vehicule_id']
)
);

$update_rdv = $connect->prepare('UPDATE RDV SET rdv_annule = 1 WHERE id = :id');
$update_rdv->execute(
array(
    'id' => $var
)
);


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
$sql = $connect->prepare("SELECT * FROM MAIL");
$sql->execute();
if($sql->RowCount() > 1){
    while($to = $sql->fetch(PDO::FETCH_ASSOC)){
        $mail->AddAddress($to['mail'],"Auto-".$brend);
    }
    $mail->SetFrom("supervision.decomble@gmail.com", "Auto ".$brend);
    $mail->Subject = "Rendez-vous annulé pour le véhicule : ".$id_vehicule['vehicule_immatriculation']." ".$id_vehicule['vehicule_marque']." ".$id_vehicule['vehicule_modele'];
    $content = "Rendez-vous annulé pour le véhicule : ".$id_vehicule['vehicule_immatriculation']." ".$id_vehicule['vehicule_marque']." ".$id_vehicule['vehicule_modele'];
    $mail->MsgHTML($content); 
    if(!$mail->Send()) {
        echo "Error while sending Email.";
        echo "<script>console.log(" . json_encode($mail) . ")</script>";
    }else{
        $query = $connect->prepare("DELETE FROM reservation WHERE id = :id");
        $query->execute(array('id' => $var));
    }
}
?>
<script>
location.href='./list_rdv';
</script>