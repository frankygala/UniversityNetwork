<?php
	require_once('database.php');
	session_start();
	$sql = "INSERT INTO partecipa (email_partecipante_progetto,id_progetto) VALUES (:email_partecipante_progetto,:id_progetto)";
	$pdo = $databaseConnection -> getPdo();
	$stmt = $pdo -> prepare($sql);
	$stmt -> bindParam(':email_partecipante_progetto',$_SESSION['email'],PDO::PARAM_STR);
	$stmt -> bindParam(':id_progetto',$_POST['idprogetto'],PDO::PARAM_STR);
	if($stmt -> execute()){
		$message['success']=1;
		echo json_encode($message);
	}else{
		$message['success']=0;
		echo json_encode($message);
	}
?>