<?php
	require 'config.php';
  if(empty($_SESSION['username'])){
    header("location:login");
  }
  $title = "Historique | Decomble";
  $page = "historique";
?>
<?php ob_start(); ?>
          <div class="content-wrapper">
            <!-- Content -->

            <div class="container-xxl flex-grow-1 container-p-y">
              <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Consulter / </span>Historique des vidanges et contrôles techniques</h4>
              <select class="form-select" name="selectSort" id="selectSort">
                  <option value="">Du + récent au + ancien</option>
                  <option value="">Du + ancien au + récent</option>
                </select>
                <hr class="my-5" />
              <!-- Basic Bootstrap Table -->
              <div class="card">
                <h5 class="card-header">Tableau</h5>
                <div class="table-responsive text-nowrap">
                  <?php
                      $sql = $connect->prepare("SELECT * FROM VEHICULE, HISTORIQUE WHERE VEHICULE.id = HISTORIQUE.id_vehicule ORDER BY HISTORIQUE.id DESC");
                      $sql->execute();
                    
                      if($sql->rowCount()) {

                  ?>
                  <table class="table" id="car_selected">
                    <thead>
                      <tr>
                        <th>ID</th>
                        <th>Immatriculation</th>
                        <th>Marque</th>
                        <th>Modèle</th>
                        <th>Motorisation</th>
                        <th>dernière vidange effectuée</th>
                        <th>dernier CT effectué</th>
                        <th>Commentaire</th>
                      </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                      <?php
                        while($row = $sql->fetch(PDO::FETCH_ASSOC))
                        {
                          $year_ct = substr($row['date_ct'], 0,4);
                          $month_ct = substr($row['date_ct'], 5,2);
                          $day_ct = substr($row['date_ct'], 8,2);
                          switch ($month_ct){
                              case "01": 
                                $month_ct_display = "Janvier";
                                break;
                              case "02": 
                                $month_ct_display = "Février";
                                break;
                              case "03": 
                                $month_ct_display = "Mars";
                                break;
                              case "04": 
                                $month_ct_display = "Avril";
                                break;
                              case "05": 
                                $month_ct_display = "Mai";
                                break;
                              case "06": 
                                $month_ct_display = "Juin";
                                break;
                              case "07": 
                                $month_ct_display = "Juillet";
                                break;
                              case "08": 
                                $month_ct_display = "Août";
                                break;
                              case "09": 
                                $month_ct_display = "Septembre";
                                break;
                              case "10": 
                                $month_ct_display = "Octobre";
                                break;
                              case "11": 
                                $month_ct_display = "Novembre";
                                break;
                              case "12": 
                                $month_ct_display = "Décembre";
                                break;
                          }
                          $date_ct_display = $day_ct." ".$month_ct_display." ".$year_ct;

                          $year_vidange = substr($row['date_vidange'], 0,4);
                          $month_vidange = substr($row['date_vidange'], 5,2);
                          $day_vidange = substr($row['date_vidange'], 8,2);
                          switch ($month_vidange){
                              case "01": 
                                $month_vidange_display = "Janvier";
                                break;
                              case "02": 
                                $month_vidange_display = "Février";
                                break;
                              case "03": 
                                $month_vidange_display = "Mars";
                                break;
                              case "04": 
                                $month_vidange_display = "Avril";
                                break;
                              case "05": 
                                $month_vidange_display = "Mai";
                                break;
                              case "06": 
                                $month_vidange_display = "Juin";
                                break;
                              case "07": 
                                $month_vidange_display = "Juillet";
                                break;
                              case "08": 
                                $month_vidange_display = "Août";
                                break;
                              case "09": 
                                $month_vidange_display = "Septembre";
                                break;
                              case "10": 
                                $month_vidange_display = "Octobre";
                                break;
                              case "11": 
                                $month_vidange_display = "Novembre";
                                break;
                              case "12": 
                                $month_vidange_display = "Décembre";
                                break;
                          }
                          $date_vidange_display = $day_vidange." ".$month_vidange_display." ".$year_vidange;
                      ?>
                      <tr>
                        <td><?php print($row['id']) ?></td>
                        <td><i class="fab fa-angular fa-lg text-danger me-3"></i> <strong><?php print($row['immatriculation']) ?></strong></td>
                        <td><?php print($row['marque'])?></td>
                        <td>
                          <ul class="list-unstyled users-list m-0 avatar-group d-flex align-items-center"><?php print($row['modele'])?>
                          </ul>
                        </td>
                        <td><span class="badge bg-label-<?php if($row['motorisation'] == 'Diesel'){print('warning');}else{print('success');}?> me-1"><?php print($row['motorisation']) ?></span></td>
                        <td><?php if(!empty($row['date_vidange'])){print($date_vidange_display);}else{print('----');}?></td>
                        <td>
                          <ul class="list-unstyled users-list m-0 avatar-group d-flex align-items-center"><?php if(!empty($row['date_ct'])){print($date_ct_display);}else{print('----');}?>
                          </ul>
                        </td>
                        <td>
                          <ul class="list-unstyled users-list m-0 avatar-group d-flex align-items-center"><?php print($row['commentaire'])?>
                          </ul>
                        </td>
                      </tr>
                      <?php
                        }
                      ?>
                    </tbody>
                  </table>
                  <?php
                    }
                  ?>
                  <script>
                    $(document).ready(function(){
                      $("#selectSort").on("change", function(){
                        var selectedValue = $(this).val();
                        $.ajax({
                          url: "sort.php",
                          type: "POST",
                          data: {id: selectedValue},
                          success: function(response){
                            
                              var data = JSON.parse(response);
                              var html = '';
                              $.each(data, function(key, value) {
                                html += "<tr>";
                                html += "<td>"+value.id_histo+"</td>";
                                html += "<td><strong>"+value.immat+"</strong></td>";
                                html += "<td>"+value.marque+"</td>";
                                html += "<td>"+value.modele+"</td>";
                                html += "<td>"+value.motorisation+"</td>";
                                html += "<td>" + (!empty(value.date_vidange) ? value.date_vidange_display : '----') + "</td>";
                                html += "<td>" + (!empty(value.date_ct) ? value.date_ct_display : '----') + "</td>";
                                html += "<td>"+value.commentaire+"</td>";
                                html += "</tr> ";
                              });
                              $("#car_selected tbody").html(html);
                            
                          }
                        });
                      });
                    });
                  </script>
                </div>
              </div>
              <!--/ Basic Bootstrap Table -->

              <hr class="my-5" />
            <?php $content = ob_get_clean(); ?>
<?php require('template.php'); ?>