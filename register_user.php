<?php
  $admin = true;
	require 'config.php';
  require 'check.php';
	  if(isset($_POST["register"]))
      {  
        if($_POST['password'] == $_POST['password_confirm'])
        {
            $username = $_POST['username'];
            $password = $_POST['password'];
            $role = $_POST['roleSelect'];
            $mail = $_POST['email'];
            $query_exist = $connect->prepare("SELECT * FROM USER WHERE username = :username OR mail = :mail");
            $query_exist->execute(
                array(
                  'username' => $username,
                  'mail' => $mail
                )
            );
          
            if($query_exist->rowCount()) {
              echo'<div class="alert alert-danger" role="alert">Cet utilisateur ou ce mail existe déjà </div>';
            }
            else{     
                $register_user = $connect->prepare('INSERT INTO USER (username, mdp, role, mail) VALUES (?,?,?,?)')->execute([$username, $password, $role,$mail]);
                header('location:index');
            }  
        }
        else{
          echo'<div class="alert alert-danger" role="alert">Les mots de passes ne correspondent pas</div>';
        }
    }
    ?>
<!DOCTYPE html>

<!-- =========================================================
* Sneat - Bootstrap 5 HTML Admin Template - Pro | v1.0.0
==============================================================

* Product Page: https://themeselection.com/products/sneat-bootstrap-html-admin-template/
* Created by: ThemeSelection
* License: You must have a valid license purchased in order to legally use the theme for your project.
* Copyright ThemeSelection (https://themeselection.com)

=========================================================
 -->
<!-- beautify ignore:start -->
<html
  lang="en"
  class="light-style customizer-hide"
  dir="ltr"
  data-theme="theme-default"
  data-assets-path="../assets/"
  data-template="vertical-menu-template-free"
>
  <head>
    <meta charset="utf-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0"
    />

    <title>Ajout un utilisateur | <?php echo $brend ?></title>

    <meta name="description" content="" />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="../assets/img/favicon/voiture.ico" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
      rel="stylesheet"
    />

    <!-- Icons. Uncomment required icon fonts -->
    <link rel="stylesheet" href="../assets/vendor/fonts/boxicons.css" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="../assets/vendor/css/core.css" class="template-customizer-core-css" />
    <link rel="stylesheet" href="../assets/vendor/css/theme-default.css" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="../assets/css/demo.css" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />

    <!-- Page CSS -->
    <!-- Page -->
    <link rel="stylesheet" href="../assets/vendor/css/pages/page-auth.css" />
    <!-- Helpers -->
    <script src="../assets/vendor/js/helpers.js"></script>

    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="../assets/js/config.js"></script>
  </head>

  <body>
    <!-- Content -->

    <div class="container-xxl">
      <div class="authentication-wrapper authentication-basic container-p-y">
        <div class="authentication-inner">
          <!-- Register -->
          <div class="card">
            <div class="card-body">
              <?php
                if(isset($_POST['register'])){
                  if($_POST['password'] == $_POST['password_confirm']){
                  }
                }
              ?>
            <h4 class="mb-2">Enregistrer un utilisateur</h4>
            <hr class="my-4" />
              <form id="formAuthentication" class="mb-3" method="POST">
                <div class="mb-3">
                  <label for="email" class="form-label">Identifiant</label>
                  <input
                    type="text"
                    class="form-control"
                    id="username"
                    name="username"
                    required
                    autofocus
                  />
                </div>
                <div class="mb-3 form-password-toggle">
                  <div class="d-flex justify-content-between">
                    <label class="form-label" for="password">Mot de passe</label>
                  </div>
                  <div class="input-group input-group-merge">
                    <input
                      type="password"
                      id="password"
                      class="form-control"
                      name="password"
                      aria-describedby="password"
                      required
                    />
                  </div>
                </div>
                <div class="mb-3 form-password-toggle">
                    <div class="d-flex justify-content-between">
                        <label class="form-label" for="password">Confirmation du Mot de passe</label>
                    </div>
                    <div class="input-group input-group-merge">
                        <input
                        type="password"
                        id="password_confirm"
                        class="form-control"
                        name="password_confirm"
                        aria-describedby="password"
                        required
                        />
                    </div>
                </div>
                <div class="mb-3 form-password-toggle">
                    <div class="d-flex justify-content-between">
                        <label class="form-label" for="password">E-mail</label>
                    </div>
                    <div class="input-group input-group-merge">
                        <input
                        type="email"
                        id="email"
                        class="form-control"
                        name="email"
                        aria-describedby="email"
                        required
                        />
                    </div>
                </div>
                <div class="mb-3">
                    <label for="roleSelect" class="form-label">Rôle</label>
                    <select class="form-select" name="roleSelect">
                        <option value="0">Consultant</option>
                        <option value="1">Administrateur</option>
                        <option value="2">Chef des travaux</option>
                    </select>
                </div>
                <div class="mb-3">
                  <button class="btn btn-primary d-grid w-100" name="register" type="submit">Enregistrer</button>
                </div>
              </form>
            </div>
          </div>
          <!-- /Register -->
        </div>
      </div>
    </div>
    
    <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->
    <script src="../assets/vendor/libs/jquery/jquery.js"></script>
    <script src="../assets/vendor/libs/popper/popper.js"></script>
    <script src="../assets/vendor/js/bootstrap.js"></script>
    <script src="../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>

    <script src="../assets/vendor/js/menu.js"></script>
    <!-- endbuild -->

    <!-- Vendors JS -->

    <!-- Main JS -->
    <script src="../assets/js/main.js"></script>

    <!-- Page JS -->

    <!-- Place this tag in your head or just before your close body tag. -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
  </body>
</html>
