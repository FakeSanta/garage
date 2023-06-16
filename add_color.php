<?php
require('model/functions.php');
require('config.php');

$all_cars = $connect->prepare("SELECT * FROM VEHICULE");
$all_cars->execute();
$results = $all_cars->fetchAll(PDO::FETCH_ASSOC);

foreach($results as $car){
    $color = rand_color();
    $id_vehicule = $car['id'];
    $update = $connect->prepare("UPDATE VEHICULE SET color = ? WHERE id = ?");
    $update->execute([$color, $id_vehicule]);
}

?>