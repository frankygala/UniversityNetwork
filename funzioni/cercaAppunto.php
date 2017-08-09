<?php
	require_once('database.php');
	session_start();
	if(isset($_POST['idcorso']) && isset($_POST['testo'])):
	$idcorso = $_POST['idcorso'];
	$testo = $_POST['testo'];
	$sql = "SELECT * FROM appunto,utente,studente WHERE codice_corso_associato=:idcorso AND appunto.titolo LIKE '%$testo%' AND email=email_stud_app AND email_studente=email_stud_app";
	$pdo = $databaseConnection -> getPdo();
	$stmt = $pdo -> prepare($sql);
	$stmt -> bindParam(':idcorso',$idcorso,PDO::PARAM_STR);
	$stmt -> execute();
	$result = $stmt -> fetchAll();
	if(count($result)):
?>
<ul class="collection" style="max-height:600px;overflow-y:auto;">
		<?php
		for($i=0;$i<count($result);$i++){
		?>
		<li class="collection-item avatar" style="height:100%;">
			<img src="../images/<?php echo $result[$i][14]; ?>" alt="Immagine profilo" class="circle">
			<span class="title">
				<?php echo "Titolo: ".$result[$i][6];
			 	?>
			</span>
			<p><?php echo "Creato da: ".$result[$i][12].' '.$result[$i][13].' ('.$result[$i][1].')';
			 	?></p>
			<div class="secondary-content">
				<p><?php echo "Caricato il: ".substr($result[$i][4],0,16); ?></p>
			</div>
			<a class="btn" name="<?php echo $result[$i][0]; ?>" onclick="seeDetailsAppunto(this)" style="font-size:12px;">Visualizza dettagli</a>
		</li>
		<?php
		}
		?>
	</ul>
<?php
	else:
?>
<h2 class="flow-text" style="text-align:center;color:red;">Nessun match</h2>
<?php
	endif;
?>
<?php
	else:
?>
<h2 class="flow-text" style="text-align:center;color:red;">Errore ricezione dati</h2>
<?php
	endif;
?>