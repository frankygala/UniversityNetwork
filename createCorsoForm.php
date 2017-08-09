<h2 class="flow-text" style="text-align:center;">Creazione corso d'insegnamento</h2>
<div class="row">
<div class="input-field col s6">
	<input type="text" id="nome" maxlength="45" title="Massimo 45 caratteri" validate />
	<label for="nome">Nome corso</label>
</div>
</div>
<div class="row">
<div class="input-field col s4">
	<input type="number" id="codice" title="0000-9999" min="0000" max="9999" validate/>
	<label for="codice">Codice corso</label>
</div>
<div class="input-field col s4">
	<input type="number" title="Da 1 a 99" min="1" max="99" id="crediti" validate/>
	<label for="crediti">Numero crediti</label>
</div>
</div>
<div class="row">
<div class="input-field col s6" style="bottom:16px;" >
	  <select id="corso_laurea">
	  	<option value="" disabled selected>Seleziona corso laurea</option>
	    <option value="informatica">Informatica</option>
	    <option value="informatica per il management">Informatica per il management</option>
	  </select>
</div>
<div class="input-field col s6" style="bottom:16px;" >
	<select id="anno">
	  	<option value="" disabled selected>Seleziona anno accademico</option>
	    <option value="2014">2014</option>
	    <option value="2015">2015</option>
	  </select>
</div>
</div>
<div class="row">
<div class="operations col s12" style="text-align:center;margin-top:10px;">
	<button class="btn" onclick="createCorso(this)">
		Crea
	</button>
</div>
</div>

<script>
$('select').material_select();
var createCorso = function(elmnt){
	var codice = $('#codice').val();
	var anno = $('#anno').val();
	var crediti = $('#crediti').val();
	var nome = $('#nome').val();
	var corso = $('#corso_laurea').val();
	if(parseInt(codice) && codice.length==4 && parseInt(anno) && parseInt(crediti) && crediti>0 && crediti<100 && nome.length>0 && corso!=null && corso.length>0){
		$.ajax({
			url: '/funzioni/createCorso.php',
			type: 'POST',
			data: {codice:codice,anno:anno,crediti:crediti,nome:nome,corso:corso},
			success: function(data){
				var result = JSON.parse(data);
				if(result.success==1){
					alertify.alert("Corso creato con successo.",function(){
						$('#content-target').load('/listaCorsi.php');
					});
				}else if(result.success==0 && result.reply==0){
					alertify.alert("Errore nell'elaborazione della richiesta. Ricontrollare i campi.");
				}else if(result.success==0 && result.reply==1){
					alertify.alert("Il corso che si sta inserendo è già presente.");
				}
			},
			error:function(){
				alertify.alert("Errore nella trasmissione dei dati.Riprovare");
			}

		});
	}else{	
		alertify.alert('Alcuni campi risultano errati o vuoti! Controllare anche il codice (4 cifre)');
	}
};
</script>