<?php
require 'model/functions.php';
require 'config.php';

$var = $_GET['id'];
$sql = $connect->prepare("SELECT * FROM VEHICULE WHERE id = ?");
$sql->execute([$var]);
$car = $sql->fetch(PDO::FETCH_ASSOC);

if($car['reservable'] == 0){
    $query = $connect->prepare("UPDATE VEHICULE SET reservable = 1 WHERE id = ?");
}else{
    $query = $connect->prepare("UPDATE VEHICULE SET reservable = 0 WHERE id = ?");
}
$query->execute([$var]);
header('Location: ./vehicule_bookable');
exit;
