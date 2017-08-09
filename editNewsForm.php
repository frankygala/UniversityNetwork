<?php
	require_once('funzioni/database.php');
	session_start();
	if(isset($_POST['id'])):
		$id = $_POST['id'];
		$sql = "SELECT * FROM news WHERE idnews=:id";
		$pdo = $databaseConnection -> getPdo();
		$stmt = $pdo->prepare($sql);
		$stmt -> bindParam(':id',$id,PDO::PARAM_INT);
		$stmt->execute();
		$result = $stmt -> fetchAll();
		if(count($result)):
?>

<div>
	<h2 class="flow-text" style="text-align:center;">Modifica news</h2>
	<div>
	<div class="input-field">
		<input type="text" id="title" value="<?php echo $result[0][2];?>" maxlength="45"/>
		<label class="active" for="title">Titolo</label>
	</div>
	<div class="input-field">
		<textarea id="textarea" class="materialize-textarea" maxlength="500"><?php echo $result[0][3];?></textarea>
		<label class="active" for="textarea">Descrizione</label>
	</div>
		<div class="operations" style="text-align:center;">
		<button id="submit" name="<?php echo $id; ?>" class="btn green">Pubblica</button>
	</div>
	</div>
	</div>
	<script type="text/javascript">
	$(document).ready(function() {
	    $('select').material_select();
	  	$('#submit').on('click',function(){
	  		var id = $('#submit').attr('name');
	  		var titolo = $('#title').val();
	  		var textArea = $('#textarea').val();
	  		if(titolo!= null && textArea!=null && titolo.length>0 && textArea.length>0 && id!=null && id.length>0){
	  			$.ajax({
	  				url: '/funzioni/updateNews.php',
	  				type: 'POST',
	  				data: {titolo:titolo,descrizione:textArea,id:id},
	  				success: function(data){
	  					var result = JSON.parse(data);
	  					if(result.success==1){
	  					alertify.alert("News modificata con successo!",function(){	
	  							window.location.replace('/homepage.php');
	  						});
	  					}else{
	  						alertify.alert("Errore aggiornamento news");
	  					}
	  				},
	  				error: function(){
	  					alertify.alert("Errore aggiornamento news");
	  				}
	  			});
	  		}else{
	  			alertify.alert("Inserire titolo e descrizione");
	  		}
	  	});
  	});
	</script>

	<?php
	else:
	?>
	<h2 class="flow-text" style="text-align:center;color:red;">
		Errore caricamento dati notizia. Riprovare
	</h2>
<?php
endif;
endif;
?>