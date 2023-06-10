<?php
    session_start();

    // Define database
    define('dbhost', 'localhost');
    define('dbuser', 'admin');
    define('dbpass', 'pacharubie1577');
    define('dbname', 'GESTION');
    if(isset($_POST['id'])){
        try {
            $connect = new PDO("mysql:host=".dbhost."; dbname=".dbname, dbuser, dbpass);
            $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $id_vehicule = $_POST['id'];
            $query = $connect->prepare("SELECT VEHICULE.id, EQUIPEMENT.marque, nom FROM VEHICULE, EQUIPEMENT, VEHICULE_EQUIPEMENT WHERE VEHICULE.id = VEHICULE_EQUIPEMENT.vehicule_id AND EQUIPEMENT.id = VEHICULE_EQUIPEMENT.equipement_id AND VEHICULE.id =?");
            $query->execute([$id_vehicule]);
            $html='';
            if($query->rowCount()){
                while($row = $query->fetch(PDO::FETCH_ASSOC)){
                    $data[] = array(
                        "nom" => $row["nom"],
                        "marque" => $row["marque"]
                      ); 
                }
            }else{
                $data[] = array(
                    "nom" => "<i>Aucun équipement n'a été assigné à ce véhicule</i>",
                    "marque" => ""
                );
            }
    
            echo json_encode($data);
        }
        catch(PDOException $e) {
            echo $e->getMessage();
        }
    }
?>