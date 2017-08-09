<?php
require_once('database.php');
session_start();
if(isset($_POST['data_inizio']) && isset($_POST['data_fine']) && isset($_POST['descrizione']) && isset($_POST['corso']) && isset($_POST['voto'])){
	$data_inizio = $_POST['data_inizio'];
	$data_fine = $_POST['data_fine'];
	$descrizione = $_POST['descrizione'];
	$corso = $_POST['corso'];
	$voto = $_POST['voto'];
	$pdo = $databaseConnection -> getPdo();
	$sql = "CALL CreaEsercitazione (:email,:corso,:descrizione,:data_inizio,:data_fine,:voto)";
	$stmt = $pdo->prepare($sql);
	$stmt -> bindParam(':email',$_SESSION['email'],PDO::PARAM_STR);
	$stmt -> bindParam(':corso',$corso,PDO::PARAM_INT);
	$stmt -> bindParam(':data_inizio',$data_inizio,PDO::PARAM_STR);
	$stmt -> bindParam(':data_fine',$data_fine,PDO::PARAM_STR);
	$stmt -> bindParam(':voto',$voto,PDO::PARAM_INT);
	$stmt -> bindParam(':descrizione',$descrizione,PDO::PARAM_STR);
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