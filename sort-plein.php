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
            $annee_select = $_POST['id'];
            $query = $connect->prepare("SELECT V.modele, V.immatriculation, V.motorisation, SUM(cout_plein) AS cout_total FROM VEHICULE V, CARBURANT WHERE CARBURANT.id_vehicule = V.id AND V.motorisation != 'Electrique' AND YEAR(date_plein) = ? GROUP BY V.id");            
            $query->execute([$annee_select]);
            $html='';
            if($query->rowCount()){
                while($row = $query->fetch(PDO::FETCH_ASSOC)){
                    $data[] = array(
                        "immatriculation" => $row["immatriculation"],
                        "modele" => $row["modele"],
                        "cout_total" => $row["cout_total"],
                        "motorisation" => $row["motorisation"]
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