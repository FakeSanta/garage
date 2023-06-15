<?php
  $admin = true;
	require 'config.php';
  require 'check.php';
  $title = "Liste des utilisateurs | ".$brend;
  $page = "list_users";
  $menu_deroulant = 1;
?>
<?php ob_start(); ?>
          <div class="content-wrapper">
            <!-- Content -->

            <div class="container-xxl flex-grow-1 container-p-y">
              <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Admin / Gestion utilisateurs / </span>Liste des utilisateurs</h4>

              <!-- Basic Bootstrap Table -->
              <div class="card">
                <h5 class="card-header">Tableau</h5>
                <div class="table-responsive text-nowrap">
                  <?php
                      #$sql = $connect->prepare("SELECT * FROM VEHICULE, VIDANGE, CT WHERE VEHICULE.id = VIDANGE.id_vehicule AND VEHICULE.id = CT.id_vehicule");
                      if($_SESSION['role'] == 4){
                        $sql = $connect->prepare("SELECT * FROM USER ORDER BY role DESC");
                      }else{
                        $sql = $connect->prepare("SELECT * FROM USER EXCEPT SELECT * FROM USER WHERE username='superviseur' ORDER BY role DESC");
                      }
                      $sql->execute();
                    
                      if($sql->rowCount()) {

                  ?>
                  <table class="table">
                    <thead>
                      <tr>
                        <th>Identifiant</th>
                        <th>Rôle</th>
                        <th>Mail</th>
                        <!--<th>Prochaine Vidange</th>
                        <th>Prochain CT</th>-->
                      </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                      <?php
                        while($row = $sql->fetch(PDO::FETCH_ASSOC))
                        {
                      ?>
                      <tr <?php if($row['role'] == 4){?>class="table-primary" <?php }?>>
                        <td><i class="fab fa-angular fa-lg text-danger me-3"></i> <strong><?php print($row['username']) ?></strong></td>
                        <td><?php if($row['role'] == 1 || $row['role'] == 2)
                                    {?><span class="badge bg-label-danger me-1"><?php }
                                    elseif($row['role'] == 4){
                                      ?><span class="badge bg-label-primary me-1"><?php
                                    }?><?php 
                                  if($row['role'] == 0){print("Consultant");} if($row['role'] == 1){print("Administrateur");}if($row['role'] == 2){print("Chef des travaux");}if($row['role'] == 4){print("⚔️Créateur⚔️");}?></span></td>
                        <td><?php print($row['mail'])?></td>
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