<?php
require_once('funzioni/database.php');
session_start();
$pdo = $databaseConnection -> getPdo();
$sql = "SELECT * FROM corso,utente,docente WHERE email=email_doc AND email_professore=email";
$stmt = $pdo -> prepare($sql);
$stmt -> execute();
$result = $stmt -> fetchAll();
if(count($result)):
?>
<h2 class="flow-text" style="text-align:center;">Lista corsi disponibili</h2>
<ul class="collection" style="max-height:600px;overflow-y:auto;">
		<?php
		for($i=0;$i<count($result);$i++){
		?>
		<li class="collection-item avatar" style="height:100%;">
			<img src="../images/<?php echo $result[$i][10]; ?>" alt="Immagine profilo" class="circle">
			<span class="title">
				<?php echo "Nome: ".$result[$i][3];
			 	?>
			</span>
			<p><?php echo "Creato da: ".$result[$i][8].' '.$result[$i][9].' ('.$result[$i][1].')';
			 	?></p>
			<div class="secondary-content">
				<p><?php echo "Anno: ".$result[$i][2]; ?></p>
				
			</div>
			<p><?php echo "Corso di laurea: ".$result[$i][5]; ?></p>
			<p>
				<?php echo "Numero crediti: ".$result[$i][4]; ?>
			</p>
			<a class="btn" onclick="listaAppunti(this)" name="<?php echo $result[$i][0]; ?>" style="font-size:12px;">Visualizza appunti</a>
			<a class="btn" name="<?php echo $result[$i][0]; ?>" onclick="listaProgetti(this)" style="font-size:12px;">Visualizza progetti</a>
			<?php
				if($_SESSION['user']==0):
			?>
			<a name="<?php echo $result[$i][0]; ?>" class="btn" onclick="inserisciAppuntoForm(this)" style="font-size:12px;">Inserisci appunto</a>
			<a name="<?php echo $result[$i][0]; ?>" onclick="inserisciProgetto(this)" class="btn" style="font-size:12px;">Inserisci progetto</a>
			<?php
			endif;
			?>
		</li>
		<?php
		}
		?>
	</ul>
	<script type="text/javascript">
		var inserisciProgetto = function(elmnt){
			var idcorso = $(elmnt).attr('name');
			if(idcorso!=null){
				$.ajax({
					url: "insertProgettoForm.php",
					type: "POST",
					data: {idcorso:idcorso},
					success: function(data){
						$('#content-target').empty();
						$('#content-target').append(data);
					},
					error: function(){
						alertify.alert("Errore nella trasmissione dei dati. Riprovare.");
					}
				});
			}else{
				alertify.alert("Errore nella trasmissione dei dati. Riprovare.");
			}
		};
		var listaAppunti = function(elmnt){
			var idcorso = $(elmnt).attr('name');
			if(idcorso!=null){
				$.ajax({
					url: "listaAppunti.php",
					type: "POST",
					data: {idcorso:idcorso},
					success: function(data){
						$('#content-target').empty();
						$('#content-target').append(data);
					},
					error: function(){
						alertify.alert("Errore nella trasmissione dei dati. Riprovare.");
					}
				});
			}else{
				alertify.alert("Errore nella trasmissione dei dati. Riprovare.");
			}
		};	
		var listaProgetti = function(elmnt){
			var idcorso = $(elmnt).attr('name');
			if(idcorso!=null){
				$.ajax({
					url: "listaProgetti.php",
					type: "POST",
					data: {idcorso:idcorso},
					success: function(data){
						$('#content-target').empty();
						$('#content-target').append(data);
					},
					error: function(){
						alertify.alert("Errore nella trasmissione dei dati. Riprovare.");
					}
				});
			}else{
				alertify.alert("Errore nella trasmissione dei dati. Riprovare.");
			}
		};	
		var inserisciAppuntoForm = function(elmnt){
			var idcorso = $(elmnt).attr('name');
			if(idcorso!=null){
				$.ajax({
					url: "inserisciAppuntoForm.php",
					type: "POST",
					data: {idcorso:idcorso},
					success: function(data){
						$('#content-target').empty();
						$('#content-target').append(data);
					},
					error: function(){
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
<h2 class="flow-text" style="text-align:center;color:red;">Nessun corso presente</h2>
<?php
endif;
?>