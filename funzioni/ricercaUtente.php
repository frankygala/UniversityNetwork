<?php
require_once('database.php');
session_start();
if(isset($_POST['testo'])){
	$pdo = $databaseConnection->getPdo();
	$testo = $_POST['testo'];
		if(strpos($testo,'@')){	//se contiene una chiocciola stiamo parlando di ricerca utente per email  
			$sql = "SELECT nome,cognome,email,foto,corso_laurea FROM utente,studente WHERE email=:email AND email!=:email_user
			AND email_studente=:email AND stato='1'
			UNION ALL
			SELECT nome,cognome,email,foto,ruolo FROM utente,docente WHERE email=:email AND email_professore=:email AND email!=:email_user AND stato='1'";
			$stmt = $pdo->prepare($sql);
			$stmt -> bindParam(':email',$testo,PDO::PARAM_STR);
			$stmt -> bindParam(':email_user',$_SESSION['email'],PDO::PARAM_STR);
			$stmt->execute();
			$result = $stmt->fetchAll();
		}else if(strpos($testo, " ")){ //se contiene un spazio bianco stiamo parlando di ricerca per nome e cognome
			$nome = substr($testo, 0,strpos($testo, " "));
			$cognome = substr($testo, strpos($testo, " ")+1,strlen($testo));
			$sql = "SELECT nome,cognome,email,foto,corso_laurea FROM utente,studente WHERE utente.nome=:nome AND utente.email!=:email_user AND utente.cognome=:cognome AND studente.email_studente=utente.email AND stato='1'
			UNION ALL
			SELECT nome,cognome,email,foto,ruolo FROM utente,docente WHERE utente.nome=:nome AND utente.cognome=:cognome AND utente.email!=:email_user AND docente.email_professore=utente.email AND stato='1'";
			$stmt = $pdo->prepare($sql);
			$stmt -> bindParam(':nome',$nome,PDO::PARAM_STR);
			$stmt -> bindParam(':cognome',$cognome,PDO::PARAM_STR);
			$stmt -> bindParam(':email_user',$_SESSION['email'],PDO::PARAM_STR);
			$stmt->execute();
			$result = $stmt->fetchAll();	
		}else{
			$sql = "SELECT nome,cognome,email,foto,corso_laurea FROM utente,studente WHERE studente.email_studente=utente.email AND utente.email!=:email_user AND stato='1' AND (utente.nome LIKE '$testo%' OR utente.cognome LIKE '$testo%')
			UNION ALL
			SELECT nome,cognome,email,foto,ruolo FROM utente,docente WHERE docente.email_professore=utente.email  AND utente.email!=:email_user AND stato='1' AND (utente.nome LIKE '$testo%' OR utente.cognome LIKE '$testo%')";
			$stmt = $pdo->prepare($sql);
			$stmt -> bindParam(':testo',$testo,PDO::PARAM_STR);
			$stmt -> bindParam(':email_user',$_SESSION['email'],PDO::PARAM_STR);
			$stmt->execute();
			$result = $stmt->fetchAll();
		}
	}
	?>
	<?php 
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
				<a class="btn" onclick="profilo(this)" name="<?php echo $result[$i][2]; ?>" style="font-size:12px;">
					Visualizza profilo
				</a>
				<?php
				$sql = "SELECT * FROM follower WHERE email_followed=:email_followed AND email_follower=:email_follower";
				$stmt = $pdo -> prepare($sql);
				$stmt -> bindParam(":email_follower",$_SESSION['email'],PDO::PARAM_STR);
				$stmt -> bindParam(":email_followed",$result[$i][2],PDO::PARAM_STR);
				$stmt -> execute();
				$resultFollowing = $stmt->fetchAll();
				if(count($resultFollowing)){
					$isFollowing=true;
				}else{
					$isFollowing=false;
				}
				?>
				<a class="btn <?php if($isFollowing){echo 'red';} else { echo 'green';} ?>" name="<?php echo $result[$i][2]; ?>" onclick="follow(this)" style="font-size:12px;">
					<?php 
					if($isFollowing){
						echo "Non seguire più";
					}else{
						echo "Inizia a seguire";
					}
					?>
				</a>
				<?php
				if($isFollowing):
					?>
				<a class="btn green" id="sendmessage" name="<?php echo $result[$i][2]; ?>" onclick="loadMessageForm(this)" style="font-size:12px;">
					Invia messaggio
				</a>
				<?php
				endif;
				?>
			</li>
			<?php
		}
		?>
	</ul>
	<?php
	else:
		?>
	<h2 class="flow-text" style="text-align:center;color:red;">Nessuna corrispondenza trovata!</h2>
	<?php
	endif;
	?>
	<script type="text/javascript">
	var follow = function(elmnt){
		var emailFollowed = $(elmnt).attr('name');
		if(emailFollowed!=null && emailFollowed.length>0){
			var op='';
		if($(elmnt).hasClass('red')){//OP INDICA L'OPERAZIONE DA ESEGUIRE. Se 0 smetto di seguire quella persona, se 1 inizio a seguirla
			var op="0";	//smetto di seguire quella persona
	}else if($(elmnt).hasClass('green')){
			var op="1";	//inizio a seguirla
		}
		$.ajax({
			url: "/funzioni/manageFollower.php",
			type:"POST",
			data: {email_followed:emailFollowed,op:op},
			success: function(data){
				var result = JSON.parse(data);
				if(result.success==1){
					var testo = $('#finduser').val();
					if(testo!=null && testo.length>0){
						$.ajax({
							url: "/funzioni/ricercaUtente.php",
							type: 'POST',
							data: {testo:testo.trim()},
							success: function(data){
								$('#container-result').empty();
								$('#container-result').append(data);
							},
							error: function(data){
								alertify.alert("Errore nella trasmissione dei dati");
							}
						});
					}else{
						alertify.alert("Campo di ricerca vuoto");
					}
					
				}else if(result.success==0){
					alertify.alert("Errore nella trasmissione dei dati! Riprovare");
				}
			},	
			error:function(data){
				alertify.alert("Errore nella trasmissione dei dati! Riprovare");
			}
		});
	}
};
var loadMessageForm = function(elmnt){
	var email = $(elmnt).attr('name');
	var nome = $(elmnt).parent().find('.title').html();
	if(email!=null && nome!=null && email.length>0 && nome.length>0){
		$.ajax({
			url: "../messageForm.php",
			type: 'POST',
			data: {email_followed:email,nome:nome.trim()},
			success: function(data){
				$('#content-target').empty();
				$('#content-target').append(data);
			},
			error: function(){
				alertify.alert("Errore nella trasmissione dei dati! Riprovare");
			}
		});
	}else{
		alertify.alert("Errore nella trasmissione dei dati! Riprovare");
	}
};
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