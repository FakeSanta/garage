<?php
  $admin = true;
	require 'config.php';
  require 'check.php';
  $title = "Assigner un équipement | ".$brend;
  $page = "assign-stuff";
?>
<?php ob_start(); ?>
          <div class="content-wrapper">
            <!-- Content -->

            <div class="container-xxl flex-grow-1 container-p-y">
              <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Admin / </span>Assigner un équipement à un véhicule</h4>

              <!-- Basic Bootstrap Table -->
              <!-- Form controls -->
              <div class="col-md-6">
                  <div class="card mb-4">
                    <h5 class="card-header"></h5>
                    <div class="card-body">
                      <form action="" method="POST">
                      <div class="mb-3">
                          <label for="immatSelect" class="form-label">Véhicule</label>
                          <select class="form-select" name="immatSelect">
                          <?php
                            $fill_select = $connect->prepare('SELECT * FROM VEHICULE ORDER BY modele ASC');
                            $fill_select->execute();
                            while($row = $fill_select->fetch(PDO::FETCH_ASSOC))
                            {
                          ?>
                            <option value="<?php print($row['id'])?>"><?php print($row['modele'].' '.$row['marque'].' | '.$row['immatriculation'])?></option>
                          <?php
                            }
                          ?>
                          </select>
                        </div>
                        <div class="mb-3">
                          <label for="stuffSelect" class="form-label">équipement à assigner</label>
                          <select class="form-select" name="stuffSelect">
                          <?php
                            $fill_select = $connect->prepare('SELECT * FROM EQUIPEMENT ORDER BY id ASC');
                            $fill_select->execute();
                            while($row = $fill_select->fetch(PDO::FETCH_ASSOC))
                            {
                          ?>
                            <option value="<?php print($row['id'])?>"><?php print($row['nom'])?></option>
                          <?php
                            }
                          ?>
                          </select>
                        </div>
                        <hr class="my-4" />
                        <div class="d-grid gap-2 col-lg-6 mx-auto">
                          <button class="btn btn-primary btn-lg" name="assign_button" type="submit">Ajouter</button>
                        </div>
                      </form>

                      <?php
                        if(isset($_POST['assign_button'])){
                            $immat = $_POST['immatSelect'];
                            $stuff = $_POST['stuffSelect'];
                            $insert_ct = $connect->prepare('INSERT INTO VEHICULE_EQUIPEMENT (vehicule_id, equipement_id) VALUES (?,?)')->execute([$immat, $stuff]);
                        }
                      ?>
                    </div>
                  </div>
                </div>

              <hr class="my-5" />
            <?php $content = ob_get_clean(); ?>
<?php require('template.php'); ?>