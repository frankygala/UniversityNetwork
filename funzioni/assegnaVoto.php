<?php
	require_once('database.php');
	session_start();
	if($_SESSION['user']==1){
	$sql = "UPDATE progetto SET voto=:voto WHERE idprogetto=:idprogetto";
	$pdo = $databaseConnection -> getPdo();
	if(isset($_POST['voto']) && isset($_POST['idprogetto']) && $_POST['voto']>17 && $_POST['voto']<31){
	$stmt = $pdo -> prepare($sql);
	$stmt -> bindParam(':voto',$_POST['voto'],PDO::PARAM_STR);
	$stmt -> bindParam(':idprogetto',$_POST['idprogetto'],PDO::PARAM_STR);
	if($stmt -> execute()){
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
}else{
	$message['success']=0;
	echo json_encode($message);
}
?>
