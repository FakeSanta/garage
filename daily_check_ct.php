
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
    $check_ct = $connect->prepare("SELECT * FROM VEHICULE INNER JOIN CT ON VEHICULE.id = CT.id_vehicule ORDER BY prochaine_date_ct ASC");
    $check_ct->execute();
    while($row = $check_ct->fetch(PDO::FETCH_ASSOC))
    {
        //sendDiscordAlert("depasse1");
        $next_ct = $row['prochaine_date_ct'];
        $next_ct_date = strtotime($next_ct);
        $jesaispasquoi = getdate($next_ct_date);

        $id_vehicule = $row['id_vehicule'];
        $count_days_query = $connect->prepare("SELECT DATEDIFF('$next_ct', CURDATE()) AS diff_date");
        $count_days_query->execute();
        $days_diff = $count_days_query->fetch(PDO::FETCH_ASSOC);
        $test = $days_diff['diff_date'];
        var_dump($test);
        if($days_diff['diff_date'] < 360 && $days_diff['diff_date'] >= 1 && $row['rdv_pris'] == 0){

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
                    echo $to['mail'];
                }
                $mail->IsHTML(true);
                $new_date = strftime("%d %B %Y",strtotime($row["prochaine_date_ct"]));
                $new_date = str_replace( 
                    array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'),
                    array('Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'),
                    $new_date
                  );
                $mail->SetFrom("supervision.decomble@gmail.com", "Auto ".$brend);
                $mail->Subject = "J-".$days_diff['diff_date']." ! Controle technique a venir pour ".$row['marque']." ".$row['modele']." (".$row['immatriculation'].")";
                $content = "J-".$days_diff['diff_date']." ! Controle technique a venir pour ".$row['marque']." ".$row['modele']." (".$row['immatriculation']." | date butoire le ".$new_date.")";
                $contentDiscord = "**J-".$days_diff['diff_date']."** ! Controle technique a venir pour *".$row['marque']." ".$row['modele']."* (***".$row['immatriculation']."*** | date butoire le **".$new_date."**)";
                sendDiscordAlert($contentDiscord);
                $mail->MsgHTML($content); 
                if(!$mail->Send()) {
                echo "Error while sending Email.";
                var_dump($mail);
                } else {
                    echo "Email sent successfully";
                }
            }
        }elseif($days_diff['diff_date'] <= 0 && $row['rdv_pris'] == 0){
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
                $new_date = strftime("%d %B %Y",strtotime($row["prochaine_date_ct"]));
                $new_date = str_replace( 
                    array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'),
                    array('Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'),
                    $new_date
                  );
                $mail->SetFrom("supervision.decomble@gmail.com", "Auto ".$brend);
                $mail->Subject = "!!!! Controle technique perime depuis ".abs($days_diff['diff_date'])." jours pour ".$row['modele']." ".$row['marque']." | ".$row['immatriculation'];
                $content = "Controle technique perime depuis ".abs($days_diff['diff_date'])." jours pour ".$row['modele']." ".$row['marque']." | ".$row['immatriculation'];
                $contentDiscord = "Controle technique perime depuis ***".abs($days_diff['diff_date'])." jours*** pour ".$row['modele']." ".$row['marque']." | ".$row['immatriculation'];
                sendDiscordAlert($contentDiscord);

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