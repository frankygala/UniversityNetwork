
<h2 class="flow-text" style="text-align:center;">Ricerca utente</h2>
<div class="row">
<div class="input-field col s9">
	<input type="text" onkeypress="manageKeyPress()" id="finduser" title="Ricerca per nome e cognome o per email" />
	<label for="finduser">Ricerca utente</label>
</div>
<div class="col s3" style="margin-top:20px;">
	<button id="submituser" class="btn">Cerca</button>
</div>
</div>
<div class="row">
	<div class="col s12" id="container-result">

	</div>
</div>
<script>
var manageKeyPress = function(){
	if(event.keyCode==13){
		$('#submituser').click();
	}
};
$('#submituser').on('click',function(){
	$('#container-result').empty();
	var testo = $('#finduser').val();
	if(testo!=null && testo.length>0){
		$.ajax({
			url: "/funzioni/ricercaUtente.php",
			type: 'POST',
			data: {testo:testo.trim()},
			success: function(data){
				$('#container-result').append(data);
			},
			error: function(data){
				alertify.alert("Errore nella trasmissione dei dati");
			}
		});
	}else{
		alertify.alert("Campo di ricerca vuoto");
	}
});
</script>
