<?php
	require_once('database.php');
	session_start();
	$pdo = $databaseConnection -> getPdo();
	$sql = "SELECT * FROM esercitazione WHERE idesercitazione=:id";
	if(isset($_POST['id'])):
		$id = $_POST['id'];
		$stmt = $pdo -> prepare($sql);
		$stmt -> bindParam(":id",$id,PDO::PARAM_INT);
		$stmt -> execute();
		$result = $stmt -> fetchAll();
		if(count($result)):
			$sql2 = "SELECT * FROM utente,docente,corso WHERE email=:email AND docente.email_professore=utente.email AND corso.codice=:codiceCorso";
			$stmt2 = $pdo -> prepare($sql2);
			$stmt2 -> bindParam(":email",$result[0][1],PDO::PARAM_STR);
			$stmt2 -> bindParam(":codiceCorso",$result[0][2],PDO::PARAM_INT);
			$stmt2 -> execute();
			$resultUser = $stmt2 -> fetchAll();
	?>
	<h2 class="flow-text" style="text-align:center;">Dettagli esercitazione</h2>
	<div class="row">
		<div class="col s6">
			<p style="font-weight:bold;">Creata da:<?php echo $resultUser[0][2]." ".$resultUser[0][3]." (".$resultUser[0][6].")"; ?></p>
			<p style="width:130%;">Corso di appartenenza:  <?php echo $resultUser[0][11]."(".$resultUser[0][13].")";?> </p>
		</div>
	</div>
	<div class="row" syle="margin-top: 10px;">
		<div class="col s12">
	  		<p>Inizio: <?php echo $result[0][4];?></p>
	  			<?php 
			$today = date('Y-m-d');
			$dataFineProgetto = date('Y-m-d',strtotime($result[0][5]));
			if($today>$dataFineProgetto):
			?>
			<p style="color:red;">Data fine:
				 <?php echo $result[0][5];?>
			</p>
			<?php
				else:
			?>
			<p>Data fine:
				 <?php echo $result[0][5];?>
			</p>
			<?php
				endif;
			?>
	 		<p>Matura voto: <?php if($result[0][6]) echo "Si"; else echo "No"; ?></p>
		</div>
	</div>
	<div class="row" style="margin-top:50px;">
		<div class="col s12">
		<p style="font-weight:bold;">Descrizione:</p>
		<p style="padding:0;">
			<?php echo trim($result[0][3]); ?>
		</p>
		</div>
	</div>
	<div class="row" style="margin-top:100px;">
		
		<?php
			if($_SESSION['user']==1):
		?>
			<div class="col s4">
				<button class="btn" onclick="loadListElaborati(this)" name="<?php echo $id; ?>" style="font-size:12px;padding-right: 12px;padding-left: 12px;">Visualizza elaborati</button>
			</div>
		<?php
		else: 
			if($today>$dataFineProgetto):
			?>
			<div class="col s4">
				<button class="btn disabled" name="<?php echo $id; ?>" style="font-size:12px;padding-right: 12px;padding-left: 12px;">Inserisci elaborato</button>
				<h2 class="flow-text" style="text-align:center;color:red">Esercitazione terminata</h2>
			</div>
			<?php
				else:
			?>
			<div class="col s4">
				<button class="btn" name="<?php echo $id; ?>" onclick="loadFormElaborato(this)" style="font-size:12px;padding-right: 12px;padding-left: 12px;">Inserisci elaborato</button>
			</div>
			<?php
				endif;
			?>
		
		<?php
		endif;
		?>
	</div>
	<script>
	var loadListElaborati = function(elmnt){
		var idesercitazione = $(elmnt).attr('name');
		if(idesercitazione>0){
			$.ajax({
				url: "/funzioni/listaElaborati.php",
				type: "POST",
				data: {idesercitazione:idesercitazione},
				success: function(data){
					$('#content-target').empty();
					$('#content-target').append(data);
				},
				error:function(){
					alertify.alert("Errore nella trasmissione dei dati. Riprovare.");
				}
			});
		}else{
			alertify.alert("Errore nella trasmissione dei dati. Riprovare.");
		}
	};
	var loadFormElaborato = function(elmnt){
		var idesercitazione = $(elmnt).attr('name');
		if(idesercitazione>0){
			$.ajax({
				url: "../insertElaborato.php",
				type: "POST",
				data: {idesercitazione:idesercitazione},
				success: function(data){
					$('#content-target').empty();
					$('#content-target').append(data);
					
				},
				error:function(){
					alertify.alert("Errore nella trasmissione dei dati. Riprovare.");
				}
			});
		}else{
			alertify.alert("Errore nella trasmissione dei dati. Riprovare.");
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
	<h2 class="flow-text red" style="text-align:center;">Errore trasmissione dati</h2>
<?php
endif;
?>