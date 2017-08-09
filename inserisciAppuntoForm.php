<?php
	require_once("funzioni/database.php");
	session_start();
	if(isset($_POST['idcorso'])):
?>
<h2 class="flow-text" style="text-align:center;">Inserimento appunto</h2>
<form action="/funzioni/creaAppunto.php" method="POST" encType="multipart/form-data">
	<input name="idcorso" value="<?php echo $_POST['idcorso']; ?>" hidden />
<div class="row">
	<div class="input-field col s12">
		<input id="titolo" maxlength="45" name="titolo" type="text" />
		<label for="titolo">Titolo</label>
	</div>
</div>
<div class="row" style="margin-top:50px;">
	<div class="file-field input-field col s6">
		<input class="file-path validate" type="text" placeholder="Carica file per appunto" required/>
		<div class="btn">
			<span>File</span>
			<input type="file" accept=".pdf,.xmind,image/*" name="file" id="file"/>
		</div>
	</div>
	<div class="col s6">
		<p>Estensioni accettate: .pdf, .xmind, images </p>
	</div>
</div>
<div class="row">
	<div class="input-field col s12">
		<textarea id="textarea" maxlength="500" class="materialize-textarea" name="descrizione" style="overflow-y:initial;"></textarea>
		<label for="textarea" class="active">Descrizione</label>
	</div>
</div>
<div class="row">
	<div class="col s12" style="text-align:center;margin-top:40px;">
		<button type="submit" class="btn">
			Crea
		</button>
	</div>
</div>
</form>
<script type="text/javascript" src="/lib/materialize.min.js"></script>
<script type="text/javascript">
	$(document).ready(function(){
		$('form').on('submit',function(event){
			var estensione = $('#file').val().substr($('#file').val().indexOf('.'),$('#file').val().length);
			if(!(estensione=='.pdf' || estensione=='.xmind' || estensione=='.jpg' || estensione=='.gif' || estensione=='.png')){
				alertify.alert("Estensione non supportata");
				event.preventDefault();
			}
		});
	});
</script>
<?php
	else:
?>
	<h2 class="flow-text red" style="text-align:center;">Errore nel caricamento del form</h2>
<?php
	endif;
?>