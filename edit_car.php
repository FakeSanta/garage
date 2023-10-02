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
            $check_ct = $connect->prepare("SELECT * FROM VEHICULE WHERE id = ?");
            $check_ct->execute([$id_vehicule]);
            $result = $check_ct->fetch(PDO::FETCH_ASSOC);
          ?>
          <div class="content-wrapper">
            <div class="container-xxl flex-grow-1 container-p-y">
              <div class="layout-demo-wrapper">
              <div class="col-md-6">
                <div class="card mb-4">
                    <h5 class="card-header">Modification des données pour : <?php echo $result['marque']." ".$result['modele']?></h5>
                    <hr class="m-0" />
                    <div class="card-body">
                      <form method="POST">
                        <div class="mb-3">
                            <label for="exampleFormControlReadOnlyInputPlain1" class="form-label"><strong>Marque</strong></label>
                            <input
                            type="text"
                            name="marque"
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
                            name="modele"
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
                            class="form-control-plaintext"
                            id="exampleFormControlReadOnlyInputPlain1"
                            name="immat"
                            value="<?php echo $result['immatriculation'];?>"
                            />
                        </div>
                        <hr class="m-0" />
                        <div class="mb-3">
                            <label for="exampleFormControlReadOnlyInputPlain1" class="form-label"><strong>Motorisation</strong></label>
                            <select class="form-select" name="carburantSelect" aria-label="Default select example">
                                <option value="Diesel" <?php if($result['motorisation'] == 'Diesel'){ echo "selected";}?>>Diesel</option>
                                <option value="Essence" <?php if($result['motorisation'] == 'Essence'){ echo "selected";}?>>Essence</option>
                                <option value="Hybride" <?php if($result['motorisation'] == 'Hybride'){ echo "selected";}?>>Hybride</option>
                                <option value="Electrique" <?php if($result['motorisation'] == 'Electrique'){ echo "selected";}?>>Electrique</option>

                            </select>
                        </div>
                        <hr class="m-0" />
                        <div class="mb-3"><br />
                        <label for="exampleFormControlReadOnlyInputPlain1" class="form-label"><strong>Utilitaire</strong></label><br />
                        <div class="form-check form-check-inline mt-3">
                            <input
                              class="form-check-input"
                              type="radio"
                              name="radio"
                              value="1"
                              <?php if($result['utilitaire'] == 1){echo 'checked';}?>
                            />
                            <label class="form-check-label" for="inlineRadio1">Oui</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input
                              class="form-check-input"
                              type="radio"
                              name="radio"
                              value="0"
                              <?php if($result['utilitaire'] == 0){echo 'checked';}?>
                            />
                            <label class="form-check-label" for="inlineRadio2">Non</label>
                          </div>
                        </div>
                        <hr class="m-0" />
                        <br>
                        <div class="d-grid gap-1 col-lg-6 mx-auto">
                          <button class="btn btn-primary" name="add_rdv" type="submit">Modifier</button><br />
                          <button class="btn btn-warning" name="retour" type="submit">Retour</button>
                        </div>
                        </form>
                        <?php
                            if(isset($_POST['add_rdv']))
                            {
                                $pattern = "/^[a-zA-Z]{2}-\d{3}-[a-zA-Z]{2}$/";
                                if(preg_match($pattern,$_POST['immat'])){ 
                                    try
                                    {
                                        $edit_immat =0;
                                        $edit_modele =0;
                                        $edit_marque =0;
                                        $edit_motorisation =0;
                                        $edit_utilitaire =0;

                                        if($_POST['immat'] != $result['immatriculation']){
                                            $edit_immat = 1;
                                        }
                                        if($_POST['modele'] != $result['modele']){
                                            $edit_modele = 1;
                                        }
                                        if($_POST['marque'] != $result['marque']){
                                            $edit_marque = 1;
                                        }
                                        if($_POST['carburantSelect'] != $result['motorisation']){
                                            $edit_motorisation = 1;
                                        }
                                        if($_POST['radio'] != $result['utilitaire']){
                                            $edit_utilitaire = 1;
                                            echo"<script>alert('".$id_vehicule."')</script>";
                                        }

                                        //$get_ct =$connect->prepare("SELECT * FROM CT WHERE id = ?");
                                        //$get_ct->execute($id_vehicule);
                                        /*if($edit_utilitaire == 1){
                                            $new_date_ct = "2023-10-06";
                                            //$new_date_ct = date('Y-m-d', strtotime($get_ct['prochaine_date_ct']. '+ 1 years'));
                                            $update_date_ct = $connect->prepare("UPDATE CT SET prochaine_date_ct = ? WHERE id_vehicule = ?");
                                            $update_date_ct->execute($new_date_ct,$id_vehicule);
                                        }else{
                                            $new_date_ct = "2025-10-06";
                                            //$new_date_ct = date('Y-m-d', strtotime($get_ct['prochaine_date_ct']. '+ 1 years'));
                                            $update_date_ct = $connect->prepare("UPDATE CT SET prochaine_date_ct = ? WHERE id_vehicule = ?");
                                            $update_date_ct->execute($new_date_ct,$id_vehicule);
                                        }*/
                                        
                                        $check = $connect->prepare("UPDATE VEHICULE SET immatriculation = ?, marque = ?, modele =?, motorisation=?, utilitaire=? WHERE id = ?");
                                        $check->execute([$_POST['immat'],$_POST['marque'],$_POST['modele'],$_POST['carburantSelect'],$_POST['radio'],$id_vehicule]);
                                        $contentDiscord = "**" . strtoupper($_SESSION['username']) . "** - Modification du véhicule ***" . $_POST['marque'] ." ".$_POST['modele']."***";
                                        $modifications = '';

                                        if ($edit_immat == 1) {
                                            $modifications .= " -- Immatriculation : ".$result['immatriculation']." -> ".$_POST['immat'];
                                        }

                                        if ($edit_marque == 1) {
                                            $modifications .= " -- Marque : ".$result['marque']." -> ".$_POST['marque'];
                                        }

                                        if ($edit_modele == 1) {
                                            $modifications .= " -- Modele : ".$result['modele']." -> ".$_POST['modele'];
                                        }

                                        if ($edit_motorisation == 1) {
                                            $modifications .= " -- Motorisation : ".$result['motorisation']." -> ".$_POST['carburantSelect'];
                                        }

                                        if ($edit_utilitaire == 1) {
                                            $get_ct =$connect->prepare("SELECT * FROM CT WHERE id_vehicule = ?");
                                            $get_ct->execute([$id_vehicule]);
                                            $get_ct_edit = $get_ct->fetch(PDO::FETCH_ASSOC);
                                            $modifications .= " -- Utilitaire : ".$result['utilitaire']." -> ".$_POST['radio'];
                                            if($_POST['radio'] == 0){
                                              //$new_date_ct = "2024-10-06";
                                              $new_date_ct = date('Y-m-d', strtotime($get_ct_edit['prochaine_date_ct']. '+ 1 years'));
                                            }else{
                                              //$new_date_ct = "2023-10-06";
                                              $new_date_ct = date('Y-m-d', strtotime($get_ct_edit['prochaine_date_ct']. '- 1 years'));
                                            }
                                            $update_date_ct = $connect->prepare("UPDATE CT SET prochaine_date_ct = ? WHERE id_vehicule = ?");
                                            $update_date_ct->execute([$new_date_ct,$id_vehicule]);
                                            echo"<script>alert('".$new_date_ct."')</script>";
                                        }

                                        $contentDiscord .= $modifications;

                                        $color ="008020";
                                        sendDiscordAlert($contentDiscord,$color);

                                        header('Location: table_parc');
                                        exit();
                                    }
                                    catch(Exception $e)
                                    {
                                        echo "Error: " . $e->getMessage();
                                    }
                                }else{
                                    header('Location: reservation');
                                }
                            }elseif(isset($_POST['retour'])){
                                header('Location:table_parc');
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
