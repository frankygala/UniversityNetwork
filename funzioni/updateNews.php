<?php
session_start();
date_default_timezone_set('Europe/Rome');
require_once('database.php');
$pdo = $databaseConnection -> getPdo();
if(isset($_POST['titolo']) && isset($_POST['descrizione']) && isset($_POST['id'])){
	$date = date('Y/m/d H:i:s', time());
	$titolo = $_POST['titolo'];
	$id = $_POST['id'];
	$descrizione = $_POST['descrizione'];
	$sql="UPDATE news 
			SET subject=:titolo,stringa_testo=:testo,data_inserimento=:data 
			WHERE idnews=:id AND email_prof=:email";
	$stmt = $pdo -> prepare($sql);
	$stmt -> bindParam(':email',$_SESSION['email'],PDO::PARAM_STR);
	$stmt -> bindParam(':titolo',$titolo,PDO::PARAM_STR);
	$stmt -> bindParam(':testo',$descrizione, PDO::PARAM_STR);
	$stmt -> bindParam(':data',$date,PDO::PARAM_STR);
	$stmt -> bindParam(':id',$id,PDO::PARAM_INT);
	$result = $stmt -> execute();
	if($result){
		$message['success'] = 1;
		echo json_encode($message);
	}else{
		$message['success'] = 0;
		echo json_encode($message);
	}
}else{
	$message['success'] = 0;
	echo json_encode($message);
}
?>