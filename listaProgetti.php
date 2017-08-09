<?php
require_once('funzioni/database.php');
session_start();
$pdo = $databaseConnection -> getPdo();
$sql = "SELECT * FROM progetto WHERE codice_corso=:idcorso";
$stmt = $pdo -> prepare($sql);
$stmt -> bindParam(':idcorso',$_POST['idcorso'],PDO::PARAM_STR);
$stmt -> execute();
$result = $stmt -> fetchAll();
if(count($result)):
	
?>
<h2 class="flow-text" style="text-align:center;">Lista progetti del corso</h2>
<div id="content-result">
<ul class="collection" style="max-height:600px;overflow-y:auto;">
		<?php
		for($i=0;$i<count($result);$i++){
			$id_progetto=$result[$i][0];
			$sql3 = "SELECT COUNT(*) FROM allegato WHERE id_prog_allegato=:id_progetto";
			$stmt = $pdo -> prepare($sql3);
			$stmt -> bindParam(':id_progetto',$id_progetto,PDO::PARAM_STR);
			$stmt -> execute();
			$resultAllegati = $stmt -> fetchAll();
		?>
		<li class="collection-item avatar" style="height:100%;">
			<p class="title">
				<?php echo "Titolo: ".$result[$i][1];
			 	?>
			</p>
			<div class="secondary-content">
				<p><?php echo "Numero partecipanti: ".$result[$i][5];?></p>
				<p><?php echo "Numero allegati: ".$resultAllegati[0][0];?></p>
			</div>
			<a class="btn" name="<?php echo $result[$i][0]; ?>" onclick="seeDetailsProject(this)" style="font-size:12px;margin-top:20px;">Visualizza dettagli</a>
		</li>
		<?php
		}
		?>
	</ul>
</div>
	<script type="text/javascript">
	var seeDetailsProject = function(elmnt){
		var id_progetto = $(elmnt).attr('name');
				if(id_progetto!=null){
					$.ajax({
						url: "/funzioni/dettagliProgetto.php",
						type: "POST",
						data:{id_progetto:id_progetto},
						success:function(data){
							$('#content-target').empty();
							$('#content-target').append(data);
						},
						error:function(){
							alertify.alert("Errore nella trasmissione dei dati");
						}
					});
				}else{
					alertify.alert("Errore caricamento form");
				}
	};	
	</script>
<?php
else:
?>
<h2 class="flow-text" style="text-align:center;color:red;">Nessun progetto disponibile</h2>
<?php
endif;
?>