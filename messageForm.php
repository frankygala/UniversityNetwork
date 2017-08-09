<?php
	require_once('funzioni/database.php');
	session_start();
	if(isset($_POST['email_followed']) && isset($_POST['nome'])):
		$email_followed=$_POST['email_followed'];
		$nome = $_POST['nome'];
?>
<h2 class="flow-text" style="text-align:center;">Invio messaggio</h2>
<div class="input-field">
	<input type="text" disabled name="<?php echo $email_followed;?>" value="<?php echo $nome.' ('.$email_followed.')'; ?>" id="destinatario"/>
	<label class="active" for="destinatario">Destinatario</label>
</div>

<div class="input-field">
	<input type="text" id="title"/>
	<label for="title">Oggetto</label>
</div>
<div class="input-field">
	<textarea class="materialize-textarea" id="descrizione" maxlength="500"></textarea>
	<label for="descrizione">Testo</label>
</div>
<div class="operations" style="text-align:center;">
	<button class="btn" onclick="sendmessage()">
		Invia
	</button>
</div>

<script>
	var sendmessage = function(){
		var descrizione = $('#descrizione').val();
		var title = $('#title').val();
		var email_followed = $('#destinatario').attr('name');
		if(title!= null && descrizione!=null && email_followed!=null && email_followed.length>0 && title.length>0 && descrizione.length>0){
	  			$.ajax({
	  				url: '/funzioni/sendMessage.php',
	  				type: 'POST',
	  				data: {titolo:title,descrizione:descrizione,email_followed:email_followed},
	  				success: function(data){
	  					var result = JSON.parse(data);
	  					if(result.success==1){
	  						alertify.alert("Messaggio inviato con successo!",function(){	
	  							window.location.replace('/homepage.php');
	  						});
	  					}else{
	  						alertify.alert("Errore invio messaggio");
	  					}
	  				},
	  				error: function(){
	  					alertify.alert("Errore invio messaggio");
	  				}
	  			});
	  		}else{
	  			alertify.alert("Inserire titolo e corpo del messaggio");
	  		}
	};
</script>
<?php
	else:
?>
	<script>
		alertify.alert("Errore nella ricezione dei dati! Riprovare");
	</script>
<?php
	endif;
?>