<?php
require_once("database.php");
session_start();
date_default_timezone_set('Europe/Rome');
$uploaded = true;
$arrayCaricati = [];
$arrayCaricatiSql = [];
$arrayNonCaricati = [];
$ok=true;
for($i=0;$i<count($_FILES['file']['name']);$i++){
$ext = pathinfo($_FILES['file']['name'][$i], PATHINFO_EXTENSION);
$nameFile = basename($_FILES["file"]["tmp_name"][$i]).".".$ext;
$targetDir = "../files/".basename($_FILES["file"]["tmp_name"][$i]).".".$ext;
if(($ext=='pdf' || $ext=='zip' || $ext=='jpg' || $ext=='png' || $ext=='gif') && move_uploaded_file($_FILES["file"]["tmp_name"][$i],$targetDir)){
	chmod($targetDir, 0777);
	$caricato = basename($_FILES["file"]["name"][$i]).".".$ext;
	$caricatoSql = basename($_FILES["file"]["tmp_name"][$i]).".".$ext;
	array_push($arrayCaricati,$caricato);
	array_push($arrayCaricatiSql,$caricatoSql);
}
else{
	$uploaded=false;
	$nonCaricato = basename($_FILES["file"][$i]["name"]).".".$ext;
	array_push($arrayNonCaricati,$nonCaricato);
	break;
}
}
if(($ext=='pdf' || $ext=='zip' || $ext=='jpg' || $ext=='png' || $ext=='gif') && $uploaded) :
	$idcorso = $_POST['idcorso'];
	$titolo = $_POST['titolo'];
	$data_fine = $_POST['data_fine'];
	$data_inizio = $_POST['data_inizio'];
	$descrizione = $_POST['descrizione'];
	$date = date('Y/m/d H:i:s', time());
	$pdo = $databaseConnection -> getPdo();
	$sql = "CALL CreaProgetto (:email_partecipante_progetto,:nome,:descrizione,:data_inizio,:data_fine,:tipo,:codice_corso,'null')";
	$stmt = $pdo -> prepare($sql);
   	$stmt -> bindParam(":email_partecipante_progetto",$_SESSION['email'],PDO::PARAM_STR);
   	$stmt -> bindParam(":nome",$titolo,PDO::PARAM_STR);
   	$stmt -> bindParam(":descrizione",$descrizione,PDO::PARAM_STR);
   	$stmt -> bindParam(":data_inizio",$data_inizio,PDO::PARAM_STR);
   	$stmt -> bindParam(":data_fine",$data_fine,PDO::PARAM_STR);
   	$stmt -> bindValue(":tipo",'universitario',PDO::PARAM_STR);
   	$stmt -> bindParam(":codice_corso",$idcorso,PDO::PARAM_STR);
   	if($stmt->execute()==false){
   			$ok = false;
   		}else{
   			$sql = "SELECT MAX(idprogetto) AS id FROM progetto";
   			$stmt = $pdo -> prepare($sql);
   			$stmt -> execute();
   			$resultId = $stmt -> fetchAll();
   			$id=$resultId[0]['id'];
   		for($i=0;$i<count($arrayCaricatiSql);$i++){
   			$sqlInsertAllegati = "INSERT INTO allegato (id_prog_allegato,file,tipo) VALUES (:id_prog_allegato,:file,:tipo)";		
   			$stmt = $pdo -> prepare($sqlInsertAllegati);
			$stmt -> bindParam(":id_prog_allegato",$id,PDO::PARAM_STR);
			$stmt -> bindParam(":file",$arrayCaricatiSql[$i],PDO::PARAM_STR);
			$ext = substr($arrayCaricatiSql[$i], strpos($arrayCaricatiSql[$i]+1, '.'), strlen($arrayCaricatiSql[$i]));
			if($ext=='pdf'){
				$stmt -> bindValue(":tipo",'documentazione',PDO::PARAM_STR);
		   	}else if($ext=='zip'){
		   		$stmt -> bindValue(":tipo",'sorgente',PDO::PARAM_STR);
		   	}else{
		   		$stmt -> bindValue(":tipo",'screenshot',PDO::PARAM_STR);
		   	}
		   	if($stmt->execute() == false){
		   		$ok = false;
		   		break;
		   	}	
		}
   	}

   	if($ok):
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
alertify.alert("Progetto caricato con successo!",function(){
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
alertify.alert("Errore caricamento progetto!",function(){
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