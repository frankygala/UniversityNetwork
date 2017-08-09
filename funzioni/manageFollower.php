<?php
require_once('database.php');
session_start();
$pdo = $databaseConnection->getPdo();
if(isset($_POST['email_followed']) && isset($_POST['op'])){
	$email_followed=$_POST['email_followed'];
	$op=$_POST['op'];
	if($op==='0'){
		$sql = "DELETE FROM follower WHERE email_follower=:email_follower AND email_followed=:email_followed";
	}else if($op==='1'){
		$sql = "INSERT into follower (email_follower,email_followed) VALUES (:email_follower,:email_followed)";
	}
	$stmt = $pdo->prepare($sql);
	$stmt->bindParam(":email_followed",$email_followed,PDO::PARAM_STR);
	$stmt->bindParam(":email_follower",$_SESSION['email'],PDO::PARAM_STR);
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