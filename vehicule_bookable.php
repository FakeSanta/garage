<?php
  $admin = true;
	require 'config.php';
  require 'check.php';
  $title = "Gérer les dispo. des véhicules | ".$brend;
  $page = "vehicule_bookable";
?>
<?php ob_start(); ?>
          <div class="content-wrapper">
            <!-- Content -->

            <div class="container-xxl flex-grow-1 container-p-y">
              <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Admin / Gestion des véhicules / </span>Véhicules réservables</h4>

              <!-- Basic Bootstrap Table -->
              <!-- Form controls -->
              <div class="col-md-6">
                  <div class="card mb-4">
                    <h5 class="card-header"></h5>
                    <div class="card-body">
                      <form action="" method="POST">
                      <div class="mb-3">
                          <label for="carSelect" class="form-label">Véhicule</label>
                          <select class="form-select" id="carSelect">
                          <?php
                            $fill_select = $connect->prepare('SELECT * FROM VEHICULE ORDER BY modele ASC');
                            $fill_select->execute();
                            while($row = $fill_select->fetch(PDO::FETCH_ASSOC))
                            {
                          ?>
                            <option value="<?php print($row['id'])?>"><?php print($row['modele']." ".$row['marque']." | ".$row['immatriculation'])?></option>
                          <?php
                            }
                          ?>
                          </select>
                        </div>
                        <div class="mb-3">
                            <label for="resSelect" class="form-label">Disponible :</label>
                            <select class="form-select" id="resSelect">
                            </select>
                        </div>
                        <hr class="my-4" />
                        <div class="d-grid gap-2 col-lg-6 mx-auto">
                          <button class="btn btn-primary btn-lg" name="modify_vehicule_bookable" type="submit">Modifier</button>
                        </div>
                      </form>

                      <?php
                        if(isset($_POST['modify_vehicule_bookable'])){
                            echo"<script>alert('".$_POST['resSelect']." ".$_POST['carSelect']."')</script>";
                            $modify_user = $connect->prepare('UPDATE VEHICULE SET reservable=? WHERE id=?')->execute([$_POST['resSelect'],$_POST['carSelect']]);
                            echo"<script>alert('Disponibilité de la voiture modifiée')</script>";
                            header('Location:vehicule_bookable');
                        }
                      ?>
                    </div>
                    <script>
                      function loadData(selectedValue) {
                        $.ajax({
                            url: "request_reservable.php",
                            type: "POST",
                            data: {id: selectedValue},
                            success: function(response){
                              var data = JSON.parse(response);
                              var html = '';
                              $.each(data, function(key, value) {
                                html += '<option value="'+ value.reservable +'">' + value.nom + '</option>';
                              });
                              $("#resSelect").html(html);
                            }
                          });
                      }

                      $(document).ready(function(){
                        loadData($("#carSelect").val());
                        $("#carSelect").on("change", function(){
                          var selectedValue = $(this).val();
                          loadData(selectedValue);
                        });
                      });
                    </script>
                  </div>
                </div>

              <hr class="my-5" />
            <?php $content = ob_get_clean(); ?>
<?php require('template.php'); ?>