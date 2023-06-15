<?php 
    require ('model/functions.php');
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
                $id=$_POST['del_id'];
                $type_ope = 'CT';
                $query = $connect->prepare('SELECT * FROM VEHICULE, CT, RDV WHERE VEHICULE.id = CT.id_vehicule AND VEHICULE.id = RDV.id_vehicule AND rdv_effectue = 0 AND rdv_annule = 0 AND VEHICULE.id = :id Order BY prochaine_date_ct ASC');
                $query->execute(array('id' => $id));
                $result = $query->fetch(PDO::FETCH_ASSOC);
          ?>
          <div class="content-wrapper">
            <div class="container-xxl flex-grow-1 container-p-y">
              <div class="layout-demo-wrapper">
              <div class="col-md-6">
                <div class="card mb-4">
                    <h5 class="card-header">RDV pour <?php echo $result['marque']." ".$result['modele']?></h5>
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
                            <label for="exampleFormControlReadOnlyInputPlain1" class="form-label"><strong>Date du contrôle technique</strong></label>
                            <input
                            type="date"
                            class="form-control-plaintext"
                            id="exampleFormControlReadOnlyInputPlain1"
                            name="datePicker"
                            value="<?php echo $result['date_rdv']; ?>"
                            max="<?php echo $result['date_rdv']; ?>"
                            min="<?php echo $result['date_rdv']; ?>"
                            />
                        </div>
                        <div class="mb-3">
                          <label for="exampleFormControlTextarea1" class="form-label">Commentaire<span class="text-muted fw-light"> (facultatif)</span></label>
                          <textarea class="form-control" name="commentaire" rows="3"></textarea>
                        </div>
                        <hr class="m-0" />
                        <br>
                        <div class="demo-inline-spacing">
                          <button class="btn btn-primary" name="add_ct" type="submit">Confirmer le contrôle technique</button>
                          <button class="btn btn-danger" name="contre_visite" type="submit">Contre Visite</button>
                        </div>
                        </form>
                        <?php
                          if(isset($_POST['add_ct']))
                          {
                              $commentaire = $_POST['commentaire'];
                              $immatriculation = $_POST['immat'];
                              $check = $connect->prepare("SELECT id, marque, modele FROM VEHICULE WHERE immatriculation = :immatriculation");
                              $check->execute(['immatriculation' => $immatriculation]);
                              $vehicule = $check->fetch(PDO::FETCH_ASSOC);        
                              if($vehicule)
                              {
                                  $id_tuture = $vehicule['id'];
                                  $query2 = $connect->prepare('SELECT * FROM VEHICULE 
                                                              INNER JOIN CT ON VEHICULE.id = CT.id_vehicule
                                                              INNER JOIN RDV ON VEHICULE.id = RDV.id_vehicule
                                                              WHERE VEHICULE.id = :id
                                                              AND RDV.rdv_annule = 0
                                                              ORDER BY prochaine_date_ct ASC');
                                  $query2->execute(array('id' => $id_tuture));
                                  $result2 = $query2->fetch(PDO::FETCH_ASSOC);
                                  if($result2 && $result2['utilitaire'] == 1) {
                                      $connect->beginTransaction();  
                                      try {
                                          $updateVehicule = $connect->prepare('UPDATE VEHICULE
                                                                              SET rdv_pris = 0
                                                                              WHERE id = :id');
                                          $updateVehicule->execute(array('id' => $id_tuture));                    
                                          $updateRdv = $connect->prepare('UPDATE RDV
                                                                          SET rdv_effectue = 1
                                                                          WHERE id_vehicule = :id');
                                          $updateRdv->execute(array('id' => $id_tuture));
                                          $updateCT = $connect->prepare('UPDATE CT
                                                                      SET date_ct = (
                                                                          SELECT date_rdv
                                                                          FROM RDV
                                                                          WHERE id_vehicule = :id
                                                                          AND rdv_annule = 0
                                                                      ),
                                                                      prochaine_date_ct = (
                                                                          SELECT DATE_ADD(date_rdv, INTERVAL 1 YEAR)
                                                                          FROM RDV
                                                                          WHERE id_vehicule = :id
                                                                          AND rdv_annule = 0
                                                                      )
                                                                      WHERE id_vehicule = :id');
                                          $updateCT->execute(array('id' => $id_tuture));
                                          $insert_histo = $connect->prepare('INSERT INTO HISTORIQUE (id_vehicule, type_operation, date_ct, commentaire) VALUES (?,?,?,?)');
                                          $insert_histo->execute([$id_tuture, $type_ope, $result2['date_rdv'], $commentaire]);
                                          $connect->commit();
                                          header('Location: suivi_ct');
                                          exit();
                                      } catch (Exception $e) {
                                          $connect->rollback();
                                          echo 'Erreur : ' . $e->getMessage();
                                      }
                                  } else {
                                      $connect->beginTransaction();
                                      try {
                                          $updateVehicule = $connect->prepare('UPDATE VEHICULE
                                                                              SET rdv_pris = 0
                                                                              WHERE id = :id');
                                          $updateVehicule->execute(array('id' => $id_tuture));
                                          $updateRdv = $connect->prepare('UPDATE RDV
                                                                          SET rdv_effectue = 1
                                                                          WHERE id_vehicule = :id');
                                          $updateRdv->execute(array('id' => $id_tuture));
                                          $updateCT = $connect->prepare('UPDATE CT
                                                                      SET date_ct = (
                                                                          SELECT date_rdv
                                                                          FROM RDV
                                                                          WHERE id_vehicule = :id
                                                                          AND rdv_annule = 0
                                                                      ),
                                                                      prochaine_date_ct = (
                                                                          SELECT DATE_ADD(date_rdv, INTERVAL 2 YEAR)
                                                                          FROM RDV
                                                                          WHERE id_vehicule = :id
                                                                          AND rdv_annule = 0
                                                                      )
                                                                      WHERE id_vehicule = :id');
                                          $updateCT->execute(array('id' => $id_tuture));
                                          $insert_histo = $connect->prepare('INSERT INTO HISTORIQUE (id_vehicule, type_operation, date_ct, commentaire) VALUES (?,?,?,?)');
                                          $insert_histo->execute([$id_tuture, $type_ope, $result2['date_rdv'], $commentaire]);
                                          $contentDiscord = "**".strtoupper($_SESSION['username'])."** - Contrôle technique validé pour **".$immatriculation."** ".$vehicule['marque']." ".$vehicule['modele'];
                                          sendDiscordAlert($contentDiscord);
                                          $connect->commit();
                                          header('Location: suivi_ct');
                                          exit();
                                      } catch (Exception $e) {
                                          $connect->rollback();
                                          echo 'Erreur : ' . $e->getMessage();
                                      }
                                  }
                              }
                          }
                          elseif(isset($_POST['contre_visite'])){
                              $check = $connect->prepare("SELECT * FROM VEHICULE WHERE immatriculation = :immatriculation");
                              $check->execute(['immatriculation' => $_POST['immat']]);
                              $_SESSION['voiture'] = $check->fetch(PDO::FETCH_ASSOC);
                              header('Location: contre-visite');
                              exit();
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
