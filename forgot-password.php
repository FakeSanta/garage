<?php
require ('model/functions.php');
require ('config.php');
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

function sendMail($email, $reset_token){
    require '/var/www/html/vendor/phpmailer/phpmailer/src/Exception.php';
    require '/var/www/html/vendor/phpmailer/phpmailer/src/PHPMailer.php';
    require '/var/www/html/vendor/phpmailer/phpmailer/src/SMTP.php';
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
    $mail->AddAddress($email,"Auto-".$brend);
    $mail->IsHTML(true);
    $mail->SetFrom("supervision.decomble@gmail.com", "Auto ".$brend);
    $mail->Subject = "Lien de récupération du mot de passe";
    $content = "Vous avez lancer la procédure de récupération de mot de passe. <br> Cliquez sur le lien ci-dessous <br>
    <a href='http://172.16.1.75/updatePassword.php?email=$email&reset_token=$reset_token'>Réinitialiser le mot de passe</a>";
    $mail->MsgHTML($content); 
    if(!$mail->Send()) {
    echo "Error while sending Email.";
    var_dump($mail);
    } else {
        echo "Email sent successfully";
    }    
}
if(isset($_POST['send_link'])){
    $email = $_POST['email'];
    
    $sql = $connect->prepare("SELECT * FROM USER WHERE mail = :mail");
    $sql->execute(array('mail' => $email));
    
    if($row = $sql->fetch(PDO::FETCH_ASSOC)){   
        $date = date("Y-m-d");
        $reset_token=bin2hex(random_bytes(16));
    
        $update = $connect->prepare("UPDATE USER SET resettoken = ?, resettokenexp = ? WHERE mail = ?");
        $update->execute([$reset_token, $date, $email]);
        if(sendMail($email, $reset_token) === TRUE){
            echo "
            <script>
                alert('Password reset link send to mail.');
                window.location.href='index'    
            </script>"; 
            
        }else{
            echo "
            
            <script type='text/javascript'>swal('E-Mail envoyé !', 'Un lien vous à été envoyé pour réinitialiser votre mot de passe', 'success');</script>
            <script>
            window.location.href='index'
            </script>";
        }
    }else{
        echo "
        <script>
            alert('Email Address Not Found');
            window.location.href='forgot-password'
        </script>";
    }
}
?>
<!DOCTYPE html>
<html
  lang="en"
  class="light-style customizer-hide"
  dir="ltr"
  data-theme="theme-default"
  data-assets-path="../assets/"
  data-template="vertical-menu-template-free"
>
  <head>
    <meta charset="utf-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0"
    />

    <title>Mot de passe oublié | <?php echo $brend ?></title>

    <meta name="description" content="" />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="../assets/img/favicon/voiture.ico" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
      rel="stylesheet"
    />
	<script src="calendar/assets/js/sweetalert.min.js"></script> 
	<link rel="stylesheet" type="text/css" href="calendar/assets/css/sweetalert.css">
    <script src="calendar/assets/js/moment.min.js"></script>
    <!-- Icons. Uncomment required icon fonts -->
    <script src="calendar/assets/js/listings.js"></script>

    <link rel="stylesheet" href="../assets/vendor/fonts/boxicons.css" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="../assets/vendor/css/core.css" class="template-customizer-core-css" />
    <link rel="stylesheet" href="../assets/vendor/css/theme-default.css" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="../assets/css/demo.css" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />

    <!-- Page CSS -->
    <!-- Page -->
    <link rel="stylesheet" href="../assets/vendor/css/pages/page-auth.css" />
    <!-- Helpers -->
    <script src="../assets/vendor/js/helpers.js"></script>

    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="../assets/js/config.js"></script>
  </head>

  <body>
    <!-- Content -->

    <div class="container-xxl">
      <div class="authentication-wrapper authentication-basic container-p-y">
        <div class="authentication-inner py-4">
          <!-- Forgot Password -->
          <div class="card">
            <div class="card-body">
              <h4 class="mb-2">Mot de passe oublié ? 🔒</h4>
              <p class="mb-4">Entrer votre adresse mail et suivez les instructions</p>
              <form id="formAuthentication" class="mb-3" method="POST">
                <div class="mb-3">
                  <label for="email" class="form-label">Mail</label>
                  <input
                    type="text"
                    class="form-control"
                    id="email"
                    name="email"
                    placeholder=""
                    autofocus
                  />
                </div>
                <button class="btn btn-primary d-grid w-100" name="send_link" id="send_link">Envoyer le lien de récupération</button>
              </form>
              <div class="text-center">
                <a href="login.php" class="d-flex align-items-center justify-content-center">
                  <i class="bx bx-chevron-left scaleX-n1-rtl bx-sm"></i>
                  Retour à la connexion
                </a>
              </div>
            </div>
          </div>
          <!-- /Forgot Password -->
        </div>
      </div>
    </div>
    <script src="../assets/vendor/libs/jquery/jquery.js"></script>
    <script src="../assets/vendor/libs/popper/popper.js"></script>
    <script src="../assets/vendor/js/bootstrap.js"></script>
    <script src="../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>

    <script src="../assets/vendor/js/menu.js"></script>
    <!-- endbuild -->

    <!-- Vendors JS -->

    <!-- Main JS -->
    <script src="../assets/js/main.js"></script>

    <!-- Page JS -->

    <!-- Place this tag in your head or just before your close body tag. -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
  </body>
</html>
