<?php
  $admin = true;
	require 'config.php';
  require 'check.php';
  $title = "Ajout d'une vidange | ".$brend;
  $page = "add_vidange";
?>
<?php ob_start(); ?>
          <div class="content-wrapper">
            <!-- Content -->

            <div class="container-xxl flex-grow-1 container-p-y">
              <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Admin / </span>Ajout d'une vidange</h4>

              <!-- Basic Bootstrap Table -->
              <!-- Form controls -->
              <div class="col-md-6">
                  <div class="card mb-4">
                    <h5 class="card-header"></h5>
                    <div class="card-body">
                      <?php
                        if(isset($_POST['add_vidange_button'])){
                          if(empty($_POST["immatSelect"]) || (empty($_POST["date_vidange"]))){
                            echo'<div class="alert alert-danger" role="alert">Veuillez remplir tous les champs</div>';
                          }else{
                            echo'<div class="alert alert-success" role="alert">Vidange ajoutée !</div>';
                          }
                      }    
                      ?>
                      <form action="" method="POST">
                      <div class="mb-3">
                          <label for="immatSelect" class="form-label">Véhicule</label>
                          <select class="form-select" name="immatSelect">
                          <?php
                            $fill_select = $connect->prepare('SELECT * FROM VEHICULE EXCEPT SELECT * FROM VEHICULE WHERE motorisation="Electrique" ORDER BY immatriculation ASC');
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
                        <div class="mb-3">
                          <label for="immat" class="form-label">Fait le :</label>
                          <input
                            type="date"
                            class="form-control"
                            id="date_vidange"
                            name="date_vidange"
                            placeholder=""
                          />
                        </div>
                        <div>
                          <label for="exampleFormControlTextarea1" class="form-label">Commentaire<span class="text-muted fw-light"> (facultatif)</span></label>
                          <textarea class="form-control" name="commentaire" rows="3"></textarea>
                        </div>
                        <hr class="my-4" />
                        <div class="d-grid gap-2 col-lg-6 mx-auto">
                          <button class="btn btn-primary btn-lg" name="add_vidange_button" type="submit">Ajouter</button>
                        </div>
                      </form>

                      <?php
                        if(isset($_POST['add_vidange_button'])){
                          if(!empty($_POST["immatSelect"]) && !empty($_POST["date_vidange"])){
                            $immat = $_POST['immatSelect'];
                            $current_date = $_POST['date_vidange'];
                            $next_date = date('Y-m-d', strtotime($current_date. '+ 1 years'));
                            $commentaire = $_POST['commentaire'];
                            $type_ope = "VIDANGE";
                            $car_picker = $connect->prepare('SELECT * FROM VEHICULE WHERE immatriculation = :immatriculation');
                            $car_picker->execute(['immatriculation' => $immat]);
                            $row = $car_picker->fetch(PDO::FETCH_ASSOC);
                            $id_check = $row['id'];
                            $check_id=$connect->prepare("DELETE FROM VIDANGE WHERE id_vehicule = $id_check");
                            $check_id->execute();
                            $insert_ct = $connect->prepare('INSERT INTO VIDANGE (id_vehicule, date_vidange, prochaine_date_vidange) VALUES (?,?,?)')->execute([$row['id'], $current_date, $next_date]);
                            $insert_histo = $connect->prepare('INSERT INTO HISTORIQUE (id_vehicule, type_operation, date_vidange, commentaire) VALUES (?,?,?,?)')->execute([$row['id'], $type_ope, $current_date, $commentaire]);
                          }
                        }
                      ?>
                    </div>
                  </div>
                </div>

              <hr class="my-5" />
            <?php $content = ob_get_clean(); ?>
<?php require('template.php'); ?>