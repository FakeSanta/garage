<?php
session_start();

// Define database
define('dbhost', 'localhost');
define('dbuser', 'admin');
define('dbpass', 'pacharubie1577');
define('dbname', 'GESTION');

// Connecting database
try {
	$connect = new PDO("mysql:host=".dbhost."; dbname=".dbname, dbuser, dbpass);
	$connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch(PDOException $e) {
	echo $e->getMessage();
}
$brend = "Decomble";
?>