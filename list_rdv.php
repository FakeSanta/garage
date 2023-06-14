<?php
  $admin = true;
  require_once('model/functions.php');
	require 'config.php';
  //require 'check.php';
  $title = "Liste des RDV | ".$brend;
  $page = "list_rdv";
?>
<?php ob_start(); ?>
          <div class="content-wrapper">
            <!-- Content -->

            <div class="container-xxl flex-grow-1 container-p-y">
              <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Consulter / </span>Liste des rendez-vous</h4>

              <!-- Basic Bootstrap Table -->
              <div class="card">
                <h5 class="card-header">Tableau</h5>
                <div class="table-responsive text-nowrap">
                  <?php
                      $sql = $connect->prepare('SELECT RDV.id AS rdv_id, RDV.date_rdv, VEHICULE.id AS vehicule_id, VEHICULE.marque, VEHICULE.modele, VEHICULE.immatriculation
                      FROM RDV
                      JOIN VEHICULE ON RDV.id_vehicule = VEHICULE.id
                      WHERE RDV.date_rdv >= CURDATE()
                      AND rdv_effectue = 0
                      AND rdv_annule = 0
                      ORDER BY RDV.date_rdv ASC;
                      ');
                      $sql->execute();                    
                      if($sql->rowCount()) {

                  ?>
                  <table class="table table-striped">
                    <thead>
                      <tr>
                        <th>Date RDV</th>
                        <th>Marque</th>
                        <th>Modele</th>
                        <th>Immatriculation</th>
                        <?php if($_SESSION['role'] == 1 || $_SESSION['role'] == 2){?>
                        <th>Actions</th>
                        <?php }?>
                      </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                      <?php
                        while($row = $sql->fetch(PDO::FETCH_ASSOC))
                        {
                            $new_date = strftime("%d %B %Y",strtotime($row["date_rdv"]));
                            $new_date = str_replace( 
                              array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'),
                              array('Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'),
                              $new_date
                            );
                            $id = $row['rdv_id'];
                      ?>
                      <tr>
                        <td><i class="fab fa-angular fa-lg text-danger me-3"></i> <strong><?php print($new_date) ?></strong></td>
                        <td><?php print($row['marque'])?></td>
                        <td><?php print($row['modele'])?></td>
                        <td><?php print($row['immatriculation'])?></td>
                        <?php if($_SESSION['role'] != 0){
                          echo"<td><a href='javascript:DelRendezVous(".$id.")' class='btn btn-danger btn-sm' role='button'><i class='bx bx-calendar-x me-1'></i> Annuler le RDV</a></td>";
                         } ?>
                      </tr>
                      <?php
                        }
                      ?>
                    </tbody>
                  </table>
                  <?php
                    }
                    if(isset($_POST['del_id'])){
                      $id=$_POST['del_id'];
                      $rdv_pris = $connect->prepare("SELECT V.id AS vehicule_id, V.immatriculation AS vehicule_immatriculation, V.marque AS vehicule_marque, V.modele AS vehicule_modele, V.motorisation AS vehicule_motorisation, V.kilometrage AS vehicule_kilometrage, V.utilitaire AS vehicule_utilitaire, V.rdv_pris AS vehicule_rdv_pris
                      FROM VEHICULE V
                      INNER JOIN RDV R ON V.id = R.id_vehicule
                      WHERE R.id = :id                      
                      ");
                      $rdv_pris->execute(
                        array(
                          'id' => $id
                        )
                      );
                      $id_vehicule = $rdv_pris->fetch(PDO::FETCH_ASSOC);
                      $content = "**".strtoupper($_SESSION['username'])."** - RDV annulé pour le véhicule : **".$id_vehicule['vehicule_immatriculation']."** ".$id_vehicule['vehicule_marque']." ".$id_vehicule['vehicule_modele'];
                      sendDiscordAlert($content);



                      $update_vehicule = $connect->prepare("UPDATE VEHICULE SET rdv_pris = 0 WHERE id = :id");
                      $update_vehicule->execute(
                        array(
                          'id' => $id_vehicule['vehicule_id']
                        )
                      );
                      $update_rdv = $connect->prepare('UPDATE RDV SET rdv_annule = 1 WHERE id = :id');
                      $update_rdv->execute(
                        array(
                          'id' => $id
                        )
                      );
                      header('Location:list_rdv');
                    }
                  ?>
                </div>
              </div>
              <!--/ Basic Bootstrap Table -->

              <hr class="my-5" />
            <?php $content = ob_get_clean(); ?>
<?php require('template.php'); ?>