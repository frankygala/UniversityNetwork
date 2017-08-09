<?php
	require_once('funzioni/database.php');
	session_start();
	$pdo = $databaseConnection->getPdo();
	$sql = "SELECT * FROM corso WHERE email_doc=:email";
	$stmt = $pdo -> prepare($sql);
	$stmt -> bindParam(":email",$_SESSION['email'],PDO::PARAM_STR);
	$stmt -> execute();
	$result  = $stmt->fetchAll();
	if(count($result)):
?>
<h2 class="flow-text">Crea esercitazione</h2>
<div class="row" style="margin-top:50px;">
	<div class="col s3 input-field">
		<input type="date" id="data_inizio" class="datepicker"/>
		<label for="data_inizio">Data di inizio</label>
	</div>
	<div class="col s3 input-field">
		<input type="date" id="data_fine" class="datepicker"/>
		<label for="data_fine">Data di fine</label>
	</div>
	
	<div class="input-field col s2" style="top:-15px;">
		<div class="input-field">
		<select id="voto">
			<option value="1">Si</option>
			<option value="0">No</option>
		</select>
		<label for="voto" class="active">Matura voto</label>
	</div>
</div>
<div class="row">
<div class="col input-field s6">

		<a class='dropdown-button btn' data-activates='dropdown1' style="width:240px;font-size:12px;padding-right:5px;padding-left:5px;">Seleziona corso</a>
		<ul class="collection dropdown-content" id='dropdown1' style="width:240px;">
	  	 <?php
			for($i=0;$i<count($result);$i++){
					echo "<a class='collection-item' onClick='changeValue(this)' name=".$result[$i][0].">".$result[$i][3]."<span class='badge'>Anno: ".$result[$i][2]."</span></a>";
			}
		?>
     	</ul>
	</div>
	<div class="col input-field s3">
		<input type="text" id="corso_choosed" value="" disabled/>
		<label for="corso_choosed" class="active">Codice corso selezionato</label>
	</div>
</div>
</div>
<div class="row">
	<div class="input-field col s12">
		<textarea id="textarea" maxlength="500" class="materialize-textarea" style="overflow-y:initial;"></textarea>
		<label for="textarea" class="active">Descrizione</label>
	</div>
</div>
<div class="row">
	<div class="col s12" style="text-align:center;margin-top:40px;">
		<button onclick="creaEsercitazioneCorso(this)" class="btn">
			Crea
		</button>
	</div>
</div>
<script>
$('.dropdown-button').dropdown({
      inDuration: 300,
      outDuration: 225,
      hover: false, // Activate on hover
      gutter: 0, // Spacing from edge
      belowOrigin: false // Displays dropdown below the button
    }
  );
var creaEsercitazioneCorso = function(){
	var data_inizio = $('#data_inizio').val();
	var data_fine = $('#data_fine').val();
	var corso = $('#corso_choosed').val();
	var descrizione = $('#textarea').val();
	var voto = $('#voto').val();
	if(data_inizio != null && data_inizio.length>0 && data_fine !=null && data_fine.length>0 && corso>=0 && descrizione!=null && descrizione.length>0){
		$.ajax({
			url:'/funzioni/creaEsercitazione.php',
			type:'POST',
			data:{data_inizio:data_inizio,data_fine:data_fine,corso:corso,descrizione:descrizione.trim(),voto:voto},
			success: function(data){
				var result = JSON.parse(data);
				if(result.success==1){
					alertify.alert('Esercitazione creata con successo',function(){
						$('#content-target').load("/listaEsercitazioni.php");
					});
				}else if(result.success==0){
					alertify.alert("Errore nella creazione dell'esercitazione");
				}
			},
			error:function(){
				alertify.alert("Errore nella trasmissione dei dati. Riprovare.");
			}
		});
	}else{
		alertify.alert("Errore nella compilazione del form. Riprovare.");
	}	
};
var changeValue = function(elmnt){
	var value = $(elmnt).attr('name');
	$('#corso_choosed').val(value);
};

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

<h2 class="flow-text red" style="text-align:center;">Attenzione! Devi prima creare il tuo corso</h2>
<?php
	endif;
?>