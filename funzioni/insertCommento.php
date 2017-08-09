<?php
require_once('database.php');
session_start();
date_default_timezone_set('Europe/Rome');
$pdo = $databaseConnection -> getPdo();
if(isset($_POST['testo']) && isset($_POST['valutazione']) && isset($_POST['idappunto']) && $_POST['valutazione']>=0 && $_POST['valutazione']<6){
	$date = date('Y/m/d H:i:s', time());
	$testo = $_POST['testo'];
	$valutazione = $_POST['valutazione'];
	$idappunto = $_POST['idappunto'];
	$sql="INSERT INTO commento (id_appunto,email_creatore,testo,data,valutazione) values (:idappunto,:email_creatore,:testo,:data,:valutazione)";
	$stmt = $pdo -> prepare($sql);
	$stmt -> bindParam(':email_creatore',$_SESSION['email'],PDO::PARAM_STR);
	$stmt -> bindParam(':testo',$testo,PDO::PARAM_STR);
	$stmt -> bindParam(':valutazione',$valutazione, PDO::PARAM_STR);
	$stmt -> bindParam(':idappunto',$idappunto, PDO::PARAM_STR);
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