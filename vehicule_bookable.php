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
                  <div class="card">
                    <h5 class="card-header">Véhicule</h5>
                        <div class="table-responsive text-nowrap">
                          <table class="table table-hover">
                            <thead>
                              <tr>
                                <th>Immatriculation</th>
                                <th>Marque</th>
                                <th>Modèle</th>
                                <th>Disponible à la réservation</th>
                                <?php if($_SESSION['role'] != 0){?>
                                <th>Réservable</th>
                                <?php } ?>
                              </tr>
                            </thead>
                            <tbody class="table-border-bottom-0">
                          <?php
                            $fill_select = $connect->prepare('SELECT * FROM VEHICULE ORDER BY modele ASC');
                            $fill_select->execute();
                            while($row = $fill_select->fetch(PDO::FETCH_ASSOC))
                            {
                              $reservable = $row['reservable'];
                              $id = $row['id'];
                          ?>
                            <tr <?php if($reservable == 0){?>class="table-danger"<?php }else{?>class="table-success"<?php }?>>
                              <td><i class="fab fa-angular fa-lg text-danger me-3"></i> <strong><?php print($row['immatriculation']) ?></strong></td>
                              <td><?php print($row['marque'])?></td>
                              <td>
                                <ul class="list-unstyled users-list m-0 avatar-group d-flex align-items-center"><?php print($row['modele'])?>
                                </ul>
                              </td>
                              <td><strong><?php if($reservable == 1){print("Oui");}else{print("Non");}?></strong></td>
                              <?php if($_SESSION['role'] == 1 || $_SESSION['role'] == 2){?>
                              <?php if($reservable == 0){
                                      echo"<td><a href='javascript:CarBookable(".$id.")' class='btn btn-success btn-sm' role='button'><i class='bx bx-calendar-check me-1'></i> Réservable</a></td>";
                                    }else{
                                      echo"<td><a href='javascript:CarNonBookable(".$id.")' class='btn btn-danger btn-sm' role='button'><i class='bx bx-calendar-x me-1'></i> Non réservable</a></td>";
                                    }
                                  } 
                              ?>
                            </tr>
                            <?php
                              }
                            ?>
                          </tbody>
                        </table>
                      </div>

                      <?php
                        /*if(isset($_POST['modify_vehicule_bookable'])){
                            echo"<script>alert('".$_POST['resSelect']." ".$_POST['carSelect']."')</script>";
                            $modify_user = $connect->prepare('UPDATE VEHICULE SET reservable=? WHERE id=?')->execute([$_POST['resSelect'],$_POST['carSelect']]);
                            echo"<script>alert('Disponibilité de la voiture modifiée')</script>";
                            header('Location:vehicule_bookable');
                        }*/
                      ?>
                    <script>
                      /*function loadData(selectedValue) {
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

              <hr class="my-5" />
            <?php $content = ob_get_clean(); ?>
<?php require('template.php'); ?>