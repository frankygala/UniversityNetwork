<?php
require_once('funzioni/database.php');
session_start();
$pdo = $databaseConnection -> getPdo();
$sql = "SELECT * FROM appunto,utente,studente WHERE email_stud_app=:email AND email=email_stud_app AND email_studente=email_stud_app";
$stmt = $pdo -> prepare($sql);
$stmt -> bindParam(':email',$_SESSION['email'],PDO::PARAM_STR);
$stmt -> execute();
$result = $stmt -> fetchAll();
if(count($result)):
?>
<h2 class="flow-text" style="text-align:center;">Lista appunti caricati</h2>
<div id="content-result">
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
</div>
	<script type="text/javascript">
			var seeDetailsAppunto = function(elmnt){
				var idappunto = $(elmnt).attr('name');
				if(idappunto!=null){
					$.ajax({
						url: "/funzioni/dettagliAppunto.php",
						type: "POST",
						data:{idappunto:idappunto},
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
<h2 class="flow-text" style="text-align:center;color:red;">Nessun appunto disponibile</h2>
<?php
endif;
?>