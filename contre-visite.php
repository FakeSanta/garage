<?php 
    require_once('model/functions.php');
    require 'config.php';
    ob_start();
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
    <title>Contre Visite | <?php  echo $brend ?></title>
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
            $id_vehicule = $_SESSION['voiture']['id'];
            $check_ct = $connect->prepare("SELECT * FROM VEHICULE INNER JOIN CT ON VEHICULE.id = CT.id_vehicule AND VEHICULE.id = :id ORDER BY date_ct ASC");
            $check_ct->execute(['id' => $id_vehicule]);
            $result = $check_ct->fetch(PDO::FETCH_ASSOC);
            //echo"<script>console.log('".$id_vehicule."')</script>";
          ?>
          <div class="content-wrapper">
            <div class="container-xxl flex-grow-1 container-p-y">
              <div class="layout-demo-wrapper">
              <div class="col-md-6">
                <div class="card mb-4">
                    <h5 class="card-header">Contre Visite pour <?php echo $result['immatriculation']?></h5>
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
                            <label for="exampleFormControlReadOnlyInputPlain1" class="form-label"><strong>Date de la contre visite</strong></label>
                            <input
                            type="date"
                            class="form-control-plaintext"
                            id="exampleFormControlReadOnlyInputPlain1"
                            name="datePicker"
                            min=<?php echo date('Y-m-d')?>
                            required
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
                                if(!empty($_POST['datePicker'])){
                                    if ($_POST['datePicker'] > date('Y-m-d')) {
                                        $check = $connect->prepare("SELECT V.id AS vehicule_id, V.immatriculation AS immatriculation, V.marque AS marque, V.modele AS modele, V.motorisation AS motorisation,
                                        V.kilometrage AS kilometrage, V.utilitaire AS utilitaire, V.rdv_pris AS rdv_pris,
                                        R.id AS rdv_id, R.date_rdv AS date_rdv, R.id_vehicule AS rdv_vehicule_id, R.rdv_effectue AS rdv_effectue, R.rdv_annule AS rdv_annule
                                        FROM VEHICULE V
                                        INNER JOIN RDV R ON V.id = R.id_vehicule
                                        WHERE V.immatriculation = :immatriculation                                 
                                      ");
                                        $check->execute(['immatriculation' => $_POST['immat']]);
                                        $final = $check->fetch(PDO::FETCH_ASSOC);

                                        $date_format = strftime("%d %B %Y",strtotime($_POST['datePicker']));
                                        $date_format = str_replace( 
                                          array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'),
                                          array('Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'),
                                          $date_format
                                        );
                                        $contentDiscord = "**".strtoupper($_SESSION['username'])."** - Contre visite pour **".$final['immatriculation']."** ".$final['marque']." ".$final['modele'].". La prochaine date est **".$date_format."**";
                                        sendDiscordAlert($contentDiscord);
                                        $id_tuture = $final['vehicule_id'];                                        
                                        $oldCt = $connect->prepare("UPDATE RDV SET rdv_annule = 1 WHERE id_vehicule = :id");
                                        $oldCt->execute(array('id' => $id_tuture));
                                        $insert_rdv = $connect->prepare('INSERT INTO RDV (date_rdv, id_vehicule, rdv_effectue, rdv_annule) VALUES (?,?, 0,0)')->execute([$_POST['datePicker'], $id_tuture]);
                                        $updateVehicule = $connect->prepare("UPDATE VEHICULE SET rdv_pris = 1 WHERE id = :id");
                                        $updateVehicule->execute(array('id' => $id_tuture));
                                        unset($_SESSION['voiture']);
                                        header('Location: index');
                                        ob_end_flush();
                                        exit(); // Ajout de la fonction exit()
                                    }
                                    else{
                                        echo"<script>console.log('date inférieur')</script>";
                                    }
                                }else{
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
