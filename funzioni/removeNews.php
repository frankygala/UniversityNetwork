<?php
	require_once('database.php');
	if(isset($_POST['id'])){
		$id = $_POST['id'];
		$pdo = $databaseConnection -> getPdo();
		$sql = "DELETE FROM news WHERE idnews=:id";
		$stmt = $pdo-> prepare($sql);
		$stmt -> bindParam(':id',$id,PDO::PARAM_INT);
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