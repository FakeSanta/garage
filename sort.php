<?php
    session_start();
    // Define database
    define('dbhost', 'localhost');
    define('dbuser', 'admin');
    define('dbpass', 'pacharubie1577');
    define('dbname', 'GESTION');
    if(isset($_POST['id'])){
        try {
            $order = $_POST['id'];
            if($order == 'ASC'){
              $connect = new PDO("mysql:host=".dbhost."; dbname=".dbname, dbuser, dbpass);
              $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
              $query = $connect->prepare("SELECT * FROM VEHICULE, HISTORIQUE WHERE VEHICULE.id = HISTORIQUE.id_vehicule ORDER BY HISTORIQUE.id ASC");
              $query->execute();
              if($query->rowCount()){
                  $html='';
                  while($row = $query->fetch(PDO::FETCH_ASSOC)){
                      setlocale(LC_TIME, 'fr_FR.utf8'); 
                      if(empty($row["date_ct"])){
                        $new_date_ct = "------";
                      }else{
                        $new_date_ct = strftime("%d %B %Y",strtotime($row["date_ct"]));
                        $new_date_ct = str_replace( 
                          array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'),
                          array('Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'),
                          $new_date_ct
                        );
                      }
                      if(empty($row["date_vidange"])){
                        $new_date_vidange = "------";
                      }else{
                        $new_date_vidange = strftime("%d %B %Y",strtotime($row["date_vidange"]));
                        $new_date_vidange = str_replace( 
                          array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'),
                          array('Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'),
                          $new_date_vidange
                        );
                      }
  
                      $data[] = array(
                        "id_histo" => $row["id"],
                        "immat" => $row["immatriculation"],
                        "marque" => $row["marque"],
                        "modele" => $row["modele"],
                        "motorisation" => $row["motorisation"],
                        "date_vidange" => $new_date_vidange !== "------" ? $new_date_vidange : "-----",
                        "date_ct" => $new_date_ct !== "------" ? $new_date_ct : "-----",
                        "commentaire" => $row["commentaire"]
                      ); 
                    }
              }    
              echo json_encode($data);
              $test = json_encode($data);
            }elseif($order == 'DESC'){
              $connect = new PDO("mysql:host=".dbhost."; dbname=".dbname, dbuser, dbpass);
              $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
              $query = $connect->prepare("SELECT * FROM VEHICULE, HISTORIQUE WHERE VEHICULE.id = HISTORIQUE.id_vehicule ORDER BY HISTORIQUE.id DESC");
              $query->execute();
              if($query->rowCount()){
                  $html='';
                  while($row = $query->fetch(PDO::FETCH_ASSOC)){
                      setlocale(LC_TIME, 'fr_FR.utf8'); 
                      if(empty($row["date_ct"])){
                        $new_date_ct = "------";
                      }else{
                        $new_date_ct = strftime("%d %B %Y",strtotime($row["date_ct"]));
                        $new_date_ct = str_replace( 
                          array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'),
                          array('Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'),
                          $new_date_ct
                        );
                      }
                      if(empty($row["date_vidange"])){
                        $new_date_vidange = "------";
                      }else{
                        $new_date_vidange = strftime("%d %B %Y",strtotime($row["date_vidange"]));
                        $new_date_vidange = str_replace( 
                          array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'),
                          array('Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'),
                          $new_date_vidange
                        );
                      }
                      $data[] = array(
                        "id_histo" => $row["id"],
                        "immat" => $row["immatriculation"],
                        "marque" => $row["marque"],
                        "modele" => $row["modele"],
                        "motorisation" => $row["motorisation"],
                        "date_vidange" => $new_date_vidange !== "------" ? $new_date_vidange : "-----",
                        "date_ct" => $new_date_ct !== "------" ? $new_date_ct : "-----",
                        "commentaire" => $row["commentaire"]
                      ); 
                    }
              }    
              echo json_encode($data);
              $test = json_encode($data);
            }elseif($order == 'CT'){
              $connect = new PDO("mysql:host=".dbhost."; dbname=".dbname, dbuser, dbpass);
              $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
              $query = $connect->prepare("SELECT * FROM VEHICULE, HISTORIQUE WHERE VEHICULE.id = HISTORIQUE.id_vehicule AND type_operation = 'CT' ORDER BY HISTORIQUE.id DESC");
              $query->execute();
              if($query->rowCount()){
                $html='';
                while($row = $query->fetch(PDO::FETCH_ASSOC)){
                    setlocale(LC_TIME, 'fr_FR.utf8'); 
                    if(empty($row["date_ct"])){
                      $new_date_ct = "------";
                    }else{
                      $new_date_ct = strftime("%d %B %Y",strtotime($row["date_ct"]));
                      $new_date_ct = str_replace( 
                        array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'),
                        array('Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'),
                        $new_date_ct
                      );
                    }
                    if(empty($row["date_vidange"])){
                      $new_date_vidange = "------";
                    }else{
                      $new_date_vidange = strftime("%d %B %Y",strtotime($row["date_vidange"]));
                      $new_date_vidange = str_replace( 
                        array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'),
                        array('Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'),
                        $new_date_vidange
                      );
                    }
                    $data[] = array(
                      "id_histo" => $row["id"],
                      "immat" => $row["immatriculation"],
                      "marque" => $row["marque"],
                      "modele" => $row["modele"],
                      "motorisation" => $row["motorisation"],
                      "date_vidange" => $new_date_vidange !== "------" ? $new_date_vidange : "-----",
                      "date_ct" => $new_date_ct !== "------" ? $new_date_ct : "-----",
                      "commentaire" => $row["commentaire"]
                    ); 
                  }
            }    
            echo json_encode($data);
            $test = json_encode($data);
            }elseif($order == 'VID'){
              $connect = new PDO("mysql:host=".dbhost."; dbname=".dbname, dbuser, dbpass);
              $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
              $query = $connect->prepare("SELECT * FROM VEHICULE, HISTORIQUE WHERE VEHICULE.id = HISTORIQUE.id_vehicule AND type_operation = 'VIDANGE' ORDER BY HISTORIQUE.id DESC");
              $query->execute();
              if($query->rowCount()){
                $html='';
                while($row = $query->fetch(PDO::FETCH_ASSOC)){
                    setlocale(LC_TIME, 'fr_FR.utf8'); 
                    if(empty($row["date_ct"])){
                      $new_date_ct = "------";
                    }else{
                      $new_date_ct = strftime("%d %B %Y",strtotime($row["date_ct"]));
                      $new_date_ct = str_replace( 
                        array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'),
                        array('Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'),
                        $new_date_ct
                      );
                    }
                    if(empty($row["date_vidange"])){
                      $new_date_vidange = "------";
                    }else{
                      $new_date_vidange = strftime("%d %B %Y",strtotime($row["date_vidange"]));
                      $new_date_vidange = str_replace( 
                        array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'),
                        array('Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'),
                        $new_date_vidange
                      );
                    }
                    $data[] = array(
                      "id_histo" => $row["id"],
                      "immat" => $row["immatriculation"],
                      "marque" => $row["marque"],
                      "modele" => $row["modele"],
                      "motorisation" => $row["motorisation"],
                      "date_vidange" => $new_date_vidange !== "------" ? $new_date_vidange : "-----",
                      "date_ct" => $new_date_ct !== "------" ? $new_date_ct : "-----",
                      "commentaire" => $row["commentaire"]
                    ); 
                  }
            }    
            echo json_encode($data);
            $test = json_encode($data);
            }else{
              $id_vehicule = $_POST['id'];
              $connect = new PDO("mysql:host=".dbhost."; dbname=".dbname, dbuser, dbpass);
              $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
              $query = $connect->prepare("SELECT * FROM VEHICULE, HISTORIQUE WHERE VEHICULE.id = HISTORIQUE.id_vehicule AND VEHICULE.id = ? ORDER BY HISTORIQUE.id DESC");
              $query->execute([$id_vehicule]);
              $html = '';
              if($query->rowCount()){
                while($row = $query->fetch(PDO::FETCH_ASSOC)){
                  if(empty($row["date_ct"])){
                    $new_date_ct = "------";
                  }else{
                    $new_date_ct = strftime("%d %B %Y",strtotime($row["date_ct"]));
                    $new_date_ct = str_replace( 
                      array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'),
                      array('Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'),
                      $new_date_ct
                    );
                  }
                  if(empty($row["date_vidange"])){
                    $new_date_vidange = "------";
                  }else{
                    $new_date_vidange = strftime("%d %B %Y",strtotime($row["date_vidange"]));
                    $new_date_vidange = str_replace( 
                      array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'),
                      array('Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'),
                      $new_date_vidange
                    );
                  }
                  $data[] = array(
                    "id_histo" => $row["id"],
                    "immat" => $row["immatriculation"],
                    "marque" => $row["marque"],
                    "modele" => $row["modele"],
                    "motorisation" => $row["motorisation"],
                    "date_vidange" => $new_date_vidange !== "------" ? $new_date_vidange : "-----",
                    "date_ct" => $new_date_ct !== "------" ? $new_date_ct : "-----",
                    "commentaire" => $row["commentaire"]
                  ); 
                }
            }else{
                $data[] = array(
                    "id_histo" => "<i>Aucun historique pour ce véhicule</i>",
                    "immat" => "------",
                    "marque" => "------",
                    "modele" => "------",
                    "motorisation" => "------",
                    "date_vidange" => "------",
                    "date_ct" => "------",
                    "commentaire" => "------"
                );
            }
            echo json_encode($data);
            }
        }
        catch(PDOException $e) {
            echo $e->getMessage();
        }
    }
?>