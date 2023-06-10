<?php
  use PHPMailer\PHPMailer\PHPMailer;
  use PHPMailer\PHPMailer\SMTP;
  use PHPMailer\PHPMailer\Exception;
  require '/var/www/html/vendor/phpmailer/phpmailer/src/Exception.php';
  require '/var/www/html/vendor/phpmailer/phpmailer/src/PHPMailer.php';
  require '/var/www/html/vendor/phpmailer/phpmailer/src/SMTP.php';
  $admin = true;
	require 'config.php';
  require 'check.php';
  $title = "Mail des utilisateurs | ".$brend;
  $page = "mail_users";
  $menu_deroulant = 1;
  
?>
<?php ob_start(); ?>
          <div class="content-wrapper">
            <div class="container-xxl flex-grow-1 container-p-y">
              <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Admin / Gestion utilisateurs / </span>Liste des mails</h4>
              <div class="mb-3">
                <label for="add_mail" class="form-label">Ajout d'un mail</label>
                <form action="" method="POST">
                <input
                    type="text"
                    class="form-control"
                    id="mail"
                    name="mail"
                    placeholder="Adresse mail"
                    />
              </div>
              <div class="d-grid gap-1 col-lg-2 mx-auto">
                    <button class="btn btn-primary btn-lg" name="add_mail_button" type="submit">Ajouter</button>
                </div>
                </form>
                <?php
                    if(isset($_POST['add_mail_button'])){
                        if(empty($_POST["mail"])){
                          echo"<script>alert('Veuillez remplir tous les champs obligatoires')</script>";
                        }else{
                          $mail = $_POST['mail'];
                          $query_exist = $connect->prepare("SELECT * FROM MAIL WHERE mail = :mail");
                          $query_exist->execute(
                            array(
                                'mail' => $mail
                              )
                          );
                          if($query_exist->rowCount()) {
                            echo"<script>alert('Erreur : Ce mail est déjà rentré dans la base')</script>";
                          }
                          else{
                              $request = $connect->prepare('INSERT INTO MAIL (mail) VALUES (?)')->execute([$mail]);
                          }
                        }
                      }
                ?>
              <hr class="my-5" />
              <div class="card">
                <h5 class="card-header">Liste des mails qui recevront les alertes</h5>
                <div class="table-responsive text-nowrap">
                  <?php
                      #$sql = $connect->prepare("SELECT * FROM VEHICULE, VIDANGE, CT WHERE VEHICULE.id = VIDANGE.id_vehicule AND VEHICULE.id = CT.id_vehicule");
                      $sql = $connect->prepare("SELECT * FROM MAIL ORDER BY mail ASC");
                      $sql->execute();
                    
                      if($sql->rowCount()) {

                  ?>
                  <table class="table">
                    <thead>
                      <tr>
                        <th>Mail</th>
                        <th>Actions</th>
                        <!--<th>Prochaine Vidange</th>
                        <th>Prochain CT</th>-->
                      </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                      <?php
                        while($row = $sql->fetch(PDO::FETCH_ASSOC))
                        {
                      ?>
                      <tr>
                        <form method="POST" action="">    
                          <td><i class="fab fa-angular fa-lg text-danger me-3"></i> <i><?php print($row['mail']) ?></i></td>
                          <td>
                              <button type="submit" class="btn p-0 dropdown-toggle hide-arrow" name="del_id" value="<?php print($row['id']) ?>">
                                <i class="bx bx-trash me-1" id="<?php print($row['mail']) ?>"></i> Supprimer
                              </button>
                          </td>
                        </form>
                        <form method="POST" action="">
                          <td>
                            <button type="submit" class="btn p-0 dropdown-toggle hide-arrow" name="test_id" value="<?php print($row['mail']) ?>">
                              <i class='bx bx-mail-send' id="<?php print($row['mail']) ?>"></i> Test d'envoi
                            </button>
                          </td>
                        </form>
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
                      $del_selected = $connect->prepare('DELETE FROM MAIL WHERE id = :id');
                      $del_selected->execute(
                        array(
                          'id' => $id
                        )
                      );
                      header('Location:mail_users');
                    }

                    if (isset($_POST['test_id'])) {
                      $mail = new PHPMailer();
                      $mail->IsSMTP();
                      $mail->Mailer = "smtp";

                      $mail->SMTPDebug  = 0;  
                      $mail->SMTPAuth   = TRUE;
                      $mail->SMTPSecure = "tls";
                      $mail->Port       = 587;
                      $mail->Host       = "smtp.gmail.com";
                      $mail->Username   = "supervision.decomble@gmail.com";
                      $mail->Password   = "rvnqrxyankxtuegm";

                      $mail->IsHTML(true);
                      $mail->AddAddress($_POST['test_id'], "recipient-name");
                      $mail->SetFrom("supervision.decomble@gmail.com", "Auto ".$brend);
                      $mail->Subject = "Test d'envoi mail d'Auto ".$brend;
                      $content = "Test d'envoi mail d'Auto ".$brend;

                      $mail->MsgHTML($content); 
                      if(!$mail->Send()) {
                        echo "Error while sending Email.";
                        echo "<script>console.log(" . json_encode($mail) . ")</script>";
                      }else{
                        header('Location:mail_users');
                      }
                    }
                  ?>
                </div>
              </div>
              <hr class="my-5" />
            <?php $content = ob_get_clean(); ?>
<?php require('template.php'); ?>