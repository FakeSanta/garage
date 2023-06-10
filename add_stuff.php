<?php
  $admin = true;
	require 'config.php';
  require 'check.php';
  $title = "Ajout d'un véhicule | ".$brend;
  $page = "add_stuff";
?>
<?php ob_start(); ?>
          <div class="content-wrapper">
            <!-- Content -->

            <div class="container-xxl flex-grow-1 container-p-y">
              <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Admin / </span>Ajout d'un équipement</h4>

              <!-- Basic Bootstrap Table -->
              <!-- Form controls -->
              <div class="col-md-6">
                  <div class="card mb-4">
                    <h5 class="card-header"></h5>
                    <div class="card-body">
                    <div class="card-body">
                      <?php
                        if(isset($_POST['add_stuff_button'])){
                          if(empty($_POST["equipement"]) || (empty($_POST["marque"]))){
                            echo'<div class="alert alert-danger" role="alert">Veuillez remplir tous les champs</div>';
                          }
                          $query_exist = $connect->prepare("SELECT * FROM EQUIPEMENT WHERE nom = :nom_equipement");
                          $query_exist->execute(['nom_equipement' => $_POST['equipement']]);
                          if($query_exist->rowCount()){
                            echo'<div class="alert alert-danger" role="alert">Cet équipement est déjà créé</div>';
                          }
                          else{
                            echo'<div class="alert alert-success" role="alert">Équipement ajouté !</div>';
                          }
                      }    
                      ?>
                      <form action="" method="POST">
                        <div class="mb-3">
                          <label for="equipement" class="form-label">Description de l'équipement</label>
                          <input
                            type="text"
                            class="form-control"
                            id="equipement"
                            name="equipement"
                            placeholder="ex : Huile moteur diesel 5w30"
                          />
                        </div>
                        <div class="mb-3">
                          <label for="equipement" class="form-label">Marque</label>
                          <input
                            type="text"
                            class="form-control"
                            id="marque"
                            name="marque"
                            placeholder="ex : Shell"
                          />
                        </div>
                        <hr class="my-4" />
                        <div class="d-grid gap-2 col-lg-6 mx-auto">
                          <button class="btn btn-primary btn-lg" name="add_stuff_button" type="submit">Ajouter</button>
                        </div>
                      </form>

                      <?php
                        if(isset($_POST['add_stuff_button'])){
                          if(empty($_POST["equipement"]) || empty($_POST["marque"])){
                          }else{
                            $stuff = $_POST['equipement'];
                            $marque = $_POST['marque'];
                            $query_exist = $connect->prepare("SELECT * FROM EQUIPEMENT WHERE nom = :nom_equipement");
                            $query_exist->execute(['nom_equipement' => $stuff]);
                            if($query_exist->rowCount()) {
                            }
                            else{
                                $request = $connect->prepare('INSERT INTO EQUIPEMENT (nom,marque) VALUES (?,?)')->execute([$stuff,$marque]);
                            }
                          }
                        }
                      ?>
                    </div>
                  </div>
                </div>

              <hr class="my-5" />
            <?php $content = ob_get_clean(); ?>
<?php require('template.php'); ?>
