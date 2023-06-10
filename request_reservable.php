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
            $query = $connect->prepare("SELECT * FROM VEHICULE WHERE VEHICULE.id =?");
            $query->execute([$id_vehicule]);
            $html='';
            if($query->rowCount()){
                while($row = $query->fetch(PDO::FETCH_ASSOC)){
                    if($row['reservable'] == 0)
                        {
                            $res = "Rendre réservable";
                        }else{
                            $res = "Rendre non réservable";
                        }
                    $data[] = array(
                        "reservable" => $row["reservable"],
                        "nom" => $res
                      ); 
                }
            }    
            echo json_encode($data);
        }
        catch(PDOException $e) {
            echo $e->getMessage();
        }
    }
?>