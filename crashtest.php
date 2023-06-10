
<?php
    require('config.php');
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;
    require '/var/www/vendor/phpmailer/phpmailer/src/Exception.php';
    require '/var/www/vendor/phpmailer/phpmailer/src/PHPMailer.php';
    require '/var/www/vendor/phpmailer/phpmailer/src/SMTP.php';
    //Connexion a la base de donnée
    require('config.php');

    // Verification des controles techniques
    $check_ct = $connect->prepare("SELECT * FROM VEHICULE INNER JOIN CT ON VEHICULE.id = CT.id_vehicule");
    $check_ct->execute();
    while($row = $check_ct->fetch(PDO::FETCH_ASSOC))
    {
        $next_ct = $row['prochaine_date_ct'];
        $next_ct_date = strtotime($next_ct);
        $jesaispasquoi = getdate($next_ct_date);

        $id_vehicule = $row['id_vehicule'];
            if(!empty($next_ct)){
            $count_days_query = $connect->prepare("SELECT DATEDIFF('$next_ct', CURDATE()) AS diff_date");
            $count_days_query->execute();
            $days_diff = $count_days_query->fetch(PDO::FETCH_ASSOC);
            $test = $days_diff['diff_date'];
            var_dump($test);
            if($days_diff['diff_date'] <= 15 && $days_diff['diff_date'] >= 1){
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
                $mail->AddAddress("aurelien.fevrier12@gmail.com", "recipient-name");
                $mail->SetFrom("supervision.decomble@gmail.com", "Auto Decomble");
                $mail->Subject = "Controle technique a venir pour ".$row['marque']." ".$row['modele']." (".$row['immatriculation'].")";
                $content = "Controle technique a venir pour ".$row['marque']." ".$row['modele']." (".$row['immatriculation']." | date buttoire le ".$row['prochaine_date_ct'].")";
    
                $mail->MsgHTML($content); 
                if(!$mail->Send()) {
                echo "Error while sending Email.";
                var_dump($mail);
                } else {
                echo "Email sent successfully";
                }
            }elseif($days_diff == -38){
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
                $mail->AddAddress("aurelien.fevrier12@gmail.com", "recipient-name");
                $mail->SetFrom("supervision.decomble@gmail.com", "Auto Decomble");
                $mail->Subject = "Controle technique DEPASSE pour ".$row['marque']." ".$row['modele']." (".$row['immatriculation'].")";
                $content = "Controle technique DEPASSE pour ".$row['marque']." ".$row['modele']." (".$row['immatriculation'].")";
    
                $mail->MsgHTML($content); 
                if(!$mail->Send()) {
                echo "Error while sending Email.";
                var_dump($mail);
                } else {
                echo "Email sent successfully";
                }
            }
        }
        else{
            echo "La valeur de next_ct est vide.";
        }
    }
?>