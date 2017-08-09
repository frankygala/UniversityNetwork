<?php
	require_once('database.php');
	session_start();
	$pdo = $databaseConnection -> getPdo();
	$sql = "UPDATE utente SET password=:password WHERE email=:email";
	if(isset($_POST['password'])){
		$stmt = $pdo -> prepare($sql);
		$stmt -> bindParam(":email",$_SESSION['email'],PDO::PARAM_STR);
		$stmt -> bindParam(":password",md5($_POST['password']),PDO::PARAM_STR);
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