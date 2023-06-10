<?php
ob_start(); // activation de la mise en mémoire tampon de sortie

require ('model/functions.php');
require ('config.php');
require('view/login.php');

if(isset($_POST["login"])) {  
     if(checkLogin($_POST["username"],$_POST["password"])) {  
          $info = getLogInfo($_POST["username"]);
          $_SESSION["username"] = $info['username'];  
          $_SESSION['role'] = $info['role'];
          $_SESSION['id'] = $info['id'];
          $_SESSION ['ip'] = $_SERVER['REMOTE_ADDR'];
          $browser = getBrowser();
          if($_SESSION['role'] == 1){
               $role = "Administrateur";
          }elseif($_SESSION['role'] == 0){
               $role = "Consultant";
          }elseif($_SESSION['role'] == 2){
               $role = "Chef des travaux";
          }
          $content = "**".strtoupper($_SESSION['username'])."** (*".$role."*) vient de se connecter avec cette IP : **".$_SESSION['ip']."** sur **".$browser."** <@273790073483427840>";
          sendDiscordAlert($content);
          header("location:index");
          ob_get_clean(); // vide le tampon de sortie et renvoie son contenu
          exit();
     }  
     else {  
          echo"<script>alert('Mauvais identifiant ou mot de passe')</script>";
     }  
}

ob_end_flush(); // envoie le contenu du tampon de sortie au navigateur
?>
