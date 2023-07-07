
<?php
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;
    require '/var/www/html/vendor/phpmailer/phpmailer/src/Exception.php';
    require '/var/www/html/vendor/phpmailer/phpmailer/src/PHPMailer.php';
    require '/var/www/html/vendor/phpmailer/phpmailer/src/SMTP.php';
    require ('model/functions.php');
    require('config.php');
    // Verification des controles techniques
    $check_booking = $connect->prepare("SELECT * FROM reservation WHERE accepted = 0 AND CURDATE() > start;");
    $check_booking->execute();
    while($row = $check_booking->fetch(PDO::FETCH_ASSOC))
    {
        //sendDiscordAlert("depasse1");
        $start = $row['start'];
        $end = $row['end'];
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
        $sql = $connect->prepare("SELECT * FROM USER WHERE id= ?");
        $sql->execute([$row['id_user']]);
        if($sql->RowCount() >= 1){
            while($to = $sql->fetch(PDO::FETCH_ASSOC)){
                $mail->AddAddress($to['mail'],"Auto-".$brend);
                echo $to['mail'];
            }
            $mail->IsHTML(true);
            $new_start = strftime("%d %B %Y %Hh%M",strtotime($start));
            $new_start = str_replace( 
                array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'),
                array('Janvier', 'Fevrier', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'),
                $new_start
                );
            $new_end = strftime("%d %B %Y %Hh%M",strtotime($end));
            $new_end = str_replace( 
                array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'),
                array('Janvier', 'Fevrier', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'),
                $new_end
            );
            $mail->SetFrom("supervision.decomble@gmail.com", "Auto ".$brend);
            $mail->Subject = "Réservation du ".$new_start." au ".$new_end." est annulé car aucune réponse n'a été donné";
            $content = "Réservation du ".$new_start." au ".$new_end." est annulé car aucune réponse n'a été donné";
            $contentDiscord = "Réservation du ".$new_start." au ".$new_end." est annulé car aucune réponse n'a été donné";
            $color ="FF8800";
            sendDiscordAlert($contentDiscord,$color);
            $mail->MsgHTML($content); 
            if(!$mail->Send()) {
            echo "Error while sending Email.";
            var_dump($mail);
            } else {
                echo "Email sent successfully";
            }
            $update_booking = $connect->prepare("UPDATE reservation SET accepted = 2 WHERE id = ?");
            $update_booking->execute([$row['id']]);
        }
    }
?>