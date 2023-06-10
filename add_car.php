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
                $immat = $_POST['immat'];
                $marque = $_POST['marque'];
                $modele = $_POST['modele'];
                $utilitaire = $_POST['radio'];
                $motorisation = $_POST['carburantSelect'];
                $kilometrage = $_POST['kilometrage'];
                $current_date = $_POST['date_ct'];
                if($utilitaire == 0){
                    $next_date = date('Y-m-d', strtotime($current_date. '+ 2 years'));
                }else{
                    $next_date = date('Y-m-d', strtotime($current_date. '+ 1 years'));
                }
                if(checkCarExist($immat) === true) {
                }
                else{
                    if(empty($_POST['kilometrage'])){
                        $content = "**".strtoupper($_SESSION['username'])."** - Véhicule créé ➡️ **".$immat."** *".$marque."* **".$modele."**";
                        sendDiscordAlert($content);
                        insertCarKm($immat, $marque, $modele, $motorisation, $utilitaire);
                        $selectCar = $connect->prepare("SELECT * FROM VEHICULE WHERE immatriculation = :immat");
                        $selectCar->execute(array('immat' => $immat));
                        $resultCar = $selectCar->fetch(PDO::FETCH_ASSOC);
                        $insert_ct = $connect->prepare('INSERT INTO CT (id_vehicule, date_ct, prochaine_date_ct) VALUES (?,?,?)')->execute([$resultCar['id'], $current_date, $next_date]);
                        $insert_histo = $connect->prepare('INSERT INTO HISTORIQUE (id_vehicule, type_operation, date_ct, commentaire) VALUES (?,?,?,"")')->execute([$resultCar['id'], $type_ope, $current_date]);
                        

                    }
                    else{
                        $content = "**".strtoupper($_SESSION['username'])."** - Véhicule créé ➡️ **".$immat."** *".$marque."* **".$modele."**";
                        sendDiscordAlert($content);
                        insertCarWithKm($immat, $marque, $modele, $motorisation,$kilometrage, $utilitaire);
                        $selectCar = $connect->prepare("SELECT * FROM VEHICULE WHERE immatriculation = :immat");
                        $selectCar->execute(array('immat' => $immat));
                        $resultCar = $selectCar->fetch(PDO::FETCH_ASSOC);
                        $insert_ct = $connect->prepare('INSERT INTO CT (id_vehicule, date_ct, prochaine_date_ct) VALUES (?,?,?)')->execute([$resultCar['id'], $current_date, $next_date]);
                        $insert_histo = $connect->prepare('INSERT INTO HISTORIQUE (id_vehicule, type_operation, date_ct, commentaire) VALUES (?,?,?,"")')->execute([$resultCar['id'], $type_ope, $current_date]);
                        
                    }
                }
            }else{
            }
        }else{
        }
    }
?>            
