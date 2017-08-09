<?php
	require_once('database.php');
	session_start();
	$pdo = $databaseConnection -> getPdo();
	$sql = "SELECT * FROM appunto,utente,studente,corso WHERE idappunto=:idappunto AND email=email_stud_app AND email_studente=email AND codice=codice_corso_associato";
	if(isset($_POST['idappunto'])):
		$id = $_POST['idappunto'];
		$stmt = $pdo -> prepare($sql);
		$stmt -> bindParam(":idappunto",$id,PDO::PARAM_INT);
		$stmt -> execute();
		$result = $stmt -> fetchAll();
		if(count($result)):
			$sql = "SELECT * FROM commento,utente,studente WHERE id_appunto=:idappunto AND email=email_creatore AND email_studente=email";
			$stmt = $pdo -> prepare($sql);	
			$stmt -> bindParam(":idappunto",$id,PDO::PARAM_INT);
			$stmt -> execute();
			$resultCommenti = $stmt -> fetchAll();
	?>
	<h2 class="flow-text" style="text-align:center;">Dettagli appunto</h2>

	<div class="row">
		<div class="col s12">
			<p class="title">
				<?php echo "Titolo: ".$result[0][6];
			 	?>
			</p>
			<p class="title">
				<?php echo "Descrizione: ".$result[0][3];
			 	?>
			</p>
			<p style="font-weight:bold;"><p><?php echo "Creato da: ".$result[0][12].' '.$result[0][13].' ('.$result[0][1].')';
			 	?></p>
			<p>Corso di appartenenza:  <?php echo $result[0][26]."(".$result[0][28].")";?> </p>
			<p><?php echo "Caricato il: ".substr($result[0][4],0,16); ?></p>
			<p>Valutazione media: <?php echo $result[0][9]; ?></p>
		</div>
	</div>
	
	<div class="row" style="margin-top:180px;">
		<a class="btn green" target="blank" href="../files/<?php echo $result[0][5]; ?>">Visualizza allegato</a>
	</div>
	
	<?php
		if(count($resultCommenti)):
	?>
	<div class="row">
	<div class="col s12">
		<h2 class="flow-text" style="text-align:center;color:green;">Commenti presenti: <?php echo count($resultCommenti); ?></h2>
		<ul class="collection" style="max-height:400px;overflow-y:auto;">
		<?php
		for($i=0;$i<count($resultCommenti);$i++){
		?>
		<li class="collection-item avatar" style="height:100%;">
			<img src="../images/<?php echo $resultCommenti[$i][10]; ?>" alt="Immagine profilo" class="circle">
			
			<p><?php echo "Creato da: ".$resultCommenti[$i][8].' '.$resultCommenti[$i][9].' ('.$resultCommenti[$i][2].')';
			 	?></p>
			<p class="title">
				Descrizione:
				<br>
				<?php echo $resultCommenti[$i][3];?>
			</p>
			<p>Valutazione: <?php echo $resultCommenti[$i][5];?> </p>
			<div class="secondary-content">
				<p><?php echo "Caricato il: ".substr($resultCommenti[$i][4],0,16); ?></p>
			</div>
		</li>
		<?php
		}
		?>
	</ul>
	<?php
		$sql = "SELECT * FROM follower WHERE email_follower=:email_follower AND email_followed=:email_followed";
		$stmt = $pdo -> prepare($sql);
		$stmt -> bindParam(":email_follower",$_SESSION['email'],PDO::PARAM_STR);
		$stmt -> bindParam(":email_followed",$result[0][1],PDO::PARAM_STR);
		$stmt -> execute();
		$resultFollo = $stmt -> fetchAll();
		if($result[0][1]==$_SESSION['email'] || count($resultFollo)):
	?>
	<a class="btn" onclick="caricaCommento(this)" name="<?php echo $id;?>">Carica commento</a>
	<?php
		else:
	?>
		<a class="btn disabled">Carica commento</a>
		<h4 class="flow-text" style="color:red;">Devi essere un follower per commentare!</h4>
	<?php
		endif;
	?>
	</div>
	</div>
	<?php
		else:
	?>
		<h2 class="flow-text" style="text-align:center;color:red;">Nessuno commento presente</h2>
		<?php
		$sql = "SELECT * FROM follower WHERE email_follower=:email_follower AND email_followed=:email_followed";
		$stmt = $pdo -> prepare($sql);
		$stmt -> bindParam(":email_follower",$_SESSION['email'],PDO::PARAM_STR);
		$stmt -> bindParam(":email_followed",$result[0][1],PDO::PARAM_STR);
		$stmt -> execute();
		$resultFollo = $stmt -> fetchAll();
		if($result[0][1]==$_SESSION['email'] || count($resultFollo)):
	?>
	<a class="btn" onclick="caricaCommento(this)" name="<?php echo $id;?>">Carica commento</a>
	<?php
		else:
	?>
		<a class="btn disabled">Carica commento</a>
		<h4 class="flow-text" style="text-align:center;color:red;">Devi essere un follower per commentare!</h4>
	<?php
		endif;
	?>
	<?php
		endif;
	?>
	</div>
	<script type="text/javascript">
		var caricaCommento = function(elmnt){
			var id = $(elmnt).attr('name');
			if(id!=null){
				$.ajax({
					url:'/funzioni/insertCommentoForm.php',
					type:"POST",
					data: {idappunto:id},
					success: function(data){
						$('#content-target').empty();
						$('#content-target').append(data);
					},
					error: function(){
						alertify.alert("Errore durante la tramissione dei dati.");
					}
				});
			}else{	
				alertify.alert("Errore durante la tramissione dei dati.");
			}
		};	
	</script>
	<?php 
	else:
	?>
	<h2 class="flow-text red" style="text-align:center;">Errore trasmissione dati</h2>
	<?php
	endif;
	else:
	?>	
	<h2 class="flow-text red" style="text-align:center;">Errore ricezione dei dati</h2>
<?php
endif;
?>