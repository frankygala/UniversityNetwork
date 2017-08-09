<?php
require_once('database.php');
session_start();
if(isset($_POST['idesercitazione'])):
$pdo = $databaseConnection -> getPdo();
$sql = "SELECT * FROM elaborato,utente,studente WHERE id_esercitazione=:idesercitazione AND email=email_studente_elaborato AND email_studente=email";
$stmt = $pdo -> prepare($sql);
$stmt -> bindParam(":idesercitazione",$_POST['idesercitazione'],PDO::PARAM_STR);
$stmt -> execute();
$result = $stmt -> fetchAll();
if(count($result)):
?>
<h2 class="flow-text" style="text-align:center;">Lista elaborati</h2>
<ul class="collection" style="max-height:600px;overflow-y:auto;">
		<?php
		for($i=0;$i<count($result);$i++){
		?>
		<li class="collection-item avatar" style="height:100%;">
			<img src="../images/<?php echo $result[$i][10]; ?>" alt="Immagine profilo" class="circle">
			<span class="title">
				<?php echo "Caricato da: ".$result[$i][8].' '.$result[$i][9];
			 	?>
			</span>
			<p>Email: <?php echo $result[$i][2]; ?></p>
			<div class="secondary-content">
				<p><?php echo "Caricato: ".substr($result[$i][5],0,16); ?></p>
			</div>
			<p style="font-weight:bold;">Commento:</p>
			<p>
				<?php echo $result[$i][3]; ?>
			</p>
			<a href="../files/<?php echo $result[$i][4]; ?>" target="blank" class="btn green" style="font-size:12px;color:white;">Scarica allegato</a>
		</li>
		<?php
		}
		?>
	</ul>
<?php
else:
?>
<h2 class="flow-text" style="text-align:center;color:red;">Nessun elaborato presente</h2>
<?php
endif;
else:
?>
<h2 class="flow-text" style="text-align:center;color:red;">Errore nella ricezione dei dati</h2>
<?php
endif;
?> 