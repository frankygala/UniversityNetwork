<h2 class="flow-text" style="text-align:center;">Chi mi segue</h2>
<?php
require_once("database.php");
session_start();
$pdo = $databaseConnection -> getPdo();
$sql = "SELECT nome,cognome,email,foto,corso_laurea FROM follower, utente, studente WHERE follower.email_followed=:email_followed AND utente.email=email_follower AND studente.email_studente=utente.email
		UNION ALL
		SELECT nome,cognome,email,foto,ruolo FROM follower, utente, docente WHERE follower.email_followed=:email_followed AND utente.email=email_follower AND docente.email_professore=utente.email
		";
$stmt = $pdo -> prepare($sql);
$stmt -> bindParam(':email_followed',$_SESSION['email'],PDO::PARAM_STR);
$stmt -> execute();
$result = $stmt -> fetchAll();
if($result != null && count($result)):
?>
	<ul class="collection">
		<?php
		for($i=0;$i<count($result);$i++){
		?>
		<li class="collection-item avatar" style="height:100%;">
			<img src="/images/<?php echo $result[$i][3]; ?>" alt="Immagine profilo" class="circle">
			<span class="title">
				<?php echo $result[$i][0].' '.$result[$i][1];
			 	?>
			</span>
			<p>
				<?php
				echo "Email: ".$result[$i][2];
				?>
				<br>
				<?php
				echo $result[$i][4];
				?>
			</p>
			<a class="btn" name="<?php echo $result[$i][2]; ?>" onclick="profilo(this)" style="font-size:12px;">
				Visualizza profilo
			</a>
		</li>
		<?php
		}
		?>
	</ul>
	<script>
	var profilo = function(elmnt){
	var emailSearch = $(elmnt).attr('name');
	$.ajax({
			url:"../profiloSearch.php",
			type:"POST",
			data:{email:emailSearch},
			success: function(data){
				$('#content-target').empty();
				$('#content-target').append(data);
			},
			error:function(data){

			}
		}); 
};
	</script>
	<?php
	else:
	?>
	<h2 class="flow-text" style="text-align:center;color:red;">Attualmente nessuno ti segue</h2>
	<?php
		endif;
	?>