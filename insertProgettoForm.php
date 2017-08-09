<?php
	require_once('funzioni/database.php');
	session_start();
	$pdo = $databaseConnection->getPdo();
	if(isset($_POST['idcorso'])):
?>
<h2 class="flow-text">Crea progetto</h2>
<form method="post" action="/funzioni/insertProgetto.php" encType="multipart/form-data">
	<input name="idcorso" value="<?php echo $_POST['idcorso']; ?>" hidden />
<div class="row">
	<div class="input-field col s12">
		<input id="titolo" maxlength="45" name="titolo" type="text" />
		<label for="titolo">Titolo</label>
	</div>
</div>
<div class="row">
	<div class="col s3 input-field">
		<input type="date" name="data_inizio" id="data_inizio" class="datepicker"/>
		<label for="data_inizio">Data di inizio</label>
	</div>
	<div class="col s3 input-field">
		<input type="date" name="data_fine" id="data_fine" class="datepicker"/>
		<label for="data_fine">Data di fine</label>
	</div>
</div>
<div class="row" style="margin-top:50px;">
	<div class="file-field input-field col s6">
		<input class="validate" id="target-file" type="text" placeholder="Carica file per appunto" style="margin-left:100px;"/>
		<div class="btn">
			<span>File</span>
			<input type="file" accept=".pdf,.zip,image/*" onchange="contaFile(this)" name="file[]" id="file" multiple/>
		</div>
	</div>
	<div class="col s6">
		<p>Estensioni accettate: .zip, .pdf, images </p>
	</div>
</div>
<div class="row">
	<div class="input-field col s12">
		<textarea id="textarea" maxlength="500" name="descrizione" class="materialize-textarea" style="overflow-y:initial;"></textarea>
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
<script>
var contaFile = function(elmnt){
var x = document.getElementById('file');
if(x.files.length>0){
	$('#target-file').val('Files caricati: '+x.files.length);
}
};
$('form').on('submit',function(event){
	var estensione = $('#file').val().substr($('#file').val().indexOf('.'),$('#file').val().length);
	if(!(estensione=='.pdf' || estensione=='.zip' || estensione=='.jpg' || estensione=='.gif' || estensione=='.png')){
		alertify.alert("Estensione non supportata");
		event.preventDefault();
	}
});
$('select').material_select();
var data_inizio = $('#data_inizio').pickadate({format:'yyyy-mm-dd',min: new Date()}), data_inizio = data_inizio.pickadate('picker');

var data_fine = $('#data_fine').pickadate({format:'yyyy-mm-dd'}), data_fine = data_fine.pickadate('picker');


// Check if there’s a “from” or “to” date to start with.
if ( data_inizio.get('value') ) {
  data_fine.set('min', data_inizio.get('select'));
}
if ( data_fine.get('value') ) {
  data_inizio.set('max', data_fine.get('select'));
}

// When something is selected, update the “from” and “to” limits.
data_inizio.on('set', function(event) {
  if ( event.select ) {
    data_fine.set('min', data_inizio.get('select'));    
  }
  else if ( 'clear' in event ) {
    data_fine.set('min', false);
  }
})
data_fine.on('set', function(event) {
  if ( event.select ) {
    data_inizio.set('max', data_fine.get('select'));
  }
  else if ( 'clear' in event ) {
    data_inizio.set('max', false);
  }
});

</script>
<?php
	else:
?>

<h2 class="flow-text red" style="text-align:center;">Attenzione! Errore ricezione dati</h2>
<?php
	endif;
?>