<?php
  setlocale(LC_TIME, 'fr_FR');
  date_default_timezone_set('Europe/Paris');
	require 'config.php';
  if(empty($_SESSION['username'])){
    header("location:login");
  }
  $title = "Suivi des CT | ".$brend;
  $page = "suivi_ct";
  $fakeDate = '2023-06-12';

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
                      $sql = $connect->prepare("SELECT
                      VEHICULE.id AS vehicule_id,
                      VEHICULE.immatriculation AS vehicule_immatriculation,
                      VEHICULE.marque AS vehicule_marque,
                      VEHICULE.modele AS vehicule_modele,
                      VEHICULE.motorisation AS vehicule_motorisation,
                      VEHICULE.kilometrage AS vehicule_kilometrage,
                      VEHICULE.utilitaire AS vehicule_utilitaire,
                      VEHICULE.rdv_pris AS vehicule_rdv_pris,
                      CT.id AS ct_id,
                      CT.id_vehicule AS ct_id_vehicule,
                      CT.date_ct AS ct_date_ct,
                      CT.prochaine_date_ct AS ct_prochaine_date_ct
                  FROM
                      VEHICULE,
                      CT
                  WHERE
                      VEHICULE.id = CT.id_vehicule
                  ORDER BY
                      CT.prochaine_date_ct ASC;
                  ");
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
                        <th>date d'expiration le</th>
                        <th>date rdv</th>
                        <?php if($_SESSION['role'] != 0){?>
                        <th>valider</th>
                        <?php } ?>
                      </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                      <?php
                        while($row = $sql->fetch(PDO::FETCH_ASSOC))
                        {
                          $rdv_taken = false;
                          $time_spend = false;
                          $date = '';
                          $new_date_ct = strftime("%d %B %Y",strtotime($row['ct_prochaine_date_ct']));
                          $new_date_ct = str_replace( 
                            array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'),
                            array('Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'),
                            $new_date_ct
                          );
                          $dateCtstr = strtotime($row['ct_prochaine_date_ct']);
                          $dateNowstr = strtotime(date("d-m-Y"));
                          if($dateNowstr > $dateCtstr){
                            $time_spend = true;
                          }
                          $date_rdv = null;
                          $date_format = null;
                          if($row['vehicule_rdv_pris'] == '1'){
                            $getDate = $connect->prepare("SELECT * FROM RDV WHERE id_vehicule = :id_vehicule AND rdv_annule = 0 AND rdv_effectue = 0");
                            $getDate->execute(['id_vehicule' => $row['ct_id_vehicule']]);
                            if($getDate->rowCount()){
                              $date_rdv = $getDate->fetch(PDO::FETCH_ASSOC);
                              $rdv_taken = true;
                              $date_format = strftime("%d %B %Y",strtotime($date_rdv['date_rdv']));
                              $date_format = str_replace( 
                                array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'),
                                array('Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'),
                                $date_format
                              );
                            }
                          }                            
                            $today = date('Y-m-d');
                            $weeksAfter = date('Y-m-d', strtotime('+14 days'));
                            //$today = $fakeDate;
                        
                            if ($rdv_taken == true && $date_rdv['date_rdv'] > $today) {
                                $blue = true;
                                $orange = false;
                                $red = false;
                                $vert = false;
                            } elseif ($rdv_taken == true && $date_rdv['date_rdv'] <= $today ) {
                                $blue = false;
                                $orange = false;
                                $red = false;
                                $vert = true;
                            } elseif ($rdv_taken == false && $time_spend == true) {
                                $blue = false;
                                $orange = false;
                                $red = true;
                                $vert = false;
                            }elseif($row['ct_prochaine_date_ct'] <= $weeksAfter){
                                $blue = false;
                                $orange = true;
                                $red = false;
                                $vert = false;
                            }else{
                                $blue = false;
                                $orange = false;
                                $red = false;
                                $vert = false;
                            }
                            
                      ?>
                      <tr <?php if($red == true){?>class="table-danger"<?php }elseif($blue == true){?>class="table-info"<?php }elseif($vert == true){ ?>class="table-success"<?php }elseif($orange == true){ ?>class="table-warning"<?php } ?>>
                        <td><i class="fab fa-angular fa-lg text-danger me-3"></i> <strong><?php print($row['vehicule_immatriculation']) ?></strong></td>
                        <td><?php print($row['vehicule_marque'])?></td>
                        <td>
                          <ul class="list-unstyled users-list m-0 avatar-group d-flex align-items-center"><?php print($row['vehicule_modele'])?>
                          </ul>
                        </td>
                        <td><span class="badge bg-label-<?php if($row['vehicule_motorisation'] == 'Diesel'){print('warning');}elseif($row['vehicule_motorisation'] == 'Electrique'){print('info');}elseif($row['vehicule_motorisation'] == 'Hybride'){print('primary');}else{print('success');}?> me-1"><?php print($row['vehicule_motorisation']) ?></span></td>
                        <td><?php print($new_date_ct);?></td>
                        <td><?php print($date_format) ?></td>
                        <?php if($_SESSION['role'] != 0){?>
                        <td><?php if($rdv_taken == true && $date_rdv['date_rdv'] <= $today){ ?> 
                          <form method="POST" action="register_ct">    
                              <button type="submit" class='btn btn-success btn-sm' name="del_id" value="<?php print($row['vehicule_id']) ?>">
                                <i class="bx bx-calendar-check me-1"></i> CT Effectué
                              </button>
                          </form><?php } ?>
                        </td>
                        <?php } ?>
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