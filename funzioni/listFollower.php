<h2 class="flow-text" style="text-align:center;">Chi seguo</h2>
<div style="text-align:center;">
<button class="btn" style="font-size:12px;padding-right:10px;padding-left:10px;" onClick="messaggioBroadcast(this)">Invia messaggio broadcast</button>
</div>
<?php
require_once("database.php");
session_start();
$pdo = $databaseConnection -> getPdo();
$sql = "SELECT nome,cognome,email,foto,corso_laurea FROM follower, utente, studente WHERE follower.email_follower=:email_follower AND utente.email=email_followed AND studente.email_studente=utente.email
		UNION ALL
		SELECT nome,cognome,email,foto,ruolo FROM follower, utente, docente WHERE follower.email_follower=:email_follower AND utente.email=email_followed AND docente.email_professore=utente.email
		";
$stmt = $pdo -> prepare($sql);
$stmt -> bindParam(':email_follower',$_SESSION['email'],PDO::PARAM_STR);
$stmt -> execute();
$result = $stmt -> fetchAll();
if($result != null && count($result)):
?>
	
	<ul class="collection" id="listaFollowed">
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
			<a class="btn red" name="<?php echo $result[$i][2]; ?>" onclick="follow(this)" style="font-size:12px;">
				Non seguire più
			</a>
			<a class="btn green" name="<?php echo $result[$i][2]; ?>" onclick="loadMessageForm(this)" style="font-size:12px;">
				Invia messaggio
			</a>
		</li>
		<?php
		}
		?>
	</ul>
	<script type="text/javascript">
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
	<h2 class="flow-text" style="text-align:center;color:red;">Attualmente non segui nessuno</h2>
	<?php
		endif;
	?>
<script type="text/javascript">
var follow = function(elmnt){
	var emailFollowed = $(elmnt).attr('name');
	if(emailFollowed!=null && emailFollowed.length>0){
		$.ajax({
			url: "/funzioni/manageFollower.php",
			type:"POST",
			data: {email_followed:emailFollowed,op:'0'},
			success: function(data){
				var result = JSON.parse(data);
				if(result.success==1){
					$('#content-target').load('/funzioni/listFollower.php');
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
var messaggioBroadcast = function(elmnt){
	if(document.getElementById('listaFollowed')!=null){
		$.ajax({
			url: "../broadcastMessageForm.php",
			success: function(data){
				$('#content-target').empty();
				$('#content-target').append(data);
			},
			error: function(){
				alertify.alert("Errore nella trasmissione dei dati! Riprovare");
			}
		});
	}else{
			alertify.alert("Errore! Devi prima seguire qualcuno per inviare un messaggio di broadcast!");
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
</script>