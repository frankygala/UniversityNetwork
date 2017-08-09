<?php
	require_once('database.php');
	session_start();
	date_default_timezone_set('Europe/Rome');
	$pdo = $databaseConnection -> getPdo();
	if(isset($_POST['titolo']) && isset($_POST['descrizione'])){
		$date = date('Y/m/d H:i:s', time());
		$titolo = $_POST['titolo'];
		$descrizione =  $_POST['descrizione'];
		$sql = "CALL BroadcastMessage(:email_mittente,:subject,:contenuto)";
		$stmt = $pdo->prepare($sql);
		$stmt -> bindParam(':subject',$titolo,PDO::PARAM_STR);
		$stmt -> bindParam(':contenuto',$descrizione,PDO::PARAM_STR);
		if($stmt->execute()){
			$message['success']=1;
			echo json_encode($message);
		}else{
			$message['success']=0;
			echo json_encode($message);
		}
	}else{
			$message['success']=0;
			echo json_encode($message);
		}
?>