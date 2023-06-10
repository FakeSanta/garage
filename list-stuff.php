<?php
	require 'config.php';
  if(empty($_SESSION['username'])){
    header("location:login");
  }
  $title = "Liste des équipements | ".$brend;
  $page = "list-stuff";
?>
<?php ob_start(); ?>
          <div class="content-wrapper">
            <!-- Content -->

            <div class="container-xxl flex-grow-1 container-p-y">
              <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Équipement / </span>Liste des équipements</h4>

              <!-- Basic Bootstrap Table -->
              <div class="card">
                <h5 class="card-header">Consommables</h5>
                <div class="table-responsive text-nowrap">
                  <?php
                      #$sql = $connect->prepare("SELECT * FROM VEHICULE, VIDANGE, CT WHERE VEHICULE.id = VIDANGE.id_vehicule AND VEHICULE.id = CT.id_vehicule");
                      $sql = $connect->prepare("SELECT * FROM EQUIPEMENT");
                      $sql->execute();
                    
                      if($sql->rowCount()) {

                  ?>
                  <table class="table table-hover">
                    <thead>
                      <tr>
                        <th>Équipement</th>
                        <th>Marque</th>
                      </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                      <?php
                        while($row = $sql->fetch(PDO::FETCH_ASSOC))
                        {
                      ?>
                      <tr>
                        <td><i class="fab fa-angular fa-lg text-danger me-3"></i> <strong><?php print($row['nom']) ?></strong></td>
                        <td><?php print($row['marque'])?></td>
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
            <!-- / Content -->
            <?php $content = ob_get_clean(); ?>
<?php require('template.php'); ?>