<?php
	require 'config.php';
  if(empty($_SESSION['username'])){
    header("location:login");
  }
  $title = "État du parc | ".$brend;
  $page = "table_parc";
?>
<?php ob_start(); ?>
          <div class="content-wrapper">
            <!-- Content -->

            <div class="container-xxl flex-grow-1 container-p-y">
              <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Consulter / </span>État du parc</h4>

              <!-- Basic Bootstrap Table -->
              <div class="card">
                <h5 class="card-header">Parc Auto <?php echo $brend ?></h5>
                <div class="table-responsive text-nowrap">
                  <?php
                      #$sql = $connect->prepare("SELECT * FROM VEHICULE, VIDANGE, CT WHERE VEHICULE.id = VIDANGE.id_vehicule AND VEHICULE.id = CT.id_vehicule");
                      $sql = $connect->prepare("SELECT * FROM VEHICULE ORDER BY immatriculation ASC");
                      $sql->execute();
                    
                      if($sql->rowCount()) {

                  ?>
                  <table class="table table-hover table-striped">
                    <thead>
                      <tr>
                        <th>Immatriculation</th>
                        <th>Marque</th>
                        <th>Modèle</th>
                        <th>Motorisation</th>
                        <?php if($_SESSION['role'] != 0){?>
                        <th>Modifier</th>
                        <th>Supprimer</th>
                        <?php } ?>
                        <!--<th>Prochain CT</th>-->
                      </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                      <?php
                        while($row = $sql->fetch(PDO::FETCH_ASSOC))
                        {
                          $id = $row['id'];
                      ?>
                      <tr>
                        <td><i class="fab fa-angular fa-lg text-danger me-3"></i> <strong><?php print($row['immatriculation']) ?></strong></td>
                        <td><?php print($row['marque'])?></td>
                        <td>
                          <ul class="list-unstyled users-list m-0 avatar-group d-flex align-items-center"><?php print($row['modele'])?>
                          </ul>
                        </td>
                        <td><span class="badge bg-label-<?php if($row['motorisation'] == 'Diesel'){print('warning');}elseif($row['motorisation'] == 'Essence'){print('success');}elseif($row['motorisation'] == 'Electrique'){print('info');}else{print('primary');}?> me-1"><?php print($row['motorisation']) ?></span></td>
                        <?php if($_SESSION['role'] != 0){?>
                        <form method="POST" action="edit_car.php?id=<?php echo $row['id'];?>">    
                          <td>
                              <button type="submit" class="btn p-0 dropdown-toggle hide-arrow" name="del_id" value="<?php print($row['id']) ?>">
                                <i class="bx bx-pencil me-1" id="<?php print($row['immatriculation']) ?>"></i> Modifier
                              </button>
                          </td>
                        </form>
                        <form method="POST" action="">    
                          <td>
                            <?php
                              echo"<a href='javascript:DeleteCar(".$id.")' class='btn p-0 dropdown-toggle hide-arrow' role='button'><i class='bx bx-trash me-1'></i> Supprimer</a>";
                            ?>
                          </td>
                        </form>
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
            <!-- / Content -->
            <?php $content = ob_get_clean(); ?>
<?php require('template.php'); ?>