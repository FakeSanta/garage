<?php
  $title = "Ajout d'un véhicule | ".$brend;
  $page = "add_car";
?>
<?php ob_start(); ?>
          <div class="content-wrapper">
            <!-- Content -->
            <div class="container-xxl flex-grow-1 container-p-y">
              <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Admin / </span>Ajout d'un véhicule</h4>

              <!-- Basic Bootstrap Table -->
              <!-- Form controls -->
              <div class="col-md-6">
                  <div class="card mb-4">
                    <h5 class="card-header"></h5>
                    <div class="card-body">
                      <?php
                        if(isset($_POST['add_car_button'])){
                          if(empty($_POST["immat"]) || (empty($_POST["marque"]) || empty($_POST["modele"]))){
                              echo'<div class="alert alert-danger" role="alert">Veuillez remplir tous les champs</div>';
                          } elseif(!preg_match($_SESSION['pattern'], $_POST['immat'])) {
                              echo'<div class="alert alert-danger" role="alert">Erreur : Mauvais format d\'immatriculation</div>';
                          }elseif(checkCarExist($_POST['immat']) === true){
                            echo'<div class="alert alert-danger" role="alert">Véhicule déjà rentré dans la base</div>';
                          }else{
                            echo'<div class="alert alert-success" role="alert">Véhicule ajouté !</div>';
                          }
                      }    
                      ?>
                      <form action="" method="POST">
                        <div class="mb-3">
                          <label for="immat" class="form-label">Plaque d'immatriculation</label>
                          <input
                            type="text"
                            class="form-control"
                            id="immat"
                            name="immat"
                            placeholder="AB-645-BG"
                          />
                        </div>
                        <div class="mb-3">
                          <label for="marque" class="form-label">Marque</label>
                          <input
                            type="text"
                            class="form-control"
                            id="marque"
                            name="marque"
                            placeholder="Audi"
                          />
                        </div>
                        <div class="mb-3">
                          <label for="modele" class="form-label">Modèle</label>
                          <input
                            type="text"
                            class="form-control"
                            id="modele"
                            name="modele"
                            placeholder="A3"
                          />
                        </div>
                        <div class="mb-3">
                          <label for="carburant" class="form-label">Carburant</label>
                          <select class="form-select" name="carburantSelect" aria-label="Default select example">
                            <option selected>------------</option>
                            <option value="Diesel">Diesel</option>
                            <option value="Essence">Essence</option>
                            <option value="Electrique">Electrique</option>
                            <option value="Hybride">Hybride</option>
                          </select>
                        </div>
                        <div class="col-md">
                        <small class="text-light fw-semibold d-block">Utilitaire</small>
                          <div class="form-check form-check-inline mt-3">
                            <input
                              class="form-check-input"
                              type="radio"
                              name="radio"
                              value="1"
                            />
                            <label class="form-check-label" for="inlineRadio1">Oui</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input
                              class="form-check-input"
                              type="radio"
                              name="radio"
                              value="0"
                            />
                            <label class="form-check-label" for="inlineRadio2">Non</label>
                          </div>
                        </div>
                        <br>
                        <div class="mb-3">
                          <label for="immat" class="form-label">Date du dernier contrôle technique :</label>
                          <input
                            type="date"
                            class="form-control"
                            id="date_ct"
                            name="date_ct"
                            placeholder=""
                            max=<?php echo date('Y-m-d')?>
                          />
                        </div>
                        <div>
                          <label for="exampleFormControlTextarea1" class="form-label">Kilométrage<span class="text-muted fw-light"> (facultatif)</span></label>
                          <input class="form-control" type="number" name="kilometrage" value="" placeholder="Facultatif" id="html5-number-input">
                        </div>
                        <hr class="my-4" />
                        <div class="d-grid gap-2 col-lg-6 mx-auto">
                          <button class="btn btn-primary btn-lg" name="add_car_button" type="submit">Ajouter</button>
                        </div>
                      </form>
                    </div>
                  </div>
                </div>

              <hr class="my-5" />
<?php $content = ob_get_clean(); ?>
<?php require('template.php'); ?>