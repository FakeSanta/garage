<?php
	require 'config.php';
  if(empty($_SESSION['username'])){
    header("location:login");
  }
  $title = "Suivi des CT | Decomble";
  $page = "suivi_ct";
  $fakeDate = '2023-05-20';

?>
<?php ob_start(); ?>
          <div class="content-wrapper">
            <!-- Content -->

            <div class="container-xxl flex-grow-1 container-p-y">
              <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Suivi / </span>Suivi des contrôles techniques</h4>

              <!-- Basic Bootstrap Table -->
              <div class="card">
                <h5 class="card-header">Tableau</h5>
                <div class="table-responsive text-nowrap">
                  <?php
                      $sql = $connect->prepare("SELECT * FROM VEHICULE, CT WHERE VEHICULE.id = CT.id_vehicule ORDER BY prochaine_date_ct ASC");
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
                        <th>prochain date échéance</th>
                        <th>date rdv</th>
                        <th>valider</th>
                      </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                      <?php
                        while($row = $sql->fetch(PDO::FETCH_ASSOC))
                        {
                          $rdv_taken = false;
                          $time_spend = false;
                          $date = '';
                          $new_date_ct = strftime("%d %B %Y",strtotime($row['prochaine_date_ct']));

                          $dateCtstr = strtotime($new_date_ct);
                          $dateNowstr = strtotime(date("d-m-Y"));
                          if($dateNowstr > $dateCtstr){
                            $time_spend = true;
                          }
                          $date_rdv = null;
                          $date_format = null;
                          if($row['rdv_pris'] == '1'){
                            $getDate = $connect->prepare("SELECT * FROM RDV WHERE id_vehicule = :id_vehicule AND date_rdv > CURDATE()");
                            $getDate->execute(['id_vehicule' => $row['id_vehicule']]);
                            $date_rdv = $getDate->fetch(PDO::FETCH_ASSOC);
                            $rdv_taken = true;
                            $date_format = strftime("%d %B %Y",strtotime($date_rdv['date_rdv']));

                          }
                            //$today = date('d-m-Y');
                            $today = $fakeDate;
                        
                            if ($rdv_taken == true && $date_rdv['date_rdv'] > $today) {
                                $blue = false;
                                $orange = true;
                                $red = false;
                            } elseif ($rdv_taken == true && $date_rdv['date_rdv'] < $today) {
                                $blue = true;
                                $orange = false;
                                $red = false;
                            } elseif ($rdv_taken == false && $time_spend == true) {
                                $blue = false;
                                $orange = false;
                                $red = true;
                            } else {
                                $blue = false;
                                $orange = false;
                                $red = false;
                            }
                            
                        
                      ?>
                      <tr <?php if($red == true){?>class="table-danger"<?php }elseif($blue == true){?>class="table-info"<?php }elseif($orange == true){ ?>class="table-warning"<?php } ?>>
                        <td><i class="fab fa-angular fa-lg text-danger me-3"></i> <strong><?php print($row['immatriculation']) ?></strong></td>
                        <td><?php print($row['marque'])?></td>
                        <td>
                          <ul class="list-unstyled users-list m-0 avatar-group d-flex align-items-center"><?php print($row['modele'])?>
                          </ul>
                        </td>
                        <td><span class="badge bg-label-<?php if($row['motorisation'] == 'Diesel'){print('warning');}elseif($row['motorisation'] == 'Electrique'){print('info');}elseif($row['motorisation'] == 'Hybride'){print('primary');}else{print('success');}?> me-1"><?php print($row['motorisation']) ?></span></td>
                        <td><?php print($new_date_ct);?></td>
                        <td><?php print($date_format) ?> 
                        <td><?php if($rdv_taken == true && $date_rdv['date_rdv'] < $today){ ?> 
                          <form method="POST" action="">    
                              <button type="submit" class="btn p-0 dropdown-toggle hide-arrow" name="del_id" value="<?php print($row['rdv_id']) ?>">
                                <i class="bx bx-calendar-x me-1"></i> CT Effectué
                              </button>
                          </form><?php } ?>
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
                </div>
              </div>
              <!--/ Basic Bootstrap Table -->

              <hr class="my-5" />
            <?php $content = ob_get_clean(); ?>
<?php require('template.php'); ?>