<?php
require_once('database.php');
session_start();
date_default_timezone_set('Europe/Rome');
$check = $_FILES["file"]["size"];
$ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
$nameFile = basename($_FILES["file"]["tmp_name"]).".".$ext;
$targetDir = "../files/".basename($_FILES["file"]["tmp_name"]).".".$ext;
if($check !== false && move_uploaded_file($_FILES["file"]["tmp_name"],$targetDir)) :
	chmod($targetDir, 0777); 
	$date = date('Y/m/d H:i:s', time());
	$pdo = $databaseConnection -> getPdo();
   	$sql = "CALL CreaAppunto (:email_stud_app,:idcorso,:descrizione,:allegato,:titolo,:tipo)";
   	$stmt = $pdo -> prepare($sql);
   	$stmt -> bindParam(":idcorso",$_POST['idcorso'],PDO::PARAM_INT);
   	$stmt -> bindParam(":email_stud_app",$_SESSION['email'],PDO::PARAM_STR);
   	$stmt -> bindParam(":titolo",$_POST['titolo'],PDO::PARAM_STR);
   	$stmt -> bindParam(":descrizione",$_POST['descrizione'],PDO::PARAM_STR);
   	$stmt -> bindParam(":allegato",$nameFile,PDO::PARAM_STR);
   	if($ext=='pdf'){
   		$stmt -> bindValue(":tipo",'pdf',PDO::PARAM_STR);
   	}else if($ext=='xmind'){
   		$stmt -> bindValue(":tipo",'mappa_mentale',PDO::PARAM_STR);
   	}else{
   		$stmt -> bindValue(":tipo",'foto',PDO::PARAM_STR);
   	}	
   	if($stmt->execute()):
   	?>
   <!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<link rel="shortcut icon" href="../images/favicon.ico" type="image/x-icon">
		<title>University Network</title>
		<link rel="stylesheet" href="../css/materialize.min.css" />
		<link rel="stylesheet" href="../css/style.css" />
		<link rel="stylesheet" href="../css/alertify.core.css" />
		<link rel="stylesheet" href="../css/alertify.default.css" />
		<script src="../lib/jquery-1.10.2.min.js"></script>
		<script type="text/javascript" src="../lib/materialize.min.js"></script>
		<script type="text/javascript" src="../lib/alertify.min.js"></script>
	</head>
	<body>
		<script>
alertify.alert("Appunto caricato con sucesso!",function(){
	window.location.replace("../homepage.php");
});
		</script>
	</body>
	</html>
 
   <?php
   else:
   ?>
	     <!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<link rel="shortcut icon" href="../images/favicon.ico" type="image/x-icon">
		<title>University Network</title>
		<link rel="stylesheet" href="../css/materialize.min.css" />
		<link rel="stylesheet" href="../css/style.css" />
		<link rel="stylesheet" href="../css/alertify.core.css" />
		<link rel="stylesheet" href="../css/alertify.default.css" />
			<script src="../lib/jquery-1.10.2.min.js"></script>
		<script type="text/javascript" src="../lib/materialize.min.js"></script>
		<script type="text/javascript" src="../lib/alertify.min.js"></script>
	</head>
	<body>
		<script>
alertify.alert("Errore caricamento appunto!",function(){
	window.location.replace("../homepage.php");
});
		</script>
	</body>
	</html>
 
<?php
endif;
?>
<?php
   else:
   ?>
   <!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<link rel="shortcut icon" href="../images/favicon.ico" type="image/x-icon">
		<title>University Network</title>
		<link rel="stylesheet" href="../css/materialize.min.css" />
		<link rel="stylesheet" href="../css/style.css" />
		<link rel="stylesheet" href="../css/alertify.core.css" />
		<link rel="stylesheet" href="../css/alertify.default.css" />
			<script src="../lib/jquery-1.10.2.min.js"></script>
		<script type="text/javascript" src="../lib/materialize.min.js"></script>
		<script type="text/javascript" src="../lib/alertify.min.js"></script>
	</head>
	<body>
	
		<script>
alertify.alert("Errore caricamento file!",function(){
	window.location.replace("../homepage.php");
});
		</script>
	</body>
	</html>
 
<?php
endif;
?>