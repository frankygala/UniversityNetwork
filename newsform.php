<?php
	require_once('funzioni/database.php');
	session_start();
?>

<div>
	<h2 class="flow-text" style="text-align:center;">Inserimento news</h2>
	<div>
	<div class="input-field">
		<input type="text" id="title" maxlength="45"/>
		<label for="title">Titolo</label>
	</div>
	<div class="input-field">
		<textarea id="textarea" class="materialize-textarea" maxlength="500"></textarea>
		<label for="textarea">Descrizione</label>
	</div>
		
		<div class="operations" style="text-align:center;">
			<button id="submit" class="btn green">Pubblica</button>
		</div>
		</div>
	</div>
	<script type="text/javascript">
	  	$('#submit').on('click',function(){
	  		var titolo = $('#title').val();
	  		var textArea = $('#textarea').val();
	  		if(titolo!= null && textArea!=null && titolo.length>0 && textArea.length>0){
	  			$.ajax({
	  				url: '/funzioni/inserisciNews.php',
	  				type: 'POST',
	  				data: {titolo:titolo,descrizione:textArea},
	  				success: function(data){
	  					var result = JSON.parse(data);
	  					if(result.success==1){
	  						alertify.alert("News caricata con successo!",function(){	
	  							window.location.replace('/homepage.php');
	  						});
	  					}else{
	  						alertify.alert("Errore caricamento news");
	  					}
	  				},
	  				error: function(){
	  					alertify.alert("Errore caricamento news");
	  				}
	  			});
	  		}else{
	  			alertify.alert("Inserire titolo e descrizione");
	  		}
	  	});
	</script>