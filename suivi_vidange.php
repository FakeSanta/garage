<?php
	require 'config.php';
  if(empty($_SESSION['username'])){
    header("location:login");
  }
  $title = "Suivi des vidanges | ".$brend;
  $page = "suivi_vidange";
?>
<?php ob_start(); ?>
          <div class="content-wrapper">
            <!-- Content -->

            <div class="container-xxl flex-grow-1 container-p-y">
              <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Suivi / </span>Suivi des vidanges</h4>

              <!-- Basic Bootstrap Table -->
              <div class="card">
                <h5 class="card-header">Tableau</h5>
                <div class="table-responsive text-nowrap">
                  <?php
                      $sql = $connect->prepare("SELECT * FROM VEHICULE, VIDANGE WHERE VEHICULE.id = VIDANGE.id_vehicule ORDER BY prochaine_date_vidange ASC;");
                      $sql->execute();
                    
                      if($sql->rowCount()) {

                  ?>
                  <table class="table table-hover">
                    <thead>
                      <tr>
                        <th>Immatriculation</th>
                        <th>Marque</th>
                        <th>Modèle</th>
                        <th>Motorisation</th>
                        <th>Date prochaine vidange</th>
                      </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                      <?php
                        while($row = $sql->fetch(PDO::FETCH_ASSOC))
                        {
                          $time_spend = false;
                          $year_vidange = substr($row['prochaine_date_vidange'], 0,4);
                          $month_vidange = substr($row['prochaine_date_vidange'], 5,2);
                          $day_vidange = substr($row['prochaine_date_vidange'], 8,2);
                          $date_vidange = $day_vidange."-".$month_vidange."-".$year_vidange;
                          switch ($month_vidange){
                            case "01": 
                              $month_display = "Janvier";
                              break;
                            case "02": 
                              $month_display = "Février";
                              break;
                            case "03": 
                              $month_display = "Mars";
                              break;
                            case "04": 
                              $month_display = "Avril";
                              break;
                            case "05": 
                              $month_display = "Mai";
                              break;
                            case "06": 
                              $month_display = "Juin";
                              break;
                            case "07": 
                              $month_display = "Juillet";
                              break;
                            case "08": 
                              $month_display = "Août";
                              break;
                            case "09": 
                              $month_display = "Septembre";
                              break;
                            case "10": 
                              $month_display = "Octobre";
                              break;
                            case "11": 
                              $month_display = "Novembre";
                              break;
                            case "12": 
                              $month_display = "Décembre";
                              break;
                        }
                          $date_vidange_display = $day_vidange." ".$month_display." ".$year_vidange;
                          $dateVidangestr = strtotime($date_vidange);
                          $dateNowstr = strtotime(date("d-m-Y"));

                          if($dateNowstr > $dateVidangestr){
                            $time_spend =true;
                          }
                          
                      ?>
                      <tr <?php if($time_spend == true){?>class="table-danger"<?php } ?>>
                        <td><i class="fab fa-angular fa-lg text-danger me-3"></i> <strong><?php print($row['immatriculation']) ?></strong></td>
                        <td><?php print($row['marque'])?></td>
                        <td>
                          <ul class="list-unstyled users-list m-0 avatar-group d-flex align-items-center"><?php print($row['modele'])?>
                          </ul>
                        </td>
                        <td><span class="badge bg-label-<?php if($row['motorisation'] == 'Diesel'){print('warning');}else{print('success');}?> me-1"><?php print($row['motorisation']) ?></span></td>
                        <td><?php if(!empty($row['prochaine_date_vidange'])){print($date_vidange_display);}else{print('----');}?></span></td>
                      </tr>
                      <?php
                        }
                      ?>
                    </tbody>
                  </table>
                  <?php
                    }
                  ?>
                </div>
              </div>
              <!--/ Basic Bootstrap Table -->

              <hr class="my-5" />
<?php $content = ob_get_clean(); ?>
<?php require('template.php'); ?>