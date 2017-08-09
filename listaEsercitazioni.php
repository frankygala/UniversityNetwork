<?php
	require_once('funzioni/database.php');
	session_start();
	$sql = "SELECT * FROM esercitazione";
	$pdo = $databaseConnection -> getPdo();
	$stmt = $pdo -> prepare($sql);
	$stmt -> execute();
	$result = $stmt -> fetchAll();
?>

<h2 class="flow-text" style="text-align:center;">Lista esercitazioni</h2>
		 			
<?php
	if(count($result)):
?>
<div style="max-height:550px;overflow-y:auto;">
<ul class="collection">
<?php
	for($i=0;$i<count($result);$i++){
		$sql2 = "SELECT * FROM utente,docente WHERE email=:email AND docente.email_professore=utente.email";
		$stmt2 = $pdo -> prepare($sql2);
		$stmt2 -> bindParam(":email",$result[$i][1],PDO::PARAM_STR);
		$stmt2 -> execute();
		$resultUser = $stmt2 -> fetchAll();
?>
<li class="collection-item avatar" class="height:200px;">
	<img src="/images/<?php echo $resultUser[0][4]; ?>" class="circle" />
 	<span class="title"><?php echo "Di: ".$resultUser[0][2]." ".$resultUser[0][3]." (".$resultUser[0][6].")"; ?></span>
  	
  	<div class="secondary-content">
	  <p>Inizio: <?php echo $result[$i][4];?></p>
	  <p>Fine: <?php echo $result[$i][5];?></p>
	</div>
	<p style="font-weight:bold;margin-top:10px;">Descrizione:</p>
  	<p class="content-p" >

  		<?php echo $result[$i][3];?> 					        
  	</p>
  	<button class="btn" onclick="seeDetails(this)" style="margin-top:10px;" name="<?php echo $result[$i][0]; ?>">Vedi dettagli</button>
</li>

<?php
}
?>
</ul>
</div>
<script>
var seeDetails = function(elmnt){
	var id = $(elmnt).attr('name');
	$.ajax({
		url:"/funzioni/dettagliEsercitazione.php",
		type:'POST',
		data: {id:id},
		success: function(data){
			$('#content-target').empty();
			$('#content-target').append(data);
		},
		error: function(){
			alertify.alert("Errore nella trasmissione dei dati. Riprovare.");
		}
	});
};
</script>
<?php
else: 
?>
<h2 class="flow-text" style="text-align:center;color:red;">Nessuna esercitazione presente</h2>
<?
endif;
?>