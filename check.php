<?php
if(empty($_SESSION['username'])){
    header("location:login");
}
if($admin == true){
	if($_SESSION['role'] == 0){
		header("location:index");
	}
}
?>