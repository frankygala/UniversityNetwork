
<h2 class="flow-text">Crea commento</h2>
<div class="row" style="margin-top:50px;">
	<div class="col s12 input-field">
		<input type="text" id="testo"/>
		<label for="testo">Testo</label>
	</div>
</div>
<div class="row">
	<div class="col s3 input-field">
		<input type="text" title="Compresa tra 0 e 5" id="valutazione"/>
		<label for="valutazione">Valutazione</label>
	</div>
</div>
<div class="row">
	<div class="col s12" style="text-align:center;margin-top:40px;">
		<button name="<?php echo $_POST['idappunto']?>" onclick="creaCommento(this)" class="btn">
			Crea
		</button>
	</div>
</div>
<script>
var creaCommento = function(elmnt){
	var testo = $('#testo').val();
	var valutazione = $('#valutazione').val();
	var idappunto = $(elmnt).attr('name');
	if(testo.length>0 && valutazione.length>0 && valutazione>=0 && valutazione<6){
		$.ajax({
			url:'/funzioni/insertCommento.php',
			type:'POST',
			data:{testo:testo,valutazione:valutazione,idappunto:idappunto},
			success: function(data){
				var result = JSON.parse(data);
				if(result.success==1){
					alertify.alert('Commento inserito con successo',function(){
						$.ajax({
						url: "/funzioni/dettagliAppunto.php",
						type: "POST",
						data:{idappunto:idappunto},
						success:function(data){
							$('#content-target').empty();
							$('#content-target').append(data);
						},
						error:function(){
							alertify.alert("Errore nella trasmissione dei dati");
						}
					});
					});
				}else if(result.success==0){
					alertify.alert("Errore nella creazione del commento");
				}
			},
			error:function(){
				alertify.alert("Errore nella trasmissione dei dati. Riprovare.");
			}
		});
	}else{
		alertify.alert("Errore nella compilazione del form. Riprovare. Controllare valutazione (0,5)");
	}	
};
</script>