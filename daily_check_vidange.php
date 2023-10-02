
<?php
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;
    require '/var/www/html/vendor/phpmailer/phpmailer/src/Exception.php';
    require '/var/www/html/vendor/phpmailer/phpmailer/src/PHPMailer.php';
    require '/var/www/html/vendor/phpmailer/phpmailer/src/SMTP.php';
    require('config.php');
    // Verification des controles techniques
    $check_vidange = $connect->prepare("SELECT * FROM VEHICULE INNER JOIN VIDANGE ON VEHICULE.id = VIDANGE.id_vehicule");
    $check_vidange->execute();
    while($row = $check_vidange->fetch(PDO::FETCH_ASSOC))
    {
        $next_vidange = $row['prochaine_date_vidange'];
        $next_vidange_date = strtotime($next_vidange);
        $jesaispasquoi = getdate($next_vidange_date);

        $id_vehicule = $row['id_vehicule'];
        $count_days_query = $connect->prepare("SELECT DATEDIFF('$next_vidange', CURDATE()) AS diff_date");
        $count_days_query->execute();
        $days_diff = $count_days_query->fetch(PDO::FETCH_ASSOC);
        $test = $days_diff['diff_date'];
        var_dump($test);
        if($days_diff['diff_date'] < 60 && $days_diff['diff_date'] >= 14){
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

            $sql = $connect->prepare("SELECT * FROM MAIL");
            $sql->execute();
            if($sql->RowCount() > 1){
                while($to = $sql->fetch(PDO::FETCH_ASSOC)){
                    $mail->AddAddress($to['mail'],"Auto-".$brend);
                }
                $mail->IsHTML(true);
                $mail->SetFrom("supervision.decomble@gmail.com", "Auto ".$brend);
                $mail->Subject = "J-".$days_diff['diff_date']." ! Vidange a venir pour ".$row['marque']." ".$row['modele']." (".$row['immatriculation'].")";
                $content = "J-".$days_diff['diff_date']." ! Vidange a venir pour ".$row['marque']." ".$row['modele']." (".$row['immatriculation'].")";

                $mail->MsgHTML($content); 
                if(!$mail->Send()) {
                echo "Error while sending Email.";
                var_dump($mail);
                } else {
                echo "Email sent successfully";
                }
            }
        }
    }
?>