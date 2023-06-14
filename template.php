<!DOCTYPE html>
<html
  lang="en"
  class="light-style layout-menu-fixed"
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
    <title><?= $title ?></title>
    <meta name="description" content="" />
    <link rel="icon" type="image/x-icon" href="../assets/img/favicon/voiture.ico" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
      rel="stylesheet"
    />
    <script src="calendar/assets/js/sweetalert.min.js"></script> 
	  <link rel="stylesheet" type="text/css" href="calendar/assets/css/sweetalert.css">
    <link rel="stylesheet" href="../assets/vendor/fonts/boxicons.css" />
    <link rel="stylesheet" href="../assets/vendor/css/core.css" class="template-customizer-core-css" />
    <link rel="stylesheet" href="../assets/vendor/css/theme-default.css" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="../assets/css/demo.css" />
    <link rel="stylesheet" href="../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />
    <script src="../assets/vendor/js/helpers.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="../assets/js/config.js"></script>
  </head>
  <body>
  
    <div class="layout-wrapper layout-content-navbar">
      
      <div class="layout-container">
        
        <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
          <div class="app-brand demo" style="max-width: 100%;">
              <a href="index" class="app-brand-link">
                  <span class="app-brand-text demo menu-text fw-bolder ms-2" style="word-break: break-all;">
                      auto-<?php echo $brend ?>
                  </span>
              </a>
          </div>
          <div class="menu-inner-shadow"></div>
          <ul class="menu-inner py-1">
            <li class="menu-item <?php if($page == "index"){echo"active";} ?>">
              <a href="index" class="menu-link">
                <i class="menu-icon tf-icons bx bx-home-circle"></i>
                <div data-i18n="Analytics">Accueil</div>
              </a>
            </li>
            <?php
              if($_SESSION['role'] != 0){
                ?>
            <li class="menu-header small text-uppercase">
              <span class="menu-header-text">Admin</span>
            </li>
            <li class="menu-item <?php if($menu_deroulant == 1){echo"active open";} ?>">
              <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-lock-open-alt"></i>
                <div data-i18n="Account Settings">Gestion utilisateurs</div>
              </a>
              <ul class="menu-sub">
                <li class="menu-item <?php if($page == "list_users"){echo"active";} ?>">
                  <a href="list_users" class="menu-link">
                    <div data-i18n="Account">Liste des utilisateurs</div>
                  </a>
                </li>
                <li class="menu-item <?php if($page == "manage_permissions"){echo"active";} ?>">
                  <a href="manage_permissions" class="menu-link">
                    <div data-i18n="Notifications">Gérer les permissions</div>
                  </a>
                </li>
                <li class="menu-item <?php if($page == "register_user"){echo"active";} ?>">
                  <a href="register_user" class="menu-link">
                    <div data-i18n="Connections">Enregistrer un utilisateur</div>
                  </a>
                </li>
                <li class="menu-item <?php if($page == "del_user"){echo"active";} ?>">
                  <a href="del_user" class="menu-link">
                    <div data-i18n="Account">Suppr. des utilisateurs</div>
                  </a>
                </li>
                <li class="menu-item <?php if($page == "mail_users"){echo"active";} ?>">
                  <a href="mail_users" class="menu-link">
                    <div data-i18n="Account">Liste des mails</div>
                  </a>
                </li>
              </ul>
            </li>
            <li class="menu-item <?php if($page == "add_car"){echo"active";} ?>">
              <a href="add_car" class="menu-link">
                <i class="menu-icon tf-icons bx bx-car"></i>
                <div data-i18n="Form Elements">Ajout d'un véhicule</div>
              </a>
            </li>
            
            <?php
              }
            ?>
            <li class="menu-header small text-uppercase"><span class="menu-header-text">réserver</span></li>
            <li class="menu-item <?php if($page == "reservation"){echo"active";} ?>">
              <a href="reservation" class="menu-link">
                <i class="menu-icon tf-icons bx bx-calendar-plus"></i>
                <div data-i18n="Tables">Réservation d'un véhicule</div>
              </a>
            </li>
            <?php
              if($_SESSION['role'] != 0){
                ?>
            <li class="menu-item <?php if($page == "vehicule_bookable"){echo"active";} ?>">
              <a href="vehicule_bookable" class="menu-link">
                <i class="menu-icon tf-icons bx bx-calendar-plus"></i>
                <div data-i18n="Tables">Véhicules dispo à la résa.</div>
              </a>
            </li>
            <?php
              }
            ?>
            <li class="menu-header small text-uppercase"><span class="menu-header-text">consulter</span></li>
            <li class="menu-item <?php if($page == "table_parc"){echo"active";} ?>">
              <a href="table_parc" class="menu-link">
                <i class="menu-icon tf-icons bx bx-table"></i>
                <div data-i18n="Tables">État du parc</div>
              </a>
            </li>
            <li class="menu-item <?php if($page == "historique"){echo"active";} ?>">
              <a href="historique" class="menu-link">
                <i class="menu-icon tf-icons bx bx-folder-open"></i>
                <div data-i18n="Tables">Historique</div>
              </a>
            </li>
            
            <li class="menu-header small text-uppercase"><span class="menu-header-text">contrôle technique</span></li>
            <li class="menu-item <?php if($page == "suivi_ct"){echo"active";} ?>">
              <a href="suivi_ct" class="menu-link">
                <i class="menu-icon tf-icons bx bx-calendar-check"></i>
                <div data-i18n="Tables">Contrôles techniques</div>
              </a>
            </li>
            <li class="menu-item <?php if($page == "list_rdv"){echo"active";} ?>">
              <a href="list_rdv" class="menu-link">
                <i class="menu-icon tf-icons bx bx-folder-open"></i>
                <div data-i18n="Tables">Liste des RDV</div>
              </a>
            </li>
            <li class="menu-header small text-uppercase"><span class="menu-header-text">vidanges</span></li>
            <li class="menu-item <?php if($page == "suivi_vidange"){echo"active";} ?>">
              <a href="suivi_vidange" class="menu-link">
                <i class="menu-icon tf-icons bx bx-calendar-check"></i>
                <div data-i18n="Tables">Vidanges à venir</div>
              </a>
            </li>
            <?php
              if($_SESSION['role'] != 0){
            ?>
            <li class="menu-item <?php if($page == "add_vidange"){echo"active";} ?>">
              <a href="add_vidange" class="menu-link">
                <i class="menu-icon tf-icons bx bx-gas-pump"></i>
                <div data-i18n="Form Elements">Ajout d'une vidange</div>
              </a>
            </li>
            <?php } ?>
            <li class="menu-header small text-uppercase">
              <span class="menu-header-text">Incident</span>
            </li>
            <li class="menu-item <?php if($page == "report-incident"){echo"active";} ?>">
              <a href="report-incident" class="menu-link">
                <i class="menu-icon tf-icons bx bx-table"></i>
                <div data-i18n="Tables">Rapport d'incident</div>
              </a>
            </li>
            <li class="menu-item <?php if($page == "suivi-incident"){echo"active";} ?>">
              <a href="suivi-incident" class="menu-link">
                <i class="menu-icon tf-icons bx bx-table"></i>
                <div data-i18n="Tables">Suivi des incidents</div>
              </a>
            </li>
            <li class="menu-header small text-uppercase"><span class="menu-header-text">carburant</span></li>
            <li class="menu-item <?php if($page == "suivi_plein"){echo"active";} ?>">
              <a href="suivi_plein" class="menu-link">
                <i class="menu-icon tf-icons bx bx-folder-open"></i>
                <div data-i18n="Tables">Suivi carburant</div>
              </a>
            </li>
            <?php
              if($_SESSION['role'] != 0){
            ?>
            <li class="menu-item <?php if($page == "add_plein"){echo"active";} ?>">
              <a href="add_plein" class="menu-link">
                <i class="menu-icon tf-icons bx bx-gas-pump"></i>
                <div data-i18n="Form Elements">Ajout d'un plein</div>
              </a>
            </li>
            <?php } ?>
            <li class="menu-header small text-uppercase"><span class="menu-header-text">Équipement</span></li>
            <li class="menu-item <?php if($page == "showcar-stuff"){echo"active";} ?>">
              <a href="showcar-stuff" class="menu-link">
                <i class="menu-icon tf-icons bx bx-table"></i>
                <div data-i18n="Tables">Équipement d'un véhicule</div>
              </a>
            </li>
            <li class="menu-item <?php if($page == "list-stuff"){echo"active";} ?>">
              <a href="list-stuff" class="menu-link">
                <i class="menu-icon tf-icons bx bx-table"></i>
                <div data-i18n="Tables">Liste des équipements</div>
              </a>
            </li>
            <?php
              if($_SESSION['role'] != 0){
            ?>
            <li class="menu-item <?php if($page == "add_stuff"){echo"active";} ?>">
              <a href="add_stuff" class="menu-link">
                <i class="menu-icon tf-icons bx bx-detail"></i>
                <div data-i18n="Form Elements">Ajout d'un équipement</div>
              </a>
            </li>
            <li class="menu-item <?php if($page == "assign-stuff"){echo"active";} ?>">
              <a href="assign-stuff" class="menu-link">
                <i class="menu-icon tf-icons bx bx-chevrons-right"></i>
                <div data-i18n="Form Elements">Assigner un équipement</div>
              </a>
            </li>
            <?php
              }
            ?>
          </ul>
        </aside>
        
        <div class="layout-page">
        <!-- Ruban maintenance
        <div class="alert alert-danger alert-dismissible" role="alert">
          Site en maintenance ce vendredi de 22h à 00h 
        </div>-->

        <nav
            class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme"
            id="layout-navbar"
          >
            <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
              <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
                <i class="bx bx-menu bx-sm"></i>
              </a>
            </div>

            <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
              <ul class="navbar-nav flex-row align-items-center ms-auto">
                <li class="nav-item navbar-dropdown dropdown-user dropdown">
                  <a href="dc">
                    <div class="avatar">
                    <span id="basic-icon-default-fullname2" class="input-group-text"
                      ><i class="bx bx-unlink"></i
                    ></span>                    </div>
                  </a>
                </li>
                <!--/ User -->
              </ul>
            </div>
          </nav>
        <?= $content ?>
      </div>
      <div class="layout-overlay layout-menu-toggle"></div>
    </div>
		<script src="calendar/assets/js/listings.js"></script>
    <script src="calendar/assets/js/moment.min.js"></script>
    <script src="../assets/vendor/libs/jquery/jquery.js"></script>
    <script src="../assets/vendor/libs/popper/popper.js"></script>
    <script src="../assets/vendor/js/bootstrap.js"></script>
    <script src="../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
    <script src="../assets/vendor/js/menu.js"></script>
    <script src="../assets/js/main.js"></script>
    <script async defer src="https://buttons.github.io/buttons.js"></script>
  </body>
</html>
