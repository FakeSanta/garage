<?php
require ('model/functions.php');
require 'config.php';
$id=$_GET['id'];
echo"<script>console.log('".$id."')</script>";
$del_selected = $connect->prepare('DELETE FROM VEHICULE WHERE id = ?');
$del_selected->execute([$id]);
header('Location:table_parc');