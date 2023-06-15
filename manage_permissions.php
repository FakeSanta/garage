<?php
  $admin = true;
	require 'config.php';
  require 'check.php';
  $title = "Gérer les permissions | ".$brend;
  $page = "manage_permissions";
  $menu_deroulant = 1;
?>
<?php ob_start(); ?>
          <div class="content-wrapper">
            <!-- Content -->

            <div class="container-xxl flex-grow-1 container-p-y">
              <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Admin / Gestion utilisateurs / </span>Gérer les permissions</h4>

              <!-- Basic Bootstrap Table -->
              <!-- Form controls -->
              <div class="col-md-6">
                  <div class="card mb-4">
                    <h5 class="card-header"></h5>
                    <div class="card-body">
                      <form action="" method="POST">
                      <div class="mb-3">
                          <label for="userSelect" class="form-label">utilisateur |  Role</label>
                          <select class="form-select" name="userSelect">
                          <?php
                            $fill_select = $connect->prepare('SELECT * FROM USER WHERE id != :id EXCEPT SELECT * FROM USER WHERE username ="superviseur" ORDER BY username ASC');
                            $fill_select->execute(array('id' => $_SESSION['id']));
                            while($row = $fill_select->fetch(PDO::FETCH_ASSOC))
                            {
                              if($row['role'] == 0){
                                $role = "Consultant";
                              }elseif($row['role'] == 1){
                                $role = "Administrateur";
                              }elseif($row['role'] == 2){
                                $role = "Chef des travaux";
                              }
                          ?>
                            <option value="<?php print($row['id'])?>"><?php print($row['username'].' | '.$role)?></option>
                          <?php
                            }
                          ?>
                          </select>
                        </div>
                        <div class="mb-3">
                            <label for="roleSelect" class="form-label">Changer en :</label>
                            <select class="form-select" name="roleSelect">
                                <option selected>------------</option>
                                <option value="0">Consultant</option>
                                <option value="1">Administrateur</option>
                                <option value="2">Chef des travaux</option>
                            </select>
                        </div>
                        <hr class="my-4" />
                        <div class="d-grid gap-2 col-lg-6 mx-auto">
                          <button class="btn btn-primary btn-lg" name="modify_permission_user" type="submit">Modifier</button>
                        </div>
                      </form>

                      <?php
                        if(isset($_POST['modify_permission_user'])){
                          if(isset($_POST["roleSelect"]) and $_POST["date_vidange"] !=''){
                            echo"<script>alert('Veuillez sélectionner un rôle')</script>";
                          }else{
                            $role = $_POST['roleSelect'];
                            $id_user = $_POST['userSelect'];
                            $user_picker = $connect->prepare('SELECT * FROM USER WHERE username = :username');
                            $user_picker->execute(['username' => $username]);
                            $row = $user_picker->fetch(PDO::FETCH_ASSOC);
                            if($id_user == $_SESSION['id']){
                              echo"<script>alert('Vous ne pouvez pas modifier votre rôle')</script>";
                            }else{
                              $modify_user = $connect->prepare('UPDATE USER SET role=? WHERE id=?')->execute([$role,$id_user]);
                              echo"<script>alert('Permission modifiée')</script>";
                              header('Location:manage_permissions');
                            }
                            
                          }
                        }


                      ?>
                    </div>
                  </div>
                </div>

              <hr class="my-5" />
            <?php $content = ob_get_clean(); ?>
<?php require('template.php'); ?>