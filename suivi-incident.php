<?php
	require 'config.php';
  if(empty($_SESSION['username'])){
    header("location:login");
  }
  $title = "Suivi des incidents | ".$brend;
  $page = "suivi-incident";
?>
<?php ob_start(); ?>
          <div class="content-wrapper">
            <!-- Content -->

            <div class="container-xxl flex-grow-1 container-p-y">
              <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Incident / </span>Suivi des incidents</h4>

              <!-- Basic Bootstrap Table -->
              <div class="card">
                <h5 class="card-header">Suivi des incidents</h5>
                <div class="table-responsive text-nowrap">
                  <?php
                      #$sql = $connect->prepare("SELECT * FROM VEHICULE, VIDANGE, CT WHERE VEHICULE.id = VIDANGE.id_vehicule AND VEHICULE.id = CT.id_vehicule");
                      $sql = $connect->prepare("SELECT * FROM VEHICULE, INCIDENT WHERE VEHICULE.id = INCIDENT.id_vehicule ORDER BY id_vehicule DESC");
                      $sql->execute();
                  ?>
                  <table class="table table-hover">
                    <thead>
                      <tr>
                        <th>Véhicule</th>
                        <th>Remarque</th>
                        <th>Date</th>
                        <th>Observateur</th>
                        <!--<th>Prochain CT</th>-->
                      </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                      <?php
                        while($row = $sql->fetch(PDO::FETCH_ASSOC))
                        {
                            $new_date = strftime("%d %B %Y",strtotime($row["date_incident"]));
                            $new_date = str_replace( 
                              array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'),
                              array('Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'),
                              $new_date
                            );
                      ?>
                      <tr>
                        <td><i class="fab fa-angular fa-lg text-danger me-3"></i> <strong><?php print($row['immatriculation'].' '.$row['marque'].' '.$row['modele']) ?></strong></td>
                        <td style="white-space: pre-wrap;"><?php print($row['remarque'])?></td>
                        <td><?php print($new_date)?></td>
                        <td><strong><?php print($row['observateur'])?></strong></td>
                      </tr>
                      <?php
                        }
                      ?>
                    </tbody>
                  </table>
                </div>
              </div>
              <!--/ Basic Bootstrap Table -->

              <hr class="my-5" />
            <!-- / Content -->
            <?php $content = ob_get_clean(); ?>
<?php require('template.php'); ?>