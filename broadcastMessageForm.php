<?php
	require_once('funzioni/database.php');
	session_start();
?>
<h2 class="flow-text" style="text-align:center;">Invio messaggio</h2>
<div class="input-field">
	<input type="text" disabled value="TUTTI" id="destinatario"/>
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
		if(title!= null && descrizione!=null && title.length>0 && descrizione.length>0){
	  			$.ajax({
	  				url: '/funzioni/sendBroadcastMessage.php',
	  				type: 'POST',
	  				data: {titolo:title,descrizione:descrizione},
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