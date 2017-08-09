<?php
	if(isset($_POST['idesercitazione'])):
?>
<h2 class="flow-text" style="text-align:center;">Inserisci elaborato</h2>
<form method="post" action="/funzioni/creaElaborato.php" enctype="multipart/form-data" id="registration-form">
<input name="idesercitazione" value="<?php echo $_POST['idesercitazione']; ?>" hidden/>
<div class="row">
	<div class="input-field col s12">
		<textarea id="textarea" name="commento" maxlength="500" class="materialize-textarea" style="overflow-y:initial;" required></textarea>
		<label for="textarea" class="active">Commento</label>
	</div>
</div>
<div class="row" style="margin-top:50px;">
	<div class="file-field input-field col s6">
		<input class="file-path validate" type="text" placeholder="Carica file per esercitazione" required/>
		<div class="btn">
			<span>File</span>
			<input type="file" name="file" id="file"/>
		</div>
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
<?php
	else:
?>
	<h2 class="flow-text red" style="text-align:center;">Errore nel caricamento del form</h2>
<?php
	endif;
?>