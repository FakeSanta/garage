<?php
    require_once('model/functions.php');
    $admin = true;
	require 'config.php';
    require 'check.php';
    require ('view/add_car.php');
    $_SESSION['pattern'] = "/^[a-zA-Z]{2}-\d{3}-[a-zA-Z]{2}$/";
    if(isset($_POST['add_car_button'])){
        if(!empty($_POST["immat"]) || !empty($_POST["marque"]) || !empty($_POST["modele"]) || !empty($_POST['radio'])){
            if(preg_match($_SESSION['pattern'], $_POST['immat'])){
                $immat = strtoupper($_POST['immat']);
                $marque = ucfirst($_POST['marque']);
                $modele = ucfirst($_POST['modele']);
                $utilitaire = $_POST['radio'];
                $motorisation = $_POST['carburantSelect'];
                $kilometrage = $_POST['kilometrage'];
                $current_date_ct = $_POST['date_ct'];
                $current_date_vidange = $_POST['date_vidange'];
                $next_date_vidange = date('Y-m-d', strtotime($current_date_vidange. '+ 1 years'));
                if($utilitaire == 0){
                    $next_date_ct = date('Y-m-d', strtotime($current_date_ct. '+ 2 years'));
                }else{
                    $next_date_ct = date('Y-m-d', strtotime($current_date_ct. '+ 1 years'));
                }
                if(checkCarExist($immat) === true) {
                }
                else{
                    $color ="008020";
                    $random_color = rand_color();
                    if(empty($_POST['kilometrage'])){
                        $content = "**".strtoupper($_SESSION['username'])."** - Véhicule créé ➡️ **".$immat."** *".$marque."* **".$modele."**";
                        sendDiscordAlert($content,$color);
                        insertCarKm($immat, $marque, $modele, $motorisation, $utilitaire,$random_color);
                        $selectCar = $connect->prepare("SELECT * FROM VEHICULE WHERE immatriculation = :immat");
                        $selectCar->execute(array('immat' => $immat));
                        $resultCar = $selectCar->fetch(PDO::FETCH_ASSOC);
                        $insert_ct = $connect->prepare('INSERT INTO CT (id_vehicule, date_ct, prochaine_date_ct) VALUES (?,?,?)')->execute([$resultCar['id'], $current_date_ct, $next_date_ct]);
                        $insert_vidange = $connect->prepare('INSERT INTO VIDANGE (id_vehicule, date_vidange, prochaine_date_vidange) VALUES (?,?,?)')->execute([$resultCar['id'], $current_date_vidange, $next_date_vidange]);
                    }
                    else{
                        $content = "**".strtoupper($_SESSION['username'])."** - Véhicule créé ➡️ **".$immat."** *".$marque."* **".$modele."**";
                        sendDiscordAlert($content,$color);
                        insertCarWithKm($immat, $marque, $modele, $motorisation,$kilometrage, $utilitaire,$random_color);
                        $selectCar = $connect->prepare("SELECT * FROM VEHICULE WHERE immatriculation = :immat");
                        $selectCar->execute(array('immat' => $immat));
                        $resultCar = $selectCar->fetch(PDO::FETCH_ASSOC);
                        $insert_ct = $connect->prepare('INSERT INTO CT (id_vehicule, date_ct, prochaine_date_ct) VALUES (?,?,?)')->execute([$resultCar['id'], $current_date_ct, $next_date_ct]);
                        $insert_vidange = $connect->prepare('INSERT INTO VIDANGE (id_vehicule, date_vidange, prochaine_date_vidange) VALUES (?,?,?)')->execute([$resultCar['id'], $current_date_vidange, $next_date_vidange]);                      
                    }
                }
            }else{
            }
        }else{
        }
    }
?>            
