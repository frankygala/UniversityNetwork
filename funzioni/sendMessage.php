<?php
	require_once('database.php');
	session_start();
	date_default_timezone_set('Europe/Rome');
	$pdo = $databaseConnection -> getPdo();
	if(isset($_POST['email_followed']) && isset($_POST['titolo']) && isset($_POST['descrizione'])){
		$date = date('Y/m/d H:i:s', time());
		$email = $_POST['email_followed'];
		$titolo = $_POST['titolo'];
		$descrizione =  $_POST['descrizione'];
		$sql = "INSERT INTO messaggio (email_mittente,subject,contenuto,data_invio,email_destinatario) 
				VALUES(:email_mittente,:subject,:contenuto,:data_invio,:email_destinatario)";
		$stmt = $pdo->prepare($sql);
		$stmt -> bindParam(':email_destinatario',$email,PDO::PARAM_STR);
		$stmt -> bindParam(':email_mittente',$_SESSION['email'],PDO::PARAM_STR);
		$stmt -> bindParam(':subject',$titolo,PDO::PARAM_STR);
		$stmt -> bindParam(':contenuto',$descrizione,PDO::PARAM_STR);
		$stmt -> bindParam(':data_invio',$date,PDO::PARAM_STR);

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