<?php
	require_once('funzioni/database.php');
	session_start();
	$pdo = $databaseConnection->getPdo();
?>
<h2 class="flow-text">Crea progetto extra</h2>
<form method="post" action="/funzioni/insertProgettoExtra.php" encType="multipart/form-data">
<div class="row">
	<div class="input-field col s12">
		<input id="titolo" maxlength="45" name="titolo" type="text" required/>
		<label for="titolo">Titolo</label>
	</div>
</div>
<div class="row">
	<div class="col s3 input-field">
		<input type="date" name="data_inizio" id="data_inizio" class="datepicker" required/>
		<label for="data_inizio">Data di inizio</label>
	</div>
	<div class="col s3 input-field">
		<input type="date" name="data_fine" id="data_fine" class="datepicker" required/>
		<label for="data_fine">Data di fine</label>
	</div>
</div>
<div class="row">
	<div class="input-field col s12">
		<input id="categoria" maxlength="45" name="categoria" type="text" required/>
		<label for="categoria">Categoria</label>
	</div>
</div>
<div class="row">
	<p class="flow-text">Aziende che hanno commissionato il progetto</p>
	<div class="input-field col s6"> 
		<input type="text" id="nome-azienda"/>
		<label for="nome-azienda">Nome azienda</label>
	</div>
<div class="input-field col s6"> 
		
		<input type="url" id="sito-azienda"/>
		<label for="sito-azienda">Sito azienda</label>
	</div>
	<div class="input-field col s6"> 
		<input type="text" id="indirizzo-azienda" />
		<label for="indirizzo-azienda">Indirizzo azienda</label>
		<a class="btn" onclick="aggiungiAzienda(this)">Inserisci</a>
	</div>
</div>
<div class="row" style="margin-top:50px;">
	<div class="col s12">
		<p class="flow-text">Lista aziende</p>
		<ul class="collection" id="lista-aziende" style="min-height:100px;max-height:300px;overflow-y:auto;">

		</ul>
	</div>
</div>
<div class="row" id="file-row" style="margin-top:330px;">
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
		<textarea id="textarea" maxlength="500" name="descrizione" class="materialize-textarea" style="overflow-y:initial;" required></textarea>
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
var aggiungiAzienda = function(){
	var nome=$('#nome-azienda').val();
	var sito=$('#sito-azienda').val();
	var indirizzo=$('#indirizzo-azienda').val();
	if(nome.length>0 && sito.length>0 && indirizzo.length>0){
		$('#nome-azienda').val('');
		$('#sito-azienda').val('');
		$('#indirizzo-azienda').val('');
		var li = document.createElement('li');

		var p = document.createElement('p');
		var input = document.createElement('input');
		$(input).val(nome);
		$(input).hide();
		$(p).html('Nome azienda: '+nome);
		$(input).attr('name','nomeAzienda[]');
		var a = document.createElement('a');
		var input1 = document.createElement('input');
		$(input1).val(sito);
		$(input1).hide();
		$(a).html(sito);
		$(a).attr('href','http://'+sito);
		$(a).attr('target','blank');
		$(a).css({
			'color' : '#FF0000',
			'text-decoration': 'underline'
		});
		$(input1).attr('name','sitoAzienda[]');
		var p2 = document.createElement('p');
		var input2 = document.createElement('input');
		$(input2).val(indirizzo);
		$(input2).hide();
		$(p2).html('Indirizzo azienda: '+indirizzo);
		$(input2).attr('name','indirizzoAzienda[]');
		$(li).addClass('collection-item');
		$(li).append(p);
		$(li).append(a);
		$(li).append(p2);
		$(li).append(input);
		$(li).append(input1);
		$(li).append(input2);
		$('#lista-aziende').append(li);
	}else{
		alertify.alert("Errore nell'inserimento dell'azienda");
	}
};

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