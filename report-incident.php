<?php
	require 'config.php';
  if(empty($_SESSION['username'])){
    header("location:login");
  }
  $title = "Rapport d'incident | ".$brend;
  $page = "report-incident";
?>
<?php ob_start(); ?>
          <div class="content-wrapper">
            <!-- Content -->

            <div class="container-xxl flex-grow-1 container-p-y">
              <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Incident / </span>Rapport d'incident</h4>

              <!-- Basic Bootstrap Table -->
                <div class="col-xl">
                  <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                      <h5 class="mb-0">Rapport d'incident</h5>
                    </div>
                    <div class="card-body">
                      <?php
                        if(isset($_POST['report'])){
                          if(empty($_POST["immatSelect"]) || (empty($_POST["date_incident"]) || empty($_POST["message"]))){
                              echo'<div class="alert alert-danger" role="alert">Veuillez remplir tous les champs</div>';
                          }elseif($erreur == false){
                            echo'<div class="alert alert-success" role="alert">Incident ajouté !</div>';
                          }
                      }    
                      ?>
                      <form method="POST">
                        <div class="mb-3">
                          <label class="form-label" for="basic-icon-default-fullname">Véhicule</label>
                          <div class="input-group input-group-merge">
                            <span id="basic-icon-default-fullname2" class="input-group-text"
                              ><i class="bx bx-car"></i
                            ></span>
                            <select class="form-select" name="immatSelect">
                            <?php
                                $fill_select = $connect->prepare('SELECT * FROM VEHICULE ORDER BY immatriculation ASC');
                                $fill_select->execute();
                                while($row = $fill_select->fetch(PDO::FETCH_ASSOC))
                                {
                            ?>
                                <option value="<?php print($row['immatriculation'])?>"><?php print($row['immatriculation'].' '.$row['marque'].' '.$row['modele'])?></option>
                            <?php
                                }
                            ?>
                            </select>
                          </div>
                        </div>
                        <div class="mb-3">
                          <label class="form-label" for="basic-icon-default-company">Date</label>
                          <div class="input-group input-group-merge">
                            <span id="basic-icon-default-company2" class="input-group-text"
                              ><i class="bx bx-calendar"></i
                            ></span>
                            <input
                              type="date"
                              id="basic-icon-default-company"
                              class="form-control"
                              name="date_incident"
                              aria-label="ACME Inc."
                              aria-describedby="basic-icon-default-company2"
                              max=<?php echo date('Y-m-d') ?>
                            />
                          </div>
                        </div>
                        <div class="mb-3">
                          <label class="form-label" for="basic-icon-default-message">Message<span class="text-muted fw-light"> (255 caractères max.)</span></label>
                          <div class="input-group input-group-merge">
                            <span id="basic-icon-default-message2" class="input-group-text"
                              ><i class="bx bx-comment"></i
                            ></span>
                            <textarea
                              id="basic-icon-default-message"
                              class="form-control"
                              name="message"
                              aria-label="Hi, Do you have a moment to talk Joe?"
                              aria-describedby="basic-icon-default-message2"
                            ></textarea>
                          </div>
                        </div>
                        <button type="submit" class="btn btn-primary" name="report">Envoyer</button>
                      </form>
                      <?php
                        if(isset($_POST['report'])){
                            if(empty($_POST["immatSelect"]) || empty($_POST["date_incident"]) || empty($_POST["message"])){
                          }else{
                            $immat = $_POST['immatSelect'];
                            $current_date = $_POST['date_incident'];
                            $message = $_POST['message'];
                            $car_picker = $connect->prepare('SELECT * FROM VEHICULE WHERE immatriculation = :immatriculation');
                            $car_picker->execute(['immatriculation' => $immat]);
                            $row = $car_picker->fetch(PDO::FETCH_ASSOC);
                            $id_check = $row['id'];
                            $observateur = $_SESSION['username'];
                            $insert_ct = $connect->prepare('INSERT INTO INCIDENT (date_incident, observateur, id_vehicule, remarque, heure_report) VALUES (?,?,?,?, DATE_ADD(NOW(), INTERVAL 2 HOUR))')->execute([$current_date, $observateur, $id_check, $message]);
                          }
                        }
                      ?>
                    </div>
                  </div>
                </div>

              <hr class="my-5" />
            <!-- / Content -->
            <?php $content = ob_get_clean(); ?>
<?php require('template.php'); ?>