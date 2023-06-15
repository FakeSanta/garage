<?php 
  use PHPMailer\PHPMailer\PHPMailer;
  use PHPMailer\PHPMailer\SMTP;
  use PHPMailer\PHPMailer\Exception;
  require '/var/www/html/vendor/phpmailer/phpmailer/src/Exception.php';
  require '/var/www/html/vendor/phpmailer/phpmailer/src/PHPMailer.php';
  require '/var/www/html/vendor/phpmailer/phpmailer/src/SMTP.php';
  require ('model/functions.php');
  require 'config.php';
  ob_start();
  $today = date('Y-m-d');
?>
<!DOCTYPE html>
<html
  lang="en"
  class="light-style layout-menu-fixed"
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
    <title>Prise de RDV | <?php echo $brend ?></title>
    <meta name="description" content="" />
    <link rel="icon" type="image/x-icon" href="../assets/img/favicon/voiture.ico" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
      rel="stylesheet"
    />
    <link rel="stylesheet" href="../assets/vendor/fonts/boxicons.css" />
    <link rel="stylesheet" href="../assets/vendor/css/core.css" class="template-customizer-core-css" />
    <link rel="stylesheet" href="../assets/vendor/css/theme-default.css" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="../assets/css/demo.css" />
    <link rel="stylesheet" href="../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />
    <script src="../assets/vendor/js/helpers.js"></script>
    <script src="../assets/js/config.js"></script>
  </head>
  <body>
    <div class="layout-wrapper layout-content-navbar layout-without-menu">
      <div class="layout-container">
        <div class="layout-page">
          <nav
            class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme"
            id="layout-navbar"
          >
            <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
            </div>
          </nav>
          <?php
            $id_vehicule = $_GET['id'];
            $check_ct = $connect->prepare("SELECT * FROM VEHICULE INNER JOIN CT ON VEHICULE.id = CT.id_vehicule AND VEHICULE.id = :id ORDER BY date_ct ASC");
            $check_ct->execute(['id' => $id_vehicule]);
            $result = $check_ct->fetch(PDO::FETCH_ASSOC);
          ?>
          <div class="content-wrapper">
            <div class="container-xxl flex-grow-1 container-p-y">
              <div class="layout-demo-wrapper">
              <div class="col-md-6">
                <div class="card mb-4">
                    <h5 class="card-header">RDV pour <?php echo $result['immatriculation']?></h5>
                    <hr class="m-0" />
                    <div class="card-body">
                      <form method="POST">
                        <div class="mb-3">
                            <label for="exampleFormControlReadOnlyInputPlain1" class="form-label"><strong>Marque</strong></label>
                            <input
                            type="text"
                            readonly
                            class="form-control-plaintext"
                            id="exampleFormControlReadOnlyInputPlain1"
                            value="<?php echo $result['marque']?>"
                            />
                        </div>
                        <hr class="m-0" />
                        <div class="mb-3">
                            <label for="exampleFormControlReadOnlyInputPlain1" class="form-label"><strong>Modèle</strong></label>
                            <input
                            type="text"
                            readonly
                            class="form-control-plaintext"
                            id="exampleFormControlReadOnlyInputPlain1"
                            value="<?php echo $result['modele']?>"
                            />
                        </div>
                        <hr class="m-0" />
                        <div class="mb-3">
                            <label for="exampleFormControlReadOnlyInputPlain1" class="form-label"><strong>Immatriculation</strong></label>
                            <input
                            type="text"
                            readonly
                            class="form-control-plaintext"
                            id="exampleFormControlReadOnlyInputPlain1"
                            name="immat"
                            value="<?php echo $result['immatriculation'];?>"
                            />
                        </div>
                        <hr class="m-0" />
                        <div class="mb-3">
                            <label for="exampleFormControlReadOnlyInputPlain1" class="form-label"><strong>Date du rendez-vous</strong></label>
                            <input
                            type="date"
                            class="form-control-plaintext"
                            id="exampleFormControlReadOnlyInputPlain1"
                            name="datePicker"
                            min=<?php echo $today?>
                            />
                        </div>
                        <hr class="m-0" />
                        <br>
                        <div class="d-grid gap-2 col-lg-6 mx-auto">
                          <button class="btn btn-primary" name="add_rdv" type="submit">Valider</button>
                        </div>
                        </form>
                        <?php
                            if(isset($_POST['add_rdv']))
                            {
                                if(!empty($_POST['datePicker']))
                                {
                                    if ($_POST['datePicker'] >= date('Y-m-d'))
                                    {
                                        try
                                        {
                                            $check = $connect->prepare("SELECT * FROM VEHICULE WHERE immatriculation = :immatriculation");
                                            $check->execute(['immatriculation' => $_POST['immat']]);
                                            $final = $check->fetch(PDO::FETCH_ASSOC);
                                            $id_tuture = $final['id'];

                                            $insert_rdv = $connect->prepare('INSERT INTO RDV (date_rdv, id_vehicule, rdv_effectue, rdv_annule) VALUES (?,?, 0,0)');
                                            $insert_rdv->execute([$_POST['datePicker'], $final['id']]);

                                            $updateVehicule = $connect->prepare("UPDATE VEHICULE SET rdv_pris = 1 WHERE id = :id");
                                            $updateVehicule->execute(array('id' => $final['id']));

                                            $new_date = strftime("%d %B %Y", strtotime($_POST['datePicker']));
                                            $new_date = str_replace(
                                                array(
                                                    'January', 'February', 'March', 'April', 'May', 'June',
                                                    'July', 'August', 'September', 'October', 'November', 'December'
                                                ),
                                                array(
                                                    'Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin',
                                                    'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'
                                                ),
                                                $new_date
                                            );

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
                                            if($sql->RowCount() > 1)
                                            {
                                                while($to = $sql->fetch(PDO::FETCH_ASSOC))
                                                {
                                                    $mail->AddAddress($to['mail'], "Auto " . $brend);
                                                }
                                            }

                                            $mail->SetFrom("supervision.decomble@gmail.com", "Auto " . $brend);
                                            $mail->Subject = "RDV le " . $new_date . " pour le controle technique de " . $final['immatriculation'] . " " . $final['marque'] . " " . $final['modele'];
                                            $content = "RDV le " . $new_date . " pour le controle technique de " . $final['immatriculation'] . " " . $final['marque'] . " " . $final['modele'];
                                            $contentDiscord = "**" . strtoupper($_SESSION['username']) . "** - RDV le ***" . $new_date . "*** pour le controle technique de **" . $final['immatriculation'] . "** " . $final['marque'] . " " . $final['modele'];
                                            $color ="008020";
                                            sendDiscordAlert($contentDiscord,$color);
                                            $mail->MsgHTML($content);

                                            if(!$mail->Send())
                                            {
                                                echo "Error while sending Email.";
                                                echo "<script>console.log(" . json_encode($mail) . ")</script>";
                                            }

                                            header('Location: index');
                                            exit();
                                        }
                                        catch(Exception $e)
                                        {
                                            echo "Error: " . $e->getMessage();
                                        }
                                    }
                                    else
                                    {
                                        echo"<script>console.log('date inférieur')</script>";
                                    }
                                }
                                else
                                {
                                    echo"<script>console.log('date vide')</script>";
                                }
                            }
                        ?>

                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="content-backdrop fade"></div>
          </div>
        </div>
      </div>
    </div>
    <script src="../assets/vendor/libs/jquery/jquery.js"></script>
    <script src="../assets/vendor/libs/popper/popper.js"></script>
    <script src="../assets/vendor/js/bootstrap.js"></script>
    <script src="../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
    <script src="../assets/vendor/js/menu.js"></script>
    <script src="../assets/js/main.js"></script>
    <script async defer src="https://buttons.github.io/buttons.js"></script>
  </body>
</html>
