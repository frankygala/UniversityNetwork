<?php
require_once("database.php");
session_start();
date_default_timezone_set('Europe/Rome');
$check = $_FILES["file"]["size"];
$ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
$nameFile = basename($_FILES["file"]["tmp_name"]).".".$ext;
$targetDir = "../files/".basename($_FILES["file"]["tmp_name"]).".".$ext;
if(($ext=='pdf' || $ext=='xmind' || $ext=='jpg' || $ext=='png' || $ext=='gif') && $check !== false && move_uploaded_file($_FILES["file"]["tmp_name"],$targetDir)) :
	chmod($targetDir, 0777); 
	$date = date('Y/m/d H:i:s', time());
	$pdo = $databaseConnection -> getPdo();
   	$sql = "INSERT INTO elaborato (id_esercitazione,email_studente_elaborato,commento,allegato,data_upload) VALUES (:id_esercitazione,:email_studente_elaborato,:commento,:allegato,:data_upload)";
   	$stmt = $pdo -> prepare($sql);
   	$stmt -> bindParam(":id_esercitazione",$_POST['idesercitazione'],PDO::PARAM_STR);
   	$stmt -> bindParam(":email_studente_elaborato",$_SESSION['email'],PDO::PARAM_STR);
   	$stmt -> bindParam(":commento",$_POST['commento'],PDO::PARAM_STR);
   	$stmt -> bindParam(":allegato",$nameFile,PDO::PARAM_STR);
   	$stmt -> bindParam(":data_upload",$date,PDO::PARAM_STR);
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
alertify.alert("Elaborato caricato con successo!",function(){
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
alertify.alert("Errore caricamento elaborato!",function(){
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