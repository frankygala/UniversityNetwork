<?php
require_once("database.php");
session_start();
$pdo = $databaseConnection -> getPdo();
$sql = "CALL DeleteAccount(:email)";
$stmt = $pdo -> prepare($sql);
$stmt -> bindParam(":email",$_SESSION['email'],PDO::PARAM_STR);
if($stmt->execute()){
	session_destroy();
	$message['success']=1;
	echo json_encode($message);
}
else{
	$message['success']=0;
	echo json_encode($message);
}
?>