<?php
  $admin = false; 
  require("model/functions.php");
	require 'config.php';
  require 'check.php';
  $title = "Accueil | ".$brend;
  $page = "index";
?>
<?php ob_start(); ?>
<div class="content-wrapper">
    <!-- Content -->

    <div class="container-xxl flex-grow-1 container-p-y">
      <div class="row">
        <div class="col-lg-8 mb-4 order-0">
          <div class="card">
            <div class="d-flex align-items-end row">
              <div class="col-sm-7">
                <div class="card-body">
                  <h5 class="card-title text-primary">Bonjour, <span class="fw-bold"><?php print(ucfirst($_SESSION['username']))?></span></h5>
                  <p class="mb-4">
                    Vous êtes connecté en tant que <span class="fw-bold"><?php if($_SESSION['role'] == 1){print('Administrateur');}elseif($_SESSION['role'] == 0){print('Consultant');}elseif($_SESSION['role'] == 2){print('Chef des travaux');}elseif($_SESSION['role'] == 4){print("⚔️Créateur⚔️");}?></span>
                  </p>
                </div>
              </div>
              <div class="col-sm-5 text-center text-sm-left">
                <div class="card-body pb-0 px-0 px-md-4">
                  <img
                    src="../assets/img/illustrations/man-with-laptop-light.png"
                    height="140"
                    alt="View Badge User"
                    data-app-dark-img="illustrations/man-with-laptop-dark.png"
                    data-app-light-img="illustrations/man-with-laptop-light.png"
                  />
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-4 col-md-4 order-1">
          <div class="row">
            <div class="col-lg-6 col-md-12 col-6 mb-4">
              <div class="card">
                <a href="table_parc">
                <div class="card-body">
                  <div class="card-title d-flex align-items-start justify-content-between">
                    <div class="avatar flex-shrink-0">
                      <i class="bx bx-car me-1" id="oui"></i>
                    </div>
                  </div>
                  <span class="fw-semibold d-block mb-1">Nb de véhicule</span>
                    <?php
                      $sql = $connect->prepare("SELECT COUNT(*) FROM VEHICULE");
                      $sql->execute();
                      $nb_car = $sql->fetch(PDO::FETCH_COLUMN);
                    ?>
                  <h3 class="card-title mb-2"><?php echo $nb_car; ?></h3>
                </div>
                </a>
              </div>
            </div>
            <div class="col-lg-6 col-md-12 col-6 mb-4">
              <div class="card">
                <a href="suivi_ct">
                <div class="card-body">
                  <div class="card-title d-flex align-items-start justify-content-between">
                    <div class="avatar flex-shrink-0">
                      <i class="bx bx-wrench me-1" id="oui"></i>
                    </div>
                    <!--
                    <div class="dropdown">
                      <button
                        class="btn p-0"
                        type="button"
                        id="cardOpt6"
                        data-bs-toggle="dropdown"
                        aria-haspopup="true"
                        aria-expanded="false"
                      >
                        <i class="bx bx-dots-vertical-rounded"></i>
                      </button>
                      <div class="dropdown-menu dropdown-menu-end" aria-labelledby="cardOpt6">
                        <a class="dropdown-item" href="javascript:void(0);">View More</a>
                        <a class="dropdown-item" href="javascript:void(0);">Delete</a>
                      </div>
                    </div>
                    -->
                  </div>
                  <span>Prochaine CT à expiré</span>
                  <?php
                      $sql = $connect->prepare("SELECT prochaine_date_ct FROM CT WHERE prochaine_date_ct = (SELECT min(prochaine_date_ct) FROM CT) ORDER BY prochaine_date_ct DESC;");
                      $sql->execute();
                      $value = $sql->fetch(PDO::FETCH_COLUMN);
                      $new_date_ct = strftime("%d %B %Y",strtotime($value));
                      $new_date_ct = str_replace( 
                        array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'),
                        array('Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'),
                        $new_date_ct
                      );
                  ?>
                  <h3 class="card-title text-nowrap mb-1"><?php echo $new_date_ct;?></h3>
                </div>
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- Deuxieme Ligne -->
      <div class="row">
        <!-- Grande carte gauche-->
        <div class="col-lg-8 mb-4 order-0">
          <div class="card">
            <div class="d-flex align-items-end row">
              <div class="col-sm-12">
                <div class="card-body">
                  <h5 class="card-title text-primary">Contrôles techniques en approche : </h5>
                  <div class="table-responsive text-nowrap">
                    <?php
                      $check_ct = $connect->prepare("SELECT * FROM VEHICULE, CT WHERE VEHICULE.id = CT.id_vehicule AND rdv_pris = 0 AND prochaine_date_ct < DATE_ADD(CURDATE(), INTERVAL 60 DAY) GROUP BY VEHICULE.id ORDER BY prochaine_date_ct ASC");
                      $check_ct->execute();
                      if($check_ct->rowCount()){
                      
                    ?>
                    <table class="table table-hover">
                      <thead>
                        <tr>
                          <th>Jours</th>
                          <th>Véhicule</th>
                          <?php if($_SESSION['role'] != 0){?>
                          <th>Prise de RDV</th>
                          <?php } ?>
                        </tr>
                      </thead>
                      <tbody class="table-border-bottom-0">
                        <?php
                          while($row = $check_ct->fetch(PDO::FETCH_ASSOC))
                          {
                              $next_ct = $row['prochaine_date_ct'];
                              $count_days_query = $connect->prepare("SELECT DATEDIFF('$next_ct', CURDATE()) AS diff_date");
                              $count_days_query->execute();
                              $days_diff = $count_days_query->fetch(PDO::FETCH_ASSOC);
                              if($days_diff['diff_date'] > 0){
                                $id = $row['id_vehicule'];
                        ?>
                        <tr <?php if($days_diff['diff_date'] <= 14){?>class="table-warning"<?php }?>>
                          <form method="POST" action="register_rdv.php?id=<?php echo $id; ?>">    
                            <td><i class="fab fa-angular fa-lg text-danger me-3"></i><?php print("<b>".$days_diff['diff_date']." jours</b> restant pour le contrôle technique de :") ?></td>
                            <td><b><?php print($row['modele']."</b> | ".$row['immatriculation']) ?></td>
                            <?php if($_SESSION['role'] != 0){?>
                            <td>
                              <button type="submit" class="btn p-0 dropdown-toggle hide-arrow" name="register_rdv" value="<?php print($row['id_vehicule']) ?>">
                                <i class="bx bx-calendar me-1" id="<?php print($row['immatriculation']) ?>"></i> RDV
                              </button>
                            </td>
                            <?php } ?>
                          </form>
                        </tr>
                        <?php
                              }elseif($days_diff['diff_date'] <=0){
                                $id = $row['id_vehicule'];
                                ?>
                                  <tr class="table-danger">
                                    <form method="POST" action="register_rdv.php?id=<?php echo $id; ?>">    
                                      <td><i class="fab fa-angular fa-lg text-danger me-3"></i><?php print("Contrôle technique périmé de <b>".abs($days_diff['diff_date'])." jours </b>pour :") ?></td>
                                      <td><b><?php print($row['modele']."</b> | ".$row['immatriculation']) ?></td>
                                      <?php if($_SESSION['role'] != 0){?>
                                      <td>
                                        <button type="submit" class="btn p-0 dropdown-toggle hide-arrow" name="register_rdv" value="<?php print($id_vehicule) ?>">
                                          <i class="bx bx-calendar me-1" id="<?php print($row['immatriculation']) ?>"></i> RDV
                                        </button>
                                      </td>
                                      <?php } ?>
                                    </form>
                                  </tr>
                                <?php
                              }
                            }                          
                        ?>
                      </tbody>
                    </table>
                    <?php
                      }else{
                        echo'<p class="mb-4">';
                          echo'Aucun rendez-vous à prendre avant les 2 prochains mois';
                        echo'</p>';
                      }
                    ?>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- Petite carte milieu -->
        <div class="col-lg-4 col-md-4 order-1">
          <div class="row">
            <div class="col-lg-6 col-md-12 col-6 mb-4">
              <div class="card">
                <a href="suivi_ct">
                <div class="card-body">
                    <?php
                      $fakeDate = ('2023-06-13');
                      $today = date('Y-m-d');
                      //$today = $fakeDate;

                      $getDate = $connect->prepare("SELECT * FROM RDV WHERE date_rdv <= :today AND rdv_effectue = 0 AND rdv_annule = 0");
                      $getDate->execute(array('today' => $today));
                      if($getDate->rowCount()){
                    ?>
                  <div class="card-title d-flex align-items-start justify-content-between">
                    <span class="fw-semibold d-block mb-1">CT à confirmer !</span>
                  </div>
                    <br>
                  <h3 class="card-title mb-2">
                    <button type="button" class="btn btn-danger">Valider</button>
                  </h3>
                  <?php
                      }else{
                  ?>
                  <br>
                  <p class="mb-4">
                    <i>Pas de contrôle technique à valider</i>
                  </p>
                  <?php
                      }
                  ?>
                </div>
                </a>
              </div>
            </div>
            <!-- Petite carte milieu -->
            <div class="col-lg-6 col-md-12 col-6 mb-4">
              <div class="card">
                <a href="suivi_plein">
                <div class="card-body">
                  <div class="card-title d-flex align-items-start justify-content-between">
                    <div class="avatar flex-shrink-0">
                      <i class="bx bx-money me-1" id="oui"></i>
                    </div>
                  </div>
                    <?php
                      $sql = $connect->prepare("SELECT ROUND(SUM(cout_plein),2) AS total_cout_annee_en_cours FROM CARBURANT WHERE YEAR(date_plein) = YEAR(CURDATE())");
                      $sql->execute();
                      $cout_plein = $sql->fetch(PDO::FETCH_COLUMN);
                    ?>
                  <span>Cout carburant <?php echo date('Y')?></span>
                  <h3 class="card-title text-nowrap mb-1"><?php echo str_replace('.',',',$cout_plein); ?>€</h3>
                </div>
              </div>
              </a>
            </div>
          </div>
        </div>
      </div>
      <!-- Troisième Ligne -->
      <?php if($_SESSION['role'] != 0){ ?>
      <div class="row">
        <!-- Grande carte gauche-->
        <div class="col-lg-12 mb-4 order-0">
          <div class="card">
            <div class="d-flex align-items-end row">
              <div class="col-sm-12">
                <div class="card-body">
                  <h5 class="card-title text-primary">Réservation en attente : </h5>
                  <div class="table-responsive text-nowrap">
                    <?php
                      listAllWaitingIndex();
                    ?>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <?php
        }
      ?>
      <?php if($_SESSION['role'] == 0){ ?>
      <div class="row">
        <!-- Grande carte gauche-->
        <div class="col-lg-12 mb-4 order-0">
          <div class="card">
            <div class="d-flex align-items-end row">
              <div class="col-sm-12">
                <div class="card-body">
                  <h5 class="card-title text-primary">Statut de vos réservations : </h5>
                  <div class="table-responsive text-nowrap">
                    <?php
                      listAllWaitingIndexCusto($_SESSION['id']);
                    ?>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <?php
        }
      ?>
    </div>
    <div class="content-backdrop fade"></div>
  </div>
<?php $content = ob_get_clean(); ?>
<?php require('template.php'); ?>