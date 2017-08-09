<?php
require_once('database.php');
session_start();
if(isset($_POST['corso']) && isset($_POST['anno']) && isset($_POST['codice']) && isset($_POST['nome']) && isset($_POST['crediti'])){
	$corso = $_POST['corso'];
	$anno = $_POST['anno'];
	$codice = $_POST['codice'];
	$nome = $_POST['nome'];
	$crediti = $_POST['crediti'];
	$pdo = $databaseConnection->getPdo();
	$sqlCheck = "s";
	$stmt = $pdo -> prepare($sqlCheck);
	$stmt -> bindParam(':codice',$codice,PDO::PARAM_INT);
	$stmt -> bindParam(':anno',$anno,PDO::PARAM_INT);
	$stmt->execute();
	$result = $stmt->fetchAll();
	if(count($result)){
	$message['success']=0;
	$message['reply']=1;
	echo json_encode($message);
	}else{
	$sql = "INSERT INTO corso (codice,email_doc,anno,nome,numero_crediti,corso_laurea) VALUES (:codice,:email_doc,:anno,:nome,:numero_crediti,:corso_laurea)";
	$stmt = $pdo -> prepare($sql);
	$stmt -> bindParam(':codice',$codice,PDO::PARAM_INT);
	$stmt -> bindParam(':email_doc',$_SESSION['email'],PDO::PARAM_STR);
	$stmt -> bindParam(':anno',$anno,PDO::PARAM_INT);
	$stmt -> bindParam(':nome',$nome,PDO::PARAM_STR);
	$stmt -> bindParam(':numero_crediti',$crediti,PDO::PARAM_INT);
	$stmt -> bindParam(':corso_laurea',$corso,PDO::PARAM_STR);
	$result = $stmt->execute();
	if($result){
	$message['success']=1;
	echo json_encode($message);
	}else{
	$message['success']=0;
	$message['reply']=0;
	echo json_encode($message);
	}
}
	}else{
	$message['success']=0;
	echo json_encode($message);
}

?>