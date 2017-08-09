<?php
session_start();
date_default_timezone_set('Europe/Rome');
require_once('database.php');
$pdo = $databaseConnection -> getPdo();
if(isset($_POST['titolo']) && isset($_POST['descrizione'])){
	$date = date('Y/m/d H:i:s', time());
	$titolo = $_POST['titolo'];
	$descrizione = $_POST['descrizione'];
	$sql="INSERT into news (email_prof,subject,stringa_testo,data_inserimento) values (:email,:titolo,:testo,:data)";
	$stmt = $pdo -> prepare($sql);
	$stmt -> bindParam(':email',$_SESSION['email'],PDO::PARAM_STR);
	$stmt -> bindParam(':titolo',$titolo,PDO::PARAM_STR);
	$stmt -> bindParam(':testo',$descrizione, PDO::PARAM_STR);
	$stmt -> bindParam(':data',$date,PDO::PARAM_STR);
	$result = $stmt -> execute();
	if($result){
		$message['success'] = ['1'];
		echo json_encode($message);
	}else{
		$message['success'] = ['0'];
		echo json_encode($message);
	}
}else{
	$message['success'] = ['0'];
	echo json_encode($message);
}
?>