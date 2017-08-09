<?php
session_start();
require_once("database.php");
$email = $_POST['email'];
$password = $_POST['password'];
try{
$pdo = $databaseConnection->getPdo();
$sql = "SELECT nome,cognome,foto FROM utente WHERE email=:email AND password=:password AND stato=1";
$stmt = $pdo -> prepare($sql);
$stmt -> bindValue(':email',$email);
$stmt -> bindValue(':password',md5($password));
$stmt -> execute();
$result = $stmt->fetchALL();
if(count($result)>0){
	$_SESSION['nome']=$result[0][0];
	$_SESSION['cognome']=$result[0][1];
	$_SESSION['email']=$email;
	$_SESSION['image']=$result[0][2];
	$_SESSION['welcome']=false;
	$sql = "SELECT * FROM docente WHERE email_professore=:email";
	$stmt = $pdo -> prepare($sql);
	$stmt -> bindValue(':email',$email);
	$stmt -> execute();
	$result = $stmt->fetchALL();
	if(count($result)>0){
		$_SESSION['ruolo']=$result[0][1];
		$_SESSION['user']=1;
	}else{
		$sql = "SELECT * FROM studente WHERE email_studente=:email";
		$stmt = $pdo -> prepare($sql);
		$stmt -> bindValue(':email',$email);
		$stmt -> execute();
		$result = $stmt->fetchALL();
		if(count($result)>0){
			$_SESSION['data_nascita']=$result[0][1];
			$_SESSION['luogo_nascita']=$result[0][2];
			$_SESSION['anno_immatricolazione']=$result[0][3];
			$_SESSION['corso']=$result[0][4];
			$_SESSION['user']=0;
		}else{
			echo "Errore! Contattare l'amministratore";
			exit();
		}
	}
	header("Location: ../homepage.php");
}else{
	$_SESSION['error']=true;
	header("Location: ../index.php");
}
exit();
}catch(PDOException $e){
	echo "Errore database: ".$e->getMessage();
	exit();
}
?>