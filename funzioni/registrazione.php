<?php
	require_once('database.php');
	session_start();	//inizializza la sessione
	$nome = $_POST['nome'];
	$cognome = $_POST['cognome'];
	$password = $_POST['password'];
	$email = $_POST['email'];
	$op = $_POST['op'];
	$uploadOk = 1;
	try{//inizia qui il try perchè è da qui che ho bisogno del pdo
	$pdo = $databaseConnection->getPdo();
	$sql = "SELECT * FROM utente WHERE email=:email";
	$stmt = $pdo->prepare($sql);
	$stmt -> bindParam(':email',$email,PDO::PARAM_STR);
	$stmt -> execute();
	$result = $stmt -> fetchAll();
	if(count($result)){
		$_SESSION['error_registration']=true;
		$_SESSION['reply']=true;
		header("Location: ../registration-form.php");
		exit();
	}else{
	// Check if image file is a actual image or fake image
	$check = getimagesize($_FILES["foto"]["tmp_name"]);
	$ext = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
	$nameFile = basename($_FILES["foto"]["tmp_name"]).".".$ext;
	$targetDir = "../images/".basename($_FILES["foto"]["tmp_name"]).".".$ext;
	if($check !== false && move_uploaded_file($_FILES["foto"]["tmp_name"],$targetDir)) {
		chmod($targetDir, 0777); 
	    $uploadOk = 1;
	} else {
	    $uploadOk = 0;
	}
	if($op==='0' && $uploadOk===1 && isset($_POST['corso']) && isset($_POST['data_nascita']) && isset($_POST['corso']) && isset($_POST['immatricolazione']) && isset($_POST['luogo_nascita']) && $uploadOk==1){
		$corso = $_POST['corso'];
		$data_nascita = $_POST['data_nascita'];
		$immatricolazione = $_POST['immatricolazione'];
		$luogo_nascita = $_POST['luogo_nascita'];
		$sql = "call InserisciStudente(:email, :password, :nome, :cognome,:data_nascita,:foto,:luogo_nascita,:anno_immatricolazione,:corso)";
		$stmt = $pdo->prepare($sql);
		$stmt -> bindParam(':email',$email,PDO::PARAM_STR);
		$stmt -> bindParam(':nome',$nome,PDO::PARAM_STR);
		$stmt -> bindParam(':cognome',$cognome,PDO::PARAM_STR);
		$stmt -> bindParam(':password',md5($password),PDO::PARAM_STR);
		$stmt -> bindParam(':foto',$nameFile,PDO::PARAM_STR);
		$stmt -> bindParam(':data_nascita',$data_nascita,PDO::PARAM_STR);
		$stmt -> bindParam(':luogo_nascita',$luogo_nascita,PDO::PARAM_STR);
		$stmt -> bindParam(':anno_immatricolazione',$immatricolazione,PDO::PARAM_INT);
		$stmt -> bindParam(':corso',$corso,PDO::PARAM_STR);
		$result = $stmt -> execute();
		if($result){
			$_SESSION['nome']=$nome;
			$_SESSION['data_nascita']=$data_nascita;
			$_SESSION['luogo_nascita']=$luogo_nascita;
			$_SESSION['anno_immatricolazione']=$anno_immatricolazione;
			$_SESSION['corso']=$corso;
			$_SESSION['cognome']=$cognome;
			$_SESSION['email']=$email;
			$_SESSION['welcome']=true;
			$_SESSION['user']=0;
 			$_SESSION['image'] = $nameFile;
			header("Location: ../homepage.php");
			exit();
		}else{
			$_SESSION['error_registration']=true;
			header("Location: ../registration-form.php");
			exit();
		}
	}else if($op==='1' && $uploadOk===1 && isset($_POST['ruolo'])){
			$ruolo = $_POST['ruolo'];
			$sql = "CALL InserisciProfessore (:email, :password, :nome, :cognome, :ruolo, :foto)";
			$stmt = $pdo -> prepare($sql);
			$stmt -> bindParam(':ruolo',$ruolo,PDO::PARAM_STR);
			$stmt -> bindParam(':email',$email,PDO::PARAM_STR);
			$stmt -> bindParam(':nome',$nome,PDO::PARAM_STR);
			$stmt -> bindParam(':cognome',$cognome,PDO::PARAM_STR);
			$stmt -> bindParam(':password',md5($password),PDO::PARAM_STR);
			$stmt -> bindParam(':foto',$nameFile,PDO::PARAM_STR);
			$result = $stmt->execute();
			if($result){
				$_SESSION['nome']=$nome;
				$_SESSION['cognome']=$cognome;
				$_SESSION['ruolo']=$ruolo;
				$_SESSION['email']=$email;
				$_SESSION['welcome']=true;
				$_SESSION['user']=1;
 				$_SESSION['image'] = $nameFile;
				header("Location: ../homepage.php");
				exit();
			}else{
			$_SESSION['error_registration']=true;
			header("Location: ../registration-form.php");
			exit();
		}
	}
}
}catch(PDOException $e){
	echo "Errore database: ".$e->getMessage();
	exit();
}
?>